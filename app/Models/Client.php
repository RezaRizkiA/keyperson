<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Client extends Model
{
    protected $table = 'clients';
    protected $guarded = ['id'];
    protected $appends = ['logo_url', 'banner_url', 'photo_url'];

    // Cast “attributes” ke array otomatis
    protected $casts = [
        'expertise_id' => 'array',
    ];

    public function getLogoUrlAttribute()
    {
        return $this->logo ? Storage::url($this->logo) : null;
    }

    public function getBannerUrlAttribute()
    {
        return $this->banner_background ? Storage::url($this->banner_background) : null;
    }

    public function getPhotoUrlAttribute()
    {
        return $this->author_photo ? Storage::url($this->author_photo) : null;
    }
}
