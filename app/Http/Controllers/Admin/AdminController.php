<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AdminRequest;
use App\Http\Resources\Admin\AdminResource;
use App\Models\Admin;
use App\Models\Role;
use App\Pipelines\Admin\Common\SortPipeline;
use App\Pipelines\Admin\SubAdmin\FilterPipeline;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Inertia\Response as InertiaResponse;
use Throwable;

class AdminController extends Controller
{
    public function index(Request $request): InertiaResponse
    {
        $this->authorize('sub admin list');

        $admins = app(Pipeline::class)
            ->send(Admin::excludeDefaultAdmins())
            ->through([
                new FilterPipeline($request?->filters),
                new SortPipeline($request?->sort),
            ])
            ->thenReturn()
            ->paginate(config('utility.pagination.per_page'));

        return inertia('Admin/SubAdmin/List', [
            'admins' => AdminResource::collection($admins),
            'pagination' => $admins->toArray(),
            'success' => session('success'),
            'error' => session('error'),
            'uuid' => session('uuid'),
        ]);
    }

    public function create(): InertiaResponse
    {
        $this->authorize('sub admin add');

        /**
         * Retrieve all roles except the default roles.
         * Note: Even developers are restricted from assigning default roles to any user.
         */
        $roles = Role::excludeDefaultRoles()
            ->pluck('name')
            ->map(fn($name) => ['value' => $name, 'label' => $name])
            ->toArray();

        return inertia('Admin/SubAdmin/AddEdit', [
            'roles' => $roles,
            'assignedRoles' => [],
            'success' => session('success'),
            'error' => session('error'),
            'uuid' => session('uuid'),
        ]);
    }

    public function store(AdminRequest $request): RedirectResponse
    {
        $this->authorize('sub admin add');

        try {
            DB::transaction(function () use ($request) {
                $admin = Admin::create($request->validated());

                $admin->assignRole($request->role);
            });

            return to_route('admin.admins.index')
                ->with([
                    'success' => [
                        'dialog_type' => 'info', // info | confirm
                        'message' => __('basecode/admin.created', ['entity' => 'Admin']),
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

    public function edit(Admin $admin): InertiaResponse
    {
        $this->authorize('sub admin edit');

        /**
         * Retrieve all roles except the default roles.
         * Note: Even developers are restricted from assigning default roles to any user.
         */
        $roles = Role::excludeDefaultRoles()
            ->pluck('name')
            ->map(fn($name) => ['value' => $name, 'label' => $name])
            ->toArray();

        $assignedRoles = $admin->getRoleNames()
            ->map(fn($name) => ['value' => $name, 'label' => $name])
            ->toArray();

        return inertia('Admin/SubAdmin/AddEdit', [
            'admin' => $admin,
            'roles' => $roles,
            'assignedRoles' => $assignedRoles,
            'success' => session('success'),
            'error' => session('error'),
            'uuid' => session('uuid'),
        ]);
    }

    public function update(AdminRequest $request, Admin $admin): RedirectResponse
    {
        $this->authorize('sub admin edit');

        try {
            DB::transaction(function () use ($request, $admin) {
                $admin->update($request->validated());

                $admin->syncRoles($request->role);
            });

            return to_route('admin.admins.index')
                ->with([
                    'success' => [
                        'dialog_type' => 'info', // info | confirm
                        'message' => __('basecode/admin.updated', ['entity' => 'Admin']),
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

    public function destroy(Admin $admin): RedirectResponse
    {
        $this->authorize('sub admin delete');

        try {
            $admin->delete();

            return to_route('admin.admins.index')
                ->with([
                    'success' => [
                        'dialog_type' => 'confirm', // info | confirm
                        'message' => __('basecode/admin.deleted', ['entity' => 'Admin']),
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

    public function changeStatus(Request $request, Admin $admin): RedirectResponse
    {
        $this->authorize('sub admin edit');

        $admin->update(['is_active' => ! $admin->is_active]);

        return back()->with([
            'success' => [
                'dialog_type' => 'confirm', // info | confirm
                'message' => __('basecode/admin.changed', ['Entity' => 'Status']),
            ],
            'uuid' => Str::uuid(),
        ]);
    }
}
