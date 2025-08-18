<?php

declare(strict_types=1);

namespace Swagger\Api\V1\Schemas;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'User',
    description: 'User Schema',
    title: 'User',
)]

class User
{
    #[OA\Property(
        type: 'string',
        format: 'uuid',
        description: 'ID',
        title: 'ID'
    )]
    private string $id;

    #[OA\Property(
        description: 'First Name',
        title: 'First Name'
    )]
    private string $first_name;

    #[OA\Property(
        description: 'Last Name',
        title: 'Last Name'
    )]
    private string $last_name;

    #[OA\Property(
        description: 'Stripe Customer Id',
        title: 'Stripe Customer Id',
        nullable: true

    )]
    private ?string $stripe_customer_id;

    #[OA\Property(
        description: 'Email',
        title: 'Email'
    )]
    private string $email;

    #[OA\Property(
        description: 'Country ISD code',
        title: 'Country ISD code',
        nullable: true
    )]
    private ?string $isd_code;

    #[OA\Property(
        description: 'Mobile',
        title: 'Mobile',
        nullable: true
    )]
    private ?string $mobile;

    #[OA\Property(
        description: 'Mobile verified time.',
        title: 'Mobile verified time.',
        nullable: true
    )]
    private ?string $mobile_verified_at;

    #[OA\Property(
        description: 'Email verified time.',
        title: 'Email verified time.',
        nullable: true
    )]
    private ?string $email_verified_at;


    #[OA\Property(
        description: 'Is Push Enabled',
        title: 'Is Push Enabled',
        type: 'boolean'
    )]
    private bool $is_push_enabled;

    #[OA\Property(
        description: 'Profile Photo',
        title: 'Profile Photo',
        nullable: true
    )]
    private ?string $profile_photo;
}
