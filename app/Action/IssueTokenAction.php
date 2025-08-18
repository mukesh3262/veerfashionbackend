<?php

declare(strict_types=1);

namespace App\Action;

use App\Models\User;
use App\Services\ConditionMappingService;
use Illuminate\Support\Str;
use Laravel\Sanctum\PersonalAccessToken;

class IssueTokenAction
{
    /**
     * Provides an object with properties.
     *
     * Supported properties:
     * - accessToken: The generated access token.
     * - refreshToken: The generated refresh token.
     *
     * @return object An object with dynamically supported properties.
     */
    public function execute(User $user, $request): object
    {
        $token = $user->tokens()->updateOrCreate([
            'device_id' => $request->device_id,
        ], [
            'device_name' => $request->device_name,
            'access_token' => hash('sha256', $plainTextToken = Str::random(40)),
            'access_token_expired_at' => config('sanctum.ac_expiration') ? now()->addMinutes(config('sanctum.ac_expiration')) : null,
            'refresh_token' => hash('sha256', $plainTextRefreshToken = Str::random(40)),
            'refresh_token_expired_at' => config('sanctum.rt_expiration') ? now()->addMinutes(config('sanctum.rt_expiration')) : null,
            'device_type' => (new ConditionMappingService)->getDeviceType($request->device_type),
            'ip' => request()->ip(),
            'fcm_key' => $request->device_token ?? null,
            'abilities' => ['*'],
        ]);

        return new class($token, $plainTextToken, $plainTextRefreshToken)
        {
            public string $accessToken;

            public string $refreshToken;

            public function __construct(PersonalAccessToken $token, string $plainTextToken, string $plainTextRefreshToken)
            {
                $this->accessToken = $token->getKey() . '|' . $plainTextToken;
                $this->refreshToken = $token->getKey() . '|' . $plainTextRefreshToken;
            }
        };
    }
}
