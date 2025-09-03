<?php

namespace App\Http\Resources\Admin;

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
            'subtitle' => $this->subtitle,
            'description' => $this->description,
            'position' => $this->position,
            'href' => $this->href,
            'image' => $this->image,
            'image_url' => $this->image ? Storage::url(config('filesystems.module_paths.banners') . $this->image) : null,
            'is_active' => [
                'id' => $this->uuid,
                'isActive' => (bool) $this->is_active,
            ],
            'action' => ['id' => $this->uuid],
            'created_at' => $this->created_at->toIso8601String(),
            'updated_at' => $this->updated_at->toIso8601String(),
        ];
    }
}
