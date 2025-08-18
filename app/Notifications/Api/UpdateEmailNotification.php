<?php

declare(strict_types=1);

namespace App\Notifications\Api;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Lang;

class UpdateEmailNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public string $token)
    {
        $this->afterCommit();
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject(Lang::get('Update Email Address Request'))
            ->line(Lang::get("Hi {$notifiable->name}!"))
            ->line(Lang::get('You are receiving this email because we received an update email request for your account.'))
            ->action(Lang::get($this->token), 'javascript:;')
            ->line(Lang::get('This OTP will expire in :expire minutes.', ['expire' => config('auth.otp_expires_in')]))
            ->line(Lang::get('If you did not intiated this action, no further action is required.'));
    }
}
