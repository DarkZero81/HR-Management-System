<?php

namespace Database\Factories;

use App\Models\HrTransaction;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

class HrTransactionFactory extends Factory
{
    protected $model = HrTransaction::class;

    public function definition(): array
    {
        $type = $this->faker->randomElement(['leave', 'permission', 'promotion', 'penalty', 'transfer']);
        $status = $this->faker->randomElement(['pending', 'approved', 'rejected']);
        $startDate = Carbon::parse($this->faker->dateTimeBetween('now', '+1 month'));
        $endDate = (clone $startDate)->addDays($this->faker->numberBetween(1, 5));

        return [
            'employee_id' => Employee::inRandomOrder()->first()?->id ?? Employee::factory(),
            'transaction_type' => $type,
            'start_date_time' => $startDate,
            'end_date_time' => $type === 'permission' ? (clone $startDate)->addHours(2) : $endDate, // الإذن يكون ساعاتي
            'description' => $this->faker->sentence(),
            'financial_impact' => $type === 'penalty' ? $this->faker->randomFloat(2, 10000, 50000) : ($type === 'promotion' ? $this->faker->randomFloat(2, 50000, 200000) : 0.00),
            'status' => $status,
            'approved_by' => $status !== 'pending' ? User::whereHas('role', function($q){ $q->whereIn('role_name', ['admin', 'manager']); })->inRandomOrder()->first()?->id : null,
        ];
    }
}
