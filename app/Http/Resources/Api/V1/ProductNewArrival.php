<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductNewArrival extends JsonResource
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
            'name' => $this->name,
            'description' => $this->description,
            'price' => '₹' . number_format($this->base_price, 2),
            'images' => array_map(fn($image) => $image['image_url'], $productImages),
        ];
    }
}
