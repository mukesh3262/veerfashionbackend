<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Category;
use Database\Seeders\Admin\AdminsTableSeeder;
use Database\Seeders\Admin\ContentPagesTableSeeder;
use Database\Seeders\Admin\PermissionsTableSeeder;
use Database\Seeders\Admin\RolesTableSeeder;
use Database\Seeders\Admin\SettingsTableSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        /**
         * Required Seeders, After Fresh Migrations for Admin.
         */
        $this->call([
            PermissionsTableSeeder::class,
            RolesTableSeeder::class,
            AdminsTableSeeder::class,
            ContentPagesTableSeeder::class,
            SettingsTableSeeder::class,
            CategorySeeder::class,
        ]);

        /**
         * Optional Seeders.
         */
        $this->call([
            UsersTableSeeder::class,
        ]);
    }
}
