<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CategoryRequest;
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
use Inertia\Response as InertiaResponse;
use Throwable;

class CategoryController extends Controller
{
    public function index(Request $request): InertiaResponse
    {
        $this->authorize('category list');

        $categories = app(Pipeline::class)
            ->send(
                Category::query()
                    ->mainCategories()
            )
            ->through([
                new FilterPipeline($request?->filters),
                new SortPipeline($request?->sort),
            ])
            ->thenReturn()
            ->paginate(config('utility.pagination.per_page'));

        return inertia('Admin/Category/List', [
            'categories' => CategoryResource::collection($categories),
            'pagination' => $categories->toArray(),
            'success' => session('success'),
            'error' => session('error'),
            'uuid' => session('uuid'),
        ]);
    }

    public function store(CategoryRequest $request): RedirectResponse
    {
        $this->authorize('category add');

        try {
            $updateData = $request->validated();

            // Category icon
            if ($request->hasFile('category_icon')) {
                $filename = uniqid('category-icon-') . '.' . $request->category_icon->getClientOriginalExtension();
                Storage::put(config('filesystems.module_paths.categories') . $filename, $request->category_icon->getContent());

                $updateData['icon'] = $filename;
            }

            // Create category
            Category::create($updateData);

            return to_route('admin.categories.index')
                ->with([
                    'success' => [
                        'dialog_type' => 'info', // info | confirm
                        'message' => __('basecode/admin.created', ['entity' => 'Category']),
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

    public function show(Category $category): InertiaResponse
    {
        $this->authorize('category view');

        return inertia('Admin/Category/Detail', [
            'category' => new CategoryResource($category),
            'success' => session('success'),
            'error' => session('error'),
            'uuid' => session('uuid'),
        ]);
    }

    public function update(CategoryRequest $request, Category $category): RedirectResponse
    {
        $this->authorize('category edit');

        try {
            $updateData = $request->validated();
            // Category icon
            if ($request->hasFile('category_icon')) {
                // Remove previous image
                if ($category->icon) {
                    Storage::delete(config('filesystems.module_paths.categories') . $category->icon);
                }

                $filename = uniqid('category-icon-') . '.' . $request->category_icon->getClientOriginalExtension();
                Storage::put(config('filesystems.module_paths.categories') . $filename, $request->category_icon->getContent());

                $updateData['icon'] = $filename;
            }

            // Update category

            $category->update($updateData);

            return to_route('admin.categories.index')
                ->with([
                    'success' => [
                        'dialog_type' => 'info', // info | confirm
                        'message' => __('basecode/admin.updated', ['entity' => 'Category']),
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

    public function changeStatus(Request $request, Category $category): RedirectResponse
    {
        $this->authorize('category edit');

        $category->update(['is_active' => !$category->is_active]);

        return back()
            ->with([
                'success' => [
                    'dialog_type' => 'confirm', // info | confirm
                    'message' => __('basecode/admin.updated', ['entity' => 'Status']),
                ],
                'uuid' => Str::uuid(),
            ]);
    }

    public function destroy(Category $category): RedirectResponse
    {
        $this->authorize('category delete');

        try {
            // Delete sub-category
            $category->subcategories()->delete();

            // Delete category
            $category->delete();

            return to_route('admin.categories.index')
                ->with([
                    'success' => [
                        'dialog_type' => 'confirm', // info | confirm
                        'message' => __('basecode/admin.deleted', ['entity' => 'Category']),
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

    public function paginatedCategories(Request $request): JsonResponse
    {
        $perPage = $request->input('per_page', 10);
        $search = $request->input('search');

        $categories = Category::query()
            ->mainCategories()
            ->when($search, function ($query) use ($search) {
                return $query->where('name', 'like', "%{$search}%");
            })
            ->paginate($perPage);

        return response()->json([
            'data' => $categories->items(),
            'pagination' => [
                'page' => $categories->currentPage(),
                'hasMore' => $categories->hasMorePages(),
            ],
        ]);
    }
}
