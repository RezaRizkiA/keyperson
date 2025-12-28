<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Transaction;
use App\Services\PaymentService;
use App\Services\QuotaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Inertia\Inertia;

/**
 * B2B Phase 2: Top-Up Controller
 * 
 * Handle quota top-up untuk HRD/Client
 */
class TopUpController extends Controller
{
    protected PaymentService $paymentService;
    protected QuotaService $quotaService;

    public function __construct(PaymentService $paymentService, QuotaService $quotaService)
    {
        $this->paymentService = $paymentService;
        $this->quotaService = $quotaService;
    }

    /**
     * Show top-up form
     */
    public function create()
    {
        $user = Auth::user();
        
        // Pastikan user adalah HRD/Client dengan company
        if (!$user->isCorporateMember() || !$user->company) {
            return redirect()->route('dashboard.index')
                ->with('error', 'Anda tidak memiliki akses ke fitur top-up.');
        }

        $client = $user->company;
        $quota = $client->quota;
        
        // Predefined amounts untuk pilihan cepat
        $predefinedAmounts = [
            ['value' => 5000000, 'label' => 'Rp 5.000.000'],
            ['value' => 10000000, 'label' => 'Rp 10.000.000'],
            ['value' => 25000000, 'label' => 'Rp 25.000.000'],
            ['value' => 50000000, 'label' => 'Rp 50.000.000'],
            ['value' => 100000000, 'label' => 'Rp 100.000.000'],
        ];

        return Inertia::render('Client/TopUp/Create', [
            'client' => [
                'id' => $client->id,
                'company_name' => $client->company_name,
                'current_balance' => $quota?->balance ?? 0,
            ],
            'predefinedAmounts' => $predefinedAmounts,
            'minAmount' => 1000000, // Minimum Rp 1.000.000
            'maxAmount' => 500000000, // Maximum Rp 500.000.000
        ]);
    }

    /**
     * Process top-up payment
     */
    public function store(Request $request)
    {
        $request->validate([
            'amount' => 'required|integer|min:1000000|max:500000000',
            'payment_method' => 'required|string',
        ]);

        $user = Auth::user();
        
        if (!$user->isCorporateMember() || !$user->company) {
            return back()->with('error', 'Anda tidak memiliki akses ke fitur top-up.');
        }

        $client = $user->company;
        $amount = $request->input('amount');
        $paymentMethod = $request->input('payment_method');

        try {
            // Generate unique transaction ID
            $trxId = 'TOPUP-' . strtoupper(Str::random(8)) . '-' . time();

            // Create transaction record dengan type = 'topup'
            $transaction = Transaction::create([
                'user_id' => $user->id,
                'appointment_id' => null, // Tidak terkait appointment
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'trx_desc' => "Top-up kuota {$client->company_name}",
                'amount' => $amount,
                'total' => $amount,
                'trx_id' => $trxId,
                'type' => 'topup', // PENTING: Ini yang membedakan dengan booking
                'status' => 'pending',
            ]);

            // Process payment via iPaymu
            $paymentData = [
                'payment_method' => $paymentMethod,
            ];

            // Use existing payment service to process
            $result = $this->paymentService->processTopUpPayment($transaction, $paymentData);

            return redirect()->route('payment.transaction', ['sid' => $transaction->sid]);
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memproses pembayaran: ' . $e->getMessage());
        }
    }

    /**
     * Show top-up success page
     */
    public function success(Request $request)
    {
        $transactionId = $request->query('transaction_id');
        
        $transaction = Transaction::where('id', $transactionId)
            ->where('type', 'topup')
            ->where('status', 'berhasil')
            ->firstOrFail();

        return Inertia::render('Client/TopUp/Success', [
            'transaction' => $transaction,
            'amount' => $transaction->amount,
        ]);
    }

    /**
     * Show ledger history
     */
    public function history()
    {
        $user = Auth::user();
        
        if (!$user->isCorporateMember() || !$user->company) {
            return redirect()->route('dashboard.index')
                ->with('error', 'Anda tidak memiliki akses ke fitur ini.');
        }

        $client = $user->company;
        $ledgers = $this->quotaService->getLedgerHistory($client, 100);

        return Inertia::render('Client/TopUp/History', [
            'client' => [
                'company_name' => $client->company_name,
                'current_balance' => $client->quota?->balance ?? 0,
            ],
            'ledgers' => $ledgers,
        ]);
    }
}
