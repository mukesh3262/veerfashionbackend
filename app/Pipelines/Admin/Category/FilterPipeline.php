<?php

declare(strict_types=1);

namespace App\Pipelines\Admin\Category;

use Carbon\Carbon;
use Closure;

class FilterPipeline
{
    public function __construct(public $filters) {}

    public function handle($builder, Closure $next)
    {
        if (empty($this->filters)) {
            return $next($builder);
        }

        $builder->where(function ($query) {
            foreach ($this->filters as ['column' => $column, 'value' => $value]) {
                if (empty($value)) {
                    continue;
                }

                match ($column) {
                    'parent_id' => $query->where($column, $value),
                    'is_active' => $query->where($column, filter_var($value, FILTER_VALIDATE_BOOLEAN)),
                    'created_at' => $query->whereDate($column, Carbon::parse($value)),
                    default => $query->whereLike($column, "%{$value}%"),
                };
            }
        });

        return $next($builder);
    }
}
