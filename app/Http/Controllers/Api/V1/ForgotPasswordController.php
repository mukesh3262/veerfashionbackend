<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\ForgotPasswordRequest;
use App\Models\User;
use App\Notifications\Frontend\ForgotPasswordNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Response as FacadesResponse;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;
use Illuminate\Http\JsonResponse;
use Throwable;

class ForgotPasswordController extends Controller
{
    public function sendResetLinkEmail(ForgotPasswordRequest $request): JsonResponse
    {
        try {
            DB::beginTransaction();

            if (config('auth.password_reset_mode') === 'otp') {

                $user = User::select('id', 'email')->where(['email' => $request->email])->first();

                $token = Helper::generateOtp();

                DB::table('password_reset_tokens')->where('email', $request->email)->delete();

                DB::table('password_reset_tokens')->insert([
                    'email' => $request->email,
                    'token' => $token,
                    'created_at' => now(),
                ]);

                Notification::send($user, new ForgotPasswordNotification($token));

                $status = Password::RESET_LINK_SENT;
            } else {
                $status = Password::sendResetLink(
                    $request->only('email')
                );
            }

            DB::commit();

            $statusCode = $status === Password::RESET_LINK_SENT
                ? SymfonyResponse::HTTP_OK
                : SymfonyResponse::HTTP_UNPROCESSABLE_ENTITY;

            return FacadesResponse::json([
                'message' => __($status),
            ], $statusCode);
        } catch (Throwable $th) {
            return FacadesResponse::error(
                message: $th->getMessage(),
                statusCode: SymfonyResponse::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}
