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
        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            $table->string('type'); // e.g., 'operator_registered', 'document_uploaded', 'vehicle_registered'
            $table->text('description'); // Human-readable description
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade'); // Who performed the action
            $table->string('subject_type')->nullable(); // Polymorphic relation - what was affected
            $table->unsignedBigInteger('subject_id')->nullable(); // Polymorphic relation ID
            $table->json('properties')->nullable(); // Additional metadata
            $table->timestamps();

            // Indexes for better performance
            $table->index(['subject_type', 'subject_id']);
            $table->index('type');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activities');
    }
};
