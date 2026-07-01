<?php

namespace Database\Factories;

use App\Models\Holiday;
use Illuminate\Database\Eloquent\Factories\Factory;

class HolidayFactory extends Factory
{
    protected $model = Holiday::class;

    public function definition(): array
    {
        return [
            'holiday_name' => fake()->word() . ' Holiday',
            'start_date' => fake()->date(),
            'end_date' => fake()->date(),
            'is_recurring' => fake()->boolean(),
        ];
    }
}