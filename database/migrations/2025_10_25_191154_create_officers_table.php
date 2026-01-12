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
        Schema::create('officers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('operator_id')->constrained()->onDelete('cascade');
            $table->string('position'); // president, treasurer, secretary
            $table->date('effective_from');
            $table->date('effective_to');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            // Ensure only one active officer per position at a time
            $table->index(['position', 'is_active', 'effective_from', 'effective_to']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('officers');
    }
};
