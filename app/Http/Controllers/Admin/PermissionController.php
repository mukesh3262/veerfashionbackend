<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PermissionRequest;
use App\Http\Resources\Admin\PermissionResource;
use App\Models\Permission;
use App\Pipelines\Admin\Common\SortPipeline;
use App\Pipelines\Admin\Permission\FilterPipeline;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Str;
use Inertia\Response as InertiaResponse;
use Throwable;

class PermissionController extends Controller
{
    public function index(Request $request): InertiaResponse
    {
        $this->authorize('permission list');

        $permissions = app(Pipeline::class)
            ->send(Permission::restrictByRole())
            ->through([
                new FilterPipeline($request?->filters),
                new SortPipeline($request?->sort),
            ])
            ->thenReturn()
            ->paginate(config('utility.pagination.per_page'));

        return inertia('Admin/Permission/List', [
            'permissions' => PermissionResource::collection($permissions),
            'pagination' => $permissions->toArray(),
            'success' => session('success'),
            'error' => session('error'),
            'uuid' => session('uuid'),
        ]);
    }

    public function create(): InertiaResponse
    {
        $this->authorize('permission add');

        return inertia('Admin/Permission/AddEdit', [
            'success' => session('success'),
            'error' => session('error'),
            'uuid' => session('uuid'),
        ]);
    }

    public function store(PermissionRequest $request): RedirectResponse
    {
        $this->authorize('permission add');

        try {
            Permission::create($request->validated());

            return to_route('admin.permissions.index')
                ->with([
                    'success' => [
                        'dialog_type' => 'info', // info | confirm
                        'message' => __('basecode/admin.created', ['entity' => 'Permission']),
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

    public function edit(Permission $permission): InertiaResponse
    {
        $this->authorize('permission edit');

        return inertia('Admin/Permission/AddEdit', [
            'permission' => $permission,
            'success' => session('success'),
            'error' => session('error'),
            'uuid' => session('uuid'),
        ]);
    }

    public function update(PermissionRequest $request, Permission $permission): RedirectResponse
    {
        $this->authorize('permission edit');

        try {
            $permission->update($request->validated());

            return to_route('admin.permissions.index')
                ->with([
                    'success' => [
                        'dialog_type' => 'info', // info | confirm
                        'message' => __('basecode/admin.updated', ['entity' => 'Permission']),
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

    public function destroy(Permission $permission): RedirectResponse
    {
        $this->authorize('permission delete');

        try {
            $permission->delete();

            return to_route('admin.permissions.index')
                ->with([
                    'success' => [
                        'dialog_type' => 'confirm', // info | confirm
                        'message' => __('basecode/admin.deleted', ['entity' => 'Permission']),
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
}
