<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Builder;

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
        'is_active' => 'boolean',
        'deactivated_at' => 'datetime',
    ];

    // =========================================================================
    // ATTRIBUTES
    // =========================================================================

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

    protected function name(): Attribute
    {
        return Attribute::make(
            set: function (string $value) {
                return ucwords(strtolower(trim($value)));
            },
        );
    }

    // =========================================================================
    // SCOPES
    // =========================================================================

    /**
     * Scope untuk filter user aktif saja
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope untuk filter user nonaktif saja
     */
    public function scopeInactive(Builder $query): Builder
    {
        return $query->where('is_active', false);
    }

    // =========================================================================
    // RELATIONSHIPS
    // =========================================================================

    /**
     * Client yang dimiliki/didaftarkan oleh user ini (jika dia HRD/Owner)
     * Berbeda dengan company() yang menunjukkan tempat user bekerja
     */
    public function ownedClient()
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

    public function expert()
    {
        return $this->hasOne(Expert::class, 'user_id');
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    /**
     * User yang menonaktifkan user ini
     */
    public function deactivator()
    {
        return $this->belongsTo(User::class, 'deactivated_by');
    }

    // =========================================================================
    // HELPER METHODS
    // =========================================================================

    /**
     * Cek apakah user aktif
     */
    public function isActive(): bool
    {
        return $this->is_active === true;
    }

    /**
     * Helper: Cek apakah user adalah owner dari client-nya
     */
    public function isClientOwner(): bool
    {
        return $this->ownedClient?->id === $this->client_id;
    }

    /**
     * Cek apakah user adalah member dari sebuah perusahaan (B2B)
     */
    public function isCorporateMember(): bool
    {
        return !is_null($this->client_id);
    }

    public function hasRole(string $role): bool
    {
        return in_array($role, $this->roles ?? [], true);
    }

    /**
     * Nonaktifkan user
     * 
     * @param int|null $deactivatedBy ID user yang menonaktifkan
     */
    public function deactivate(?int $deactivatedBy = null): bool
    {
        return $this->update([
            'is_active' => false,
            'deactivated_at' => now(),
            'deactivated_by' => $deactivatedBy,
        ]);
    }

    /**
     * Aktifkan kembali user
     */
    public function activate(): bool
    {
        return $this->update([
            'is_active' => true,
            'deactivated_at' => null,
            'deactivated_by' => null,
        ]);
    }
}

