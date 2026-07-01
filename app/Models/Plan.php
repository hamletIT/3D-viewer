<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'max_sessions',
        'max_objects_per_scene',
        'price',
        'icon',
        'duration_days',
        'sort_order',
        'active',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'active' => 'boolean',
        ];
    }

    public function isUnlimited(): bool
    {
        return $this->max_sessions === -1;
    }
}
