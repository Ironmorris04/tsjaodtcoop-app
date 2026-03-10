<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // MySQL doesn't support directly modifying ENUM values, so we need to use raw SQL
        DB::statement("ALTER TABLE meetings MODIFY COLUMN type ENUM('general_assembly', 'board_of_directors', 'special', 'emergency') DEFAULT 'general_assembly'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to original ENUM values
        DB::statement("ALTER TABLE meetings MODIFY COLUMN type ENUM('general', 'board', 'special', 'emergency') DEFAULT 'general'");
    }
};
