<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class CustomMessageNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public string $message) {}

    public function via(object $notifiable): array
    {
        return ['sms'];
    }

    public function toSms(object $notifiable): ?string
    {
        return $this->message;
    }
}