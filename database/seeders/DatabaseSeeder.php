<?php

namespace Database\Seeders;

use App\Models\Manipulation;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@workshop.test',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
        ]);

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('test123'),
            'role' => 'user',
        ]);

        User::factory()->create([
            'name' => 'Moderator',
            'email' => 'moderator@workshop.test',
            'password' => Hash::make('mod123'),
            'role' => 'moderator',
        ]);

        \App\Models\Setting::create([
            'key' => 'app_name',
            'value' => 'Workshop Admin',
            'type' => 'string',
            'description' => 'The application display name.',
        ]);

        \App\Models\Setting::create([
            'key' => 'landing_hero_title',
            'value' => '3D Workshop Explorer',
            'type' => 'string',
            'description' => 'Landing page hero title.',
        ]);

        \App\Models\Setting::create([
            'key' => 'landing_hero_subtitle',
            'value' => 'Upload. Explore. Create. A powerful 3D model viewer with real-time object manipulation.',
            'type' => 'text',
            'description' => 'Landing page hero subtitle.',
        ]);

        \App\Models\Setting::create([
            'key' => 'landing_cta_text',
            'value' => 'Get Started',
            'type' => 'string',
            'description' => 'Landing page call-to-action button text.',
        ]);

        \App\Models\Setting::create([
            'key' => 'landing_show_login',
            'value' => 'true',
            'type' => 'boolean',
            'description' => 'Show login button on landing page.',
        ]);

        \App\Models\Setting::create([
            'key' => 'landing_show_register',
            'value' => 'true',
            'type' => 'boolean',
            'description' => 'Show register button on landing page.',
        ]);

        \App\Models\Setting::create([
            'key' => 'landing_features',
            'value' => json_encode([
                ['title' => '3D Model Viewer', 'description' => 'Upload and explore 3D models in real-time with full walkthrough controls.', 'icon' => '🎮'],
                ['title' => 'Object Placement', 'description' => 'Place, scale, rotate, and color objects directly in the 3D scene.', 'icon' => '🎯'],
                ['title' => 'Real-time Controls', 'description' => 'Walk through your scene with WASD, transform objects with intuitive gizmos.', 'icon' => '⚡'],
            ]),
            'type' => 'json',
            'description' => 'Landing page feature blocks (JSON array with title, description, icon).',
        ]);

        // Keyboard shortcut defaults
        $shortcuts = [
            'kbd_recolor'  => ['KeyK', 'Recolor hovered mesh (cycles manipulations)'],
            'kbd_duplicate' => ['KeyH', 'Duplicate hovered object'],
            'kbd_select'   => ['KeyL', 'Select object for transform controls'],
            'kbd_place'    => ['KeyO', 'Open file picker to place an OBJ'],
            'kbd_delete'   => ['Delete', 'Delete selected/hovered object'],
            'kbd_undo'     => ['KeyZ', 'Undo last action'],
            'kbd_translate'=> ['KeyG', 'Set transform mode to translate'],
            'kbd_rotate'   => ['KeyR', 'Set transform mode to rotate'],
            'kbd_scale'    => ['KeyT', 'Set transform mode to scale'],
            'kbd_fwd'      => ['KeyW', 'Move forward'],
            'kbd_back'     => ['KeyS', 'Move backward'],
            'kbd_left'     => ['KeyA', 'Strafe left'],
            'kbd_right'    => ['KeyD', 'Strafe right'],
            'kbd_up'       => ['KeyE', 'Move up (fly mode)'],
            'kbd_down'     => ['KeyQ', 'Move down (fly mode)'],
            'kbd_sprint'   => ['ShiftLeft', 'Sprint (hold)'],
        ];
        foreach ($shortcuts as $key => [$value, $desc]) {
            \App\Models\Setting::create([
                'key' => $key,
                'value' => $value,
                'type' => 'string',
                'description' => $desc,
            ]);
        }

        $this->call(PlanSeeder::class);
        $this->call(TextureSeeder::class);
        $this->call(InstructionSeeder::class);
        $this->call(LandingSectionSeeder::class);

        Manipulation::create([
            'model_name' => 'Default Cube',
            'color' => '#1180d4',
            'random_color' => true,
            'colors' => ['#9e9e9e', '#808080', '#636363'],
            'scale' => 1.00,
            'position_x' => 0, 'position_y' => 0, 'position_z' => 0,
            'rotation_x' => 0, 'rotation_y' => 0, 'rotation_z' => 0,
            'roughness' => 0.70,
            'metalness' => 0.10,
            'style' => 'solid',
        ]);

        Manipulation::create([
            'model_name' => 'Metallic Red',
            'color' => '#ef4444',
            'scale' => 1.50,
            'position_x' => 2, 'position_y' => 0, 'position_z' => 0,
            'rotation_x' => 0, 'rotation_y' => 45, 'rotation_z' => 0,
            'roughness' => 0.20,
            'metalness' => 0.90,
            'style' => 'solid',
        ]);

        Manipulation::create([
            'model_name' => 'Glass Blue',
            'color' => '#e44d0c',
            'scale' => 0.80,
            'position_x' => -2, 'position_y' => 0.5, 'position_z' => 0,
            'rotation_x' => 0, 'rotation_y' => 0, 'rotation_z' => 0,
            'roughness' => 0.05,
            'metalness' => 0.00,
            'style' => 'solid',
        ]);

        Manipulation::create([
            'model_name' => 'Wireframe Green',
            'color' => '#22c55e',
            'scale' => 1.20,
            'position_x' => 0, 'position_y' => 0, 'position_z' => 2,
            'rotation_x' => 30, 'rotation_y' => 0, 'rotation_z' => 15,
            'roughness' => 0.50,
            'metalness' => 0.30,
            'style' => 'wireframe',
        ]);
    }
}
