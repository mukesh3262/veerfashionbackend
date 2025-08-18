<?php

declare(strict_types=1);

namespace App\Traits\Exceptions;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response as FacadesResponse;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;
use Throwable;

trait ExceptionMessages
{
    public function exceptionResponse(Throwable $th): JsonResponse
    {
        $statusCode = method_exists($th, 'getStatusCode')
            ? $th->getStatusCode()
            : SymfonyResponse::HTTP_INTERNAL_SERVER_ERROR;

        return FacadesResponse::error(
            message: $th->getMessage(),
            statusCode: $statusCode
        );
    }

    public function validationErrorResponse(Validator $validator): JsonResponse
    {
        return FacadesResponse::error(
            message: $validator->errors()->first(),
            statusCode: SymfonyResponse::HTTP_UNPROCESSABLE_ENTITY
        );
    }
}
