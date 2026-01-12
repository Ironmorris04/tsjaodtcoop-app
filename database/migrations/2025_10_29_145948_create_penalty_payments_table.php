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
        Schema::create('penalty_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('penalty_id')->constrained()->onDelete('cascade');
            $table->foreignId('operator_id')->constrained()->onDelete('cascade');
            $table->foreignId('received_by')->nullable()->constrained('users')->onDelete('set null'); // Treasurer who received payment
            $table->decimal('amount', 10, 2);
            $table->date('payment_date');
            $table->enum('payment_method', ['cash', 'check', 'bank_transfer', 'gcash', 'paymaya'])->default('cash');
            $table->string('reference_number')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penalty_payments');
    }
};
