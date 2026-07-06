<?php

namespace App\Console;

use App\Console\Commands\SendDocumentExpiryNotifications;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        SendDocumentExpiryNotifications::class,
    ];

    protected function schedule(Schedule $schedule): void
    {
        $schedule->command('documents:send-expiry-notifications')
            ->dailyAt('08:00')
            ->withoutOverlapping();
    }

    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');
    }
}