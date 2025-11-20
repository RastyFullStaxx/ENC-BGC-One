<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('actor_name');
            $table->string('actor_email')->nullable();
            $table->string('action');
            $table->string('module')->default('General');
            $table->string('target')->nullable();
            $table->string('action_type')->nullable();
            $table->string('risk')->default('low');
            $table->string('status')->default('success');
            $table->string('source')->nullable();
            $table->string('environment')->nullable();
            $table->string('ip')->nullable();
            $table->string('location')->nullable();
            $table->string('device')->nullable();
            $table->string('session_id')->nullable();
            $table->string('correlation_id')->nullable();
            $table->text('notes')->nullable();
            $table->json('before')->nullable();
            $table->json('after')->nullable();
            $table->json('changes')->nullable();
            $table->timestamps();

            $table->index(['module', 'risk', 'status']);
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
