<?php

namespace Database\Seeders;

use App\Models\HrTransaction;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class HrTransactionSeeder extends Seeder
{
    public function run(): void
    {
        $employees = Employee::all();
        $transactionTypes = ['leave', 'permission', 'promotion', 'penalty', 'transfer'];

        foreach ($employees as $employee) {
            $numTransactions = fake()->numberBetween(1, 3);
            for ($i = 0; $i < $numTransactions; $i++) {
                $startDate = now()->subDays(fake()->numberBetween(10, 60));
                $endDate = (clone $startDate)->addDays(fake()->numberBetween(1, 5));

                HrTransaction::create([
                    'employee_id' => $employee->id,
                    'transaction_type' => $transactionTypes[array_rand($transactionTypes)],
                    'start_date_time' => $startDate,
                    'end_date_time' => $endDate,
                    'description' => fake()->sentence(),
                    'financial_impact' => fake()->randomFloat(2, 0, 500),
                    'status' => fake()->randomElement(['pending', 'approved', 'rejected']),
                ]);
            }
        }
    }
}