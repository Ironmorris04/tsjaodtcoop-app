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
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->morphs('documentable'); // Creates documentable_id and documentable_type
            $table->string('document_type'); // e.g., 'License', 'Registration', 'Permit', etc.
            $table->string('document_name');
            $table->string('document_number')->nullable();
            $table->date('issue_date')->nullable();
            $table->date('expiry_date')->nullable();
            $table->date('due_date')->nullable();
            $table->string('file_path')->nullable();
            $table->text('notes')->nullable();
            $table->enum('status', ['active', 'expired', 'expiring_soon'])->default('active');
            $table->timestamps();

            // Indexes for better query performance
            $table->index('expiry_date');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
