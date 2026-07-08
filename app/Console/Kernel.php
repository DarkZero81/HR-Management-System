<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Console\Commands\SendDocumentExpiryNotifications;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        SendDocumentExpiryNotifications::class,
    ];

    protected function schedule(Schedule $schedule): void
    {
        $schedule->command('notifications:document-expiry')
            ->dailyAt('08:00')
            ->timezone('Asia/Riyadh')
            ->withoutOverlapping();
    }

    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');
    }
}