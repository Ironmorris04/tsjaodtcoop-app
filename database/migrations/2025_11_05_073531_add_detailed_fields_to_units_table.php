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
            // Add new detailed fields
            $table->string('body_number')->nullable()->after('plate_no');
            $table->string('engine_number')->nullable()->after('body_number');
            $table->string('chassis_number')->nullable()->after('engine_number');
            $table->string('color')->nullable()->after('chassis_number');
            $table->string('lto_cr_number')->nullable()->after('color');
            $table->date('lto_cr_date_issued')->nullable()->after('lto_cr_number');
            $table->string('lto_or_number')->nullable()->after('lto_cr_date_issued');
            $table->date('lto_or_date_issued')->nullable()->after('lto_or_number');
            $table->string('franchise_case')->nullable()->after('lto_or_date_issued');
            $table->string('mv_file')->nullable()->after('franchise_case');
            $table->string('mbp_no_prev_year')->nullable()->after('mv_file');
            $table->string('mch_no_prev_year')->nullable()->after('mbp_no_prev_year');
            $table->string('year_model')->nullable()->after('mch_no_prev_year');

            // Make old fields nullable since we're replacing them
            $table->string('type')->nullable()->change();
            $table->string('brand')->nullable()->change();
            $table->string('model')->nullable()->change();
            $table->integer('capacity')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('units', function (Blueprint $table) {
            $table->dropColumn([
                'body_number',
                'engine_number',
                'chassis_number',
                'color',
                'lto_cr_number',
                'lto_cr_date_issued',
                'lto_or_number',
                'lto_or_date_issued',
                'franchise_case',
                'mv_file',
                'mbp_no_prev_year',
                'mch_no_prev_year',
                'year_model'
            ]);

            // Restore old fields to not nullable
            $table->string('type')->nullable(false)->change();
            $table->string('brand')->nullable(false)->change();
            $table->string('model')->nullable(false)->change();
            $table->integer('capacity')->nullable(false)->change();
        });
    }
};
