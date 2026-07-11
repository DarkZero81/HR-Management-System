<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('role_id')->nullable()->change();
        });

        $adminRole = \App\Models\RolePermission::where('role_name', 'admin')->first();
        if ($adminRole) {
            \App\Models\User::whereHas('role', function ($q) {
                $q->whereIn('role_name', ['hr', 'investor']);
            })->update(['role_id' => $adminRole->id]);
        }

        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('role_id')->nullable(false)->change();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('role_id')->nullable()->change();
        });
    }
};
