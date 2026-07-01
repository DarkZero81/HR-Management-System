<?php

namespace Database\Factories;

use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AuditLogFactory extends Factory
{
    protected $model = AuditLog::class;

    public function definition(): array
    {
        return [
            'user_id' => User::inRandomOrder()->first()?->id,
            'action_type' => fake()->randomElement(['INSERT', 'UPDATE', 'DELETE']),
            'table_name' => fake()->randomElement(['employees', 'documents', 'attendance_logs', 'hr_transactions']),
            'record_id' => fake()->numberBetween(1, 100),
            'old_values' => fake()->sentence(),
            'new_values' => fake()->sentence(),
            'performed_at' => now()->subDays(fake()->numberBetween(1, 30)),
        ];
    }
}