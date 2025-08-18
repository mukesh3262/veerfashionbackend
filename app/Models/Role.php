<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
    /** @use HasFactory<\Database\Factories\RoleFactory> */
    use HasFactory;

    public const ROLE_SUPER_ADMIN = 'super admin';

    public const ROLE_DEVELOPER = 'developer';

    public function scopeExcludeDefaultRoles(Builder $query): void
    {
        $query->whereNotIn('name', [self::ROLE_SUPER_ADMIN, self::ROLE_DEVELOPER]);
    }
}
