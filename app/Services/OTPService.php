<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\User;
use App\Notifications\Api\EmailVerificationNotification;
use App\Notifications\Api\UpdateEmailNotification;
use Illuminate\Support\Facades\Notification;
use Twilio\Rest\Client;
use Illuminate\Support\Facades\Log;

class OTPService
{
    protected Client $twilioClient;
    protected string $fromNumber;

    /**
     * Constructor to initialize Twilio client and service SID.
     *
     * @throws \Exception
     */
    public function __construct()
    {
        $this->twilioClient = new Client(config('services.twilio.sid'), config('services.twilio.token'));
        $this->fromNumber = config('services.twilio.phone_number');
    }

    /**
     * Send OTP via email
     */
    public function sendOTPByEmail(User $user, string $otp): void
    {
        // Notification::send($user, new UpdateEmailNotification($otp));
        Notification::send($user, new EmailVerificationNotification($otp));
    }

    /**
     * Send OTP via SMS
     */
    public function sendOTPBySMS(User $user, string $otp): void
    {
        // Implement SMS sending logic
        try {
            $mobile = $user->isd_code . $user->mobile;
            $message = "Your verification code is: $otp";
    
           $this->twilioClient->messages->create($mobile, [
                'from' => $this->fromNumber,
                'body' => $message,
            ]);
        } catch (\Exception $exception) {
            Log::error('Twilio SMS Error: ' . $exception->getMessage());
        }
    }

    /**
     * Update user with OTP details
     */
    public function updateUserOTPDetails(
        User $user,
        string $otp,
        string $verificationType
    ): void {
        $otpFields = $this->getOTPFields($verificationType);

        $user->update([
            $otpFields['otp'] => $otp,
            $otpFields['expiry'] => now()->addMinutes(config('auth.otp_expires_in')),
        ]);
    }

    /**
     * Determine OTP-related fields based on verification type
     */
    private function getOTPFields(string $verificationType): array
    {
        return $verificationType === 'email'
            ? [
                'otp' => 'email_otp',
                'expiry' => 'email_otp_expired_at',
            ]
            : [
                'otp' => 'mobile_otp',
                'expiry' => 'mobile_otp_expired_at',
            ];
    }
}
