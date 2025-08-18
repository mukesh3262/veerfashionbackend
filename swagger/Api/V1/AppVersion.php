<?php

declare(strict_types=1);

namespace Swagger\Api\V1;

use App\Enums\DeviceTypeEnum;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

#[OA\Post(
    path: '/settings/app-version',
    tags: ['Settings'],
    summary: 'Get the application version of each platform i.e. android, ios.',
    operationId: 'appVersion',
    parameters: [
        new OA\Parameter(
            ref: '#/components/parameters/Accept'
        ),
        new OA\Parameter(
            ref: '#/components/parameters/Accept-Language'
        ),
    ],
    requestBody: new OA\RequestBody(
        description: 'Input data format',
        content: new OA\MediaType(
            mediaType: 'application/x-www-form-urlencoded',
            schema: new OA\Schema(
                required: ['platform', 'version'],
                properties: [
                    new OA\Property(
                        property: 'platform',
                        type: 'string',
                        description: 'platform of which you want version information. (ios & android)',
                        enum: [DeviceTypeEnum::IOS, DeviceTypeEnum::ANDROID]
                    ),
                    new OA\Property(
                        property: 'version',
                        type: 'string',
                        description: 'Application\'s current version. (ios & android)'
                    ),
                ]
            )
        )
    ),
    responses: [
        new OA\Response(
            response: SymfonyResponse::HTTP_OK,
            description: 'Successful operation',
            content: new OA\JsonContent(
                ref: '#/components/schemas/AppVersionResponse'
            )
        ),
        new OA\Response(response: SymfonyResponse::HTTP_UNPROCESSABLE_ENTITY, ref: '#/components/responses/UnprocessableEntity'),
        new OA\Response(response: SymfonyResponse::HTTP_INTERNAL_SERVER_ERROR, ref: '#/components/responses/InternalServerError'),
        new OA\Response(response: SymfonyResponse::HTTP_SERVICE_UNAVAILABLE, ref: '#/components/responses/ServiceUnavailable'),
    ]
)]

#[OA\Schema(
    schema: 'AppVersionResponse',
    type: 'object',
    allOf: [
        new OA\Schema(ref: '#/components/schemas/Message'),
        new OA\Schema(
            properties: [
                new OA\Property(
                    property: 'data',
                    type: 'object',
                    ref: '#/components/schemas/AppVersion'
                ),
            ]
        ),
    ]
)]

class AppVersion {}
