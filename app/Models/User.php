<?php
namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $table   = 'users';
    protected $guarded = ['id'];

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
