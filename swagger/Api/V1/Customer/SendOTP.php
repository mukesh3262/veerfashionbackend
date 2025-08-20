<?php

declare(strict_types=1);

namespace Swagger\Api\V1;

use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

#[OA\Patch(
    path: '/customer/send-otp',
    tags: ['User Profile'],
    summary: 'It sends the OTP on mobile number OR email address you want to update.',
    operationId: 'sendOTP',
    security: [['bearerAuth' => []]],
    parameters: [
        new OA\Parameter(ref: '#/components/parameters/Accept'),
        new OA\Parameter(ref: '#/components/parameters/Accept-Language'),
    ],
    requestBody: new OA\RequestBody(
        description: 'Input data format',
        content: new OA\MediaType(
            mediaType: 'application/x-www-form-urlencoded',
            schema: new OA\Schema(
                required: ['verification_for'],
                properties: [
                    new OA\Property(
                        property: 'verification_for',
                        type: 'string',
                        enum: ['email', 'mobile'],
                    ),
                    new OA\Property(
                        property: 'isd_code',
                        type: 'string',
                        example: '+91',
                        description: 'Its required when type = mobile',
                    ),
                    new OA\Property(
                        property: 'mobile',
                        type: 'string',
                        description: 'Its required when type = mobile',
                    ),
                    new OA\Property(
                        property: 'email',
                        type: 'string',
                        description: 'Its required when type = email',
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

class SendOTP {}
