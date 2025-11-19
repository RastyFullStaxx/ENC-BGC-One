<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BookingsPreviewSeeder extends Seeder
{
    /**
     * Seed a few bookings for today's landing preview (occupied, limited, maintenance).
     */
    public function run(): void
    {
        $now = Carbon::now('Asia/Manila');
        $today = $now->toDateString();
        $futureDate = $now->copy()->addDays(3)->toDateString();

        // Prefer the demo account if it exists, otherwise fall back to first user
        $userId = DB::table('users')
            ->where('email', 'gemrasty@ministry.gov')
            ->value('id')
            ?? DB::table('users')->value('id')
            ?? 1;

        // Keep facility statuses aligned with the landing preview
        DB::table('facilities')->whereIn('id', [1, 2, 4])->update(['status' => 'Available']);
        DB::table('facilities')->where('id', 3)->update(['status' => 'Under_Maintenance']);

        // Clear existing preview bookings for today to avoid duplicates when re-seeding
        DB::table('bookings')
            ->whereDate('date', $today)
            ->delete();

        $records = [
            [
                'facility_id' => 1,
                'purpose' => 'Leadership sync',
                'start_at' => $now->copy()->subMinutes(30),
                'end_at' => $now->copy()->addMinutes(75),
            ],
            [
                'facility_id' => 2,
                'purpose' => 'Project roadmapping',
                'start_at' => $now->copy()->addMinutes(75),
                'end_at' => $now->copy()->addMinutes(165),
            ],
            [
                'facility_id' => 4,
                'purpose' => 'Workshop block',
                'start_at' => $now->copy()->addHours(3),
                'end_at' => $now->copy()->addHours(5),
            ],
        ];

        foreach ($records as $record) {
            $bookingId = DB::table('bookings')->insertGetId([
                'facility_id' => $record['facility_id'],
                'requester_id' => $userId,
                'date' => $today,
                'start_at' => $record['start_at']->format('H:i:s'),
                'end_at' => $record['end_at']->format('H:i:s'),
                'status' => 'approved',
                'reference_code' => $this->code(),
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            DB::table('booking_details')->insert([
                'booking_id' => $bookingId,
                'purpose' => $record['purpose'],
                'attendees_count' => 8,
                'sfi_support' => false,
                'sfi_count' => 0,
                'additional_notes' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        // Seed a couple of pending bookings on a future date for demo
        DB::table('bookings')
            ->whereDate('date', $futureDate)
            ->delete();

        $pendingRecords = [
            [
                'facility_id' => 1,
                'purpose' => 'Upcoming strategy session',
                'start_at' => $now->copy()->addDays(3)->setTime(9, 0),
                'end_at' => $now->copy()->addDays(3)->setTime(10, 30),
            ],
            [
                'facility_id' => 2,
                'purpose' => 'Planning workshop',
                'start_at' => $now->copy()->addDays(3)->setTime(14, 0),
                'end_at' => $now->copy()->addDays(3)->setTime(16, 0),
            ],
        ];

        foreach ($pendingRecords as $record) {
            $bookingId = DB::table('bookings')->insertGetId([
                'facility_id' => $record['facility_id'],
                'requester_id' => $userId,
                'date' => $futureDate,
                'start_at' => $record['start_at']->format('H:i:s'),
                'end_at' => $record['end_at']->format('H:i:s'),
                'status' => 'pending',
                'reference_code' => $this->code(),
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            DB::table('booking_details')->insert([
                'booking_id' => $bookingId,
                'purpose' => $record['purpose'],
                'attendees_count' => 10,
                'sfi_support' => false,
                'sfi_count' => 0,
                'additional_notes' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }

    private function code(): string
    {
        return 'ENC-' . Str::upper(Str::random(6));
    }
}
