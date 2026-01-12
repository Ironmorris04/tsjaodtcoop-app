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
        Schema::create('meeting_attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('meeting_id')->constrained('meetings')->onDelete('cascade');
            $table->foreignId('operator_id')->constrained('operators')->onDelete('cascade');
            $table->enum('status', ['present', 'absent', 'excused', 'late'])->default('absent');
            $table->text('remarks')->nullable();
            $table->timestamp('checked_in_at')->nullable();
            $table->timestamps();

            // Unique constraint: one attendance record per operator per meeting
            $table->unique(['meeting_id', 'operator_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meeting_attendances');
    }
};
