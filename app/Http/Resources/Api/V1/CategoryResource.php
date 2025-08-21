<?php

namespace App\Http\Resources\Api\V1;

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
        $this->loadMissing(['parentCategory', 'subcategories.products']);

        $minPrice = $this->subcategories->flatMap->products->min('base_price') ?? 0;
        $maxPrice = $this->subcategories->flatMap->products->max('base_price') ?? 0;

        return [
            'id' => $this->uuid,
            'name' => $this->name,
            'description' => $this->description,
            'icon' => $this->icon,
            'icon_url' => $this->icon_url,
            'primary_id' => $this->id,
            'min_price' => $minPrice,
            'max_price' => $maxPrice,
            'parent_category' => $this->parentCategory ? [
                'uuid' => $this->parentCategory?->uuid,
                'id' => $this->parentCategory?->id,
                'name' => $this->parentCategory?->name
            ] : null
        ];
    }
}
