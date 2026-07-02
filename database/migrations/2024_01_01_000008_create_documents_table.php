<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            // علاقة مع الموظف، ويحذف السجل تلقائياً في حال حذف ملف الموظف
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();
            $table->enum('document_type', ['identity', 'passport', 'contract', 'health_certificate']);
            $table->string('document_number', 100);
            $table->date('expiry_date'); // تاريخ انتهاء الوثيقة لمراقبة الصلاحية
            $table->string('file_path', 255); // مسار تخزين الملف السحابي أو المحلي
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
