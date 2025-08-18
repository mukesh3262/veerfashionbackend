<?php

declare(strict_types=1);

namespace App\Models;

use App\Helpers\Helper;
use App\Notifications\Api\EmailVerificationNotification;
use Carbon\Carbon;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasUuids, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'uuid',
        'role',
        'first_name',
        'last_name',
        'stripe_customer_id',
        'email',
        'password',
        'email_verified_at',
        'isd_code',
        'mobile',
        'mobile_verified_at',
        'email_otp',
        'email_otp_expired_at',
        'mobile_otp',
        'mobile_otp_expired_at',
        'profile_photo',
        'is_active',
        'is_push_enabled',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'mobile_verified_at' => 'datetime',
            'email_otp_expired_at' => 'datetime',
            'mobile_otp_expired_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Returns an array of unique identifiers for the model.
     *
     * By default, this includes the "uuid" field
     *
     * @return array<string>
     */
    public function uniqueIds()
    {
        return ['uuid'];
    }

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'uuid';
    }

    // Conditional Methods
    public function isLoggedInToOtherDevice(string $deviceId): bool
    {
        return $this->tokens()
            ->where('device_id', '!=', $deviceId)
            ->count() > 0 ? true : false;
    }

    // Relationship
    public function socialLogins(): HasMany
    {
        return $this->hasMany(SocialLogin::class, 'user_id', 'id');
    }

    public function sendOTP()
    {
        $otp = Helper::generateOtp();
        
        $this->update([
            'email_otp' => $otp,
            'email_otp_expired_at' => Carbon::now()->addMinutes(config('auth.otp_expires_in')),
        ]);

        // Send OTP email
        $this->sendEmailVerificationNotification($otp);
    }

    public function sendEmailVerificationNotification($otp = "")
    {
        $this->notify(new EmailVerificationNotification($otp));
    }

}
