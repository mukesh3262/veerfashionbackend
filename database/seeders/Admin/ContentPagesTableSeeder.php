<?php

declare(strict_types=1);

namespace Database\Seeders\Admin;

use App\Models\ContentPage;
use Illuminate\Database\Seeder;

class ContentPagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ContentPage::insert([
            [
                'title' => 'Privacy Policy',
                'slug' => 'privacy-policy',
                'content' => '<p>Privacy Policy Content</p>',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Terms & Conditions',
                'slug' => 'terms-conditions',
                'content' => '<p>Terms & Conditions Content</p>',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'About Us',
                'slug' => 'about-us',
                'content' => '<p>About Us Content</p>',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Cancellation Policy',
                'slug' => 'cancellation-policy',
                'content' => '<p>Cancellation Policy Content</p>',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Refund Policy',
                'slug' => 'refund-policy',
                'content' => '<p>Refund Policy Content</p>',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
