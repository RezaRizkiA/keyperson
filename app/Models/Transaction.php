<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    protected $casts = [
        'payment_date'         => 'datetime',
        'expired_date'         => 'datetime',
        'trx_chacking'         => 'boolean',
        'trx_calender_process' => 'boolean',
    ];

    public function appointment():BelongsTo
    {
        return $this->belongsTo(Appointment::class);
    }
}
