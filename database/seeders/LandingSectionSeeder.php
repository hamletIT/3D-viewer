<?php

namespace Database\Seeders;

use App\Models\LandingFeature;
use App\Models\LandingSection;
use Illuminate\Database\Seeder;

class LandingSectionSeeder extends Seeder
{
    public function run(): void
    {
        $brand = LandingSection::create([
            'slug' => 'brand',
            'type' => 'brand',
            'title' => '3D Workshop Explorer',
            'icon' => '⚒️',
            'link_url' => config('APP_URL'),
            'link_text' => '/',
            'sort_order' => 1,
            'active' => true,
        ]);

        $hero = LandingSection::create([
            'slug' => 'hero',
            'type' => 'hero',
            'title' => '3D Workshop Explorer',
            'subtitle' => 'Upload. Explore. Create. A powerful 3D model viewer with real-time object manipulation.',
            'icon' => '⚒️',
            'link_text' => 'Get Started',
            'data' => [
                'show_login' => true,
                'show_register' => true,
                'show_how_to' => true,
                'login_text' => 'I already have an account',
            ],
            'sort_order' => 2,
            'active' => true,
        ]);

        $features = LandingSection::create([
            'slug' => 'features',
            'type' => 'features',
            'title' => 'Everything you need',
            'subtitle' => 'Explore, manipulate, and create with powerful 3D tools.',
            'sort_order' => 3,
            'active' => true,
        ]);

        LandingFeature::create([
            'section_id' => $features->id,
            'icon' => '🎮',
            'title' => '3D Model Viewer',
            'description' => 'Upload and explore 3D models in real-time with full walkthrough controls.',
            'sort_order' => 1,
        ]);
        LandingFeature::create([
            'section_id' => $features->id,
            'icon' => '🎯',
            'title' => 'Object Placement',
            'description' => 'Place, scale, rotate, and color objects directly in the 3D scene.',
            'sort_order' => 2,
        ]);
        LandingFeature::create([
            'section_id' => $features->id,
            'icon' => '⚡',
            'title' => 'Real-time Controls',
            'description' => 'Walk through your scene with WASD, transform objects with intuitive gizmos.',
            'sort_order' => 3,
        ]);

        $scan = LandingSection::create([
            'slug' => 'scan',
            'type' => 'scan',
            'title' => 'Scan Real Objects with 3D Snap',
            'icon' => '📱',
            'content' => 'Use the <strong>3D Snap</strong> mobile app to scan real-world objects with your phone camera and import them directly into the workshop.',
            'subtitle' => 'Export scans as OBJ and import them into the workshop.',
            'sort_order' => 4,
            'active' => true,
        ]);

        LandingFeature::create([
            'section_id' => $scan->id,
            'icon' => '▶️',
            'title' => 'Android',
            'description' => 'https://play.google.com/store/apps/details?id=ai.polycam',
            'sort_order' => 1,
        ]);
        LandingFeature::create([
            'section_id' => $scan->id,
            'icon' => '🍎',
            'title' => 'iPhone',
            'description' => 'https://apps.apple.com/us/app/3d-snap-lidar-scanner-ruler/id6477467417',
            'sort_order' => 2,
        ]);

        $build = LandingSection::create([
            'slug' => 'build',
            'type' => 'features',
            'title' => '📋 The Master Image-to-3D Prompt',
            'subtitle' => 'Prompt for Photo- to -3D AI Engines',
            'content' => "Prompt:\nConstruct an accurate, manifold, watertight 3D architectural asset based precisely on the uploaded image layout. Execute a clean vertical extrusion of the 2D floor plan lines to form rigid, straight structural walls with a uniform height of 3 meters. Honor all layout section measurements, wall thicknesses, window cutouts, and door openings explicitly. The final topology must feature clean, error-free geometry with distinct material zones. Generate two unified output files: a perfectly indexed .obj file containing all geometric vertices and faces, and an accompanying .mtl file mapping accurate PBR material assignments (concrete, wood, glass) matching the visual cues of the source image. No floating artifacts, no non-manifold edges, and no open gaps.\n\n⚙️ 3 Crucial Rules for Your Input Photos\nAn AI can only \"examine everything perfectly\" if the source image is highly readable. For the best 3D structural results:\n\nUse High Contrast & Clean Textures: Ensure the layout image has stark, clear lines separating rooms and walls. Blurry lines result in melted or warped 3D walls.\n\nIsolate the Object: If you are trying to turn a rendering of a whole house into a 3D asset, make sure the background is a solid color or transparent PNG so the AI doesn't accidentally turn the sky or grass into part of your .obj file.\n\nSpecify the Scale Tool: Once the tool finishes generating, use the platform's built-in Resize/Scale tool (available natively in most 2026 pipelines like Meshy or Tripo) to manually lock in your real-world width and height metrics before you hit download.",
            'icon' => '🤖  ⚡  🧠  🔮',
            'sort_order' => 5,
            'active' => true,
        ]);

        LandingFeature::create([
            'section_id' => $build->id,
            'icon' => '⚡',
            'title' => 'First Step',
            'description' => 'Prepare a floor plan or top-down image of your house or property.',
            'sort_order' => 1,
        ]);
        LandingFeature::create([
            'section_id' => $build->id,
            'icon' => '🧠',
            'title' => 'Second Step',
            'description' => 'Using the top-down image, utilize the AI tool to generate and place the desired objects within the property.',
            'sort_order' => 2,
        ]);
        LandingFeature::create([
            'section_id' => $build->id,
            'icon' => '🤖',
            'title' => 'Third Step',
            'description' => 'Once the AI generation is complete, you will receive the output files (.obj, .mtl, and any associated .png texture files). Upload all of these files to our project.',
            'sort_order' => 3,
        ]);

        LandingSection::create([
            'slug' => 'footer',
            'type' => 'footer',
            'title' => config('app.name', 'Workshop'),
            'subtitle' => 'All rights reserved.',
            'icon' => '⚒️',
            'sort_order' => 6,
            'active' => true,
        ]);

        $this->command->info('Seeded landing sections and features.');
    }
}
