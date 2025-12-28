<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuotaLedger extends Model
{
    protected $table = 'quota_ledgers';
    protected $guarded = ['id'];

    protected $casts = [
        'amount' => 'integer',
        'balance_before' => 'integer',
        'balance_after' => 'integer',
    ];

    /**
     * Relasi ke Client (perusahaan pemilik quota)
     */
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Relasi ke User yang melakukan transaksi
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke Appointment (jika transaksi terkait booking)
     */
    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    /**
     * Relasi ke Transaction (jika transaksi terkait payment)
     */
    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    /**
     * Scope untuk filter berdasarkan type
     */
    public function scopeDebits($query)
    {
        return $query->where('type', 'debit');
    }

    public function scopeCredits($query)
    {
        return $query->where('type', 'credit');
    }

    /**
     * Scope untuk filter berdasarkan reference_type
     */
    public function scopeBookings($query)
    {
        return $query->where('reference_type', 'booking');
    }

    public function scopeTopups($query)
    {
        return $query->where('reference_type', 'topup');
    }

    public function scopeRefunds($query)
    {
        return $query->where('reference_type', 'refund');
    }
}
