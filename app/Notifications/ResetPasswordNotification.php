<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\App as AppFacade;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResetPasswordNotification extends Notification
{
    use Queueable;

    public function __construct(
        protected string $token,
        protected string $appLocale = 'en',
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        AppFacade::setLocale($this->appLocale);

        $resetUrl = route('password.reset', [
            'locale' => $this->appLocale,
            'token' => $this->token,
        ]).'?email='.urlencode($notifiable->email);

        return (new MailMessage)
            ->subject(__('messages.password_reset_subject'))
            ->view('emails.auth.reset-password', [
                'resetUrl' => $resetUrl,
                'userEmail' => $notifiable->email,
                'appName' => config('app.name', __('messages.site_name')),
                'logoUrl' => asset('images/logo.png'),
                'greeting' => __('messages.password_reset_greeting'),
                'line' => __('messages.password_reset_line'),
                'buttonText' => __('messages.reset_password'),
                'expiration' => __('messages.password_reset_expiration'),
            ]);
    }
}
