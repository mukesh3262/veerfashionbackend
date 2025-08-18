<?php

declare(strict_types=1);

namespace App\Pipelines\Admin\Seeder;

use Closure;

class FilterPipeline
{
    public function __construct(public $filters) {}

    public function handle($builder, Closure $next)
    {
        if (empty($this->filters)) {
            return $next($builder);
        }

        $filters = array_filter($this->filters, fn ($filter) => ! is_null($filter['value']));

        if (empty($filters)) {
            return $next($builder);
        }

        $builder = $builder->filter(function ($file) use ($filters) {
            foreach ($filters as $filter) {
                if (mb_stripos($file->{$filter['column']}, $filter['value']) === false) {
                    return false;
                }
            }

            return true;
        });

        return $next($builder);
    }
}
