<?php

declare(strict_types=1);

namespace Database\Seeders\Admin;

use App\Enums\MobileVersionEnum;
use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Setting::create([
            'key' => MobileVersionEnum::KEY->value,
            'values' => [
                [
                    'platform' => MobileVersionEnum::ANDROID->value,
                    'version' => 0,
                    'force_updateable' => false,
                ],
                [
                    'platform' => MobileVersionEnum::IOS->value,
                    'version' => 0,
                    'force_updateable' => false,
                ],
            ],
        ]);
    }
}
