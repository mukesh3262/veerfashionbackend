<?php

declare(strict_types=1);

namespace Swagger\Api\V1\Schemas;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'Message',
    type: 'object',
)]

class Message
{
    #[OA\Property()]
    private string $message;
}
