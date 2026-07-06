<?php

namespace Database\Seeders;

use App\Models\Holiday;
use Illuminate\Database\Seeder;

class HolidaySeeder extends Seeder
{
    public function run(): void
    {
        $holidays = [
            ['holiday_name' => 'New Year', 'start_date' => '2025-01-01', 'end_date' => '2025-01-01', 'is_recurring' => true],
            ['holiday_name' => 'Independence Day', 'start_date' => '2025-07-04', 'end_date' => '2025-07-04', 'is_recurring' => true],
            ['holiday_name' => 'Christmas Day', 'start_date' => '2025-12-25', 'end_date' => '2025-12-25', 'is_recurring' => true],
        ];

        foreach ($holidays as $holiday) {
            Holiday::firstOrCreate(
                ['holiday_name' => $holiday['holiday_name']],
                $holiday
            );
        }
    }
}