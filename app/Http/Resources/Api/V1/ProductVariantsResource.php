<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductVariantsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $this->load('images');
        return [
            'id' => $this->uuid,
            'product_id' => $this->product_id,
            'sku' => $this->sku,
            'brand' => $this->brand,
            'attributes' => json_decode($this->attributes, true),
            'price' => $this->price,
            'stock' => $this->stock,
            'variant_images' => ProductVariantImageResource::collection($this->whenLoaded('images'))->resolve(),
        ];
    }
}
