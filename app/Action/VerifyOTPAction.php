<?php

declare(strict_types=1);

namespace App\Action;

use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;

class VerifyOTPAction
{
    /**
     * Verify and complete OTP verification for a user
     *
     * @throws Exception
     */
    public function execute(User $user, string $verificationType): bool
    {
        DB::transaction(function () use ($user, $verificationType) {
            $updateData = $verificationType === 'email'
                ? [
                    'email_otp' => null,
                    'email_otp_expired_at' => null,
                    'email_verified_at' => now(),
                    'email' => request()->email,
                ]
                : [
                    'mobile_otp' => null,
                    'mobile_otp_expired_at' => null,
                    'mobile_verified_at' => now(),
                    'isd_code' => request()->isd_code,
                    'mobile' => request()->mobile,
                ];

            $user->update($updateData);
        });

        return true;
    }
}
