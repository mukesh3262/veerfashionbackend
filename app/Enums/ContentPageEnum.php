<?php

declare(strict_types=1);

namespace App\Enums;

enum ContentPageEnum: string
{
    case PRIVACY_POLICY = 'privacy-policy';
    case TERMS_CONDITIONS = 'tearms-conditions';
    case ABOUT_US = 'about-us';
}
