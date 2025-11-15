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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();

            // Foreign keys
            $table->foreignId('facility_id')->constrained('facilities')->onDelete('cascade');
            $table->foreignId('requester_id')->constrained('users')->onDelete('cascade');

            // Booking details
            $table->date('date');
            $table->time('start_at');
            $table->time('end_at');

            // Booking workflow status
            $table->enum('status', ['pending','approved','rejected','cancelled','noshow'])->default('pending');

            // Timestamps
            $table->timestamps();

            // Soft deletes
            $table->softDeletes(); // creates deleted_at TIMESTAMP NULL

            // Indexes for performance
            $table->index(['facility_id','date'], 'idx_facility_date');
            $table->index(['requester_id','status'], 'idx_requester_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
