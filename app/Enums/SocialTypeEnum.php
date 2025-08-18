<?php

declare(strict_types=1);

namespace App\Enums;

enum SocialTypeEnum: string
{
    case GOOGLE = 'Google';
    case FACEBOOK = 'Facebook';
    case TWITTER = 'Twitter';
    case APPLE = 'Apple';
}
