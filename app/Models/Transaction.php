<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'appointment_id',
        'trx_desc',
        'sessionID',
        'url',
        'trx_id',
        'status',
        'sid',
        'feeDirection',
        'paymentName',
        'paymentNo',
        'refId',
        'total',
        'via',
        'payment_date',
        'expired_date',
        'amount',
        'trx_fee',
        'tax_system',
        'trx_chacking',
        'trx_calender_process',
        'channel',
        'type',
        'phone',
        'email',
        'name',
    ];

    protected $casts = [
        'payment_date' => 'datetime',
        'expired_date' => 'datetime',
        'trx_chacking' => 'boolean',
        'trx_calender_process' => 'boolean',
        'channels' => 'json', // Jika kolom 'channels' ada di tabel ini, jika tidak, bisa dihapus
    ];

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }
}
