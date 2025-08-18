<?php

declare(strict_types=1);

namespace App\Pipelines\Admin\Common;

use Closure;

class SortPipeline
{
    public function __construct(protected $sort) {}

    public function handle($builder, Closure $next)
    {
        $sort = $this->sort;

        if (empty($sort)) {
            return $next($builder);
        }

        [$column, $sortBy] = [key($sort), reset($sort)];

        return $next($builder->orderBy($column, $sortBy));
    }
}
