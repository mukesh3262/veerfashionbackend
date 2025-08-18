<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\DeviceTypeEnum;
use App\Enums\MobileVersionEnum;
use App\Http\Requests\API\V1\AppVersionRequest;
use App\Models\AppVersionLog;
use App\Models\Setting;

class AppVersionService
{
    public function getAppVersionInfo(AppVersionRequest $request): array
    {
        $isAppVersionOld = $this->isAppVersionOld();

        $appVersions = Setting::query()
            ->where('key', MobileVersionEnum::KEY->value)
            ->value('values');

        $versionInfo = (array) collect($appVersions)->where('platform', $request->platform)->first();

        $needForceUpdate = $this->isAppUpdated($versionInfo);

        return array_merge(
            $versionInfo,
            ['force_updateable' => $isAppVersionOld ? true : $needForceUpdate]
        );
    }

    public function isAppUpdated(array $versionInfo): bool
    {
        return (int) request()->version === $versionInfo['version']
            ? false
            : $versionInfo['force_updateable'];
    }

    public function isAppVersionOld(): bool
    {
        return AppVersionLog::query()
            ->when(request()->platform === DeviceTypeEnum::IOS->value, function ($query) {
                $query->where('ios_version', '>', request()->version)
                    ->where('is_ios_force_update', 1);
            }, function ($query) {
                $query->where('android_version', '>', request()->version)
                    ->where('is_android_force_update', 1);
            })
            ->count();
    }
}
