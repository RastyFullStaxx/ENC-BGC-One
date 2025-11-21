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
        Schema::create('policies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('domain_key')->default('bookings'); // e.g., bookings, sfi, shuttle
            $table->string('status')->default('draft'); // draft, active, archived
            $table->boolean('active')->default(false);
            $table->string('owner')->nullable();
            $table->string('reminder')->nullable();
            $table->string('updated_by')->nullable();
            $table->text('desc')->nullable();
            $table->text('impact')->nullable();
            $table->json('tags')->nullable();
            $table->boolean('expiring')->default(false);
            $table->boolean('needs_review')->default(false);
            $table->timestamp('last_reviewed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('policies');
    }
};
