<?php

// declare(strict_types=1);

// namespace Swagger\Api\V1;

// use OpenApi\Attributes as OA;
// use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

// #[OA\Post(
//     path: '/password/email',
//     tags: ['User Authentication'],
//     summary: 'Send the reset password link or OTP email to user.',
//     operationId: 'forgotPassword',
//     parameters: [
//         new OA\Parameter(ref: '#/components/parameters/Accept'),
//         new OA\Parameter(ref: '#/components/parameters/Accept-Language'),
//     ],
//     requestBody: new OA\RequestBody(
//         description: 'Input data format',
//         content: new OA\MediaType(
//             mediaType: 'application/x-www-form-urlencoded',
//             schema: new OA\Schema(
//                 required: ['email'],
//                 properties: [
//                     new OA\Property(
//                         property: 'email',
//                         type: 'string',
//                     ),
//                 ],
//             ),
//         ),
//     ),
//     responses: [
//         new OA\Response(response: SymfonyResponse::HTTP_OK, ref: '#/components/responses/OK'),
//         new OA\Response(response: SymfonyResponse::HTTP_UNPROCESSABLE_ENTITY, ref: '#/components/responses/UnprocessableEntity'),
//         new OA\Response(response: SymfonyResponse::HTTP_INTERNAL_SERVER_ERROR, ref: '#/components/responses/InternalServerError'),
//         new OA\Response(response: SymfonyResponse::HTTP_SERVICE_UNAVAILABLE, ref: '#/components/responses/ServiceUnavailable'),
//     ]
// )]

// class ForgotPassword {}
