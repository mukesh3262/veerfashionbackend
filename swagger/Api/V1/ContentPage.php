<?php

declare(strict_types=1);

namespace Swagger\Api\V1;

use App\Enums\ContentPageEnum;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

#[OA\Get(
    path: '/content-pages/{slug}',
    tags: ['Settings'],
    summary: 'Get the page content.',
    operationId: 'contentPage',
    parameters: [
        new OA\Parameter(ref: '#/components/parameters/Accept'),
        new OA\Parameter(ref: '#/components/parameters/Accept-Language'),
        new OA\Parameter(
            name: 'slug',
            description: 'Slug of a page that you expect in response.',
            in: 'path',
            required: true,
            schema: new OA\Schema(
                type: 'string',
                enum: ContentPageEnum::class,
            ),
        ),
    ],
    responses: [
        new OA\Response(
            response: SymfonyResponse::HTTP_OK,
            description: 'Successful operation',
            content: new OA\MediaType(
                mediaType: 'text/html',
                schema: new OA\Schema(
                    ref: '#/components/schemas/ContentPageResponse',
                ),
                example: '<html><p>String</p></html>',
            ),
        ),
        new OA\Response(response: SymfonyResponse::HTTP_UNPROCESSABLE_ENTITY, ref: '#/components/responses/UnprocessableEntity'),
        new OA\Response(response: SymfonyResponse::HTTP_INTERNAL_SERVER_ERROR, ref: '#/components/responses/InternalServerError'),
        new OA\Response(response: SymfonyResponse::HTTP_SERVICE_UNAVAILABLE, ref: '#/components/responses/ServiceUnavailable'),
    ]
)]

#[OA\Schema(
    schema: 'ContentPageResponse',
    type: 'string',
)]

class ContentPage {}
