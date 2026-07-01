<?php

namespace Database\Seeders;

use App\Models\SocialDiscount;
use Illuminate\Database\Seeder;

class SocialDiscountSeeder extends Seeder
{
    public function run(): void
    {
        SocialDiscount::create([
            'platform' => 'linkedin',
            'label' => 'LinkedIn',
            'icon' => '💼',
            'discount_percent' => 10,
            'description' => 'Post about our app on LinkedIn and get 10% off your plan.',
            'share_url' => 'https://www.linkedin.com/sharing/share-offsite/?url={{URL}}',
            'is_active' => true,
            'sort_order' => 0,
        ]);

        SocialDiscount::create([
            'platform' => 'instagram',
            'label' => 'Instagram',
            'icon' => '📸',
            'discount_percent' => 15,
            'description' => 'Share our app on Instagram stories and get 15% off your plan.',
            'share_url' => 'https://instagram.com',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        SocialDiscount::create([
            'platform' => 'facebook',
            'label' => 'Facebook',
            'icon' => '👍',
            'discount_percent' => 10,
            'description' => 'Share our app on Facebook and get 10% off your plan.',
            'share_url' => 'https://www.facebook.com/sharer/sharer.php?u={{URL}}',
            'is_active' => true,
            'sort_order' => 2,
        ]);
    }
}
