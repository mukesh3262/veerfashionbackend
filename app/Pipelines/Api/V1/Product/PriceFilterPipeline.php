<?php

declare(strict_types=1);

namespace App\Pipelines\Api\V1\Product;

class PriceFilterPipeline
{
    protected $min;
    protected $max;

    public function __construct($min, $max)
    {
        $this->min = $min;
        $this->max = $max;
    }

    public function handle($query, $next)
    {
        if ($this->min !== null) {
            $query->where('base_price', '>=', $this->min);
        }

        if ($this->max !== null) {
            $query->where('base_price', '<=', $this->max);
        }

        return $next($query);
    }
}