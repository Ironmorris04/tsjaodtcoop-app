<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('operator_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('operator_id')->constrained()->onDelete('cascade');
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->date('birthdate');
            $table->string('birthplace');
            $table->string('religion')->nullable();
            $table->string('citizenship');
            $table->string('occupation')->nullable();
            $table->enum('sex', ['male', 'female']);
            $table->enum('civil_status', ['single', 'married', 'widowed', 'separated']);
            $table->enum('indigenous_people', ['yes', 'no'])->default('no');
            $table->enum('pwd', ['yes', 'no'])->default('no');
            $table->enum('senior_citizen', ['yes', 'no'])->default('no');
            $table->enum('fourps_beneficiary', ['yes', 'no'])->default('no');
            $table->string('id_type');
            $table->string('id_number');
            $table->string('valid_id_path')->nullable();
            $table->string('profile_photo_path')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('operator_details');
    }
};