<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response as FacadeResponse;
use Illuminate\Support\ServiceProvider;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class ResponseMacroServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        FacadeResponse::macro('success', function (string $message, mixed $data = null, int $statusCode = SymfonyResponse::HTTP_OK): JsonResponse {
            $response = [
                'message' => $message,
            ];

            if ($data) {
                $response['data'] = $data;
            }

            return FacadeResponse::json($response, $statusCode);
        });

        FacadeResponse::macro('error', function (string $message, mixed $data = null, int $statusCode = SymfonyResponse::HTTP_UNPROCESSABLE_ENTITY): JsonResponse {
            $response = [
                'message' => $message,
            ];

            if ($data) {
                $response['data'] = $data;
            }

            return FacadeResponse::json($response, $statusCode);
        });
    }
}
