<?php

namespace App\Notifications\Channels;

use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Http;

class SmsChannel
{
    public function send(object $notifiable, Notification $notification): void
    {
        $message = $notification->toSms($notifiable);

        if ($message) {
            $this->sendViaWhatsApp($notifiable->phone ?? '', $message);
        }
    }

    private function sendViaWhatsApp(string $phone, string $message): void
    {
        // تكامل مع WhatsApp Business API أو Twilio
        Http::post(env('WHATSAPP_API_URL'), [
            'phone' => $phone,
            'message' => $message,
            'token' => env('WHATSAPP_API_TOKEN'),
        ]);
    }
}