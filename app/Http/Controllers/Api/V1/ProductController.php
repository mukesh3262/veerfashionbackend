<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\ProductNewArrival;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Pipeline\Pipeline;
use App\Models\Product;
use App\Pipelines\Admin\Common\SortPipeline;
use App\Pipelines\Admin\Product\FilterPipeline;
use App\Http\Resources\Api\V1\ProductResource;
use App\Pipelines\Api\V1\Product\CategoryPipeline;
use App\Pipelines\Api\V1\Product\PriceFilterPipeline;
use App\Pipelines\Api\V1\Product\SearchPipeline;

use Google\Service\CustomSearchAPI\Search;

class ProductController extends Controller
{
    public function filters(){

        $categories = Category::with('subcategories')
            ->whereNull('parent_id')
            ->get()
            ->map(function ($category) {
                return [
                    'label' => $category->name,
                    'options' => $category->subcategories->map(function ($subcategory) {
                        return [
                            'label' => $subcategory->name,
                            'value' => $subcategory->id,
                        ];
                    })->toArray(),
                ];
            })->toArray();

        return response()->json([
            'data' => $categories
        ]);
    }

    public function products(Request $request)
    {
        $products = app(Pipeline::class)
            ->send(
                Product::query()->where('is_active', true)
                    ->with(['variants', 'images', 'category'])
            )
            ->through([
                new SearchPipeline($request?->search ?? null),
                new CategoryPipeline($request?->category_id ?? null),
                new PriceFilterPipeline($request?->price_min ?? null, $request?->price_max ?? null),
            ])
            ->thenReturn()
            ->paginate(config('utility.pagination.per_page'));
    
        return response()->json([
            'data' => ProductResource::collection($products),
            'pagination' => [
                'total' => $products->total(),
                'current_page' => $products->currentPage(),
                'per_page' => $products->perPage(),
                'last_page' => $products->lastPage(),
            ],
        ]);
    }

    public function newArrival(){
        $products = Product::where('is_active', true)
            ->with('images')
            ->orderBy('created_at', 'desc')
            ->limit(6)
            ->get();
            
        return response()->json([
            'data' => ProductNewArrival::collection($products),
        ]);
    }
}
