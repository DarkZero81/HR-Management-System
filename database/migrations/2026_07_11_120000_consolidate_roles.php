<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('role_id')->nullable()->change();
        });

        $adminRole = DB::table('roles_permissions')->where('role_name', 'admin')->first();
        if ($adminRole) {
            DB::table('users')
                ->whereIn('users.role_id', function ($query) {
                    $query->select('id')->from('roles_permissions')->whereIn('role_name', ['hr', 'investor']);
                })
                ->update(['role_id' => $adminRole->id]);
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
