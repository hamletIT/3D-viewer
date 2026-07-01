<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SocialDiscount extends Model
{
    protected $fillable = [
        'platform', 'label', 'icon', 'discount_percent',
        'description', 'share_url', 'is_active', 'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order');
    }

    public function userPosts()
    {
        return $this->hasMany(UserSocialPost::class);
    }
}
