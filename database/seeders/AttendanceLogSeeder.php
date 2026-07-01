<?php

namespace Database\Seeders;

use App\Models\AttendanceLog;
use App\Models\AttendanceDevice;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class AttendanceLogSeeder extends Seeder
{
    public function run(): void
    {
        $employees = Employee::with('shift')->get();
        $devices = AttendanceDevice::all();

        foreach ($employees as $employee) {
            $shift = $employee->shift;
            if (!$shift) continue;

            for ($day = 0; $day < 30; $day++) {
                $logDate = now()->subDays($day)->format('Y-m-d');
                $shiftTime = date('H:i:s', strtotime($shift->start_time));
                $checkInTime = Carbon::parse("{$logDate} {$shiftTime}")->addMinutes(fake()->numberBetween(0, 120));

                if (fake()->boolean(10)) continue;

                $checkOutTime = (clone $checkInTime)->addHours(fake()->numberBetween(8, 9));
                $lateMinutes = 0;
                $shiftStartTime = Carbon::parse("{$logDate} {$shiftTime}");

                if ($checkInTime > $shiftStartTime->addMinutes($shift->grace_period_minutes)) {
                    $lateMinutes = $checkInTime->diffInMinutes($shiftStartTime);
                }

                AttendanceLog::create([
                    'employee_id' => $employee->id,
                    'device_id' => $devices->random()->id,
                    'log_date' => $logDate,
                    'check_in' => $checkInTime,
                    'check_out' => $checkOutTime,
                    'late_minutes' => $lateMinutes,
                    'overtime_minutes' => 0,
                    'status' => $lateMinutes > 0 ? 'late' : 'present',
                ]);
            }
        }
    }
}