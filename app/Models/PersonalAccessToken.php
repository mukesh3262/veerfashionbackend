<?php

declare(strict_types=1);

namespace App\Models;

use Laravel\Sanctum\PersonalAccessToken as SanctumPersonalAccessToken;

class PersonalAccessToken extends SanctumPersonalAccessToken
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'device_name',
        'token',
        'access_token',
        'access_token_expired_at',
        'refresh_token',
        'refresh_token_expired_at',
        'device_type',
        'device_id',
        'ip',
        'fcm_key',
        'abilities',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'abilities' => 'json',
        'access_token_expired_at' => 'datetime',
        'refresh_token_expired_at' => 'datetime',
        'last_used_at' => 'datetime',
    ];

    /**
     * Find the token instance matching the given token.
     *
     * @param  string  $token
     * @return static|null
     */
    public static function findRefreshToken($token): ?self
    {
        $token = preg_replace('/^Bearer\s/', '', $token);

        if (mb_strpos($token, '|') === false) {
            return static::where('refresh_token', hash('sha256', $token))->first();
        }

        [$id, $token] = explode('|', $token, 2);

        if ($instance = static::find($id)) {
            return hash_equals($instance->refresh_token, hash('sha256', $token)) ? $instance : null;
        }
    }

    /**
     * Find the token instance matching the given token.
     *
     * @param  string  $token
     * @return static|null
     */
    public static function findToken($token): ?self
    {
        if (mb_strpos($token, '|') === false) {
            return static::where('access_token', hash('sha256', $token))->first();
        }

        [$id, $token] = explode('|', $token, 2);

        if ($instance = static::find($id)) {
            return hash_equals($instance->access_token, hash('sha256', $token)) ? $instance : null;
        }
    }
}
