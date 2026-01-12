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
        Schema::create('general_info', function (Blueprint $table) {
            $table->id();

            // Cooperative Information
            $table->string('registration_no')->nullable();
            $table->string('cooperative_name')->nullable();

            // Registered Address
            $table->string('reg_region')->nullable();
            $table->string('reg_province')->nullable();
            $table->string('reg_municipality_city')->nullable();
            $table->string('reg_barangay')->nullable();
            $table->string('reg_street')->nullable();
            $table->string('reg_house_lot_blk_no')->nullable();

            // Present Address
            $table->string('present_region')->nullable();
            $table->string('present_province')->nullable();
            $table->string('present_municipality_city')->nullable();
            $table->string('present_barangay')->nullable();
            $table->string('present_street')->nullable();
            $table->string('present_house_lot_blk_no')->nullable();

            // Date Registered
            $table->date('date_registration_prior_ra9520')->nullable();
            $table->date('date_registration_under_ra9520')->nullable();

            // Business Permit
            $table->string('business_permit_no')->nullable();
            $table->date('business_permit_date_issued')->nullable();
            $table->decimal('business_permit_amount_paid', 10, 2)->nullable();

            // Other Information
            $table->string('tax_identification_number')->nullable();
            $table->string('category_of_cooperative')->nullable();
            $table->string('type_of_cooperative')->nullable();
            $table->string('asset_size')->nullable();
            $table->string('common_bond_membership')->nullable();
            $table->date('date_of_general_assembly')->nullable();
            $table->text('area_of_operation')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('general_info');
    }
};
