<?php

declare(strict_types=1);

namespace Swagger\Api\V1;

use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

#[OA\Get(
    path: '/customer/profile',
    tags: ['User Profile'],
    summary: 'Get the user\'s profile info.',
    operationId: 'userProfile',
    security: [['bearerAuth' => []]],
    parameters: [
        new OA\Parameter(ref: '#/components/parameters/Accept'),
        new OA\Parameter(ref: '#/components/parameters/Accept-Language'),
    ],
    responses: [
        new OA\Response(
            response: SymfonyResponse::HTTP_OK,
            description: 'Successful operation',
            content: new OA\JsonContent(ref: '#/components/schemas/UserProfileResponse')
        ),
        new OA\Response(response: SymfonyResponse::HTTP_UNPROCESSABLE_ENTITY, ref: '#/components/responses/UnprocessableEntity'),
        new OA\Response(response: SymfonyResponse::HTTP_INTERNAL_SERVER_ERROR, ref: '#/components/responses/InternalServerError'),
        new OA\Response(response: SymfonyResponse::HTTP_SERVICE_UNAVAILABLE, ref: '#/components/responses/ServiceUnavailable'),
    ],
)]

#[OA\Schema(
    schema: 'UserProfileResponse',
    type: 'object',
    allOf: [
        new OA\Schema(ref: '#/components/schemas/Message'),
        new OA\Schema(
            properties: [
                new OA\Property(
                    property: 'data',
                    type: 'object',
                    ref: '#/components/schemas/UserProfileData'
                ),
            ]
        ),
    ]
)]

#[OA\Schema(
    schema: 'UserProfileData',
    type: 'object',
    allOf: [
        new OA\Schema(
            properties: [
                new OA\Property(
                    property: 'user',
                    type: 'object',
                    ref: '#/components/schemas/User'
                ),
            ]
        ),
    ]
)]

class UserProfile {}
