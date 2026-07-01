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
            $table->foreignId('user_id')->unique()->nullable()->references('id')->on('users')->onDelete('set null');
            $table->foreignId('shift_id')->nullable()->references('id')->on('shifts')->onDelete('restrict');
            $table->string('first_name', 50);
            $table->string('last_name', 50);
            $table->string('national_id', 50)->unique();
            $table->string('phone', 20)->nullable();
            $table->decimal('base_salary', 10, 2);
            $table->string('bank_account_iban', 50)->nullable();
            $table->date('join_date');
            $table->date('resign_date')->nullable();
            $table->integer('vacation_balance')->default(21);
            $table->decimal('performance_score', 3, 2)->default(0.00);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};