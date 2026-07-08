<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $code,
        public string $type = 'login',
        public string $recipientEmail = ''
    ) {}

    public function envelope(): Envelope
    {
        $subject = $this->type === 'register'
            ? 'رمز تأكيد إنشاء الحساب'
            : 'رمز تسجيل الدخول';

        return new Envelope(
            subject: $subject,
            from: $this->recipientEmail ?: config('mail.from.address')
        );
    }

    public function content(): Content
    {
        $body = $this->type === 'register'
            ? 'مرحباً بك! رمز تأكيد إنشاء حسابك هو:'
            : 'رمز تسجيل الدخول الخاص بك هو:';

        return new Content(
            view: 'emails.otp',
            with: [
                'code' => $this->code,
                'body' => $body,
            ],
        );
    }
}