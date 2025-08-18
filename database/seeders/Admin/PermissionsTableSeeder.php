<?php

declare(strict_types=1);

namespace Database\Seeders\Admin;

use App\Helpers\Helper;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\PermissionRegistrar;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        Permission::truncate();
        Schema::enableForeignKeyConstraints();

        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $superAdminPermissions = Helper::getPermissions(Role::ROLE_SUPER_ADMIN);
        $developerPermissions = Helper::getPermissions(Role::ROLE_DEVELOPER);

        $permissions = array_unique([
            ...$superAdminPermissions,
            ...$developerPermissions,
        ]);

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission, 'guard_name' => 'admin']);
        }
    }
}
