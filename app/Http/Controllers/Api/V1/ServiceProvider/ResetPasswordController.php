<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\ServiceProvider;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\ResetPasswordRequest;
use App\Models\User;
use App\Traits\Exceptions\ExceptionMessages;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response as FacadesResponse;
use Throwable;

class ResetPasswordController extends Controller
{
    use ExceptionMessages;

    public function reset(ResetPasswordRequest $request): JsonResponse
    {
        try {
            DB::beginTransaction();

            DB::table('password_reset_tokens')
                ->where('email', $request->email)
                ->delete();

            User::where('email', $request->email)
                ->update([
                    'password' => bcrypt($request->password),
                ]);

            DB::commit();

            return FacadesResponse::success(message: __('passwords.reset'));
        } catch (Throwable $th) {
            DB::rollBack();

            return $this->exceptionResponse($th);
        }
    }
}
