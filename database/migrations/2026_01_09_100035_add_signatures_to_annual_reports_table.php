<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('annual_reports', function (Blueprint $table) {
            $table->string('secretary_signature')->nullable()->after('report_data');
            $table->string('chairperson_signature')->nullable()->after('secretary_signature');
        });
    }

    public function down()
    {
        Schema::table('annual_reports', function (Blueprint $table) {
            $table->dropColumn(['secretary_signature', 'chairperson_signature']);
        });
    }
};
