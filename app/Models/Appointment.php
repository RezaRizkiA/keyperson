<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $table   = 'appointments';
    protected $guarded = ['id'];

    protected $casts = [
        'date_time'      => 'datetime',
        'calendar_token' => 'array',
    ];

    protected $fillable = [
        'user_id',
        'expert_id',
        'appointment',
        'date_time',
        'hours',
        'price',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function expert()
    {
        return $this->belongsTo(Expert::class);
    }
}
