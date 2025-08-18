<?php

declare(strict_types=1);

namespace App\Http\Middleware\Api;

use App\Models\PersonalAccessToken;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response as FacadesResponse;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class RefreshTokenMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): SymfonyResponse
    {
        if (! request()->hasHeader('Refresh-Token')) {
            return $this->invalidTokenResponse();
        }

        $accessToken = PersonalAccessToken::findRefreshToken(request()->header('Refresh-Token'));

        if (! $accessToken || $this->isTokenExpired($accessToken)) {
            return $this->invalidTokenResponse();
        }

        Auth::login(
            $accessToken->tokenable()->first()->withAccessToken($accessToken)
        );

        return $next($request);
    }

    public function invalidTokenResponse(): JsonResponse
    {
        return FacadesResponse::error(
            message: __('basecode/api.invalid_token'),
            statusCode: SymfonyResponse::HTTP_UNAUTHORIZED
        );
    }

    public function isTokenExpired(PersonalAccessToken $accessToken): bool
    {
        return $accessToken->refresh_token_expired_at &&
            $accessToken->refresh_token_expired_at->isPast();
    }
}
