<?php

namespace Database\Factories;

use App\Models\Employee;
use App\Models\User;
use App\Models\HrTransaction;
use Illuminate\Database\Eloquent\Factories\Factory;

class HrTransactionFactory extends Factory
{
    protected $model = HrTransaction::class;

    public function definition(): array
    {
        $startDate = now()->subDays(fake()->numberBetween(10, 60));
        $endDate = (clone $startDate)->addDays(fake()->numberBetween(1, 5));

        return [
            'employee_id' => Employee::inRandomOrder()->first()?->id ?? 1,
            'transaction_type' => fake()->randomElement(['leave', 'permission', 'promotion', 'penalty', 'transfer']),
            'start_date_time' => $startDate,
            'end_date_time' => $endDate,
            'description' => fake()->sentence(),
            'financial_impact' => fake()->randomFloat(2, 0, 500),
            'status' => fake()->randomElement(['pending', 'approved', 'rejected']),
            'approved_by' => null,
        ];
    }
}