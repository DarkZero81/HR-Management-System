<?php

namespace App\Console\Commands;

use App\Models\Document;
use App\Notifications\DocumentExpiryNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;

class SendDocumentExpiryNotifications extends Command
{
    protected $signature = 'notifications:document-expiry';
    protected $description = 'Send automatic notifications for documents expiring within 7 days';

    public function handle(): int
    {
        $expiringSoon = Document::with('employee.user')
            ->whereBetween('expiry_date', [now(), now()->addDays(7)])
            ->where('expiry_date', '>=', now())
            ->get();

        $count = 0;
        $totalNotifications = 0;

        foreach ($expiringSoon->groupBy('employee_id') as $employeeId => $documents) {
            $user = $documents->first()?->employee?->user;
            if ($user && $user->email) {
                $docData = $documents->map(fn($d) => [
                    'type' => $d->document_type,
                    'expiry' => $d->expiry_date->format('Y-m-d'),
                ])->toArray();

                Notification::send($user, new DocumentExpiryNotification($docData, 'mail'));
                $count++;
                $totalNotifications += count($documents);
            }
        }

        $this->info("Sent {$totalNotifications} expiry notifications to {$count} employees.");
        return self::SUCCESS;
    }
}