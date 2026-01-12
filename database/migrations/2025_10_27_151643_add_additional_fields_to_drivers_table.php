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
        Schema::table('drivers', function (Blueprint $table) {
            // Rename license_no to license_number for consistency
            $table->renameColumn('license_no', 'license_number');

            // Add additional fields
            $table->date('date_of_birth')->nullable()->after('last_name');
            $table->string('email')->nullable()->after('phone');
            $table->string('license_type')->nullable()->after('license_number');
            $table->date('hire_date')->nullable()->after('address');
            $table->string('emergency_contact')->nullable()->after('hire_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('drivers', function (Blueprint $table) {
            $table->renameColumn('license_number', 'license_no');
            $table->dropColumn([
                'date_of_birth',
                'email',
                'license_type',
                'hire_date',
                'emergency_contact'
            ]);
        });
    }
};
