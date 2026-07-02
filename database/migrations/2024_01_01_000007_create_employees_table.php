<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            // علاقة One-to-One مع حساب المستخدم، ونستخدم set null عند حذف الحساب لتبقى بيانات الموظف التاريخية محفوظة
            $table->foreignId('user_id')->nullable()->unique()->constrained('users')->onDelete('set null');
            // علاقة مع الوردية، ونستخدم restrict لمنع حذف وردية إذا كان هناك موظفون مسجلون عليها
            $table->foreignId('shift_id')->nullable()->constrained('shifts')->onDelete('restrict');

            $table->string('first_name', 50);
            $table->string('last_name', 50);
            $table->string('national_id', 50)->unique(); // الرقم الوطني للذاتية
            $table->string('phone', 20)->nullable();
            $table->decimal('base_salary', 10, 2); // الراتب الأساسي التعاقدي الثابت
            $table->string('bank_account_iban', 50)->nullable();
            $table->date('join_date'); // تاريخ التعيين
            $table->date('resign_date')->nullable(); // تاريخ الاستقالة إن وجد
            $table->integer('vacation_balance')->default(21); // رصيد الإجازات السنوي المتاح الخاضع للقانون
            $table->decimal('performance_score', 3, 2)->default(0.00); // تقييم الأداء العام للموظف
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
