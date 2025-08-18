<?php

declare(strict_types=1);

namespace Swagger\Api\V1;

use App\Enums\LanguageEnum;
use OpenApi\Attributes as OA;

#[OA\Info(
    title: L5_SWAGGER_CONST_APP_NAME,
    version: L5_SWAGGER_CONST_API_VERSION,
    description: L5_SWAGGER_CONST_PROJECT_BRIEF
)]

#[OA\Components(
    [
        new OA\SecurityScheme(
            securityScheme: 'bearerAuth',
            type: 'http',
            scheme: 'bearer'
        ),
        new OA\Attachable,
    ]
)]

#[OA\Server(
    url: L5_SWAGGER_CONST_HOST,
    description: L5_SWAGGER_CONST_HOST_DESC
)]

#[OA\Parameter(
    name: 'Accept-Language',
    description: 'ISO 2 Letter Language Code',
    in: 'header',
    required: true,
    schema: new OA\Schema(
        type: 'string',
        enum: LanguageEnum::class
    )
)]

#[OA\Parameter(
    name: 'Accept',
    description: 'Type of response you are expecting from API. i.e. (application/json)',
    in: 'header',
    required: true,
    schema: new OA\Schema(
        default: 'application/json',
        type: 'string'
    )
)]

#[OA\Response(
    response: 'OK',
    description: 'Operation Successful.',
    content: new OA\JsonContent(ref: '#/components/schemas/Message'),
)]

#[OA\Response(
    response: 'ServiceUnavailable',
    description: 'Service unavailable',
    content: new OA\JsonContent(ref: '#/components/schemas/Message'),
)]

#[OA\Response(
    response: 'UnprocessableEntity',
    description: 'Validation failed.',
    content: new OA\JsonContent(ref: '#/components/schemas/Message'),
)]

#[OA\Response(
    response: 'InternalServerError',
    description: 'Error occured while performing some action.',
    content: new OA\JsonContent(ref: '#/components/schemas/Message'),
)]

#[OA\Response(
    response: 'Unauthorized',
    description: 'Error occured while performing some action.',
    content: new OA\JsonContent(ref: '#/components/schemas/Message'),
)]

#[OA\Response(
    response: 'ServiceUnavailableAsString',
    description: 'Service unavailable',
    content: new OA\MediaType(
        mediaType: 'text/plain; charset=utf-8',
        schema: new OA\Schema(
            ref: '#/components/schemas/MessageAsString'
        )
    )
)]

#[OA\Response(
    response: 'UnprocessableEntityAsString',
    description: 'Validation failed.',
    content: new OA\MediaType(
        mediaType: 'text/plain; charset=utf-8',
        schema: new OA\Schema(
            ref: '#/components/schemas/MessageAsString'
        )
    )
)]

#[OA\Response(
    response: 'InternalServerErrorAsString',
    description: 'Error occured while performing some action.',
    content: new OA\MediaType(
        mediaType: 'text/plain; charset=utf-8',
        schema: new OA\Schema(
            ref: '#/components/schemas/MessageAsString'
        )
    )
)]

#[OA\Response(
    response: 'UnauthorizedAsString',
    description: 'Error occured while performing some action.',
    content: new OA\JsonContent(ref: '#/components/schemas/MessageAsString'),
)]

class BaseREST {}
