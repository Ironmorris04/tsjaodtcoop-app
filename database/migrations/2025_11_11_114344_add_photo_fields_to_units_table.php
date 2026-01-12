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
        Schema::table('units', function (Blueprint $table) {
            // Add unit_photo first if it doesn't exist
            if (!Schema::hasColumn('units', 'unit_photo')) {
                $table->string('unit_photo')->nullable()->after('year_model');
            }
            // Only add columns if they don't exist
            if (!Schema::hasColumn('units', 'business_permit_photo')) {
                $table->string('business_permit_photo')->nullable()->after('unit_photo');
            }
            if (!Schema::hasColumn('units', 'or_photo')) {
                $table->string('or_photo')->nullable()->after('business_permit_photo');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('units', function (Blueprint $table) {
            if (Schema::hasColumn('units', 'or_photo')) {
                $table->dropColumn('or_photo');
            }
            if (Schema::hasColumn('units', 'business_permit_photo')) {
                $table->dropColumn('business_permit_photo');
            }
            if (Schema::hasColumn('units', 'unit_photo')) {
                $table->dropColumn('unit_photo');
            }
        });
    }
};
