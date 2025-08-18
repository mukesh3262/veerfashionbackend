<?php

declare(strict_types=1);

namespace App\Action;

use App\Models\User;
use App\Services\ConditionMappingService;

class LinkSocialAccountAction
{
    public function execute(User $user, array $data): void
    {
        $user->socialLogins()->firstOrCreate([
            'user_id' => $user->getKey(),
            'social_id' => $data['social_id'],
        ], [
            'type' => (new ConditionMappingService)->getSocialType($data['social_type']),
        ]);
    }
}
