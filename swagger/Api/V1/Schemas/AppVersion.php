<?php

declare(strict_types=1);

namespace Swagger\Api\V1\Schemas;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'AppVersion',
    type: 'object',
)]

class AppVersion
{
    #[OA\Property(format: 'int32')]
    private int $version;

    #[OA\Property()]
    private string $platform;

    #[OA\Property()]
    private bool $force_updateable;
}
