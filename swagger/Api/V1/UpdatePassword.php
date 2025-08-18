<?php

// declare(strict_types=1);

// namespace Swagger\Api\V1;

// use OpenApi\Attributes as OA;
// use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

// #[OA\Patch(
//     path: '/update-password',
//     tags: ['User Profile'],
//     summary: 'Update the user password.',
//     operationId: 'updatePassword',
//     security: [['bearerAuth' => []]],
//     parameters: [
//         new OA\Parameter(ref: '#/components/parameters/Accept'),
//         new OA\Parameter(ref: '#/components/parameters/Accept-Language'),
//     ],
//     requestBody: new OA\RequestBody(
//         description: 'Input data format',
//         content: new OA\MediaType(
//             mediaType: 'application/x-www-form-urlencoded',
//             schema: new OA\Schema(
//                 required: ['password', 'new_password'],
//                 properties: [
//                     new OA\Property(
//                         property: 'password',
//                         type: 'string',
//                     ),
//                     new OA\Property(
//                         property: 'new_password',
//                         type: 'string',
//                     ),
//                 ],
//             ),
//         ),
//     ),
//     responses: [
//         new OA\Response(response: SymfonyResponse::HTTP_OK, ref: '#/components/responses/OK'),
//         new OA\Response(response: SymfonyResponse::HTTP_UNAUTHORIZED, ref: '#/components/responses/Unauthorized'),
//         new OA\Response(response: SymfonyResponse::HTTP_UNPROCESSABLE_ENTITY, ref: '#/components/responses/UnprocessableEntity'),
//         new OA\Response(response: SymfonyResponse::HTTP_INTERNAL_SERVER_ERROR, ref: '#/components/responses/InternalServerError'),
//         new OA\Response(response: SymfonyResponse::HTTP_SERVICE_UNAVAILABLE, ref: '#/components/responses/ServiceUnavailable'),
//     ]
// )]

// class UpdatePassword {}
