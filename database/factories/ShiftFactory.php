<?php

namespace Database\Factories;

use App\Models\Shift;
use Illuminate\Database\Eloquent\Factories\Factory;

class ShiftFactory extends Factory
{
    protected $model = Shift::class;

    public function definition(): array
    {
        return [
            'shift_name' => fake()->randomElement(['Morning Shift', 'Evening Shift', 'Night Shift']),
            'start_time' => fake()->randomElement(['08:00:00', '16:00:00', '00:00:00']),
            'end_time' => fake()->randomElement(['16:00:00', '00:00:00', '08:00:00']),
            'grace_period_minutes' => 15,
        ];
    }
}