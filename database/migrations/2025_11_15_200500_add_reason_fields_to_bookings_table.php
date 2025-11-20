<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->string('cancel_reason_code')->nullable()->after('status');
            $table->string('no_show_reason_code')->nullable()->after('cancel_reason_code');
            $table->string('cancel_reason_note')->nullable()->after('no_show_reason_code');
            $table->string('no_show_reason_note')->nullable()->after('cancel_reason_note');
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn([
                'cancel_reason_code',
                'no_show_reason_code',
                'cancel_reason_note',
                'no_show_reason_note',
            ]);
        });
    }
};
