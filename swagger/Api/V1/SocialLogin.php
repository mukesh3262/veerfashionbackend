<?php

// declare(strict_types=1);

// namespace Swagger\Api\V1;

// use App\Enums\DeviceTypeEnum;
// use App\Enums\SocialTypeEnum;
// use OpenApi\Attributes as OA;
// use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

// #[OA\Post(
//     path: '/social-login',
//     tags: ['User Authentication'],
//     summary: 'Make the user login using socialite.',
//     operationId: 'socialLogin',
//     parameters: [
//         new OA\Parameter(ref: '#/components/parameters/Accept'),
//         new OA\Parameter(ref: '#/components/parameters/Accept-Language'),
//     ],
//     requestBody: new OA\RequestBody(
//         description: 'Input data format',
//         content: new OA\MediaType(
//             mediaType: 'application/x-www-form-urlencoded',
//             schema: new OA\Schema(
//                 required: ['social_id', 'name', 'social_type', 'device_name', 'device_type', 'device_id'],
//                 properties: [
//                     new OA\Property(
//                         property: 'social_id',
//                         type: 'string'
//                     ),
//                     new OA\Property(
//                         property: 'name',
//                         type: 'string'
//                     ),
//                     new OA\Property(
//                         property: 'email',
//                         type: 'string'
//                     ),
//                     new OA\Property(
//                         property: 'social_type',
//                         type: 'string',
//                         enum: SocialTypeEnum::class
//                     ),
//                     new OA\Property(
//                         property: 'device_type',
//                         type: 'string',
//                         enum: [DeviceTypeEnum::IOS, DeviceTypeEnum::ANDROID]
//                     ),
//                     new OA\Property(
//                         property: 'device_name',
//                         type: 'string'
//                     ),
//                     new OA\Property(
//                         property: 'device_id',
//                         type: 'string'
//                     ),
//                     new OA\Property(
//                         property: 'device_token',
//                         type: 'string'
//                     ),
//                 ]
//             )
//         )
//     ),
//     responses: [
//         new OA\Response(
//             response: SymfonyResponse::HTTP_OK,
//             description: 'Successful operation',
//             content: new OA\JsonContent(ref: '#/components/schemas/LoginResponse'),
//         ),
//         new OA\Response(response: SymfonyResponse::HTTP_UNPROCESSABLE_ENTITY, ref: '#/components/responses/UnprocessableEntity'),
//         new OA\Response(response: SymfonyResponse::HTTP_INTERNAL_SERVER_ERROR, ref: '#/components/responses/InternalServerError'),
//         new OA\Response(response: SymfonyResponse::HTTP_SERVICE_UNAVAILABLE, ref: '#/components/responses/ServiceUnavailable'),
//     ]
// )]

// class SocialLogin {}
