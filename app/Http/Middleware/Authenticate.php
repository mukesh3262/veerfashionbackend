<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as MiddlewareAuthenticate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class Authenticate extends MiddlewareAuthenticate
{
    protected function redirectTo(Request $request): ?string
    {
        if (Route::is('admin.*')) {
            return route('admin.login');
        }

        return route('login');
    }
}
