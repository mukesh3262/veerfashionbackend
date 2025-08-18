<?php

declare(strict_types=1);

namespace Swagger\Api\V1\Schemas;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'Pagination',
    description: 'Pagination Schema',
    title: 'Pagination',
)]

class Pagination
{
   
    #[OA\Property(
        description: 'Total',
        title: 'Total',
        type: 'integer'
    )]
    private int $total;

    #[OA\Property(
        description: 'Per Page',
        title: 'Per Page'
    )]
    private int $per_page;

    #[OA\Property(
        description: 'Current Page',
        title: 'Current Page',
    )]
    private ?int $current_page;

    #[OA\Property(
        description: 'Last Page',
        title: 'Last Page'
    )]
    private int $last_page;
}
