<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class ProductVariantImageResource extends JsonResource
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
            'product_variant_id' => $this->product_variant_id,
            'image' => $this->image,
            'image_url' => $this->image ? Storage::url(config('filesystems.module_paths.products-variants') . $this->image) : null,
        ];
    }
}
