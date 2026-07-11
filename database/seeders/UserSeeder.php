<?php

namespace Database\Seeders;

use App\Models\RolePermission;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = RolePermission::where('role_name', 'admin')->first();

        User::firstOrCreate(
            ['email' => 'admin@hr.com'],
            [
                'email' => 'admin@hr.com',
                'password' => Hash::make('password'),
                'role_id' => $adminRole->id,
                'is_active' => true,
            ]
        );

        $employeeRole = RolePermission::where('role_name', 'employee')->first();

        // Only create users that don't have employee records yet
        User::factory()->count(20)->create([
            'role_id' => $employeeRole->id,
            'is_active' => true,
        ]);
    }
}
