<?php

namespace Database\Seeders;

use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Database\Seeder;

class AuditLogSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();
        $actionTypes = ['create', 'update', 'delete'];
        $tableNames = ['employees', 'documents', 'attendance_logs', 'hr_transactions'];

        foreach ($users as $user) {
            $numLogs = fake()->numberBetween(5, 15);
            for ($i = 0; $i < $numLogs; $i++) {
                AuditLog::create([
                    'user_id' => $user->id,
                    'action_type' => $actionTypes[array_rand($actionTypes)],
                    'table_name' => $tableNames[array_rand($tableNames)],
                    'record_id' => fake()->numberBetween(1, 100),
                    'old_values' => fake()->sentence(),
                    'new_values' => fake()->sentence(),
                    'performed_at' => now()->subDays(fake()->numberBetween(1, 30)),
                ]);
            }
        }
    }
}
