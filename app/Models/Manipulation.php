<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Manipulation extends Model
{
    protected $fillable = [
        'user_id', 'session_id', 'model_name', 'color', 'scale',
        'position_x', 'position_y', 'position_z',
        'rotation_x', 'rotation_y', 'rotation_z',
        'roughness', 'metalness', 'style', 'random_color', 'colors',
    ];

    protected function casts(): array
    {
        return [
            'scale' => 'float',
            'position_x' => 'float',
            'position_y' => 'float',
            'position_z' => 'float',
            'rotation_x' => 'float',
            'rotation_y' => 'float',
            'rotation_z' => 'float',
            'roughness' => 'float',
            'metalness' => 'float',
            'random_color' => 'boolean',
            'colors' => 'array',
        ];
    }

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
