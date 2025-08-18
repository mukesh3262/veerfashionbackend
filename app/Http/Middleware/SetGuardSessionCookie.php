<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Response;

class SetGuardSessionCookie
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Route::is('admin.*')) {
            Config::set('session.cookie', env('SESSION_COOKIE_ADMIN', 'laravel_admin_session'));
        } else {
            Config::set('session.cookie', env('SESSION_COOKIE_FRONTEND', 'laravel_frontend_session'));
        }

        return $next($request);
    }
}
