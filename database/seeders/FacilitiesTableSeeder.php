<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class FacilitiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        DB::table('facilities')->insert([
            [
                'facility_code' => 'F001',
                'name' => 'Conference Room A',
                'room_number' => '301',
                'building_id' => 1, // ENC Tower A
                'floor' => 'ground',
                'capacity' => 12,
                'type' => 'meeting',
                'status' => 'Available',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'facility_code' => 'F002',
                'name' => 'Conference Room B',
                'room_number' => '305',
                'building_id' => 2, // ENC Tower B
                'floor' => '3rd',
                'capacity' => 10,
                'type' => 'training',
                'status' => 'Available',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'facility_code' => 'F003',
                'name' => 'BGC Training',
                'room_number' => '302',
                'building_id' => 1, // ENC Tower A
                'floor' => '2nd',
                'capacity' => 20,
                'type' => 'training',
                'status' => 'Available',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'facility_code' => 'F004',
                'name' => 'Conference Room C',
                'room_number' => '303',
                'building_id' => 1, // ENC Tower A
                'floor' => '3rd',
                'capacity' => 12,
                'type' => 'multipurpose',
                'status' => 'Available',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}
