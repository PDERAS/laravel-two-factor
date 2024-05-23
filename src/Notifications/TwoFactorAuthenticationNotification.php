<?php

namespace Pderas\TwoFactor\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TwoFactorAuthenticationNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct()
    {
        //
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
        $code = $this->getVerificationCode($notifiable);
        return (new MailMessage)
            ->subject('Two Factor Authentication Code')
            ->line('Here is your two factor authentication code:')
            ->line($code);
    }

    /**
     * Get the notifiable's latest verification code
     *
     * @param  mixed  $notifiable
     * @return string
     */
    protected function getVerificationCode($notifiable)
    {
        return $notifiable->latestVerificationCode;
    }
}
