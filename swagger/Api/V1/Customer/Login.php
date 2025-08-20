<?php

declare(strict_types=1);

namespace Swagger\Api\V1;

use App\Enums\DeviceTypeEnum;
use App\Enums\LoginTypeEnum;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

#[OA\Post(
    path: '/customer/login',
    tags: ['Customer Authentication'],
    summary: 'Make the user login.',
    operationId: 'login',
    parameters: [
        new OA\Parameter(ref: '#/components/parameters/Accept'),
        new OA\Parameter(ref: '#/components/parameters/Accept-Language'),
    ],
    requestBody: new OA\RequestBody(
        description: 'Input data format',
        content: new OA\MediaType(
            mediaType: 'application/x-www-form-urlencoded',
            schema: new OA\Schema(
                required: ['username', 'device_type', 'device_name', 'device_id'],
                properties: [
                    new OA\Property(
                        property: 'username',
                        type: 'string',
                        description: 'username can either be email or mobile number. mobile must be prefix with country code i.e. +91 xxxxxxxxxx'
                    ),
                    new OA\Property(
                        property: 'device_type',
                        type: 'string',
                        enum: [DeviceTypeEnum::IOS, DeviceTypeEnum::ANDROID]
                    ),
                    new OA\Property(
                        property: 'device_name',
                        type: 'string'
                    ),
                    new OA\Property(
                        property: 'device_id',
                        type: 'string'
                    ),
                    new OA\Property(
                        property: 'device_token',
                        type: 'string'
                    ),
                ]
            )
        )
    ),
    responses: [
        new OA\Response(
            response: SymfonyResponse::HTTP_OK,
            description: 'Successful operation',
            content: new OA\JsonContent(ref: '#/components/schemas/LoginResponse'),
        ),
        new OA\Response(
            response: SymfonyResponse::HTTP_UNAUTHORIZED,
            ref: '#/components/responses/Unauthorized'
        ),
        new OA\Response(
            response: SymfonyResponse::HTTP_UNPROCESSABLE_ENTITY,
            ref: '#/components/responses/UnprocessableEntity'
        ),
        new OA\Response(
            response: SymfonyResponse::HTTP_INTERNAL_SERVER_ERROR,
            ref: '#/components/responses/InternalServerError'
        ),
        new OA\Response(
            response: SymfonyResponse::HTTP_SERVICE_UNAVAILABLE,
            ref: '#/components/responses/ServiceUnavailable'
        ),
    ]
)]

#[OA\Schema(
    schema: 'LoginResponse',
    type: 'object',
    allOf: [
        new OA\Schema(ref: '#/components/schemas/Message'),
        new OA\Schema(
            properties: [
                new OA\Property(
                    property: 'data',
                    type: 'object',
                    ref: '#/components/schemas/LoginData'
                ),
            ]
        ),
    ]
)]

#[OA\Schema(
    schema: 'LoginData',
    type: 'object',
    allOf: [
        new OA\Schema(
            properties: [
                new OA\Property(
                    property: 'user',
                    type: 'object',
                    ref: '#/components/schemas/User'
                ),
                new OA\Property(
                    property: 'is_already_logged_in',
                    type: 'boolean',
                ),
            ]
        ),
        new OA\Schema(ref: '#/components/schemas/Token'),
    ]
)]

class Login {}
