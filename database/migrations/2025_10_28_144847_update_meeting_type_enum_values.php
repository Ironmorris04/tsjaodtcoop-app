<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            CREATE TYPE meeting_type_new AS ENUM (
                'general_assembly',
                'board_of_directors',
                'special',
                'emergency'
            )
        ");

        DB::statement("
            ALTER TABLE meetings
            ALTER COLUMN type TYPE meeting_type_new
            USING type::text::meeting_type_new
        ");

        DB::statement("
            ALTER TABLE meetings
            ALTER COLUMN type SET DEFAULT 'general_assembly'
        ");
    }

    public function down(): void
    {
        DB::statement("
            CREATE TYPE meeting_type_old AS ENUM (
                'general',
                'board',
                'special',
                'emergency'
            )
        ");

        DB::statement("
            ALTER TABLE meetings
            ALTER COLUMN type TYPE meeting_type_old
            USING type::text::meeting_type_old
        ");

        DB::statement("
            ALTER TABLE meetings
            ALTER COLUMN type SET DEFAULT 'general'
        ");
    }
};
