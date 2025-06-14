<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appoinment extends Model
{
    protected $table   = 'appoinments';
    protected $guarded = ['id'];

    protected $casts = [
        'date_time'      => 'datetime',
        'calendar_token' => 'array',
    ];
}
