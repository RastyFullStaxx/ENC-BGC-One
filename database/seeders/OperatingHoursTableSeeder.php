<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class OperatingHoursTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        // Default operating hours for all facilities
        $facilities = [1, 2, 3, 4]; // facility IDs from facilities table

        foreach ($facilities as $facility_id) {
            DB::table('operating_hours')->insert([
                'facility_id' => $facility_id,
                'open_time' => '08:00:00',
                'close_time' => '22:00:00',
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }
}
