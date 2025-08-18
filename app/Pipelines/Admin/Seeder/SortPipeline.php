<?php

declare(strict_types=1);

namespace App\Pipelines\Admin\Seeder;

use Closure;

class SortPipeline
{
    public function __construct(protected $sort) {}

    public function handle($builder, Closure $next)
    {
        $sort = $this->sort;

        if ($sort) {
            foreach ($sort as $column => $direction) {
                $builder = $builder->sortBy($column, SORT_REGULAR, $direction === 'desc');
            }
        }

        return $next($builder);
    }
}
