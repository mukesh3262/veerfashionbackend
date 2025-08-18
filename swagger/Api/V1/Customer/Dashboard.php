<?php

declare(strict_types=1);

namespace Swagger\Api\V1\Customer;

use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

#[OA\Get(
    path: '/customer/dashboard',
    tags: ['Dashboard'],
    summary: 'Get dashboard data including categories',
    operationId: 'getDashboard',
    security: [['bearerAuth' => []]],
    parameters: [
        new OA\Parameter(ref: '#/components/parameters/Accept'),
        new OA\Parameter(ref: '#/components/parameters/Accept-Language'),
    ],
    responses: [
        new OA\Response(
            response: SymfonyResponse::HTTP_OK,
            description: 'Successful response',
            content: new OA\MediaType(
                mediaType: 'application/json',
                schema: new OA\Schema(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Ok'),
                        new OA\Property(
                            property: 'data',
                            type: 'object',
                            properties: [
                                new OA\Property(
                                    property: 'categories',
                                    type: 'array',
                                    items: new OA\Items(
                                        type: 'object',
                                        properties: [
                                            new OA\Property(
                                                property: 'id',
                                                type: 'string',
                                                format: 'uuid',
                                            ),
                                            new OA\Property(
                                                property: 'name',
                                                type: 'string',
                                            ),
                                            new OA\Property(
                                                property: 'icon',
                                                type: 'string',
                                                format: 'url',
                                            )
                                        ]
                                    )
                                )
                            ]
                        )
                    ]
                )
            )
        ),
        new OA\Response(response: SymfonyResponse::HTTP_UNAUTHORIZED, ref: '#/components/responses/Unauthorized'),
        new OA\Response(response: SymfonyResponse::HTTP_INTERNAL_SERVER_ERROR, ref: '#/components/responses/InternalServerError'),
        new OA\Response(response: SymfonyResponse::HTTP_SERVICE_UNAVAILABLE, ref: '#/components/responses/ServiceUnavailable'),
    ]
)]
class Dashboard {}
