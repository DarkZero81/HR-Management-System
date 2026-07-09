<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->date('date_of_birth')->nullable()->after('resign_date');
            $table->string('place_of_birth')->nullable()->after('date_of_birth');
            $table->enum('education_level', ['high_school', 'diploma', 'bachelor', 'master', 'phd', 'other'])->nullable()->after('place_of_birth');
            $table->enum('marital_status', ['single', 'married', 'divorced', 'widowed'])->nullable()->after('education_level');
            $table->string('nationality')->default('أردني')->after('marital_status');
            $table->string('address')->nullable()->after('nationality');
            $table->string('emergency_contact_name')->nullable()->after('address');
            $table->string('emergency_contact_phone')->nullable()->after('emergency_contact_name');
            $table->string('job_title')->nullable()->after('emergency_contact_phone');
            $table->date('contract_end_date')->nullable()->after('job_title');
            $table->string('insurance_number')->nullable()->after('contract_end_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn([
                'date_of_birth',
                'place_of_birth',
                'education_level',
                'marital_status',
                'nationality',
                'address',
                'emergency_contact_name',
                'emergency_contact_phone',
                'job_title',
                'contract_end_date',
                'insurance_number',
            ]);
        });
    }
};
