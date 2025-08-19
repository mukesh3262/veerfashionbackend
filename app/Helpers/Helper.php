<?php

declare(strict_types=1);

namespace App\Helpers;

use App\Models\Role;
use Illuminate\Pagination\LengthAwarePaginator;

class Helper
{
    public static function getPermissions($role = Role::ROLE_SUPER_ADMIN): array
    {
        /**
         * Common Permissions for super admin and developer
         */
        $permissions = [
            'user list',
            'user edit',
            'user view',

            'category list',
            'category add',
            'category edit',
            'category view',
            'category delete',

            'product list',
            'product add',
            'product edit',
            'product view',
            'product delete',

            'cms list',
            'cms add',
            'cms edit',
            'cms view',
            'cms delete',

            'sub admin list',
            'sub admin add',
            'sub admin edit',
            'sub admin delete',

            'mobile config list',
            'mobile config edit',

            'smtp config list',
            'smtp config edit',

            'sub admin list',
            'sub admin add',
            'sub admin edit',
            'sub admin view',
            'sub admin delete',

            'role list',
            'role add',
            'role edit',
            'role delete',
        ];

        // Developer specific permissions
        if ($role === Role::ROLE_DEVELOPER) {
            $permissions = [
                ...$permissions,
                ...[
                    'seeder list',
                    'seeder execute',

                    'permission list',
                    'permission add',
                    'permission edit',
                    'permission delete',
                ],
            ];
        }

        return $permissions;
    }

    public static function paginator(mixed $data, ?int $currentPage, ?int $perPage): mixed
    {
        $perPage = config('utility.pagination.per_page', 15);
        $offset = ($currentPage - 1) * $perPage;

        return new LengthAwarePaginator(
            $data->slice($offset, $perPage),
            $data->count(),
            $perPage,
            $currentPage,
            ['path' => request()->url(), 'query' => request()->query()]
        );
    }

    public static function generateOtp(int $digit = 4): string
    {
        $string = (string) (mt_rand(0, (int) str_repeat('9', $digit)));

        return mb_str_pad($string, $digit, '0', STR_PAD_LEFT);
    }
}
