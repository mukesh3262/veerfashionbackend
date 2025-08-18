<?php

declare(strict_types=1);

namespace App\Enums;

enum DeviceTypeEnum: string
{
    case IOS = 'ios';
    case ANDROID = 'android';
    case WEB = 'web';
}
