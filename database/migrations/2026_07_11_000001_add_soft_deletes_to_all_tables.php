<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $tables = ['users', 'roles_permissions', 'departments', 'employees', 'shifts', 'holidays', 'attendance_devices', 'documents', 'hr_transactions', 'payroll_orders', 'system_settings', 'audit_logs'];

        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->softDeletes();
            });
        }
    }

    public function down(): void
    {
        $tables = ['users', 'roles_permissions', 'departments', 'employees', 'shifts', 'holidays', 'attendance_devices', 'documents', 'hr_transactions', 'payroll_orders', 'system_settings', 'audit_logs'];

        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->dropSoftDeletes();
            });
        }
    }
};
