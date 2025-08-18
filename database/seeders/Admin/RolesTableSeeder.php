<?php

declare(strict_types=1);

namespace Database\Seeders\Admin;

use App\Helpers\Helper;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        Role::truncate();
        Schema::enableForeignKeyConstraints();

        Role::upsert([
            ['name' => Role::ROLE_DEVELOPER, 'guard_name' => 'admin'],
            ['name' => Role::ROLE_SUPER_ADMIN, 'guard_name' => 'admin'],
        ], ['name', 'guard_name'], ['name']);

        // Assign 'developer permission' to 'developer role'.
        $developerPermissions = Helper::getPermissions(Role::ROLE_DEVELOPER);
        $developerPermissions = Permission::whereIn('name', $developerPermissions)->get();
        $developerRole = Role::whereName(Role::ROLE_DEVELOPER)->first();

        if ($developerRole) {
            $developerRole->syncPermissions($developerPermissions);
        }

        // Assign 'super admin permission' to 'super admin role'.
        $superAdminPermissions = Helper::getPermissions(Role::ROLE_SUPER_ADMIN);
        $superAdminPermissions = Permission::whereIn('name', $superAdminPermissions)->get();
        $superAdminRole = Role::whereName(Role::ROLE_SUPER_ADMIN)->first();

        if ($superAdminRole) {
            $superAdminRole->syncPermissions($superAdminPermissions);
        }
    }
}
