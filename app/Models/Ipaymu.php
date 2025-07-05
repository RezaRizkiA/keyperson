<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ipaymu extends Model
{
    use HasFactory;

    protected $table = 'ipaymu'; // Pastikan nama tabel benar

    protected $fillable = [
        'code',
        'name',
        'description',
        'channels',
    ];

}
