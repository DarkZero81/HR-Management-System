<?php

namespace App\Observers;

use App\Models\Holiday;
use Illuminate\Support\Facades\Cache;

class HolidayObserver
{
    public function created(Holiday $holiday): void
    {
        Cache::forget('holidays.all');
    }

    public function updated(Holiday $holiday): void
    {
        Cache::forget('holidays.all');
    }

    public function deleted(Holiday $holiday): void
    {
        Cache::forget('holidays.all');
    }

    public function restored(Holiday $holiday): void
    {
        Cache::forget('holidays.all');
    }
}
