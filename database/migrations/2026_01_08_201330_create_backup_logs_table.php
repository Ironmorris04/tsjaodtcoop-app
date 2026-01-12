<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('backup_logs', function (Blueprint $table) {
            $table->id();
            $table->string('filename'); // Name of the backup file
            $table->enum('status', ['pending', 'success', 'failed'])->default('pending');
            $table->text('notes')->nullable(); // Optional error messages
            $table->foreignId('admin_id')->nullable()->constrained('users')->nullOnDelete(); // Who triggered it, null for automatic
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('backup_logs');
    }
};
