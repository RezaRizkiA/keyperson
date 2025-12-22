<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expert extends Model
{
    protected $table   = 'experts';
    protected $guarded = ['id'];

    protected $casts = [
        'price'        => 'integer',
        'rating'       => 'decimal:2',
        'total_reviews' => 'integer',
        'total_sessions' => 'integer',
        'experiences'  => 'array',
        'licenses'     => 'array',
        'gallerys'     => 'array',
        'socials'      => 'array',
        'type'         => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function skills()
    {
        return $this->belongsToMany(Skill::class, 'expert_skill');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}
