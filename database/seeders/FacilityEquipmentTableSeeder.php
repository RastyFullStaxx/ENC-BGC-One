<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class FacilityEquipmentTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        // Get all facilities and equipments
        $facilities = DB::table('facilities')->pluck('id')->toArray();
        $equipments = DB::table('equipment')->pluck('id')->toArray();

        foreach ($facilities as $facility_id) {
            foreach ($equipments as $equipment_id) {
                DB::table('facility_equipment')->insert([
                    'facility_id' => $facility_id,
                    'equipment_id' => $equipment_id,
                    'quantity' => 5,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }
    }
}
