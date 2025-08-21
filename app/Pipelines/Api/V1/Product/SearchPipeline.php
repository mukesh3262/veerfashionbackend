<?php

declare(strict_types=1);

namespace App\Pipelines\Api\V1\Product;

class SearchPipeline
{
    protected $search;

    public function __construct($search)
    {
        $this->search = $search;
    }

    public function handle($query, $next)
    {
        if ($this->search) {
            $query->whereLike('name', "%{$this->search}%")
                ->orWhereLike('code', "%{$this->search}%")
                ->orWhereLike('description', "%{$this->search}%")
                ->orWhereLike('base_price', "%{$this->search}%");
        }
        return $next($query);
    }
}
