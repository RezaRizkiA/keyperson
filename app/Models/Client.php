<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Client extends Model
{
    protected $table = 'clients';

    protected $guarded = ['id'];

    protected $appends = ['logo_url', 'cover_url'];

    /**
     * Mutator: Auto-prepend https:// ke website jika belum ada
     * Input: mysite.com → Save: https://mysite.com
     * Input: www.mysite.com → Save: https://www.mysite.com
     * Input: https://mysite.com → Save: https://mysite.com (unchanged)
     */
    protected function website(): Attribute
    {
        return Attribute::make(
            set: function (?string $value) {
                if (empty($value)) {
                    return null;
                }

                // 1. Bersihkan spasi di awal/akhir (Sering terjadi saat copy-paste)
                $value = trim($value);

                // 2. Jika sudah ada protocol (http:// atau https://), return as-is
                if (preg_match('/^https?:\/\//i', $value)) {
                    return $value;
                }

                // 3. Tambahkan https:// dan bersihkan "/" di awal jika user mengetik "/google.com"
                return 'https://'.ltrim($value, '/');
            }
        );
    }

    /**
     * Relationship to User model
     * Client belongs to a user
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relationship to Skills
     * Client can have many skills
     */
    public function skills()
    {
        return $this->belongsToMany(Skill::class, 'client_skill');
    }

    /**
     * B2B: Relasi ke Users via client_id (karyawan perusahaan)
     * User dengan client_id = this.id adalah karyawan dari perusahaan ini
     */
    public function employees()
    {
        return $this->hasMany(User::class, 'client_id');
    }

    /**
     * B2B: Relasi ke ClientQuota (saldo deposit perusahaan)
     */
    public function quota()
    {
        return $this->hasOne(ClientQuota::class);
    }

    /**
     * B2B: Relasi ke QuotaLedger (history transaksi quota)
     */
    public function ledgers()
    {
        return $this->hasMany(QuotaLedger::class);
    }

    /**
     * B2B: Relasi ke Employee Invites
     */
    public function invites()
    {
        return $this->hasMany(EmployeeInvite::class);
    }

    /**
     * Relationship to Appointments through User
     * Client can have many appointments through their user
     */
    public function appointments()
    {
        return $this->hasManyThrough(
            Appointment::class,
            User::class,
            'id',        // Foreign key on users table
            'user_id',   // Foreign key on appointments table
            'user_id',   // Local key on clients table
            'id'         // Local key on users table
        );
    }

    public function getLogoUrlAttribute()
    {
        if (! $this->logo) {
            return null;
        }

        if (filter_var($this->logo, FILTER_VALIDATE_URL)) {
            return $this->logo;
        }

        // Generate URL S3 berdasarkan .env AWS_URL
        return Storage::disk('s3')->url($this->logo);
    }

    /**
     * Accessor untuk mendapatkan Full URL Cover
     * Cara panggil: $client->cover_url
     */
    public function getCoverUrlAttribute()
    {
        if (! $this->cover_image) {
            return null;
        }

        if (filter_var($this->cover_image, FILTER_VALIDATE_URL)) {
            return $this->cover_image;
        }

        return Storage::disk('s3')->url($this->cover_image);
    }
}
