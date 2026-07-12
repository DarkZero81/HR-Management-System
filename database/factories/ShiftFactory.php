<?php

namespace Database\Factories;

use App\Models\Shift;
use Illuminate\Database\Eloquent\Factories\Factory;

class ShiftFactory extends Factory
{
    protected $model = Shift::class;

    public function definition(): array
    {
        $shiftNames = [
            ['Morning Shift', '08:00:00', '16:00:00', false],
            ['Evening Shift', '16:00:00', '00:00:00', true],
            ['Night Shift', '00:00:00', '08:00:00', true],
        ];

        $shift = fake()->randomElement($shiftNames);

        return [
            'shift_name' => $shift[0],
            'start_time' => $shift[1],
            'end_time' => $shift[2],
            'grace_period_minutes' => 15,
            'is_overnight' => $shift[3],
        ];
    }
}