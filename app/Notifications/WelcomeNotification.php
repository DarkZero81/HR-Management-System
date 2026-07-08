<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class WelcomeNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public string $userName) {}

    public function via(object $notifiable): array
    {
        return ['sms'];
    }

    public function toSms(object $notifiable): ?string
    {
        return "مرحباً {$this->userName}! مرحباً بك في HR Engine. تم تفعيل حسابك بنجاح.";
    }
}