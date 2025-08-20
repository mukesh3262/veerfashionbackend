<?php

declare(strict_types=1);

namespace Swagger\Api\V1;

use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

#[OA\Delete(
    path: '/customer/past/logout',
    tags: ['User Profile'],
    summary: 'Make the user logout from other devices except current one.',
    operationId: 'pastLogout',
    security: [['bearerAuth' => []]],
    parameters: [
        new OA\Parameter(
            ref: '#/components/parameters/Accept',
        ),
        new OA\Parameter(
            ref: '#/components/parameters/Accept-Language',
        ),
    ],
    responses: [
        new OA\Response(
            response: SymfonyResponse::HTTP_OK,
            ref: '#/components/responses/OK',
        ),
        new OA\Response(
            response: SymfonyResponse::HTTP_UNAUTHORIZED,
            ref: '#/components/responses/Unauthorized',
        ),
        new OA\Response(
            response: SymfonyResponse::HTTP_UNPROCESSABLE_ENTITY,
            ref: '#/components/responses/UnprocessableEntity',
        ),
        new OA\Response(
            response: SymfonyResponse::HTTP_INTERNAL_SERVER_ERROR,
            ref: '#/components/responses/InternalServerError',
        ),
        new OA\Response(
            response: SymfonyResponse::HTTP_SERVICE_UNAVAILABLE,
            ref: '#/components/responses/ServiceUnavailable',
        ),
    ]
)]

class PastLogout {}
