<?php

namespace Database\Factories;

use App\Models\AttendanceLog;
use App\Models\Employee;
use App\Models\AttendanceDevice;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

class AttendanceLogFactory extends Factory
{
    protected $model = AttendanceLog::class;

    public function definition(): array
    {
        $logDate = $this->faker->dateTimeBetween('-1 month', 'now')->format('Y-m-d');

        // توليد ساعة الحضور بشكل عشوائي بين الـ 7:45 والـ 9:15 صباحاً
        $checkInHour = $this->faker->numberBetween(7, 9);
        $checkInMinute = $checkInHour === 7 ? $this->faker->numberBetween(45, 59) : $this->faker->numberBetween(0, 59);
        $checkIn = Carbon::parse("$logDate $checkInHour:$checkInMinute:00");

        // حساب دقائق التأخير الافتراضية بناءً على موعد الحضور الرسمي الافتراضي (08:00 صباحاً)
        $officialStart = Carbon::parse("$logDate 08:00:00");
        $lateMinutes = 0;
        $status = 'present';

        if ($checkIn->gt($officialStart)) {
            $lateMinutes = $checkIn->diffInMinutes($officialStart);
            $status = 'late';
        }

        // توليد ساعة الانصراف بعد 8 ساعات عمل على الأقل من وقت الحضور الرسمي
        $checkOut = (clone $checkIn)->addHours($this->faker->numberBetween(8, 10));
        return [
            // الطريقة الأنظف لحل تضارب الـ Intelephense نهائياً في محرر الأكواد
            'employee_id' => Employee::query()->inRandomOrder()->first()?->id ?? \App\Models\Employee::newFactory()->create()->id,
            'device_id' => AttendanceDevice::query()->inRandomOrder()->first()?->id ?? \App\Models\AttendanceDevice::newFactory()->create()->id,
            'log_date' => $logDate,
            'check_in' => $checkIn->format('Y-m-d H:i:s'),
            'check_out' => $checkOut->format('Y-m-d H:i:s'),
            'late_minutes' => $lateMinutes,
            'overtime_minutes' => $lateMinutes === 0 ? $this->faker->randomElement([0, 60, 120]) : 0,
            'status' => $status,
        ];


    }
}
