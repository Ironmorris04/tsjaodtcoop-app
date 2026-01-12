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
        Schema::table('social_activities', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }

    public function down()
    {
        Schema::table('social_activities', function (Blueprint $table) {
            $table->softDeletes();
        });
    }
};
