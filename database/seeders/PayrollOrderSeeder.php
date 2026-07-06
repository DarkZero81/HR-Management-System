<?php

namespace Database\Seeders;

use App\Models\PayrollOrder;
use App\Models\Employee;
use Illuminate\Database\Seeder;

class PayrollOrderSeeder extends Seeder
{
    public function run(): void
    {
        $employees = Employee::all();

        foreach ($employees as $employee) {
            $allowances = fake()->randomFloat(2, 100, 500);
            $deductions = fake()->randomFloat(2, 0, 200);
            $netSalary = $employee->base_salary + $allowances - $deductions;

            PayrollOrder::firstOrCreate(
                ['employee_id' => $employee->id, 'salary_month' => now()->subMonth()->format('Y-m')],
                [
                    'employee_id' => $employee->id,
                    'salary_month' => now()->subMonth()->format('Y-m'),
                    'allowances' => $allowances,
                    'deductions' => $deductions,
                    'net_salary' => max($netSalary, 0),
                    'payment_status' => fake()->randomElement(['draft', 'approved', 'paid']),
                ]
            );
        }
    }
}