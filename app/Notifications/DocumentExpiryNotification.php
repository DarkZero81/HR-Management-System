<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DocumentExpiryNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public array $documents, public string $type = 'mail') {}

    public function via(object $notifiable): array
    {
        return match ($this->type) {
            'sms' => ['sms'],
            'whatsapp' => ['sms'],
            default => ['mail'],
        };
    }

    public function toMail(object $notifiable): MailMessage
    {
        $docList = collect($this->documents)
            ->map(fn($d) => "• {$d['type']}: تنتهي في {$d['expiry']}")
            ->join('\n');

        return (new MailMessage)
            ->subject('تنبيه: وثائقك تنتهي صلاحيتها قريباً')
            ->line("لديك الوثائق التالية التي تنتهي صلاحيتها خلال 7 أيام:\n\n{$docList}")
            ->action('إدارة الوثائق', url(route('my.documents.index')))
            ->line('يرجى التجديد قبل انتهاء الصلاحية.');
    }

    public function toSms(object $notifiable): ?string
    {
        return "تنبيه: لديك " . count($this->documents) . " وثيقة تنتهي صلاحيتها قريباً. يرجى مراجعة بوابة الموارد البشرية.";
    }
}