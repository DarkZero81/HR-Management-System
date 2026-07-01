<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\Shift;
use App\Models\User;
use Illuminate\Database\Seeder;

class EmployeeSeeder extends Seeder
{
    public function run(): void
    {
        $shifts = Shift::all();
        $users = User::where('email', '!=', 'admin@hr.com')->get();

        foreach ($users as $user) {
            Employee::create([
                'user_id' => $user->id,
                'shift_id' => $shifts->random()->id,
                'first_name' => fake()->firstName(),
                'last_name' => fake()->lastName(),
                'national_id' => 'NID' . fake()->unique()->numberBetween(1000000, 9999999),
                'phone' => fake()->phoneNumber(),
                'base_salary' => fake()->randomFloat(2, 3000, 8000),
                'bank_account_iban' => 'IBAN' . fake()->unique()->numberBetween(100000000, 999999999),
                'join_date' => fake()->date(),
                'vacation_balance' => 21,
                'performance_score' => fake()->randomFloat(2, 1, 5),
            ]);
        }
    }
}