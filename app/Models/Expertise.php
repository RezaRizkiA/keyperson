<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expertise extends Model
{
    protected $table   = 'expertises';
    protected $guarded = ['id'];

    public function childrens()
    {
        return $this->hasMany(Expertise::class, 'parent_id')->orderBy('order');
    }

    public function parent()
    {
        return $this->belongsTo(Expertise::class, 'parent_id');
    }

    public function childrensRecursive()
    {
        return $this->childrens()->with('childrensRecursive');
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
