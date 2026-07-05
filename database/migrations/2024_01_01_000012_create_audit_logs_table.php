<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null'); // من قام بالحركة
            $table->enum('action_type', ['create', 'update', 'delete']); // نوع العملية (موحّد مع القيم التي تُكتب فعلياً من الكنترولرز)
            $table->string('table_name', 50); // اسم الجدول المتأثر
            $table->unsignedBigInteger('record_id'); // رقم السجل المتأثر
            $table->text('old_values')->nullable(); // البيانات القديمة قبل التعديل (تخزن كـ JSON)
            $table->text('new_values')->nullable(); // البيانات الجديدة بعد التعديل (تخزن كـ JSON)
            $table->timestamp('performed_at')->useCurrent(); // وقت العملية
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
