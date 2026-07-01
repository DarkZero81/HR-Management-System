<?php

namespace Database\Factories;

use App\Models\Employee;
use App\Models\Shift;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class EmployeeFactory extends Factory
{
    protected $model = Employee::class;

    public function definition(): array
    {
        return [
            'user_id' => User::inRandomOrder()->first()?->id,
            'shift_id' => Shift::inRandomOrder()->first()?->id,
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'national_id' => 'NID' . fake()->unique()->numberBetween(1000000, 9999999),
            'phone' => fake()->phoneNumber(),
            'base_salary' => fake()->randomFloat(2, 3000, 8000),
            'bank_account_iban' => 'IBAN' . fake()->unique()->numberBetween(100000000, 999999999),
            'join_date' => fake()->date(),
            'vacation_balance' => 21,
            'performance_score' => fake()->randomFloat(2, 1, 5),
        ];
    }
}