<?php

namespace Database\Seeders;

use App\Models\AttendanceDevice;
use App\Models\AttendanceLog;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class AttendanceLogSeeder extends Seeder
{
    public function run(): void
    {
        $employees = Employee::with('shift')->get();
        $devices = AttendanceDevice::all();

        if ($employees->isEmpty() || $devices->isEmpty()) {
            return; // Safety guard clause if tables are unpopulated
        }

        foreach ($employees as $employee) {
            $shift = $employee->shift;
            if (! $shift) {
                continue;
            }

            // Loop back 30 days to build out clear historical performance data
            for ($day = 30; $day >= 0; $day--) {
                $logDate = now()->subDays($day)->format('Y-m-d');

                // Skip tracking on standard weekend intervals
                if (Carbon::parse($logDate)->isWeekend()) {
                    continue;
                }

                // 10% chance a given employee calls out absent completely
                if (fake()->boolean(10)) {
                    continue;
                }

                // Safely extract the configured standard shift timing hour window
                $shiftTime = date('H:i:s', strtotime($shift->start_time));

                // Generate baseline shift benchmark time
                $shiftStartTime = Carbon::parse("{$logDate} {$shiftTime}");

                // REALISTIC ARRIVAL RADAR: 70% arrive slightly early/on time. 30% arrive late.
                if (fake()->boolean(70)) {
                    // Arrive anywhere from 20 minutes early to right on the dot
                    $checkInTime = $shiftStartTime->copy()->subMinutes(fake()->numberBetween(0, 20));
                } else {
                    // Arrive anywhere from 1 to 90 minutes late
                    $checkInTime = $shiftStartTime->copy()->addMinutes(fake()->numberBetween(1, 90));
                }

                // FIX: Calculate accurate lateness parameters using safe cloning boundaries
                $lateMinutes = 0;
                $gracePeriodMinutes = $shift->grace_period_minutes ?? 15;
                $gracePeriodThreshold = $shiftStartTime->copy()->addMinutes($gracePeriodMinutes);

                if ($checkInTime->gt($gracePeriodThreshold)) {
                    // Lateness is calculated from the true shift start marker, not the grace threshold
                    $lateMinutes = $checkInTime->diffInMinutes($shiftStartTime);
                }

                // Generate check-out time exactly 8 to 9 hours post-arrival
                $checkOutTime = $checkInTime->copy()->addHours(fake()->numberBetween(8, 9))->addMinutes(fake()->numberBetween(0, 59));

                // Commit the synchronized metric variables safely down into the database rows
                AttendanceLog::updateOrCreate(
                    ['employee_id' => $employee->id, 'log_date' => $logDate],
                    [
                        'device_id' => $devices->random()->id,
                        'check_in' => $checkInTime,
                        'check_out' => $checkOutTime,
                        'late_minutes' => $lateMinutes,
                        'overtime_minutes' => 0,
                        'status' => $lateMinutes > 0 ? 'late' : 'present',
                    ]
                );
            }
        }
    }
}
