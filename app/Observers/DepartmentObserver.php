<?php

namespace App\Observers;

use App\Models\Department;
use Illuminate\Support\Facades\Cache;

class DepartmentObserver
{
    public function created(Department $department): void
    {
        Cache::forget('departments.all');
    }

    public function updated(Department $department): void
    {
        Cache::forget('departments.all');
    }

    public function deleted(Department $department): void
    {
        Cache::forget('departments.all');
    }

    public function restored(Department $department): void
    {
        Cache::forget('departments.all');
    }
}
