<?php

declare(strict_types=1);

namespace App\Pipelines\Api\V1\Product;

use Closure;

class SortPipeline
{
    public function __construct(protected $sort) {}

    public function handle($builder, Closure $next)
    {
        $sort = $this->sort;

        if (empty($sort)) {
            return $next($builder->orderByDesc('created_at'));
        }

        [$column, $sortBy] = [key($sort), reset($sort)];

        return $next($builder->orderBy($column, $sortBy));
    }
}
