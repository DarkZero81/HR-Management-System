<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Employee;
use App\Models\AttendanceLog;
use Carbon\Carbon;

class MarkAbsentDaily extends Command
{
    protected $signature = 'attendance:mark-absent';
    protected $description = 'Mark employees absent if no check-in was recorded for today';

    public function handle(): void
    {
        $today = Carbon::today()->format('Y-m-d');
        $employees = Employee::all();
        $marked = 0;

        foreach ($employees as $employee) {
            $exists = AttendanceLog::where('employee_id', $employee->id)
                ->where('log_date', $today)
                ->exists();

            if (!$exists) {
                AttendanceLog::create([
                    'employee_id' => $employee->id,
                    'log_date' => $today,
                    'check_in' => null,
                    'check_out' => null,
                    'late_minutes' => 0,
                    'overtime_minutes' => 0,
                    'status' => 'absent',
                ]);
                $marked++;
            }
        }

        $this->info("Marked {$marked} absent records for {$today}.");
    }
}
