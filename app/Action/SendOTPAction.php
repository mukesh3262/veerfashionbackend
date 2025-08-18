<?php

declare(strict_types=1);

namespace App\Action;

use App\Helpers\Helper;
use App\Http\Requests\Api\V1\SendOTPRequest;
use App\Services\OTPService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\Response;

class SendOTPAction
{
    public function __construct(
        private OTPService $otpService
    ) {}

    /**
     * Send OTP based on verification type
     */
    public function execute(SendOTPRequest $request): JsonResponse
    {
        try {
            DB::beginTransaction();

            $user = $request->user();
            $verificationType = $request->verification_for;

            // Generate OTP
            $otp = Helper::generateOtp();

            // Send OTP based on type
            $this->sendOTPByType($user, $otp, $verificationType);

            // Update user with OTP details
            $this->otpService->updateUserOTPDetails(
                $user,
                $otp,
                $verificationType
            );

            DB::commit();

            return response()->json([
                'message' => $this->getSuccessMessage(
                    $verificationType,
                    $request
                ),
            ]);
        } catch (Exception $e) {
            DB::rollBack();

            return $this->handleException($e);
        }
    }

    /**
     * Send OTP via appropriate channel
     */
    private function sendOTPByType($user, string $otp, string $type): void
    {
        match ($type) {
            'email' => $this->otpService->sendOTPByEmail($user, $otp),
            'mobile' => $this->otpService->sendOTPBySMS($user, $otp),
            default => throw new InvalidArgumentException('Invalid verification type')
        };
    }

    /**
     * Generate success message
     */
    private function getSuccessMessage(
        string $verificationType,
        SendOTPRequest $request
    ): string {

        $destination = $verificationType === 'email'
            ? $request->user()->email
            : "{$request->user()->isd_code}{$request->user()->mobile}";

        return __('basecode/api.otp.sent', ['over' => $destination]);
    }

    /**
     * Handle different types of exceptions
     */
    private function handleException(Exception $e): JsonResponse
    {
        return match (get_class($e)) {
            \Illuminate\Http\Client\ConnectionException::class => response()->json([
                'message' => __('basecode/api.otp.timed_out'),
            ], Response::HTTP_INTERNAL_SERVER_ERROR),

            \Illuminate\Http\Client\RequestException::class => response()->json([
                'message' => __('basecode/api.otp.something_went_wrong'),
            ], Response::HTTP_INTERNAL_SERVER_ERROR),

            default => response()->json([
                'message' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR)
        };
    }
}
