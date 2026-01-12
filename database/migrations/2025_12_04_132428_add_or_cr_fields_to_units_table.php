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
            // Add LTO OR/CR fields only if they don't exist
            if (!Schema::hasColumn('units', 'lto_or_number')) {
                $table->string('lto_or_number')->nullable()->after('capacity');
            }
            if (!Schema::hasColumn('units', 'lto_cr_number')) {
                $table->string('lto_cr_number')->nullable()->after('lto_or_number');
            }
            if (!Schema::hasColumn('units', 'lto_cr_validity')) {
                $table->date('lto_cr_validity')->nullable()->after('lto_cr_number');
            }
            if (!Schema::hasColumn('units', 'unit_or_number')) {
                $table->string('unit_or_number')->nullable()->after('lto_cr_validity');
            }
            if (!Schema::hasColumn('units', 'unit_cr_number')) {
                $table->string('unit_cr_number')->nullable()->after('unit_or_number');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('units', function (Blueprint $table) {
            $columns = ['lto_or_number', 'lto_cr_number', 'lto_cr_validity', 'unit_or_number', 'unit_cr_number'];

            foreach ($columns as $column) {
                if (Schema::hasColumn('units', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
