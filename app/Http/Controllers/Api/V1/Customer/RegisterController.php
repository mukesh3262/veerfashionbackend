<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Customer;

use App\Action\IssueTokenAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Customer\RegisterRequest;
use App\Http\Resources\Api\V1\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response as FacadesResponse;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;
use Throwable;

class RegisterController extends Controller
{

    public function register(RegisterRequest $request, IssueTokenAction $issueToken): JsonResponse
    {
        try {
            DB::beginTransaction();
            $user = User::create([
                'role'  => $request->role,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'isd_code' => $request->isd_code,
                'mobile' => $request->mobile,
            ]);

            // Issue Token(s) [accessToken, refreshToken]
            $tokens = $issueToken->execute($user, $request);
            DB::commit();
            
            // Send OTP using the reusable method
            $user->sendOTP();
             
            return FacadesResponse::success(
                message: __('basecode/api.registered', ['Entity' => 'User']),
                data: [
                    'user' => new UserResource($user->refresh()),
                    'access_token' => $tokens->accessToken,
                    'refresh_token' => $tokens->refreshToken,
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
}
