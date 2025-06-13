<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expertise extends Model
{
    protected $table   = 'expertises';
    protected $guarded = ['id'];

    public function category()
    {
        // Setiap expertise “belongTo / milik” satu category
        return $this->belongsTo(ExpertiseCategory::class, 'category_id');
    }

    // Mendapatkan semua expert yang memiliki expertise ini (JSON)
    public function getExpertsAttribute()
    {
        return Expert::whereJsonContains('expertise_id', (string) $this->id)->get();
    }

    // Jumlah expert yang punya expertise ini (JSON)
    public function getExpertsCountAttribute()
    {
        return Expert::whereJsonContains('expertise_id', (string) $this->id)->count();
    }
}
