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
            $table->foreignId('department_id')->nullable()->constrained('departments')->onDelete('set null');
            $table->string('avatar')->nullable();
            $table->string('first_name', 50);
            $table->string('last_name', 50);
            $table->string('national_id', 50)->unique(); // الرقم الوطني للذاتية
            $table->string('phone', 20)->nullable();
            $table->decimal('base_salary', 15, 2); // الراتب الأساسي التعاقدي الثابت (زيادة من 10 إلى 15 للسماح برواتب عالية)
            $table->string('bank_account_iban', 50)->nullable();
            $table->date('join_date'); // تاريخ التعيين
            $table->date('date_of_birth')->nullable(); // تاريخ الميلاد
            $table->string('place_of_birth', 100)->nullable(); // مكان الميلاد
            $table->enum('education_level', ['high_school', 'diploma', 'bachelor', 'master', 'phd', 'other'])->nullable(); // مستوى التعليم
            $table->enum('marital_status', ['single', 'married', 'divorced', 'widowed'])->nullable(); // الحالة الاجتماعية
            $table->string('nationality', 50)->nullable(); // الجنسية
            $table->text('address')->nullable(); // العنوان
            $table->string('emergency_contact_name', 100)->nullable(); // اسم جهة الاتصال في حالات الطوارئ
            $table->string('emergency_contact_phone', 20)->nullable(); // رقم هاتف جهة الاتصال في حالات الطوارئ
            $table->string('job_title', 100)->nullable(); // المسمى الوظيفي
            $table->date('resign_date')->nullable(); // تاريخ الاستقالة إن وجد
            $table->date('contract_end_date')->nullable(); // تاريخ انتهاء العقد إن وجد
            $table->date('last_promotion_date')->nullable(); // تاريخ آخر ترقية إن وجد
            $table->string('insurance_number', 50)->nullable(); // رقم التأمين الصحي
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
