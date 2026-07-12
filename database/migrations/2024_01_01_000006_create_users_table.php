<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('email', 191)->unique();
            $table->string('password');
            $table->string('avatar')->nullable();
            $table->foreignId('role_id')->constrained('roles_permissions')->restrictOnDelete();
            $table->tinyInteger('is_active')->default(1); // 1 = نشط، 0 = معطل
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
