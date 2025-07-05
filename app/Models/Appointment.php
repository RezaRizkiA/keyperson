<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $table   = 'appointments';
    protected $guarded = ['id'];

    protected $casts = [
        'date_time' => 'datetime',
    ];

    protected $fillable = [
        'user_id',
        'expert_id',
        'appointment',
        'date_time',
        'google_calendar_event_id',
        'hours',
        'price',
        'status',
        'payment_status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function expert()
    {
        return $this->belongsTo(Expert::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}