<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $productImages = [];
        $price = 0;
        $variants = ProductVariantsResource::collection($this->whenLoaded('variants'))->resolve();
        if (!empty($variants)) {
            $price = $variants[0]['price'];
            $productImages = $variants[0]['variant_images'] ?? [];
        } else {
            $price = $this->base_price;
            $imageCollection = ProductImageResource::collection($this->whenLoaded('images'))->resolve();
            $productImages = array_map(fn($image) => $image['image_url'], $imageCollection);
        }
        
        return [
            'id' => $this->uuid,
            'code' => $this->code,
            'name' => $this->name,
            'description' => $this->description,
            'category_id' => $this->category_id,
            'price' =>  '₹' . number_format($price, 2),
            'product_images' =>  $productImages,
            'variants' => ProductVariantsResource::collection($this->whenLoaded('variants'))->resolve(),
            // 'category' => CategoryResource::make($this->whenLoaded('category'))->resolve(),
        ];
    }
}
