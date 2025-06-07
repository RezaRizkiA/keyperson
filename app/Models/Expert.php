<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expert extends Model
{
    protected $table = 'experts';
    protected $guarded = ['id'];

    protected $casts = [
        'expertise_id' => 'array',
        'experiences' => 'array',
        'licenses' => 'array',
        'gallerys' => 'array',
    ];
}
