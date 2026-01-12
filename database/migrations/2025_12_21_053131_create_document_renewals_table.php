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
        Schema::create('document_renewals', function (Blueprint $table) {
            $table->id();

            // Reference to the operator who submitted the renewal
            $table->foreignId('operator_id')->constrained()->onDelete('cascade');

            // Type of document being renewed
            $table->enum('document_type', [
                'driver_license',
                'business_permit',
                'unit_or',
                'unit_cr',
                'lto_or',
                'lto_cr'
            ]);

            // Reference to the specific record (driver_id or unit_id)
            $table->string('documentable_type'); // Driver or Unit
            $table->unsignedBigInteger('documentable_id');

            // Original values (before renewal)
            $table->date('original_expiry_date')->nullable();
            $table->string('original_document_number')->nullable();

            // New values (pending approval)
            $table->date('new_expiry_date');
            $table->string('new_document_number')->nullable();

            // Supporting documents
            $table->string('document_photo')->nullable(); // Photo of renewed document

            // Status and review
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('rejection_reason')->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('reviewed_at')->nullable();

            $table->timestamps();

            // Indexes
            $table->index(['documentable_type', 'documentable_id']);
            $table->index('status');
            $table->index('operator_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_renewals');
    }
};
