<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\UserResource;
use App\Http\Resources\Shared\PersonalAccessTokenResource;
use App\Models\User;
use App\Pipelines\Admin\Common\SortPipeline;
use App\Pipelines\Admin\User\FilterPipeline;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Str;
use Inertia\Response as InertiaResponse;

class UserController extends Controller
{
    public function index(Request $request): InertiaResponse
    {
        $this->authorize('user list');

        $users = app(Pipeline::class)
            ->send(User::query())
            ->through([
                new FilterPipeline($request?->filters),
                new SortPipeline($request?->sort),
            ])
            ->thenReturn()
            ->paginate(config('utility.pagination.per_page'));

        return inertia('Admin/User/List', [
            'users' => UserResource::collection($users),
            'pagination' => $users->toArray(),
            'success' => session('success'),
            'error' => session('error'),
            'uuid' => session('uuid'),
        ]);
    }

    public function show($id): InertiaResponse
    {
        $user = User::find($id);
        $this->authorize('user view');

        return inertia('Admin/User/Detail', [
            'user' => new UserResource($user),
            'tokens' => PersonalAccessTokenResource::collection($user->tokens()->get()),
            'success' => session('success'),
            'error' => session('error'),
            'uuid' => session('uuid'),
        ]);
    }

    public function changeStatus(Request $request, User $user): RedirectResponse
    {
        $this->authorize('user edit');

        $user->update(['is_active' => ! $user->is_active]);

        return back()->with([
            'success' => [
                'dialog_type' => 'confirm', // info | confirm
                'message' => __('basecode/admin.changed', ['Entity' => 'Status']),
            ],
            'uuid' => Str::uuid(),
        ]);
    }
}
