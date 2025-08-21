<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\BannerRequest;
use App\Http\Resources\Admin\BannerResource;
use App\Models\Banner;
use App\Pipelines\Admin\Banner\FilterPipeline;
use App\Pipelines\Admin\Common\SortPipeline;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Response as InertiaResponse;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Throwable;

class BannerController extends Controller
{
    public function index(Request $request): InertiaResponse
    {
        $this->authorize('banner list');

        $banners = app(Pipeline::class)
            ->send(
                Banner::query()
            )
            ->through([
                new FilterPipeline($request?->filters),
                new SortPipeline($request?->sort),
            ])
            ->thenReturn()
            ->paginate(config('utility.pagination.per_page'));

        return inertia('Admin/Banner/List', [
            'banners' => BannerResource::collection($banners),
            'pagination' => $banners->toArray(),
            'success' => session('success'),
            'error' => session('error'),
            'uuid' => session('uuid'),
        ]);
    }


    public function create(): InertiaResponse
    {
        $this->authorize('banner add');

        return inertia('Admin/Banner/AddEdit', [
            'success' => session('success'),
            'error' => session('error'),
            'uuid' => session('uuid'),
        ]);
    }

    public function store(BannerRequest $request): RedirectResponse
    {
        $this->authorize('banner add');

        try {
            $updateData = $request->validated();

            // Category icon
            if ($request->hasFile('image')) {
                $filename = uniqid('category-icon-') . '.' . $request->image->getClientOriginalExtension();
                Storage::put(config('filesystems.module_paths.banners') . $filename, $request->image->getContent());
                
                $updateData['image'] = $filename;
            }

            // Create category
            Banner::create($updateData);

            return to_route('admin.banners.index')
                ->with([
                    'success' => [
                        'dialog_type' => 'info', // info | confirm
                        'message' => __('basecode/admin.created', ['entity' => 'Banner']),
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
