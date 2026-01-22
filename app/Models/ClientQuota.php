<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientQuota extends Model
{
    protected $table = 'client_quotas';
    protected $guarded = ['id'];

    protected $casts = [
        'balance' => 'integer',
        'plan_limit' => 'integer',
    ];

    /**
     * Plan limits in Rupiah
     */
    public const PLAN_LIMITS = [
        'starter' => 5000000,       // 5 juta
        'professional' => 25000000, // 25 juta
        'enterprise' => 100000000,  // 100 juta
    ];

    /**
     * Relasi ke Client (perusahaan pemilik quota)
     */
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Get the plan limit based on plan type
     * 
     * @return int Plan limit in Rupiah
     */
    public function getPlanLimit(): int
    {
        if ($this->plan === 'custom') {
            return $this->plan_limit;
        }

        return self::PLAN_LIMITS[$this->plan] ?? self::PLAN_LIMITS['starter'];
    }

    /**
     * Cek apakah saldo mencukupi untuk jumlah tertentu
     *
     * @param int $amount Jumlah dalam Rupiah
     * @return bool
     */
    public function hasSufficientBalance(int $amount): bool
    {
        return $this->balance >= $amount;
    }
}
