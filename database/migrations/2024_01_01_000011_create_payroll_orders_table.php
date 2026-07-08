<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payroll_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();
            $table->string('salary_month', 7); // يخزن بصيغة السنة والشهر "YYYY-MM"

            $table->decimal('allowances', 15, 2)->default(0.00); // إجمالي المكافآت والبدلات من جدول الوقوعات
            $table->decimal('deductions', 15, 2)->default(0.00); // إجمالي الخصومات وأقساط السلف والغياب والتأخير
            $table->decimal('net_salary', 15, 2); // الراتب الصافي النهائي المستحق = (الأساسي + البدلات - الخصومات)

            $table->enum('payment_status', ['draft', 'approved', 'paid'])->default('draft');
            $table->timestamp('paid_at')->nullable(); // توقيت تأكيد عملية الصرف البنكي
            $table->timestamps();

            // منع توليد أكثر من أمر صرف راتب لنفس الموظف في نفس الشهر
            $table->unique(['employee_id', 'salary_month']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payroll_orders');
    }
};