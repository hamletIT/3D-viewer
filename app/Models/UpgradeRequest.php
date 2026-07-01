<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UpgradeRequest extends Model
{
    protected $fillable = [
        'user_id',
        'plan_id',
        'name',
        'email',
        'phone',
        'status',
        'admin_notes',
    ];

    protected function casts(): array
    {
        return [
            'status' => 'string',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }
}
