<?php

namespace Database\Factories;

use App\Models\Employee;
use App\Models\PayrollOrder;
use Illuminate\Database\Eloquent\Factories\Factory;

class PayrollOrderFactory extends Factory
{
    protected $model = PayrollOrder::class;

    public function definition(): array
    {
        $employee = Employee::inRandomOrder()->first();
        $allowances = fake()->randomFloat(2, 100, 500);
        $deductions = fake()->randomFloat(2, 0, 200);
        $netSalary = ($employee?->base_salary ?? 5000) + $allowances - $deductions;

        return [
            'employee_id' => $employee?->id ?? 1,
            'salary_month' => now()->format('Y-m'),
            'allowances' => $allowances,
            'deductions' => $deductions,
            'net_salary' => max($netSalary, 0),
            'payment_status' => fake()->randomElement(['draft', 'approved', 'paid']),
            'paid_at' => null,
        ];
    }
}