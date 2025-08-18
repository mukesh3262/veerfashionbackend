<?php

declare(strict_types=1);

namespace Swagger\Api\V1;

use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

#[OA\Post(
    path: '/inquiry',
    tags: ['Settings'],
    summary: 'Submit inquiry request',
    operationId: 'newInquiry',
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
                    'name',
                    'email',
                    'isd_code',
                    'mobile',
                    'message',
                ],
                properties: [
                    new OA\Property(
                        property: 'name',
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
                        property: 'message',
                        type: 'string',
                    ),
                ]
            )
        )
    ),
    responses: [
        new OA\Response(
            response: SymfonyResponse::HTTP_OK,
            ref: '#/components/responses/OK',
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

class InquiryRequest {}
