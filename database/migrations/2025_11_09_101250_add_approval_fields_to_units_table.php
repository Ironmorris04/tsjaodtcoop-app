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
            $table->string('unit_id')->nullable()->unique()->after('id');
            $table->string('cr_receipt_photo')->nullable()->after('status');
            $table->enum('approval_status', ['pending', 'approved', 'rejected'])->default('pending')->after('cr_receipt_photo');
            $table->timestamp('approved_at')->nullable()->after('approval_status');
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null')->after('approved_at');
            $table->text('rejection_reason')->nullable()->after('approved_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('units', function (Blueprint $table) {
            $table->dropForeign(['approved_by']);
            $table->dropColumn(['unit_id', 'cr_receipt_photo', 'approval_status', 'approved_at', 'approved_by', 'rejection_reason']);
        });
    }
};
