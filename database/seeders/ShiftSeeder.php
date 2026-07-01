<?php

namespace Database\Seeders;

use App\Models\Shift;
use Illuminate\Database\Seeder;

class ShiftSeeder extends Seeder
{
    public function run(): void
    {
        $shifts = [
            ['shift_name' => 'Morning Shift', 'start_time' => '08:00:00', 'end_time' => '16:00:00', 'grace_period_minutes' => 15],
            ['shift_name' => 'Evening Shift', 'start_time' => '16:00:00', 'end_time' => '00:00:00', 'grace_period_minutes' => 15],
            ['shift_name' => 'Night Shift', 'start_time' => '00:00:00', 'end_time' => '08:00:00', 'grace_period_minutes' => 15],
        ];

        foreach ($shifts as $shift) {
            Shift::create($shift);
        }
    }
}