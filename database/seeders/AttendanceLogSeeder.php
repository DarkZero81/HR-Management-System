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

        if ($employees->isEmpty() || $devices->isEmpty()) {
            return; // Safety guard clause if tables are unpopulated
        }

        foreach ($employees as $employee) {
            $shift = $employee->shift;
            if (!$shift) continue;

            // Loop back 30 days to build out clear historical performance data
            for ($day = 30; $day >= 0; $day--) {
                $logDate = now()->subDays($day)->format('Y-m-d');

                // Skip tracking on standard weekend intervals
                if (Carbon::parse($logDate)->isWeekend()) {
                    continue;
                }

                // 10% chance a given employee calls out absent completely
                if (fake()->boolean(10)) continue;

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
                $gracePeriodThreshold = $shiftStartTime->copy()->addMinutes($shift->grace_period_minutes);

                if ($checkInTime->gt($gracePeriodThreshold)) {
                    // Lateness is calculated from the true shift start marker, not the grace threshold
                    $lateMinutes = $checkInTime->diffInMinutes($shiftStartTime);
                }

                // Generate check-out time exactly 8 to 9 hours post-arrival
                $checkOutTime = $checkInTime->copy()->addHours(fake()->numberBetween(8, 9))->addMinutes(fake()->numberBetween(0, 59));

                // Commit the synchronized metric variables safely down into the database rows
                AttendanceLog::create([
                    'employee_id'      => $employee->id,
                    'device_id'        => $devices->random()->id,
                    'log_date'         => $logDate,
                    'check_in'         => $checkInTime->format('Y-m-d H:i:s'),  // Explicit database formatting string
                    'check_out'        => $checkOutTime->format('Y-m-d H:i:s'), // Explicit database formatting string
                    'late_minutes'     => $lateMinutes,
                    'overtime_minutes' => 0,
                    'status'           => $lateMinutes > 0 ? 'late' : 'present',
                ]);
            }
        }
    }
}
