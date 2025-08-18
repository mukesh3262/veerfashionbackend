<?php

declare(strict_types=1);

namespace Swagger\Api\V1\Customer;

use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

#[OA\Get(
    path: '/customer/category/{categoryId}/sub-categories',
    tags: ['Dashboard'],
    summary: 'Retrieve list of subcategories for a given category',
    operationId: 'getSubCategories',
    security: [['bearerAuth' => []]],
    parameters: [
        new OA\Parameter(
            name: 'categoryId',
            in: 'path',
            required: true,
            schema: new OA\Schema(type: 'string', format: 'uuid'),
            description: 'UUID of the category'
        ),
        new OA\Parameter(ref: '#/components/parameters/Accept'),
        new OA\Parameter(ref: '#/components/parameters/Accept-Language'),
        new OA\Parameter(
            name: 'page',
            in: 'query',
            required: false,
            schema: new OA\Schema(type: 'integer', example: 1),
            description: 'Page number for pagination'
        ),
    ],
    responses: [
        new OA\Response(
            response: SymfonyResponse::HTTP_OK,
            description: 'Successful response',
            content: new OA\MediaType(
                mediaType: 'application/json',
                schema: new OA\Schema(
                    properties: [
                        new OA\Property(
                            property: 'data',
                            type: 'object',
                            properties: [
                                new OA\Property(
                                    property: 'subcategories',
                                    type: 'array',
                                    items: new OA\Items(
                                        type: 'object',
                                        properties: [
                                            new OA\Property(property: 'id', type: 'string', format: 'uuid'),
                                            new OA\Property(property: 'name', type: 'string'),
                                            new OA\Property(property: 'slug', type: 'string'),
                                            new OA\Property(property: 'description', type: 'string'),
                                            new OA\Property(property: 'icon', type: 'string', format: 'url'),
                                            new OA\Property(property: 'location', type: 'string', example: null),
                                            new OA\Property(property: 'latitude', type: 'number', example: null),
                                            new OA\Property(property: 'longitude', type: 'number', example: null),
                                            new OA\Property(property: 'sort_order', type: 'integer'),
                                        ]
                                    )
                                ),
                                new OA\Property(
                                    property: 'pagination',
                                    ref: '#/components/schemas/Pagination'
                                ),
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
class SubCategory {}

