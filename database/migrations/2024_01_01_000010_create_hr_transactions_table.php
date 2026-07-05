<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hr_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();
            $table->enum('transaction_type', ['leave', 'permission', 'promotion', 'penalty', 'transfer']);
            $table->timestamp('start_date_time')->nullable();
            $table->timestamp('end_date_time')->nullable();

            $table->text('description')->nullable(); // الأسباب أو تفاصيل الترقية/العقوبة
            $table->decimal('financial_impact', 10, 2)->default(0.00); // القيمة المالية (قرض، خصم عقوبة، زيادة راتب ترقية)

            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            // معرف المستخدم (المدير أو الـ HR) الذي قام بتبديل حالة الطلب واعتماده
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hr_transactions');
    }
};
