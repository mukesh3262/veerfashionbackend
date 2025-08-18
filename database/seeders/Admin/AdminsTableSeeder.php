<?php

declare(strict_types=1);

namespace Database\Seeders\Admin;

use App\Models\Admin;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class AdminsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        Admin::truncate();
        Schema::enableForeignKeyConstraints();

        Admin::upsert([
            [
                'name' => 'Developer',
                'email' => Admin::DEVELOPER_EMAIL,
                'email_verified_at' => now(),
                'password' => Hash::make('developer@spaceo'),
                'remember_token' => Str::random(10),
            ],
            [
                'name' => 'Super Admin',
                'email' => Admin::SUPER_ADMIN_EMAIL,
                'email_verified_at' => now(),
                'password' => Hash::make('admin@spaceo'),
                'remember_token' => Str::random(10),
            ],
        ], ['email'], ['name']);

        $admins = Admin::with('roles')
            ->whereIn('email', [Admin::DEVELOPER_EMAIL, Admin::SUPER_ADMIN_EMAIL])
            ->select('id', 'email')
            ->get();

        $admins->first(function ($user) {
            return $user->email === Admin::DEVELOPER_EMAIL;
        })->assignRole(Role::ROLE_DEVELOPER);

        $admins->first(function ($user) {
            return $user->email === Admin::SUPER_ADMIN_EMAIL;
        })->assignRole(Role::ROLE_SUPER_ADMIN);
    }
}
