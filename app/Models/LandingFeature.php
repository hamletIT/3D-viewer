<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LandingFeature extends Model
{
    protected $fillable = [
        'section_id', 'icon', 'title', 'description', 'sort_order', 'active',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    public function section()
    {
        return $this->belongsTo(LandingSection::class);
    }
}
