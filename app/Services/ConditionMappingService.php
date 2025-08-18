<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\DeviceTypeEnum;
use App\Enums\SocialTypeEnum;

class ConditionMappingService
{
    public function getSocialType(string $type): int
    {
        return match ($type) {
            SocialTypeEnum::GOOGLE->value => 1,
            SocialTypeEnum::FACEBOOK->value => 2,
            SocialTypeEnum::TWITTER->value => 3,
            SocialTypeEnum::APPLE->value => 4,
        };
    }

    public function getDeviceType($type): int
    {
        return match ($type) {
            DeviceTypeEnum::IOS->value => 1,
            DeviceTypeEnum::ANDROID->value => 2,
            DeviceTypeEnum::WEB->value => 3
        };
    }
}
