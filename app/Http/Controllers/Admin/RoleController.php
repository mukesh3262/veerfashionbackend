<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\RoleRequest;
use App\Http\Resources\Admin\RoleResource;
use App\Models\Permission;
use App\Models\Role;
use App\Pipelines\Admin\Common\SortPipeline;
use App\Pipelines\Admin\Role\FilterPipeline;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Inertia\Response as InertiaResponse;
use Throwable;

class RoleController extends Controller
{
    public function index(Request $request): InertiaResponse
    {
        $this->authorize('role edit');

        $permissions = app(Pipeline::class)
            ->send(Role::excludeDefaultRoles())
            ->through([
                new FilterPipeline($request?->filters),
                new SortPipeline($request?->sort),
            ])
            ->thenReturn()
            ->paginate(config('utility.pagination.per_page'));

        return inertia('Admin/Role/List', [
            'roles' => RoleResource::collection($permissions),
            'pagination' => $permissions->toArray(),
            'success' => session('success'),
            'error' => session('error'),
            'uuid' => session('uuid'),
        ]);
    }

    public function create(): InertiaResponse
    {
        $this->authorize('role add');

        $permissions = Permission::restrictByRole()->pluck('name')
            ->map(fn ($name) => ['value' => $name, 'label' => $name])
            ->toArray();

        return inertia('Admin/Role/AddEdit', [
            'permissions' => $permissions,
            'success' => session('success'),
            'error' => session('error'),
            'uuid' => session('uuid'),
        ]);
    }

    public function store(RoleRequest $request): RedirectResponse
    {
        $this->authorize('role add');

        try {
            DB::transaction(function () use ($request) {
                $role = Role::create($request->validated());

                $role->syncPermissions($request->permissions);
            });

            return to_route('admin.roles.index')
                ->with([
                    'success' => [
                        'dialog_type' => 'info', // info | confirm
                        'message' => __('basecode/admin.created', ['entity' => 'Role']),
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

    public function edit(Role $role): InertiaResponse
    {
        $this->authorize('role edit');

        $permissions = Permission::pluck('name')
            ->map(fn ($name) => ['value' => $name, 'label' => $name])
            ->toArray();

        $selectedPermissions = $role->permissions->pluck('name')
            ->map(fn ($name) => ['value' => $name, 'label' => $name])
            ->toArray();

        return inertia('Admin/Role/AddEdit', [
            'role' => $role,
            'permissions' => $permissions,
            'selectedPermissions' => $selectedPermissions,
            'success' => session('success'),
            'error' => session('error'),
            'uuid' => session('uuid'),
        ]);
    }

    public function update(RoleRequest $request, Role $role): RedirectResponse
    {
        $this->authorize('role edit');

        try {
            DB::transaction(function () use ($role, $request) {
                $role->update($request->validated());

                $role->syncPermissions($request->permissions);
            });

            return to_route('admin.roles.index')
                ->with([
                    'success' => [
                        'dialog_type' => 'info', // info | confirm
                        'message' => __('basecode/admin.updated', ['entity' => 'Role']),
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

    public function destroy(Role $role): RedirectResponse
    {
        $this->authorize('role delete');

        try {
            $role->delete();

            return to_route('admin.roles.index')
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
}
