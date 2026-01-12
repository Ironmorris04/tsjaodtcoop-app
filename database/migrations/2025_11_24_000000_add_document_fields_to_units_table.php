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
            // Business Permit fields
            if (!Schema::hasColumn('units', 'business_permit_no')) {
                $table->string('business_permit_no')->nullable()->after('business_permit_photo');
            }
            if (!Schema::hasColumn('units', 'business_permit_validity')) {
                $table->date('business_permit_validity')->nullable()->after('business_permit_no');
            }

            // Official Receipt fields
            if (!Schema::hasColumn('units', 'or_number')) {
                $table->string('or_number')->nullable()->after('or_photo');
            }
            if (!Schema::hasColumn('units', 'or_date_issued')) {
                $table->date('or_date_issued')->nullable()->after('or_number');
            }

            // Certificate of Registration fields
            if (!Schema::hasColumn('units', 'cr_number')) {
                $table->string('cr_number')->nullable()->after('cr_photo');
            }
            if (!Schema::hasColumn('units', 'cr_validity')) {
                $table->date('cr_validity')->nullable()->after('cr_number');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('units', function (Blueprint $table) {
            $columns = ['business_permit_no', 'business_permit_validity', 'or_number', 'or_date_issued', 'cr_number', 'cr_validity'];

            foreach ($columns as $column) {
                if (Schema::hasColumn('units', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
