<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LandingSection extends Model
{
    protected $fillable = [
        'slug', 'type', 'title', 'subtitle', 'content', 'icon',
        'image_path', 'link_url', 'link_text', 'data', 'sort_order', 'active',
    ];

    protected $casts = [
        'data' => 'array',
        'active' => 'boolean',
    ];

    public function features()
    {
        return $this->hasMany(LandingFeature::class, 'section_id')->where('active', true)->orderBy('sort_order');
    }

    public function scopeActive($q)
    {
        return $q->where('active', true);
    }
}
