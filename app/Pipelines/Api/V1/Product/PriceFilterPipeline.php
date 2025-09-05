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
        return $next(
            $query->where(function ($q) {
                $q->whereHas('variants', function ($query) {
                    if ($this->min !== null) {
                        $query->where('price', '>=', $this->min);
                    }

                    if ($this->max !== null) {
                        $query->where('price', '<=', $this->max);
                    }
                });

                // include products without variants
                if ($this->min === null && $this->max === null) {
                    $q->orDoesntHave('variants');
                }
            })
        );
    }
}
