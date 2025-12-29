<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $table   = 'appointments';
    protected $guarded = ['id'];

    protected $casts = [
        'date_time' => 'datetime',
        'guests' => 'array'
    ];

    protected $fillable = [
        'user_id',
        'expert_id',
        'skill_id',
        'date_time',
        'topic',
        'location_url',
        'google_calendar_event_id',
        'hours',
        'price',
        'status',
        'payment_status',
        'type',
        'guests'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function expert()
    {
        return $this->belongsTo(Expert::class, 'expert_id');
    }

    public function skill()
    {
        return $this->belongsTo(Skill::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
