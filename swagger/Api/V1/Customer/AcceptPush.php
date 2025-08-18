<?php

declare(strict_types=1);

namespace Swagger\Api\V1\Customer;

use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

#[OA\Patch(
    path: '/customer/accept-push',
    tags: ['User Profile'],
    summary: 'Update push notification status.',
    operationId: 'acceptPush',
    security: [['bearerAuth' => []]],
    parameters: [
        new OA\Parameter(
            ref: '#/components/parameters/Accept',
        ),
    ],
    requestBody: new OA\RequestBody(
        description: 'Input data format',
        content: new OA\MediaType(
            mediaType: 'application/x-www-form-urlencoded',
            schema: new OA\Schema(
                required: ['status'],
                properties: [
                    new OA\Property(
                        property: 'status',
                        type: 'integer',
                        format: 'int32',
                        enum: [0, 1]
                    ),
                ]
            )
        )
    ),
    responses: [
        new OA\Response(response: SymfonyResponse::HTTP_OK, ref: '#/components/responses/OK'),
        new OA\Response(response: SymfonyResponse::HTTP_UNAUTHORIZED, ref: '#/components/responses/Unauthorized'),
        new OA\Response(response: SymfonyResponse::HTTP_UNPROCESSABLE_ENTITY, ref: '#/components/responses/UnprocessableEntity'),
        new OA\Response(response: SymfonyResponse::HTTP_INTERNAL_SERVER_ERROR, ref: '#/components/responses/InternalServerError'),
        new OA\Response(response: SymfonyResponse::HTTP_SERVICE_UNAVAILABLE, ref: '#/components/responses/ServiceUnavailable'),
    ]
)]

class AcceptPush {}
