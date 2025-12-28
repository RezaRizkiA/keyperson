<?php

namespace App\Services;

use App\Models\Appointment;
use App\Models\Client;
use App\Models\ClientQuota;
use App\Models\QuotaLedger;
use App\Models\Transaction;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;

/**
 * B2B Phase 2: Quota Service
 * 
 * Mengelola operasi quota dengan:
 * - Ledger logging untuk audit trail
 * - lockForUpdate() untuk concurrency handling
 * - Atomic transactions untuk data integrity
 */
class QuotaService
{
    /**
     * Potong quota untuk booking (DEBIT)
     * 
     * @param Client $client Perusahaan yang quotanya akan dipotong
     * @param int $amount Jumlah dalam Rupiah
     * @param Appointment $appointment Booking terkait
     * @param User $user User yang melakukan booking
     * @return bool True jika berhasil, false jika saldo tidak cukup
     * @throws Exception Jika terjadi error
     */
    public function deductQuota(Client $client, int $amount, Appointment $appointment, User $user): bool
    {
        return DB::transaction(function () use ($client, $amount, $appointment, $user) {
            // Lock row untuk mencegah race condition
            $quota = ClientQuota::where('client_id', $client->id)
                ->lockForUpdate()
                ->first();

            if (!$quota) {
                throw new Exception("Client {$client->company_name} tidak memiliki quota.");
            }

            // Cek saldo cukup
            if ($quota->balance < $amount) {
                return false;
            }

            $balanceBefore = $quota->balance;
            
            // Potong saldo
            $quota->decrement('balance', $amount);
            
            $balanceAfter = $quota->fresh()->balance;

            // Catat ke ledger
            QuotaLedger::create([
                'client_id' => $client->id,
                'user_id' => $user->id,
                'appointment_id' => $appointment->id,
                'transaction_id' => null,
                'type' => 'debit',
                'amount' => $amount,
                'balance_before' => $balanceBefore,
                'balance_after' => $balanceAfter,
                'reference_type' => 'booking',
                'description' => "Booking #{$appointment->id} oleh {$user->name}",
            ]);

            return true;
        });
    }

    /**
     * Tambah quota dari top-up (CREDIT)
     * 
     * @param Client $client Perusahaan penerima quota
     * @param int $amount Jumlah dalam Rupiah
     * @param Transaction $transaction Transaksi pembayaran terkait
     * @param User $user User (HRD) yang melakukan top-up
     */
    public function addQuota(Client $client, int $amount, Transaction $transaction, User $user): void
    {
        DB::transaction(function () use ($client, $amount, $transaction, $user) {
            $quota = ClientQuota::where('client_id', $client->id)
                ->lockForUpdate()
                ->first();

            if (!$quota) {
                // Buat quota baru jika belum ada
                $quota = ClientQuota::create([
                    'client_id' => $client->id,
                    'balance' => 0,
                ]);
            }

            $balanceBefore = $quota->balance;
            
            // Tambah saldo
            $quota->increment('balance', $amount);
            
            $balanceAfter = $quota->fresh()->balance;

            // Catat ke ledger
            QuotaLedger::create([
                'client_id' => $client->id,
                'user_id' => $user->id,
                'appointment_id' => null,
                'transaction_id' => $transaction->id,
                'type' => 'credit',
                'amount' => $amount,
                'balance_before' => $balanceBefore,
                'balance_after' => $balanceAfter,
                'reference_type' => 'topup',
                'description' => "Top-up via {$transaction->paymentName} oleh {$user->name}",
            ]);
        });
    }

    /**
     * Refund quota saat booking dibatalkan (CREDIT)
     * 
     * @param Client $client Perusahaan penerima refund
     * @param int $amount Jumlah dalam Rupiah
     * @param Appointment $appointment Booking yang dibatalkan
     * @param User|null $cancelledBy User yang membatalkan (null jika system)
     */
    public function refundQuota(Client $client, int $amount, Appointment $appointment, ?User $cancelledBy = null): void
    {
        DB::transaction(function () use ($client, $amount, $appointment, $cancelledBy) {
            $quota = ClientQuota::where('client_id', $client->id)
                ->lockForUpdate()
                ->first();

            if (!$quota) {
                throw new Exception("Client {$client->company_name} tidak memiliki quota.");
            }

            $balanceBefore = $quota->balance;
            
            // Tambah saldo (refund)
            $quota->increment('balance', $amount);
            
            $balanceAfter = $quota->fresh()->balance;

            $cancelledByName = $cancelledBy ? $cancelledBy->name : 'System';

            // Catat ke ledger
            QuotaLedger::create([
                'client_id' => $client->id,
                'user_id' => $cancelledBy?->id,
                'appointment_id' => $appointment->id,
                'transaction_id' => null,
                'type' => 'credit',
                'amount' => $amount,
                'balance_before' => $balanceBefore,
                'balance_after' => $balanceAfter,
                'reference_type' => 'refund',
                'description' => "Refund booking #{$appointment->id} oleh {$cancelledByName}",
            ]);
        });
    }

    /**
     * Adjustment manual oleh admin (DEBIT atau CREDIT)
     * 
     * @param Client $client Perusahaan target
     * @param int $amount Jumlah dalam Rupiah (positif untuk credit, negatif untuk debit)
     * @param User $admin Admin yang melakukan adjustment
     * @param string $reason Alasan adjustment
     */
    public function adjustQuota(Client $client, int $amount, User $admin, string $reason): void
    {
        DB::transaction(function () use ($client, $amount, $admin, $reason) {
            $quota = ClientQuota::where('client_id', $client->id)
                ->lockForUpdate()
                ->firstOrFail();

            $balanceBefore = $quota->balance;
            $type = $amount >= 0 ? 'credit' : 'debit';
            $absAmount = abs($amount);

            if ($type === 'credit') {
                $quota->increment('balance', $absAmount);
            } else {
                $quota->decrement('balance', $absAmount);
            }

            $balanceAfter = $quota->fresh()->balance;

            QuotaLedger::create([
                'client_id' => $client->id,
                'user_id' => $admin->id,
                'appointment_id' => null,
                'transaction_id' => null,
                'type' => $type,
                'amount' => $absAmount,
                'balance_before' => $balanceBefore,
                'balance_after' => $balanceAfter,
                'reference_type' => 'adjustment',
                'description' => "Adjustment oleh {$admin->name}: {$reason}",
            ]);
        });
    }

    /**
     * Get ledger history untuk client
     */
    public function getLedgerHistory(Client $client, int $limit = 50)
    {
        return QuotaLedger::where('client_id', $client->id)
            ->with(['user', 'appointment', 'transaction'])
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }
}
