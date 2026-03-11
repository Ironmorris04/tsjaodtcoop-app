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
        Schema::table('meetings', function (Blueprint $table) {
            $table->dropColumn("type");
            $table->enum('type', ['general_assembly', 'board_of_directors', 'special', 'emergency'])->default('general_assembly');
        });
    }
};
