<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LoginOtpNotification extends Notification
{
    public function __construct(
        public string $code
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject(__('رمز تسجيل الدخول'))
            ->greeting(__('مرحباً :name!', ['name' => $notifiable->name]))
            ->line(__('رمز تسجيل الدخول الخاص بك هو: **:code**', ['code' => $this->code]))
            ->line(__('ينتهي صلاحية هذا الرمز خلال 15 دقيقة.'));
    }
}
