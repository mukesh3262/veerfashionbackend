<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProductRequest;
use App\Http\Resources\Admin\ProductResource;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Pipelines\Admin\Common\SortPipeline;
use App\Pipelines\Admin\Product\FilterPipeline;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Response as InertiaResponse;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Str;
use Throwable;
use Illuminate\Support\Facades\Storage;
use DB;

class ProductController extends Controller
{
    public function index(Request $request): InertiaResponse
    {
        $this->authorize('product list');

        $products = app(Pipeline::class)
            ->send(
                Product::query()->with(['variants','images', 'category'])
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
        $this->authorize('product add');
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

        return inertia('Admin/Product/AddEdit', [
            'success' => session('success'),
            'error' => session('error'),
            'uuid' => session('uuid'),
            'categories' => $categories
        ]);
    }

    public function store(ProductRequest $request): RedirectResponse
    {
        try {
            DB::beginTransaction();

            // ✅ Create Product
            $product = Product::create([
                'category_id' => $request->category,
                'name'        => $request->name,
                'description' => $request->description,
                'base_price'  => $request->base_price,
            ]);

            // ✅ Save Product Images
            if ($request->hasFile('product_images')) {
                $imagesData = [];
                foreach ($request->file('product_images') as $index => $image) {
                    $filename = uniqid('product-') . '.' . $image->getClientOriginalExtension();
                    Storage::put(config('filesystems.module_paths.products') . $filename, $image->getContent());

                    $imagesData[] = [
                        'product_id' => $product->id,
                        'image' => $filename, // only filename
                        'is_default' => $index === 0 ? 1 : 0, // first image is default
                    ];
                }
                // Bulk insert
                if (!empty($imagesData)) {
                    $product->images()->createMany($imagesData);
                }
            }


            // ✅ Save Variants with Images
            if ($request->has('variants')) {
                foreach ($request->variants as $variantData) {
                    $variant = $product->variants()->create([
                        'sku'    => $variantData['sku'] ?? null,
                        'brand'    => $variantData['brand'] ?? null,
                        'price'  => $variantData['price'] ?? null,
                        'attributes' => json_encode($variantData['attributes'] ?? null),
                    ]);


                    // Save Variant Images
                    if (isset($variantData['variant_images'])) {
                        $variantImagesData = [];
                        foreach ($variantData['variant_images'] as $variantImage) {
                            $filename = uniqid('product-variant-') . '.' . $variantImage->getClientOriginalExtension();
                            Storage::put(config('filesystems.module_paths.products-variants') . $filename, $variantImage->getContent());

                            $variantImagesData[] = [
                                'product_variant_id' => $variant->id,
                                'image' => $filename, // only filename
                                'is_default' => $index === 0 ? 1 : 0, // first image is default
                            ];
                        }
                        // Bulk insert
                        if (!empty($variantImagesData)) {
                            $variant->images()->createMany($variantImagesData);
                        }
                    }
                }
            }

            DB::commit();

            return to_route('admin.products.index')
                ->with([
                    'success' => [
                        'dialog_type' => 'info',
                        'message'     => __('basecode/admin.created', ['entity' => 'Product']),
                        'uuid'        => Str::uuid(),
                    ],
                ]);
        } catch (Throwable $th) {
            DB::rollBack();

            return back()->with([
                'error' => $th->getMessage(),
                'uuid'  => Str::uuid(),
            ]);
        }
    }


    public function show(Product $product): InertiaResponse
    {
        $product = $product->load(['category', 'images', 'variants']);

        return inertia('Admin/Product/Details', [
            'product' => (new ProductResource($product))->resolve(),
            'success' => session('success'),
            'error' => session('error'),
            'uuid' => session('uuid'),
        ]);
    }

    public function edit(Product $product): InertiaResponse
    {
        // Find product or category that is being edited
        $product = Product::with(['category', 'images', 'variants'])->findOrFail($product->id);

        // Fetch categories with subcategories
        $categories = Category::with('subcategories')->whereNull('parent_id')->get();

        // Transform into grouped options
        $formattedCategories = $categories->map(function ($category) {
            return [
                'label' => $category->name,
                'options' => $category->subcategories->map(function ($sub) {
                    return [
                        'label' => $sub->name,
                        'value' => $sub->id,
                    ];
                })->toArray(),
            ];
        })->toArray();

        // Preselect the assigned category/subcategory
        $assignedCategory = null;
        if ($product->category) {
            $assignedCategory = [
                'label' => $product->category->name,
                'value' => $product->category->id,
            ];
        }

        return inertia('Admin/Product/AddEdit', [
            'success'          => session('success'),
            'error'            => session('error'),
            'uuid'             => session('uuid'),
            'categories'       => $formattedCategories,
            'assignedCategory' => $assignedCategory,
            'product'          => (new ProductResource($product))->resolve(),
        ]);
    }

    public function update(ProductRequest $request, Product $product): RedirectResponse
    {
        try {
            DB::beginTransaction();

            // ✅ Update product details
            $product->update([
                'name'        => $request->name,
                'base_price'  => $request->base_price,
                'description' => $request->description,
                'category_id' => $request->category,
            ]);
    
            /**
             * ---------------------------
             * ✅ Handle Product Images
             * ---------------------------
             */
            // Delete removed images
            if (isset($request->deleted_product_images) && count($request->deleted_product_images) > 0) {
                // Get images that will be deleted
                $images = $product->images()
                    ->whereIn('uuid', $request->deleted_product_images)
                    ->get();
                foreach ($images as $product_image) {
                    if ($product_image->image && Storage::exists(config('filesystems.module_paths.products') .$product_image->image)) {
                        Storage::delete(config('filesystems.module_paths.products') .$product_image->image);
                    }
                }
                // Delete DB records
                $product->images()
                    ->whereIn('uuid', $request->deleted_product_images)
                    ->delete();
            }

            // Save new uploaded images
            if ($request->hasFile('product_images')) {
                $imagesData = [];
                foreach ($request->file('product_images') as $index => $image) {
                    $filename = uniqid('product-') . '.' . $image->getClientOriginalExtension();
                    Storage::put(config('filesystems.module_paths.products') . $filename, $image->getContent());
    
                    $imagesData[] = [
                        'product_id' => $product->id,
                        'image'      => $filename,
                        'is_default' => $index === 0 ? 1 : 0, // keep first as default if none exists
                    ];
                }
                if (!empty($imagesData)) {
                    $product->images()->createMany($imagesData);
                }
            }
    
            /**
             * ---------------------------
             * ✅ Handle Variants
             * ---------------------------
             */
            if ($request->has('variants')) {
                foreach ($request->variants as $variantData) {

                    // Update existing OR create new
                    $variant = !empty($variantData['id'])
                        ? $product->variants()->where('uuid', $variantData['id'])->firstOrFail()
                        : $product->variants()->create([
                            'sku'        => $variantData['sku'] ?? null,
                            'brand'      => $variantData['brand'] ?? null,
                            'price'      => $variantData['price'] ?? null,
                            'attributes' => json_encode($variantData['attributes'] ?? null),
                        ]);
    
                    if (!empty($variantData['id'])) {
                        $variant->update([
                            'sku'        => $variantData['sku'] ?? null,
                            'brand'      => $variantData['brand'] ?? null,
                            'price'      => $variantData['price'] ?? null,
                            'attributes' => json_encode($variantData['attributes'] ?? null),
                        ]);
                    }
    
                    // ✅ Handle Variant Images
                    if (!empty($variantData['deleted_variant_images'])) {

                        $images = $variant->images()
                            ->whereIn('uuid', $variantData['deleted_variant_images'])
                            ->get();

                         foreach ($images as $variant_image) {
                            if ($variant_image->image && Storage::exists(config('filesystems.module_paths.products-variants'))) {
                                Storage::delete(config('filesystems.module_paths.products-variants') .$variant_image->image);
                            }
                        }
                        // Delete DB records
                        $variant->images()
                            ->whereIn('uuid', $variantData['deleted_variant_images'])
                            ->delete();
                    }
    
                    if (!empty($variantData['variant_images'])) {
                        $variantImagesData = [];
                        foreach ($variantData['variant_images'] as $index => $variantImage) {
                            $filename = uniqid('product-variant-') . '.' . $variantImage->getClientOriginalExtension();
                            Storage::put(config('filesystems.module_paths.products-variants') . $filename, $variantImage->getContent());
    
                            $variantImagesData[] = [
                                'product_variant_id' => $variant->id,
                                'image'      => $filename,
                                'is_default' => $index === 0 ? 1 : 0,
                            ];
                        }
                        if (!empty($variantImagesData)) {
                            $variant->images()->createMany($variantImagesData);
                        }
                    }
                }
            }
    
            DB::commit();
    
            return to_route('admin.products.index')
                ->with([
                    'success' => [
                        'dialog_type' => 'info',
                        'message'     => __('basecode/admin.updated', ['entity' => 'Product']),
                        'uuid'        => Str::uuid(),
                    ],
                ]);
    
        } catch (Throwable $th) {
            DB::rollBack();
    
            return back()->with([
                'error' => $th->getMessage(),
                'uuid'  => Str::uuid(),
            ]);
        }
    }
    

    public function destroy(Product $product): RedirectResponse
    {
        $this->authorize('product delete');

        try {
            $product->delete();

            return to_route('admin.products.index')
                ->with([
                    'success' => [
                        'dialog_type' => 'confirm', // info | confirm
                        'message' => __('basecode/admin.deleted', ['entity' => 'Product']),
                    ],
                    'uuid' => Str::uuid(),
                ]);
        } catch (Throwable $th) {
            return back()->with([
                'error' => $th->getMessage(),
                'uuid' => Str::uuid(),
            ]);
        }
    }


    public function changeStatus(Request $request, Product $product): RedirectResponse
    {
        $this->authorize('product edit');
        $product->update(['is_active' => !$product->is_active]);

        return back()
            ->with([
                'success' => [
                    'dialog_type' => 'confirm', // info | confirm
                    'message' => __('basecode/admin.updated', ['entity' => 'Status']),
                ],
                'uuid' => Str::uuid(),
            ]);
    }
}
