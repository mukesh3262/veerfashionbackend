<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Helper;
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
use Intervention\Image\Laravel\Facades\Image;

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

            if ($request->hasFile('image')) {
                $filename = uniqid('banner-') . '.' . $request->image->getClientOriginalExtension();

                // Compress image by quality params 
                $image = Helper::compressImage($request->image, 70);
                Storage::put(config('filesystems.module_paths.banners') . $filename, (string) $image);
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

    public function show(Banner $banner): InertiaResponse
    {
        $this->authorize('banner view');
        return inertia('Admin/Banner/Detail', [
            'banner' => $banner,
            'success' => session('success'),
            'error' => session('error'),
            'uuid' => session('uuid'),
        ]);
    }

    public function edit(Banner $banner): InertiaResponse
    {
        $this->authorize('banner edit');

        return inertia('Admin/Banner/AddEdit', [
            'banner' => (new BannerResource($banner))->resolve(),
            'success' => session('success'),
            'error' => session('error'),
            'uuid' => session('uuid'),
        ]);
    }

    public function update(BannerRequest $request, Banner $banner): RedirectResponse
    {
        $this->authorize('banner edit');
        try {
            $updateData = array_filter($request->validated(), 'strlen');
            if ($request->hasFile('image')) {

                $filename = uniqid('banner-') . '.' . $request->image->getClientOriginalExtension();
                // Compress and save image
                $image = Helper::compressImage($request->image, 70);
                Storage::put(config('filesystems.module_paths.banners') . $filename, (string) $image);

                // Optionally delete old image if exists
                if ($banner->image && Storage::exists(config('filesystems.module_paths.banners') . $banner->image)) {
                    Storage::delete(config('filesystems.module_paths.banners') . $banner->image);
                }

                $updateData['image'] = $filename;
            }

            // Update banner
            $banner->update($updateData);

            return to_route('admin.banners.index')
                ->with([
                    'success' => [
                        'dialog_type' => 'info',
                        'message' => __('basecode/admin.updated', ['entity' => 'Banner']),
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

    public function destroy(Banner $banner): RedirectResponse
    {
        $this->authorize('banner delete');

        try {
            $banner->delete();

            return to_route('admin.banners.index')
                ->with([
                    'success' => [
                        'dialog_type' => 'confirm', // info | confirm
                        'message' => __('basecode/admin.deleted', ['entity' => 'Banner']),
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

    public function changeStatus(Request $request, Banner $banner): RedirectResponse
    {
        $this->authorize('banner edit');
        $banner->update(['is_active' => !$banner->is_active]);

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
