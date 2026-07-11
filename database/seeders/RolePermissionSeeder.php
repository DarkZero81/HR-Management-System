<?php

namespace Database\Seeders;

use App\Models\RolePermission;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            ['role_name' => 'admin', 'description' => 'System administrator with full access'],
            ['role_name' => 'manager', 'description' => 'Department manager with team oversight'],
            ['role_name' => 'employee', 'description' => 'Regular employee with self-service access'],
        ];

        foreach ($roles as $role) {
            RolePermission::firstOrCreate(
                ['role_name' => $role['role_name']],
                $role
            );
        }
    }
}
