<?php

declare(strict_types=1);

namespace Swagger\Api\V1;

use App\Enums\DeviceTypeEnum;
use App\Enums\UserRoleEnum;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

#[OA\Post(
    path: '/customer/register',
    tags: ['Customer Authentication'],
    summary: 'Make the user register.',
    operationId: 'register',
    parameters: [
        new OA\Parameter(
            ref: '#/components/parameters/Accept',
        ),
        new OA\Parameter(
            ref: '#/components/parameters/Accept-Language',
        ),
    ],
    requestBody: new OA\RequestBody(
        description: 'Input data format',
        content: new OA\MediaType(
            mediaType: 'application/x-www-form-urlencoded',
            schema: new OA\Schema(
                required: [
                    'first_name',
                    'last_name',
                    'email',
                    'isd_code',
                    'mobile',
                    'role',
                    'device_type',
                    'device_name',
                    'device_id',
                ],
                properties: [
                    new OA\Property(
                        property: 'first_name',
                        type: 'string',
                    ),
                    new OA\Property(
                        property: 'last_name',
                        type: 'string',
                    ),
                    new OA\Property(
                        property: 'email',
                        type: 'string',
                        format: 'email',
                    ),
                    new OA\Property(
                        property: 'isd_code',
                        type: 'string',
                        example: '+91',
                    ),
                    new OA\Property(
                        property: 'mobile',
                        type: 'string',
                    ),
                    new OA\Property(
                        property: 'role',
                        type: 'string',
                        enum: [UserRoleEnum::CUSTOMER, UserRoleEnum::SERVICE_PROVIDER],
                    ),
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
                ]
            )
        )
    ),
    responses: [
        new OA\Response(
            response: SymfonyResponse::HTTP_OK,
            description: 'Successful operation',
            content: new OA\JsonContent(ref: '#/components/schemas/RegisterResponse'),
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

#[OA\Schema(
    schema: 'RegisterResponse',
    type: 'object',
    allOf: [
        new OA\Schema(ref: '#/components/schemas/Message'),
        new OA\Schema(
            properties: [
                new OA\Property(
                    property: 'data',
                    type: 'object',
                    ref: '#/components/schemas/RegisterData',
                ),
            ]
        ),
    ]
)]

#[OA\Schema(
    schema: 'RegisterData',
    type: 'object',
    allOf: [
        new OA\Schema(
            properties: [
                new OA\Property(
                    property: 'user',
                    type: 'object',
                    ref: '#/components/schemas/User',
                ),
            ]
        ),
        new OA\Schema(ref: '#/components/schemas/Token'),
    ]
)]

class Register {}
