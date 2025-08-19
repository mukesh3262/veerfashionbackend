<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'Clothing' => [
                'T-Shirts',
                'Shirts',
                'Jeans',
                'Jackets',
                'Suits',
            ],
            'Accessories' => [
                'Belts',
                'Wallets',
                'Sunglasses',
                'Bags',
            ],
            // 'Footwear' => [
            //     'Formal Shoes',
            //     'Casual Shoes',
            //     'Sneakers',
            // ],
            'Fragrances' => [
                'Perfumes',
                'Body Sprays',
            ],
        ];

        foreach ($categories as $mainCategory => $subCategories) {
            $main = Category::create([
                'uuid' => Str::uuid(),
                'name' => $mainCategory,
                'slug' => Str::slug($mainCategory),
                'icon' => Str::slug($mainCategory) . '.png', // Adjust as needed
                'description' => $mainCategory . ' collection',
                'is_active' => true,
                'sort_order' => rand(1, 100),
            ]);

            foreach ($subCategories as $subCategory) {
                Category::create([
                    'uuid' => Str::uuid(),
                    'parent_id' => $main->id,
                    'name' => $subCategory,
                    'slug' => Str::slug($subCategory),
                    'icon' => Str::slug($subCategory) . '.png',
                    'description' => $subCategory . ' in ' . $mainCategory,
                    'is_active' => true,
                    'sort_order' => rand(1, 100),
                ]);
            }
        }
    }
}
