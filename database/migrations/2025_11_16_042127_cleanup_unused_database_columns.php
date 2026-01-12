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
        // Remove ip_address column from audit_trails (never populated)
        Schema::table('audit_trails', function (Blueprint $table) {
            $table->dropColumn('ip_address');
        });

        // Remove fine_paid column from meeting_attendances (using penalties table instead)
        Schema::table('meeting_attendances', function (Blueprint $table) {
            $table->dropColumn('fine_paid');
        });

        // Remove duplicate and unused columns from drivers table
        Schema::table('drivers', function (Blueprint $table) {
            $table->dropColumn([
                'date_of_birth',  // Duplicate of birthdate
                'gender',         // Duplicate of sex
                'user_id'         // Added but not used
            ]);
        });

        // Remove unused columns from units table
        Schema::table('units', function (Blueprint $table) {
            $table->dropColumn([
                'user_id',  // Virtual accessor, not stored
                'year'      // Duplicate of year_model
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Restore ip_address column to audit_trails
        Schema::table('audit_trails', function (Blueprint $table) {
            $table->string('ip_address')->nullable()->after('changes');
        });

        // Restore fine_paid column to meeting_attendances
        Schema::table('meeting_attendances', function (Blueprint $table) {
            $table->boolean('fine_paid')->default(false)->after('remarks');
        });

        // Restore columns to drivers table
        Schema::table('drivers', function (Blueprint $table) {
            $table->date('date_of_birth')->nullable()->after('gender');
            $table->enum('gender', ['male', 'female'])->nullable()->after('contact_person');
            $table->string('user_id')->nullable()->after('id');
        });

        // Restore columns to units table
        Schema::table('units', function (Blueprint $table) {
            $table->string('user_id')->nullable()->after('id');
            $table->year('year')->nullable()->after('model');
        });
    }
};
