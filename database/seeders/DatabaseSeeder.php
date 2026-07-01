<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RolePermissionSeeder::class,
            SystemSettingSeeder::class,
            ShiftSeeder::class,
            AttendanceDeviceSeeder::class,
            HolidaySeeder::class,
            UserSeeder::class,
            EmployeeSeeder::class,
            DocumentSeeder::class,
            AttendanceLogSeeder::class,
            HrTransactionSeeder::class,
            PayrollOrderSeeder::class,
            AuditLogSeeder::class,
        ]);
    }
}