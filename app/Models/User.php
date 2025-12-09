<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{
    protected $table   = 'users';
    protected $guarded = ['id'];
    protected $appends = ['picture_url', 'has_password'];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'roles'             => 'array',
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
        'google_token_expires_at' => 'datetime',
        'google_scopes' => 'array',
        'calendar_connected' => 'boolean',
    ];

    public function getPictureUrlAttribute()
    {
        if (!$this->picture) {
            return asset('assets/images/profile/user-3.jpg');
        }

        // Storage::url() otomatis mendeteksi apakah pakai S3 atau Local
        // dan menghasilkan URL lengkap (https://...)
        return Storage::url($this->picture);
    }

    public function getHasPasswordAttribute()
    {
        return !is_null($this->password);
    }

    public function client()
    {
        return $this->hasOne(Client::class, 'user_id');
    }

    public function expert()
    {
        return $this->hasOne(Expert::class, 'user_id');
    }

    public function hasRole(string $role): bool
    {
        return in_array($role, $this->roles ?? [], true);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
}
