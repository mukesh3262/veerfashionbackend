<?php

declare(strict_types=1);

namespace Swagger\Api\V1\Schemas;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'Token',
    type: 'object',
)]

class Token
{
    #[OA\Property()]
    private string $access_token;

    #[OA\Property()]
    private string $refresh_token;
}
