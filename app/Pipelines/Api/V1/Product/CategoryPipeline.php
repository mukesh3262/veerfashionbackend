<?php

declare(strict_types=1);

namespace App\Pipelines\Api\V1\Product;

class CategoryPipeline
{
    protected $categoryId;

    public function __construct($categoryId)
    {
        $this->categoryId = $categoryId;
    }

    public function handle($query, $next)
    {
        if ($this->categoryId) {
            $query->where('category_id', $this->categoryId);
        }

        return $next($query);
    }
}