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
        // OAuth tokens - SANGAT SENSITIF, jangan pernah ekspos ke frontend
        'google_id',
        'google_access_token',
        'google_refresh_token',
        'google_token_expires_at',
        'google_scopes',
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

        if (str_starts_with($this->picture, 'http')) {
            return $this->picture;
        }
        
        return Storage::disk('s3')->url($this->picture);
    }

    public function getHasPasswordAttribute()
    {
        return !is_null($this->password);
    }

    public function client()
    {
        return $this->hasOne(Client::class, 'user_id');
    }

    /**
     * Relasi ke Client via client_id (user adalah karyawan perusahaan ini)
     * Berbeda dengan client() yang untuk pemilik/HRD yang mendaftarkan perusahaan
     */
    public function company()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    /**
     * Cek apakah user adalah member dari sebuah perusahaan (B2B)
     */
    public function isCorporateMember(): bool
    {
        return !is_null($this->client_id);
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
