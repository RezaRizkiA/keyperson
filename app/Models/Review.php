<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'rating' => 'integer',
        'helpful_count' => 'integer',
    ];

    /**
     * Relationship to User (who wrote the review)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relationship to Appointment (reviewed session)
     */
    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    /**
     * Relationship to Expert (who was reviewed)
     */
    public function expert()
    {
        return $this->belongsTo(Expert::class);
    }

    /**
     * Relationship to Skill (session topic)
     */
    public function skill()
    {
        return $this->belongsTo(Skill::class);
    }
}
