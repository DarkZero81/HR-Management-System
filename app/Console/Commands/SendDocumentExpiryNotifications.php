<?php

namespace App\Console\Commands;

use App\Models\Document;
use App\Notifications\DocumentExpiryNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;

class SendDocumentExpiryNotifications extends Command
{
    protected $signature = 'documents:send-expiry-notifications';
    protected $description = 'Send automatic notifications for documents expiring within 7 days';

    public function handle(): int
    {
        $expiringSoon = Document::with('employee.user')
            ->whereBetween('expiry_date', [now(), now()->addDays(7)])
            ->where('expiry_date', '>=', now())
            ->get();

        $count = 0;
        foreach ($expiringSoon->groupBy('employee_id') as $employeeId => $documents) {
            $user = $documents->first()?->employee?->user;
            if ($user) {
                $docData = $documents->map(fn($d) => [
                    'type' => $d->document_type,
                    'expiry' => $d->expiry_date->format('Y-m-d'),
                ])->toArray();

                Notification::send($user, new DocumentExpiryNotification($docData));
                $count++;
            }
        }

        $this->info("Sent {$count} document expiry notifications.");
        return self::SUCCESS;
    }
}