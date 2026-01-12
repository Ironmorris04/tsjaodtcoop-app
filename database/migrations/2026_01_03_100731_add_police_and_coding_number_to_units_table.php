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
        Schema::table('units', function (Blueprint $table) {
            $table->string('police_number')->nullable()->after('chassis_number');
            $table->string('coding_number')->nullable()->after('police_number');
        });
    }

    public function down()
    {
        Schema::table('units', function (Blueprint $table) {
            $table->dropColumn(['police_number', 'coding_number']);
        });
    }

};
