<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientQuota extends Model
{
    protected $table = 'client_quotas';
    protected $guarded = ['id'];

    protected $casts = [
        'balance' => 'integer',
    ];

    /**
     * Relasi ke Client (perusahaan pemilik quota)
     */
    public function client()
    {
        return $this->belongsTo(Client::class);
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
