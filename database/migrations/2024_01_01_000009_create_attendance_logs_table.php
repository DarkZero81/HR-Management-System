<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendance_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();
            // ربط السجل بالجهاز التقني لمعرفة مصدر البصمة، وتكون القيمة null في حال حذف الجهاز
            $table->foreignId('device_id')->nullable()->constrained('attendance_devices')->onDelete('set null');

            $table->date('log_date'); // تاريخ يوم الحضور
            $table->timestamp('check_in')->nullable(); // وقت الدخول الفعلي
            $table->timestamp('check_out')->nullable(); // وقت الانصراف الفعلي
            $table->integer('late_minutes')->default(0); // دقائق التأخير المحسوبة برمجياً مقارنة بالوردية
            $table->integer('overtime_minutes')->default(0); // دقائق العمل الإضافي المعتمدة
            $table->enum('status', ['present', 'absent', 'late', 'holiday'])->default('absent');
            $table->timestamps();
            $table->enum('action_type', ['INSERT', 'UPDATE', 'DELETE']);

            // إضافة مفتاح مركب فريد لمنع تكرار سجل الحضور لنفس الموظف في نفس اليوم
            $table->unique(['employee_id', 'log_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendance_logs');
    }
};
