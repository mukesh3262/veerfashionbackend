<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\ProductResource;
use App\Models\Product;
use App\Pipelines\Admin\Common\SortPipeline;
use App\Pipelines\Admin\Product\FilterPipeline;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Response as InertiaResponse;
use Illuminate\Pipeline\Pipeline;

class ProductController extends Controller
{
    public function index(Request $request): InertiaResponse
    {
        $this->authorize('product list');

        $products = app(Pipeline::class)
            ->send(
                Product::query()
            )
            ->through([
                new FilterPipeline($request?->filters),
                new SortPipeline($request?->sort),
            ])
            ->thenReturn()
            ->paginate(config('utility.pagination.per_page'));
            
        return inertia('Admin/Product/List', [
            'products' => ProductResource::collection($products),
            'pagination' => $products->toArray(),
            'success' => session('success'),
            'error' => session('error'),
            'uuid' => session('uuid'),
        ]);
    }

    public function create(): InertiaResponse
    {
        return inertia();
    }

    public function store(Request $request): RedirectResponse
    {
        return to_route('/');
    }

    public function show(string $id): InertiaResponse
    {
        return inertia();
    }

    public function edit(string $id): InertiaResponse
    {
        return inertia();
    }

    public function update(Request $request, string $id): RedirectResponse
    {
        return to_route('/');
    }

    public function destroy(string $id): RedirectResponse
    {
        return to_route('/');
    }
}
