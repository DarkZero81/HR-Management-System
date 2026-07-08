<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payroll_orders', function (Blueprint $table) {
            $table->decimal('allowances', 15, 2)->default(0.00)->change();
            $table->decimal('deductions', 15, 2)->default(0.00)->change();
            $table->decimal('net_salary', 15, 2)->change();
        });
    }

    public function down(): void
    {
        Schema::table('payroll_orders', function (Blueprint $table) {
            $table->decimal('allowances', 10, 2)->default(0.00)->change();
            $table->decimal('deductions', 10, 2)->default(0.00)->change();
            $table->decimal('net_salary', 10, 2)->change();
        });
    }
};