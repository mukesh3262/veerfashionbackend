<?php

declare(strict_types=1);

namespace App\Models;

use App\Helpers\Helper;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Models\Permission as SpatiePermission;

class Permission extends SpatiePermission
{
    /** @use HasFactory<\Database\Factories\PermissionFactory> */
    use HasFactory;

    public function scopeRestrictByRole(Builder $query): void
    {
        /**
         * Determine the permissions for the logged-in user based on their role.
         * If the user is a developer, use the developer's permissions.
         * For all other roles, default to super admin permissions.
         */
        $userRole = auth()->user()->getRoleNames()->first() ?? Role::ROLE_SUPER_ADMIN;
        $userRole = ($userRole === Role::ROLE_DEVELOPER) ? Role::ROLE_DEVELOPER : Role::ROLE_SUPER_ADMIN;

        $permissionsForRole = Helper::getPermissions($userRole);
        $query->whereIn('name', $permissionsForRole);
    }
}
