<?php

namespace Database\Seeders;

use App\Models\Texture;
use Illuminate\Database\Seeder;

class TextureSeeder extends Seeder
{
    public function run(): void
    {
        $adminId = \App\Models\User::where('role', 'admin')->value('id') ?? 1;

        $textures = [];
        for ($i = 1; $i <= 17; $i++) {
            $ext = $i <= 3 ? 'jpg' : 'png';
            $textures[] = ['name' => "textura-$i", 'file' => "textura-$i.$ext", 'original' => "textura-$i.$ext"];
        }

        foreach ($textures as $t) {
            Texture::create([
                'user_id' => $adminId,
                'name' => $t['name'],
                'file_path' => 'default-textures/' . $t['file'],
                'original_name' => $t['original'],
            ]);
        }

        $this->command->info('Seeded ' . count($textures) . ' default textures.');
        $this->command->warn('Put actual texture images in storage/app/public/default-textures/');
    }
}
