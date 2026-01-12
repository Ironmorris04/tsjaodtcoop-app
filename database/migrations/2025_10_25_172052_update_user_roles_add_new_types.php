<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // For SQLite, we need to recreate the table to change enum
        // First, create a temporary column
        DB::statement('ALTER TABLE users ADD COLUMN role_temp TEXT');

        // Copy data
        DB::statement('UPDATE users SET role_temp = role');

        // Drop old column
        DB::statement('ALTER TABLE users DROP COLUMN role');

        // Add new column with updated enum values
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['admin', 'operator', 'president', 'treasurer', 'auditor'])
                  ->default('operator')
                  ->after('email');
        });

        // Copy data back
        DB::statement('UPDATE users SET role = role_temp');

        // Drop temporary column
        DB::statement('ALTER TABLE users DROP COLUMN role_temp');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reverse: remove new roles
        DB::statement('ALTER TABLE users ADD COLUMN role_temp TEXT');
        DB::statement('UPDATE users SET role_temp = role');
        DB::statement('ALTER TABLE users DROP COLUMN role');

        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['admin', 'operator'])->default('operator')->after('email');
        });

        DB::statement('UPDATE users SET role = role_temp WHERE role_temp IN ("admin", "operator")');
        DB::statement('ALTER TABLE users DROP COLUMN role_temp');
    }
};
