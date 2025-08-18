<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SubCategoryRequest;
use App\Http\Resources\Admin\CategoryResource;
use App\Models\Category;
use App\Pipelines\Admin\Common\SortPipeline;
use App\Pipelines\Admin\Category\FilterPipeline;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Throwable;

class SubCategoryController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $this->authorize('category list');

        $subCategories = app(Pipeline::class)
            ->send(Category::query())
            ->through([
                new FilterPipeline($request?->filters),
                new SortPipeline($request?->sort),
            ])
            ->thenReturn()
            ->paginate(config('utility.pagination.per_page'));

        // filter options
        $filterOptions = [];

        return response()->json([
            'subCategories' => CategoryResource::collection($subCategories),
            'pagination' => $subCategories->toArray(),
            'filterOptions' => $filterOptions,
        ]);
    }

    public function store(SubCategoryRequest $request): RedirectResponse
    {
        $this->authorize('category add');

        try {
            $updateData = $request->validated();

            // Category icon
            if ($request->hasFile('category_icon')) {
                $filename = uniqid('subcategory-icon-') . '.' . $request->category_icon->getClientOriginalExtension();
                Storage::put(config('filesystems.module_paths.categories') . $filename, $request->category_icon->getContent());

                $updateData['icon'] = $filename;
            }

            // Create category
            Category::create($updateData);

            // get parent category
            $parentCategory = Category::find($updateData['parent_id']);
            $toRoute = $request->destination === 'admin.categories.show' ? to_route('admin.categories.show', $parentCategory->uuid) : to_route('admin.categories.index');

            return $toRoute
                ->with([
                    'success' => [
                        'dialog_type' => 'info', // info | confirm
                        'message' => __('basecode/admin.created', ['entity' => 'SubCategory']),
                        'uuid' => Str::uuid(),
                    ],
                ]);
        } catch (Throwable $th) {
            return back()->with([
                'error' => $th->getMessage(),
                'uuid' => Str::uuid(),
            ]);
        }
    }

    public function update(SubCategoryRequest $request, Category $subcategory): RedirectResponse
    {
        $this->authorize('category edit');

        try {
            $updateData = $request->validated();

            // Category icon
            if ($request->hasFile('category_icon')) {
                // Remove previous image
                if ($subcategory->icon) {
                    Storage::delete(config('filesystems.module_paths.categories') . $subcategory->icon);
                }

                $filename = uniqid('subcategory-icon-') . '.' . $request->category_icon->getClientOriginalExtension();
                Storage::put(config('filesystems.module_paths.categories') . $filename, $request->category_icon->getContent());

                $updateData['icon'] = $filename;
            }

            // Update category
            $subcategory->update($updateData);

            // get parent category
            $subcategory->loadMissing('parentCategory');

            return to_route('admin.categories.show', $subcategory->parentCategory?->uuid)
                ->with([
                    'success' => [
                        'dialog_type' => 'info', // info | confirm
                        'message' => __('basecode/admin.updated', ['entity' => 'SubCategory']),
                        'uuid' => Str::uuid(),
                    ],
                ]);
        } catch (Throwable $th) {
            return back()->with([
                'error' => $th->getMessage(),
                'uuid' => Str::uuid(),
            ]);
        }
    }

    public function changeStatus(Category $subcategory): JsonResponse
    {
        try {
            $this->authorize('category edit');

            $subcategory->update(['is_active' => !$subcategory->is_active]);

            return response()
                ->json([
                    'success' => true,
                    'message' => [
                        'dialog_type' => 'confirm', // info | confirm
                        'message' => __('basecode/admin.updated', ['entity' => 'Status']),
                    ]
                ]);
        } catch (Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ]);
        }
    }

    public function destroy(Category $subcategory): JsonResponse
    {
        $this->authorize('category delete');

        try {
            // Delete subcategory
            $subcategory->delete();

            return response()
                ->json([
                    'success' => true,
                    'message' => [
                        'dialog_type' => 'confirm', // info | confirm
                        'message' => __('basecode/admin.deleted', ['entity' => 'Category']),
                    ]
                ]);
        } catch (Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ]);
        }
    }
}
