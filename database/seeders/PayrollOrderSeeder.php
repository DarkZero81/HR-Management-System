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
            $baseSalary = (float) ($employee->base_salary ?? 0);

            if ($baseSalary <= 0) {
                $baseSalary = 1000;
            }

            $allowances = fake()->randomFloat(2, 100, 200);
            $deductions = fake()->randomFloat(2, 0, 100);
            $netSalary = $baseSalary + $allowances - $deductions;

            PayrollOrder::firstOrCreate(
                ['employee_id' => $employee->id, 'salary_month' => now()->format('Y-m')],
                [
                    'employee_id' => $employee->id,
                    'salary_month' => now()->format('Y-m'),
                    'allowances' => $allowances,
                    'deductions' => $deductions,
                    'net_salary' => max($netSalary, 0),
                    'payment_status' => fake()->randomElement(['draft', 'approved', 'paid']),
                ]
            );
        }
    }
}
