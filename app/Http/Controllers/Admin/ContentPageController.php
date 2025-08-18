<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ContentPageRequest;
use App\Http\Resources\Admin\ContentPageResource;
use App\Models\ContentPage;
use App\Pipelines\Admin\Common\SortPipeline;
use App\Pipelines\Admin\ContentPage\FilterPipeline;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Str;
use Inertia\Response as InertiaResponse;
use Throwable;

class ContentPageController extends Controller
{
    public function index(Request $request): InertiaResponse
    {
        $this->authorize('cms list');

        $content_pages = app(Pipeline::class)
            ->send(ContentPage::query())
            ->through([
                new FilterPipeline($request?->filters),
                new SortPipeline($request?->sort),
            ])
            ->thenReturn()
            ->paginate(config('utility.pagination.per_page'));

        return inertia('Admin/ContentPage/List', [
            'content_pages' => ContentPageResource::collection($content_pages),
            'pagination' => $content_pages->toArray(),
            'success' => session('success'),
            'error' => session('error'),
            'uuid' => session('uuid'),
        ]);
    }

    public function create(): InertiaResponse
    {
        $this->authorize('cms add');

        return inertia('Admin/ContentPage/AddEdit', [
            'success' => session('success'),
            'error' => session('error'),
            'uuid' => session('uuid'),
        ]);
    }

    public function store(ContentPageRequest $request): RedirectResponse
    {
        $this->authorize('cms add');

        try {
            ContentPage::create($request->validated());

            return to_route('admin.content-pages.index')
                ->with([
                    'success' => [
                        'dialog_type' => 'info', // info | confirm
                        'message' => __('basecode/admin.created', ['entity' => 'Content Page']),
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

    public function edit(ContentPage $content_page): InertiaResponse
    {
        $this->authorize('cms edit');

        return inertia('Admin/ContentPage/AddEdit', [
            'content_page' => $content_page,
            'success' => session('success'),
            'error' => session('error'),
            'uuid' => session('uuid'),
        ]);
    }

    public function update(ContentPageRequest $request, ContentPage $content_page): RedirectResponse
    {
        $this->authorize('cms edit');

        try {
            $content_page->update($request->validated());

            return to_route('admin.content-pages.index')
                ->with([
                    'success' => [
                        'dialog_type' => 'info', // info | confirm
                        'message' => __('basecode/admin.updated', ['entity' => 'Content Page']),
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

    public function destroy(ContentPage $contentPage): RedirectResponse
    {
        $this->authorize('cms delete');

        try {
            $contentPage->delete();

            return to_route('admin.content-pages.index')
                ->with([
                    'success' => [
                        'dialog_type' => 'confirm', // info | confirm
                        'message' => __('basecode/admin.deleted', ['entity' => 'Role']),
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

    public function changeStatus(Request $request, ContentPage $contentPage): RedirectResponse
    {
        $this->authorize('cms edit');

        $contentPage->update(['is_active' => ! $contentPage->is_active]);

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
