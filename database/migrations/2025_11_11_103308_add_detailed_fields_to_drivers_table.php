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
            // Driver Photo and Biodata
            $table->string('photo')->nullable()->after('address');
            $table->string('biodata_photo')->nullable()->after('photo');

            // Personal Information
            $table->date('birthdate')->nullable()->after('biodata_photo');
            $table->enum('sex', ['Male', 'Female'])->nullable()->after('birthdate');

            // License Details
            $table->string('license_photo')->nullable()->after('license_expiry');
            $table->string('license_restrictions')->nullable()->after('license_photo');
            $table->string('dl_codes')->nullable()->after('license_restrictions');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('drivers', function (Blueprint $table) {
            $table->dropColumn([
                'photo',
                'biodata_photo',
                'birthdate',
                'sex',
                'license_photo',
                'license_restrictions',
                'dl_codes'
            ]);
        });
    }
};
