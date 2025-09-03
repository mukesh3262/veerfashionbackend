<?php

declare(strict_types=1);

use App\Http\Middleware\AlwaysAcceptJson;
use App\Http\Middleware\Api\LocaleMiddleware;
use App\Http\Middleware\Api\RefreshTokenMiddleware;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response as FacadesResponse;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

/*
|--------------------------------------------------------------------------
| Web Routes and Mappings
|--------------------------------------------------------------------------
|
| Here is where you can create and map Web related routes and it's files.
|
 */

if (! function_exists('mapWebRoutes')) {
    function mapWebRoutes(): void
    {
        Route::middleware('web')
            ->group(base_path('routes/web.php'));

        Route::middleware('web', 'guest')
            ->group(base_path('routes/frontend/guest.php'));

        Route::middleware(['web', 'auth'])
            ->group(base_path('routes/frontend/auth.php'));
    }
}

/*
|--------------------------------------------------------------------------
| Admin Routes and Mappings
|--------------------------------------------------------------------------
|
| Here is where you can create and map Admin related routes and it's files.
|
 */
if (! function_exists('mapAdminRoutes')) {
    function mapAdminRoutes(): void
    {
        Route::middleware(['web', 'guest:admin'])
            ->prefix('admin')
            ->as('admin.')
            ->group(base_path('routes/admin/guest.php'));

        Route::middleware(['web', 'auth:admin'])
            ->prefix('admin')
            ->as('admin.')
            ->group(base_path('routes/admin/auth.php'));
    }
}

/*
|--------------------------------------------------------------------------
| API Routes and Mappings
|--------------------------------------------------------------------------
|
| Here is where you can create and map API related routes and it's files.
|
 */
if (! function_exists('mapApiRoutes')) {
    function mapApiRoutes(): void
    {
        Route::middleware('api')
            ->prefix('api')
            ->as('api.')
            ->group(base_path('routes/api/api.php'));

        Route::middleware(['api', 'throttle:api'])
            ->prefix('api/v1')
            ->as('api.')
            ->group(base_path('routes/api/v1/guest.php'));

        Route::middleware(['api', 'auth:sanctum', 'throttle:api'])
            ->prefix('api/v1')
            ->as('api.')
            ->group(base_path('routes/api/v1/auth.php'));
    }
}

if (! function_exists('handleInertiaExceptions')) {
    function handleInertiaExceptions(Exceptions $exceptions): void
    {
        $exceptions->respond(function (SymfonyResponse $response, Throwable $exception, Request $request) {
            if (
                ! app()->environment(['local', 'testing']) &&
                in_array($response->getStatusCode(), [
                    SymfonyResponse::HTTP_INTERNAL_SERVER_ERROR,
                    SymfonyResponse::HTTP_SERVICE_UNAVAILABLE,
                    SymfonyResponse::HTTP_NOT_FOUND,
                    SymfonyResponse::HTTP_FORBIDDEN,
                ], true)
            ) {
                return inertia('Admin/Errors/Index', [
                    'status' => $response->getStatusCode(),
                    'error' => $exception?->getMessage() ?: __('basecode/admin.internal_server_error'),
                ])
                    ->toResponse($request)
                    ->setStatusCode($response->getStatusCode());
            } elseif ($response->getStatusCode() === SymfonyResponse::HTTP_FORBIDDEN) {
                return inertia('Admin/Dashboard', [
                    'error' => __('basecode/admin.forbidden'),
                ]);
            }

            return $response;
        });
    }
}

if (! function_exists('handleApiExceptions')) {
    function handleApiExceptions(Throwable $exception, Request $request): JsonResponse|Throwable
    {
        if ($request->is('api/*') || $request->wantsJson() || $request->expectsJson()) {
            return match (true) {
                $exception instanceof ValidationException => FacadesResponse::error(
                    message: $exception->validator->errors()->first(),
                    statusCode: SymfonyResponse::HTTP_UNPROCESSABLE_ENTITY
                ),

                $exception instanceof AuthenticationException => FacadesResponse::error(
                    message: $exception->getMessage(),
                    statusCode: SymfonyResponse::HTTP_UNAUTHORIZED
                ),

                $exception instanceof AuthorizationException => FacadesResponse::error(
                    message: $exception->getMessage(),
                    statusCode: SymfonyResponse::HTTP_FORBIDDEN
                ),

                default => FacadesResponse::error(
                    message: $exception->getMessage(),
                    statusCode: SymfonyResponse::HTTP_INTERNAL_SERVER_ERROR
                ),
            };
        } else {
            return $exception;
        }
    }
}

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
        then: function () {
            // Web Routes and Mappings
            mapWebRoutes();

            // API Routes and Mappings
            mapAdminRoutes();

            // API Routes and Mappings
            mapApiRoutes();
        }
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->web(
            prepend: [
                // \App\Http\Middleware\SetGuardSessionCookie::class,
            ],
            append: [
                \App\Http\Middleware\HandleInertiaRequests::class,
                \Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets::class,
            ],
            replace: [],
        );

        $middleware->alias([
            'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
            'auth' => \App\Http\Middleware\Authenticate::class,
            'refresh.sanctum' => RefreshTokenMiddleware::class,
            'api.locale' => LocaleMiddleware::class,
        ]);

        $middleware->api(
            prepend: [
                AlwaysAcceptJson::class,
            ]
        );
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->renderable(function (Throwable $exception, Request $request) {
            handleApiExceptions($exception, $request);
        });

        handleInertiaExceptions($exceptions);
    })->create();
