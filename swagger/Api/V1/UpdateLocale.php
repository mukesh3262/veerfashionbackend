<?php

// declare(strict_types=1);

// namespace Swagger\Api\V1;

// use App\Enums\LanguageEnum;
// use OpenApi\Attributes as OA;
// use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

// #[OA\Patch(
//     path: '/update-locale',
//     tags: ['User Profile'],
//     summary: 'Update the user locale.',
//     operationId: 'updateLocale',
//     security: [['bearerAuth' => []]],
//     parameters: [
//         new OA\Parameter(ref: '#/components/parameters/Accept'),
//     ],
//     requestBody: new OA\RequestBody(
//         description: 'Input data format',
//         content: new OA\MediaType(
//             mediaType: 'application/x-www-form-urlencoded',
//             schema: new OA\Schema(
//                 required: ['locale'],
//                 properties: [
//                     new OA\Property(
//                         property: 'locale',
//                         type: 'string',
//                         enum: LanguageEnum::class
//                     ),
//                 ]
//             )
//         )
//     ),
//     responses: [
//         new OA\Response(
//             response: 200,
//             ref: '#/components/responses/OK'
//         ),
//         new OA\Response(
//             response: SymfonyResponse::HTTP_UNAUTHORIZED,
//             ref: '#/components/responses/Unauthorized'
//         ),
//         new OA\Response(
//             response: SymfonyResponse::HTTP_UNPROCESSABLE_ENTITY,
//             ref: '#/components/responses/UnprocessableEntity'
//         ),
//         new OA\Response(
//             response: SymfonyResponse::HTTP_INTERNAL_SERVER_ERROR,
//             ref: '#/components/responses/InternalServerError'
//         ),
//         new OA\Response(
//             response: SymfonyResponse::HTTP_SERVICE_UNAVAILABLE,
//             ref: '#/components/responses/ServiceUnavailable'
//         ),
//     ]
// )]

// class UpdateLocale {}
