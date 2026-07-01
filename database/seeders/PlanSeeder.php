<?php

namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    public function run(): void
    {
        Plan::create([
            'name' => 'Free',
            'slug' => 'free',
            'max_sessions' => 5,
            'max_objects_per_scene' => 5,
            'price' => 0,
            'icon' => '🆓',
            'duration_days' => null,
            'sort_order' => 1,
            'active' => true,
        ]);

        Plan::create([
            'name' => 'Pro',
            'slug' => 'pro',
            'max_sessions' => 50,
            'max_objects_per_scene' => 50,
            'price' => 9.99,
            'icon' => '⭐',
            'duration_days' => 30,
            'sort_order' => 2,
            'active' => true,
        ]);

        Plan::create([
            'name' => 'Expert',
            'slug' => 'expert',
            'max_sessions' => -1,
            'max_objects_per_scene' => -1,
            'price' => 29.99,
            'icon' => '👑',
            'duration_days' => null,
            'sort_order' => 3,
            'active' => true,
        ]);

        $this->command->info('Seeded plans.');
    }
}
