<?php

declare(strict_types=1);

namespace Swagger\Api\V1;

use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

#[OA\Patch(
    path: '/customer/verify-otp',
    tags: ['User Profile'],
    summary: 'It updates mobile number OR email address right after OTP verified.',
    operationId: 'verifyOTP',
    security: [['bearerAuth' => []]],
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
                required: [
                    'verification_for',
                    'otp',
                ],
                properties: [
                    new OA\Property(
                        property: 'verification_for',
                        type: 'string',
                        enum: ['email', 'mobile']
                    ),
                    new OA\Property(
                        property: 'isd_code',
                        type: 'string',
                        example: '+91',
                        description: 'Its required when type = mobile'
                    ),
                    new OA\Property(
                        property: 'mobile',
                        type: 'string',
                        description: 'Its required when type = mobile'
                    ),
                    new OA\Property(
                        property: 'email',
                        type: 'string',
                        description: 'Its required when type = email'
                    ),
                    new OA\Property(
                        property: 'otp',
                        type: 'integer',
                        format: 'int32'
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

class VerifyOTP {}
