<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'photo',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function fileTypes(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(FileType::class);
    }

    public function userPlans(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(UserPlan::class);
    }

    public function getCurrentPlan(): ?Plan
    {
        $up = $this->userPlans()
            ->where(function ($q) {
                $q->whereNull('expires_at')->orWhere('expires_at', '>', now());
            })
            ->latest()
            ->first();
        return $up?->plan;
    }

    public function upgradeRequests(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(UpgradeRequest::class);
    }
}
