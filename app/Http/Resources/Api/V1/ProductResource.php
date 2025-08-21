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
        return [
            'id' => $this->uuid,
            'code' => $this->code,
            'name' => $this->name,
            'description' => $this->description,
            'category_id' => $this->category_id,
            'base_price' => $this->base_price,
            'product_images' => ProductImageResource::collection($this->whenLoaded('images'))->resolve(),
            'variants' => ProductVariantsResource::collection($this->whenLoaded('variants'))->resolve(),
            'category' => CategoryResource::make($this->whenLoaded('category'))->resolve(),
        ];
    }
}
