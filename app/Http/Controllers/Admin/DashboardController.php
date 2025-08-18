<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContentPage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Response as InertiaResponse;
use Throwable;

class DashboardController extends Controller
{
    public function __invoke(Request $request): InertiaResponse
    {
        try {
            $usersCount = User::query()
                ->selectRaw('
                    COUNT(*) as totalCount,
                    SUM(CASE WHEN is_active = ? THEN 1 ELSE 0 END) as activeCount,
                    SUM(CASE WHEN is_active = ? THEN 1 ELSE 0 END) as inactiveCount
                ', [1, 0])
                ->first()
                ->toArray();

            $contentPagesCount = ContentPage::query()
                ->selectRaw('
                    COUNT(*) as totalCount,
                    SUM(CASE WHEN is_active = ? THEN 1 ELSE 0 END) as activeCount,
                    SUM(CASE WHEN is_active = ? THEN 1 ELSE 0 END) as inactiveCount
                ', [1, 0])
                ->first()
                ->toArray();

            return inertia('Admin/Dashboard', [
                'data' => compact('usersCount', 'contentPagesCount'),
                'success' => __('basecode/admin.retrieved', ['Entity' => 'Dashboard Data']),
                'uuid' => Str::uuid(),
            ]);
        } catch (Throwable $th) {
            return inertia('Admin/Dashboard', [
                'error' => $th->getMessage(),
                'uuid' => Str::uuid(),
            ]);
        }
    }
}
