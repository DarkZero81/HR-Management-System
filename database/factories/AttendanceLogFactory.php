<?php

namespace Database\Factories;

use App\Models\AttendanceDevice;
use App\Models\AttendanceLog;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class AttendanceLogFactory extends Factory
{
    protected $model = AttendanceLog::class;

    public function definition(): array
    {
        $employee = Employee::inRandomOrder()->first();
        $shift = $employee?->shift;
        
        if ($shift) {
            $shiftStart = Carbon::parse($shift->start_time);
            $checkInTime = Carbon::parse(fake()->date())->addHours($shiftStart->hour)->addMinutes(fake()->numberBetween(0, 120));
            $checkOutTime = (clone $checkInTime)->addHours(fake()->numberBetween(8, 9));
            
            $lateMinutes = 0;
            $shiftStartTime = Carbon::parse(fake()->date() . ' ' . $shift->start_time);
            if ($checkInTime > $shiftStartTime->addMinutes($shift->grace_period_minutes)) {
                $lateMinutes = $checkInTime->diffInMinutes($shiftStartTime);
            }
        } else {
            $checkInTime = now();
            $checkOutTime = now()->addHours(8);
            $lateMinutes = 0;
        }

        return [
            'employee_id' => $employee?->id ?? 1,
            'device_id' => AttendanceDevice::inRandomOrder()->first()?->id,
            'log_date' => fake()->date(),
            'check_in' => $checkInTime,
            'check_out' => $checkOutTime,
            'late_minutes' => $lateMinutes,
            'overtime_minutes' => fake()->numberBetween(0, 60),
            'status' => fake()->randomElement(['present', 'absent', 'late', 'holiday']),
        ];
    }
}