<?php

namespace App\Observers;

use App\Models\Shift;
use Illuminate\Support\Facades\Cache;

class ShiftObserver
{
    public function created(Shift $shift): void
    {
        Cache::forget('shifts.all');
    }

    public function updated(Shift $shift): void
    {
        Cache::forget('shifts.all');
    }

    public function deleted(Shift $shift): void
    {
        Cache::forget('shifts.all');
    }

    public function restored(Shift $shift): void
    {
        Cache::forget('shifts.all');
    }
}
