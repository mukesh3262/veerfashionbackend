<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ProductImage;
use App\Models\ProductVariantImage;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // Example Product
        $product = Product::create([
            'uuid'        => Str::uuid(),
            'name'        => 'Cargo Shorts',
            'description' => 'Tactical cargo shorts for summer',
            'base_price'  => 49.99,
            'status'      => 1,
        ]);

        // Product Images (common gallery)
        ProductImage::insert([
            [
                'uuid'       => Str::uuid(),
                'product_id' => $product->id,
                'image'      => 'products/cargo-shorts/main1.jpg',
                'is_default' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'uuid'       => Str::uuid(),
                'product_id' => $product->id,
                'image'      => 'products/cargo-shorts/main2.jpg',
                'is_default' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Variants
        $variants = [
            [
                'sku'        => 'CS-BLK-M',
                'brand'      => 'Levis',
                'attributes' => json_encode(['color' => 'Black', 'size' => 'M']),
                'price'      => 49.99,
                'stock'      => 50,
                'images'     => [
                    'products/cargo-shorts/black1.jpg',
                    'products/cargo-shorts/black2.jpg'
                ],
            ],
            [
                'sku'        => 'CS-BLU-L',
                'brand'      => 'Levis',
                'attributes' => json_encode(['color' => 'Blue', 'size' => 'L']),
                'price'      => 54.99,
                'stock'      => 30,
                'images'     => [
                    'products/cargo-shorts/blue1.jpg',
                    'products/cargo-shorts/blue2.jpg'
                ],
            ],
            [
                'sku'        => 'CS-GRN-XL',
                'brand'      => 'Levis',
                'attributes' => json_encode(['color' => 'Green', 'size' => 'XL']),
                'price'      => 59.99,
                'stock'      => 20,
                'images'     => [
                    'products/cargo-shorts/green1.jpg',
                    'products/cargo-shorts/green2.jpg'
                ],
            ]
        ];

        foreach ($variants as $variantData) {
            $variant = ProductVariant::create([
                'uuid'       => Str::uuid(),
                'product_id' => $product->id,
                'sku'        => $variantData['sku'],
                'brand'      => $variantData['brand'],
                'attributes' => $variantData['attributes'],
                'price'      => $variantData['price'],
                'stock'      => $variantData['stock'],
            ]);

            foreach ($variantData['images'] as $key => $img) {
                ProductVariantImage::create([
                    'uuid'               => Str::uuid(),
                    'product_variant_id' => $variant->id,
                    'image'              => $img,
                    'is_default'         => $key === 0, // first image as default
                ]);
            }
        }
    }
}
