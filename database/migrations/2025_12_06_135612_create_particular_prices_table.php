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
        Schema::create('particular_prices', function (Blueprint $table) {
            $table->id();
            $table->string('particular'); // subscription_capital, management_fee, membership_fee, monthly_dues, business_permit
            $table->decimal('amount', 10, 2);
            $table->date('start_date');
            $table->date('end_date');
            $table->text('notes')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();

            // Index for faster queries
            $table->index(['particular', 'start_date', 'end_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('particular_prices');
    }
};
