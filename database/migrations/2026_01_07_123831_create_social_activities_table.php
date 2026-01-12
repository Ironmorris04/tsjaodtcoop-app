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
        Schema::create('social_activities', function (Blueprint $table) {
            $table->id();
            $table->enum('activity_type', ['cooperative', 'community'])->comment('Type of activity: cooperative or community');
            $table->string('activity_name');
            $table->date('date_conducted');
            $table->integer('participants_count')->default(0);
            $table->decimal('amount_utilized', 10, 2)->default(0);
            $table->string('fund_source')->comment('CETF, Optional Fund, Outright Expense, CDF');
            $table->json('photos')->nullable()->comment('Array of photo paths');
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            $table->softDeletes();

            // Indexes for better query performance
            $table->index('activity_type');
            $table->index('date_conducted');
            $table->index(['activity_type', 'date_conducted']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('social_activities');
    }
};