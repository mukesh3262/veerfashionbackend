<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\ServiceProvider;

use App\Action\IssueTokenAction;
use App\Action\LinkSocialAccountAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\ServiceProvider\LoginRequest;
use App\Http\Requests\Api\V1\RefreshTokenRequest;
use App\Http\Requests\Api\V1\SocialLoginRequest;
use App\Http\Resources\Api\V1\UserResource;
use App\Models\SocialLogin;
use App\Models\User;
use App\Services\ConditionMappingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response as FacadesResponse;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;
use Throwable;

class LoginController extends Controller
{
    public function login(LoginRequest $request, IssueTokenAction $issueToken): JsonResponse
    {
        try {
            // Generate a condition to find the user
            $condition = $this->generateConditionalMatch($request->login_type, $request->username);

            // Find the user by the given condition
            $user = User::query()
                ->select('id', 'name', 'email', 'locale', 'isd_code', 'mobile', 'mobile_verified_at', 'email_verified_at', 'password', 'is_active')
                ->when(is_string($condition), function ($query) use ($condition) {
                    $query->whereRaw($condition);
                }, function ($query) use ($condition) {
                    $query->where($condition);
                })
                ->first();

            // Abort if the user is not found or the password is incorrect
            abort_if(
                ! $user || ! Hash::check($request->password, $user->password),
                SymfonyResponse::HTTP_UNAUTHORIZED,
                __('auth.failed')
            );

            // Abort if the user is inactive
            abort_if(! $user->is_active, SymfonyResponse::HTTP_FORBIDDEN, __('basecode/api.inactive_account'));

            // Issue Token(s) [accessToken, refreshToken]
            $tokens = $issueToken->execute($user, $request);

            return FacadesResponse::success(
                message: __('basecode/api.logged_in', ['Entity' => 'User']),
                data: [
                    'user' => new UserResource($user),
                    'access_token' => $tokens->accessToken,
                    'refresh_token' => $tokens->refreshToken,
                    'is_already_logged_in' => $user->isLoggedInToOtherDevice($request->device_id) ?? false,
                ]
            );
        } catch (Throwable $th) {
            return FacadesResponse::error(
                message: $th->getMessage(),
                statusCode: SymfonyResponse::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    protected function generateConditionalMatch($type, $username): array|string
    {
        return match ($type) {
            'username' => ['username' => $username],
            'email' => ['email' => $username],
            'mobile' => "CONCAT(`isd_code`, `mobile`) = {$username}",
        };
    }

    public function socialLogin(SocialLoginRequest $request, IssueTokenAction $issueToken): JsonResponse
    {
        try {
            DB::beginTransaction();

            // Check if the social account is associated with a different user
            $isAccountAssociateWithOther = SocialLogin::query()
                ->where([
                    'social_id' => $request->social_id,
                    'type' => (new ConditionMappingService)->getSocialType($request->social_type),
                ])
                ->whereHas('user', function ($query) use ($request) {
                    $query->where('email', '!=', $request->email);
                })
                ->first();

            // Abort if account is associated with another user
            abort_if(
                $isAccountAssociateWithOther,
                SymfonyResponse::HTTP_UNPROCESSABLE_ENTITY,
                __('basecode/api.account_linked')
            );

            // Create a new user account if it doesn't exist
            $user = User::firstOrCreate([
                'email' => $request->email,
            ], [
                'password' => bcrypt(Str::random()),
                'email_verified_at' => $request->email ? now() : null,
                'username' => $this->usernameFromEmail($request->email ?? uniqid()),
                'name' => $request->name,
            ]);

            // Link the social account to the user
            (new LinkSocialAccountAction())->execute($user, $request->only('social_id', 'social_type'));

            if (! $user->wasRecentlyCreated && ! $user->is_active) {
                return FacadesResponse::error(
                    message: __('basecode/api.inactive_account'),
                    statusCode: SymfonyResponse::HTTP_FORBIDDEN
                );
            }

            // Issue access and refresh tokens
            $tokens = $issueToken->execute($user, $request);

            DB::commit();

            return FacadesResponse::success(
                message: __('basecode/api.logged_in', ['Entity' => 'User']),
                data: [
                    'user' => new UserResource($user),
                    'access_token' => $tokens->accessToken,
                    'refresh_token' => $tokens->refreshToken,
                    'is_already_logged_in' => $user->isLoggedInToOtherDevice($request->device_id) ?? false,
                ]
            );
        } catch (Throwable $th) {
            DB::rollBack();

            return FacadesResponse::error(
                message: $th->getMessage(),
                statusCode: SymfonyResponse::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    protected function usernameFromEmail($email): string
    {
        $emailPart = mb_strpos($email, '@');

        if ($emailPart === false) {
            return uniqid();
        }

        return mb_substr($email, 0, $emailPart) . uniqid();
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return FacadesResponse::success(
            message: __('basecode/api.signed_out')
        );
    }

    public function refreshToken(RefreshTokenRequest $request, IssueTokenAction $issueToken): JsonResponse
    {
        try {
            // Issue Token(s) [accessToken, refreshToken]
            $tokens = $issueToken->execute($request->user(), $request);

            return FacadesResponse::success(
                message: __('label.ok'),
                data: [
                    'access_token' => $tokens->accessToken,
                    'refresh_token' => $tokens->refreshToken,
                ]
            );
        } catch (Throwable $th) {
            return FacadesResponse::error(
                message: $th->getMessage(),
                statusCode: SymfonyResponse::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    public function logoutFromPastLogin(Request $request): JsonResponse
    {
        $currentTokenId = $request->user()->currentAccessToken()->getKey();

        $request->user()
            ->tokens()
            ->whereKeyNot($currentTokenId)
            ->delete();

        return response()->json([
            'message' => __('basecode/api.past_signed_out'),
        ]);
    }
}
