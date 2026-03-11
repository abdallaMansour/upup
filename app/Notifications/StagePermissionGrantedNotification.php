<?php

namespace App\Notifications;

use App\Models\UserChildhoodStage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class StagePermissionGrantedNotification extends Notification
{
    public function __construct(
        public UserChildhoodStage $stage,
        public string $pin,
        public string $granteeName,
        public string $expiresAtFormatted
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $pinUrl = route('profile.pin.form', $this->stage);

        return (new MailMessage)
            ->subject('صلاحية مؤقتة لعرض صفحة: ' . $this->stage->name)
            ->greeting('مرحباً ' . $this->granteeName . '!')
            ->line('تم منحك صلاحية مؤقتة لعرض صفحة **' . $this->stage->name . '**.')
            ->line('رمز الدخول (PIN) الخاص بك هو: **' . $this->pin . '**')
            ->action('عرض الصفحة', $pinUrl)
            ->line('ينتهي صلاحية هذه الصلاحية في: ' . $this->expiresAtFormatted)
            ->line('احتفظ بهذا الرمز ولا تشاركه مع الآخرين.');
    }
}
