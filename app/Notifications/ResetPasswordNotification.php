<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class ResetPasswordNotification extends Notification
{
    use Queueable;

    public string $token;

    public function __construct(string $token)
    {
        $this->token = $token;
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $resetUrl = url(route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));

        return (new MailMessage)
            ->subject('Reset Password — Autonexa')
            ->markdown('emails.reset-password', [
                'resetUrl'  => $resetUrl,
                'userName'  => $notifiable->name,
                'userEmail' => $notifiable->email,
                'expireMin' => config('auth.passwords.'.config('auth.defaults.passwords').'.expire', 60),
            ]);
    }

    public function toArray(object $notifiable): array
    {
        return [];
    }
}