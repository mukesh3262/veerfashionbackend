<?php

declare(strict_types=1);

namespace App\Enums;

enum MobileVersionEnum: string
{
    case KEY = 'app_versions';

    case IOS = 'ios';
    case ANDROID = 'android';
}
