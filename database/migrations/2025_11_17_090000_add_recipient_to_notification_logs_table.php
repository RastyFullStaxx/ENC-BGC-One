<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('notification_logs', function (Blueprint $table) {
            if (! Schema::hasColumn('notification_logs', 'recipient_id')) {
                $table->foreignId('recipient_id')
                    ->nullable()
                    ->after('booking_id')
                    ->constrained('users')
                    ->nullOnDelete();
            }

            if (! Schema::hasColumn('notification_logs', 'recipient_role')) {
                $table->string('recipient_role', 20)
                    ->default('user')
                    ->after('recipient_id');
            }
        });

        // postgre version
        if (Schema::hasColumn('notification_logs', 'recipient_id')) {
            DB::statement('
                UPDATE notification_logs AS nl
                SET 
                    recipient_id = COALESCE(nl.recipient_id, b.requester_id),
                    recipient_role = COALESCE(nl.recipient_role, \'user\')
                FROM bookings AS b
                WHERE nl.booking_id = b.id
                AND nl.recipient_id IS NULL
            ');

        }

        // mysql version
        //  if (Schema::hasColumn('notification_logs', 'recipient_id')) {
        //     DB::statement('
        //         UPDATE notification_logs nl
        //         LEFT JOIN bookings b ON nl.booking_id = b.id
        //         SET nl.recipient_id = COALESCE(nl.recipient_id, b.requester_id),
        //             nl.recipient_role = COALESCE(nl.recipient_role, "user")
        //         WHERE nl.recipient_id IS NULL
        //     ');
        // }
    }

    public function down(): void
    {
        Schema::table('notification_logs', function (Blueprint $table) {
            if (Schema::hasColumn('notification_logs', 'recipient_id')) {
                $table->dropForeign(['recipient_id']);
                $table->dropColumn('recipient_id');
            }

            if (Schema::hasColumn('notification_logs', 'recipient_role')) {
                $table->dropColumn('recipient_role');
            }
        });
    }
};
