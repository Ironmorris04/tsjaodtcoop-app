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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('type')->default('string'); // string, number, boolean, json
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Insert default penalty settings
        DB::table('settings')->insert([
            [
                'key' => 'penalty_amount_per_absence',
                'value' => '100.00',
                'type' => 'number',
                'description' => 'Penalty amount in PHP for each meeting absence',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'penalty_due_days',
                'value' => '30',
                'type' => 'number',
                'description' => 'Number of days to pay penalty after meeting',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
