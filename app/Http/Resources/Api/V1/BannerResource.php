<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class BannerResource extends JsonResource
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
            'title' => $this->title,
            'image' => $this->image ? Storage::url(config('filesystems.module_paths.banners') . $this->image) : null,
            'href' => $this->href,
            'subtitle' => $this->subtitle,
            "description" =>$this->description,
            'position' => $this->position,
        ];
    }
}
