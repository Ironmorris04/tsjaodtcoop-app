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
        Schema::create('audit_trails', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('user_name')->nullable(); // Store name in case user is deleted
            $table->string('action'); // e.g., 'created', 'updated', 'deleted', 'approved', 'rejected'
            $table->string('model')->nullable(); // e.g., 'Operator', 'Transaction', 'AnnualReport'
            $table->unsignedBigInteger('model_id')->nullable(); // ID of the affected record
            $table->text('description'); // Human-readable description
            $table->json('changes')->nullable(); // Store before/after values
            $table->string('ip_address')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'created_at']);
            $table->index(['model', 'model_id']);
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_trails');
    }
};
