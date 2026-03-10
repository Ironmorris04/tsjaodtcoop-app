<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            DO $$
            BEGIN
                IF NOT EXISTS (SELECT 1 FROM pg_type WHERE typname = 'meeting_type_new') THEN
                    CREATE TYPE meeting_type_new AS ENUM (
                        'general_assembly',
                        'board_of_directors',
                        'special',
                        'emergency'
                    );
                END IF;
            END$$;
        ");

        // Remove old default first
        DB::statement("
            ALTER TABLE meetings
            ALTER COLUMN type DROP DEFAULT
        ");

        // Convert existing data values first
        DB::statement("
            UPDATE meetings
            SET type = 'general_assembly'
            WHERE type = 'general'
        ");

        DB::statement("
            UPDATE meetings
            SET type = 'board_of_directors'
            WHERE type = 'board'
        ");

        // Change column type
        DB::statement("
            ALTER TABLE meetings
            ALTER COLUMN type TYPE meeting_type_new
            USING type::text::meeting_type_new
        ");

        // Set new default
        DB::statement("
            ALTER TABLE meetings
            ALTER COLUMN type SET DEFAULT 'general_assembly'
        ");
    }

    public function down(): void
    {
        DB::statement("
            DO $$
            BEGIN
                IF NOT EXISTS (SELECT 1 FROM pg_type WHERE typname = 'meeting_type_old') THEN
                    CREATE TYPE meeting_type_old AS ENUM (
                        'general',
                        'board',
                        'special',
                        'emergency'
                    );
                END IF;
            END$$;
        ");

        // Remove current default first
        DB::statement("
            ALTER TABLE meetings
            ALTER COLUMN type DROP DEFAULT
        ");

        // Convert data back first
        DB::statement("
            UPDATE meetings
            SET type = 'general'
            WHERE type = 'general_assembly'
        ");

        DB::statement("
            UPDATE meetings
            SET type = 'board'
            WHERE type = 'board_of_directors'
        ");

        // Change column type back
        DB::statement("
            ALTER TABLE meetings
            ALTER COLUMN type TYPE meeting_type_old
            USING type::text::meeting_type_old
        ");

        // Restore old default
        DB::statement("
            ALTER TABLE meetings
            ALTER COLUMN type SET DEFAULT 'general'
        ");
    }
};
