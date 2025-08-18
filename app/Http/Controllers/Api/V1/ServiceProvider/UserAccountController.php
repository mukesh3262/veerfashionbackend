<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\ServiceProvider;

use App\Action\SendOTPAction;
use App\Action\VerifyOTPAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\AcceptPushRequest;
use App\Http\Requests\Api\V1\SendOTPRequest;
use App\Http\Requests\Api\V1\UpdateLocaleRequest;
use App\Http\Requests\Api\V1\UpdatePasswordRequest;
use App\Http\Requests\Api\V1\UpdateProfileRequest;
use App\Http\Requests\Api\V1\VerifyOTPRequest;
use App\Http\Resources\Api\V1\UserResource;
use App\Models\User;
use App\Traits\Exceptions\ExceptionMessages;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response as FacadesResponse;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;
use Throwable;

class UserAccountController extends Controller
{
    use ExceptionMessages;

    public function getUserProfile(Request $request): JsonResponse
    {
        return response()->json([
            'message' => __('label.ok'),
            'data' => [
                'user' => new UserResource($request->user()),
            ],
        ]);
    }

    public function updateUserProfile(UpdateProfileRequest $request): JsonResponse
    {
        try {
            // Update the user's profile
            $user = tap($request->user(), function (User $user) use ($request) {
                $user->fill($request->validated());

                // Reset email verification timestamp if email is dirty
                if ($user->isDirty('email')) {
                    $user->email_verified_at = null;
                }

                // Reset mobile verification timestamp if mobile is dirty
                if ($user->isDirty('mobile')) {
                    $user->mobile_verified_at = null;
                }
                $user->save();
            });

            return FacadesResponse::success(
                message: __('basecode/api.updated', ['Entity' => 'Your profile']),
                data: ['user' => new UserResource($user)]
            );
        } catch (Throwable $th) {
            return $this->exceptionResponse($th);
        }
    }

    public function updatePassword(UpdatePasswordRequest $request): JsonResponse
    {
        try {
            // Update the user's password
            $request->user()->update([
                'password' => bcrypt($request->new_password),
            ]);

            return FacadesResponse::success(
                message: __('basecode/api.password_updated', ['entity' => 'Your password']),
                statusCode: SymfonyResponse::HTTP_OK
            );
        } catch (Throwable $th) {
            return $this->exceptionResponse($th);
        }
    }

    public function updateLocale(UpdateLocaleRequest $request): JsonResponse
    {
        try {
            $request->user()->update([
                'locale' => $request->locale,
            ]);

            return FacadesResponse::success(
                message: __('basecode/api.updated', ['entity' => 'Your language']),
                statusCode: SymfonyResponse::HTTP_OK
            );
        } catch (Throwable $th) {
            return $this->exceptionResponse($th);
        }
    }

    public function acceptPush(AcceptPushRequest $request): JsonResponse
    {
        try {
            // Update the user's push notification status
            $request->user()->update([
                'is_push_enabled' => $request->status,
            ]);

            return FacadesResponse::success(
                message: __('basecode/api.updated', ['Entity' => 'Push Notification']),
                statusCode: SymfonyResponse::HTTP_OK
            );
        } catch (Throwable $th) {
            return $this->exceptionResponse($th);
        }
    }

    public function sendOTP(SendOTPRequest $request, SendOTPAction $sendOTPAction): JsonResponse
    {
        // TODO: Implement SMS sending logic for mobile number
        // OTPService -> sendOTPBySMS (SMS sending logic)
        return $sendOTPAction->execute($request);
    }

    public function verifyOTP(VerifyOTPRequest $request, VerifyOTPAction $verifyOTPAction): JsonResponse
    {
        try {
            $verificationType = $request->verification_for;

            // Execute the verification action
            $verifyOTPAction->execute($request->user(), $verificationType);

            return FacadesResponse::success(
                message: __('basecode/api.otp.verified'),
                statusCode: SymfonyResponse::HTTP_OK
            );
        } catch (Throwable $th) {
            return $this->exceptionResponse($th);
        }
    }
}
