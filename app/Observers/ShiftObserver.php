<?php

namespace App\Observers;

use App\Models\Shift;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ShiftObserver
{
    public function creating(Shift $shift): void
    {
        Log::info('Creating shift', ['name' => $shift->shift_name]);
    }

    public function created(Shift $shift): void
    {
        Log::info('Shift created', ['id' => $shift->id, 'name' => $shift->shift_name]);
        $this->clearShiftsCache();
    }

    public function updating(Shift $shift): void
    {
        Log::info('Updating shift', ['id' => $shift->id, 'name' => $shift->shift_name]);
    }

    public function updated(Shift $shift): void
    {
        Log::info('Shift updated', ['id' => $shift->id, 'name' => $shift->shift_name]);
        $this->clearShiftsCache();
    }

    public function deleting(Shift $shift): void
    {
        Log::info('Deleting shift', ['id' => $shift->id, 'name' => $shift->shift_name]);
    }

    public function deleted(Shift $shift): void
    {
        Log::info('Shift deleted', ['id' => $shift->id, 'name' => $shift->shift_name]);
        $this->clearShiftsCache();
    }

    private function clearShiftsCache(): void
    {
        Cache::forget('shifts.all');
    }
}
