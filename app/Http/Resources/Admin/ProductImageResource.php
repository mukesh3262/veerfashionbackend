<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class ProductImageResource extends JsonResource
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
            'product_id' => $this->product_id,
            'image' => $this->image,
            'image_url' => $this->image ? Storage::url(config('filesystems.module_paths.products') . $this->image) : null,
            'is_default' => $this->is_default,
        ];
    }
}
