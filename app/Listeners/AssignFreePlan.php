<?php

namespace App\Listeners;

use App\Models\Plan;
use App\Models\UserPlan;
use Illuminate\Auth\Events\Registered;

class AssignFreePlan
{
    public function handle(Registered $event): void
    {
        $free = Plan::where('slug', 'free')->first();
        if ($free) {
            UserPlan::create([
                'user_id' => $event->user->id,
                'plan_id' => $free->id,
                'starts_at' => now(),
                'expires_at' => null,
            ]);
        }
    }
}
