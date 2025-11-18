<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
    * Run the migrations.
    */
    public function up(): void
    {
        // Add Under_Maintenance to the allowed status values
        DB::statement(
            "ALTER TABLE `facilities` MODIFY `status` ENUM('Available','Occupied','Limited_Availability','Under_Maintenance') NOT NULL DEFAULT 'Available'"
        );
    }

    /**
    * Reverse the migrations.
    */
    public function down(): void
    {
        // Revert to the original enum set
        DB::statement(
            "ALTER TABLE `facilities` MODIFY `status` ENUM('Available','Occupied','Limited_Availability') NOT NULL DEFAULT 'Available'"
        );
    }
};
