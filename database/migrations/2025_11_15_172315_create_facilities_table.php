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
        Schema::create('facilities', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->string('facility_code', 10)->unique(); // F001, F002
            $table->string('name');
            $table->string('room_number');
            $table->foreignId('building_id')->constrained('buildings')->onDelete('cascade');
            $table->enum('floor', ['ground', '2nd', '3rd']);
            $table->integer('capacity');
            $table->enum('type', ['meeting','training','multipurpose']);
            $table->enum('status', ['Available','Occupied', 'Limited_Availability'])->default('Available');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('facilities');
    }
};
