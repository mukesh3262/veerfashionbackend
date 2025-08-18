<?php

declare(strict_types=1);

namespace Swagger\Api\V1\Customer;

use App\Enums\DeviceTypeEnum;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

#[OA\Put(
    path: '/customer/token/refresh',
    tags: ['Customer Authentication'],
    summary: 'Refresh the user\'s access token',
    operationId: 'tokenRefresh',
    parameters: [
        new OA\Parameter(
            ref: '#/components/parameters/Accept',
        ),
        new OA\Parameter(
            ref: '#/components/parameters/Accept-Language',
        ),
        new OA\Parameter(
            name: 'Refresh-Token',
            description: 'Refresh Token',
            in: 'header',
            required: true,
            schema: new OA\Schema(
                type: 'string',
            )
        ),
    ],
    requestBody: new OA\RequestBody(
        description: 'Input data format',
        content: new OA\MediaType(
            mediaType: 'application/x-www-form-urlencoded',
            schema: new OA\Schema(
                required: ['device_type', 'device_name', 'device_id'],
                properties: [
                    new OA\Property(
                        property: 'device_type',
                        type: 'string',
                        enum: [DeviceTypeEnum::IOS, DeviceTypeEnum::ANDROID],
                    ),
                    new OA\Property(
                        property: 'device_name',
                        type: 'string',
                    ),
                    new OA\Property(
                        property: 'device_id',
                        type: 'string',
                    ),
                    new OA\Property(
                        property: 'device_token',
                        type: 'string',
                    ),
                ]
            )
        )
    ),
    responses: [
        new OA\Response(
            response: SymfonyResponse::HTTP_OK,
            description: 'Successful operation',
            content: new OA\JsonContent(ref: '#/components/schemas/RefreshTokenResponse')
        ),
        new OA\Response(response: SymfonyResponse::HTTP_UNAUTHORIZED, ref: '#/components/responses/Unauthorized'),
        new OA\Response(response: SymfonyResponse::HTTP_UNPROCESSABLE_ENTITY, ref: '#/components/responses/UnprocessableEntity'),
        new OA\Response(response: SymfonyResponse::HTTP_INTERNAL_SERVER_ERROR, ref: '#/components/responses/InternalServerError'),
        new OA\Response(response: SymfonyResponse::HTTP_SERVICE_UNAVAILABLE, ref: '#/components/responses/ServiceUnavailable'),
    ]
)]

#[OA\Schema(
    schema: 'RefreshTokenResponse',
    type: 'object',
    allOf: [
        new OA\Schema(ref: '#/components/schemas/Message'),
        new OA\Schema(
            properties: [
                new OA\Property(
                    property: 'data',
                    type: 'object',
                    ref: '#/components/schemas/RefreshTokenData'
                ),
            ]
        ),
    ]
)]

#[OA\Schema(
    schema: 'RefreshTokenData',
    type: 'object',
    allOf: [
        new OA\Schema(ref: '#/components/schemas/Token'),
    ]
)]

class RefreshToken {}
