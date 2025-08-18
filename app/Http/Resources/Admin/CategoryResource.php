<?php

declare(strict_types=1);

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $this->loadMissing('parentCategory');

        return [
            'id' => $this->uuid,
            'name' => $this->name,
            'description' => $this->description,
            'icon' => $this->icon,
            'icon_url' => $this->icon_url,
            'is_active' => [
                'id' => $this->uuid,
                'isActive' => (bool) $this->is_active,
            ],
            'created_at' => $this->created_at->toIso8601String(),
            'updated_at' => $this->updated_at->toIso8601String(),
            'action' => ['id' => $this->uuid],
            'primary_id' => $this->id,
            'parent_category' => $this->parentCategory ? [
                'uuid' => $this->parentCategory?->uuid,
                'id' => $this->parentCategory?->id,
                'name' => $this->parentCategory?->name
            ] : null
        ];
    }
}
