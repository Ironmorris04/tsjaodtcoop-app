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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('operator_id')->constrained()->onDelete('cascade');
            $table->date('date');
            $table->string('particular'); // subscription_capital, management_fee, membership_fee, monthly_dues, business_permit, misc
            $table->string('month'); // Month name (e.g., 'January', 'February')
            $table->string('or_number')->nullable();
            $table->decimal('amount', 10, 2);
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();

            $table->index(['operator_id', 'date']);
            $table->index('particular');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
