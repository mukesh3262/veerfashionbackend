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
         $productImages = ProductImageResource::collection($this->whenLoaded('images'))->resolve();
        return [
            'id' => $this->uuid,
            'code' => $this->code,
            'name' => $this->name,
            'description' => $this->description,
            'category_id' => $this->category_id,
            'price' =>  '₹' . number_format($this->base_price, 2),
            'product_images' =>  array_map(fn($image) => $image['image_url'], $productImages),
            'variants' => ProductVariantsResource::collection($this->whenLoaded('variants'))->resolve(),
            // 'category' => CategoryResource::make($this->whenLoaded('category'))->resolve(),
        ];
    }
}
