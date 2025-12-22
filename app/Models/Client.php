<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Client extends Model
{
    protected $table = 'clients';
    protected $guarded = ['id'];
    protected $appends = ['logo_url', 'cover_url'];

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
     * Relationship to Appointments
     * Client can have many appointments
     */
    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function getLogoUrlAttribute()
    {
        if (!$this->logo) return null;

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
        if (!$this->cover_image) return null;

        if (filter_var($this->cover_image, FILTER_VALIDATE_URL)) {
            return $this->cover_image;
        }

        return Storage::disk('s3')->url($this->cover_image);
    }
}
