<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class FacilityPhotosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        DB::table('facility_photos')->insert([
            [
                'facility_id' => 1,
                'url' => '/images/facilities/conference_A.png',
                'caption' => 'Conference Room A',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'facility_id' => 2,
                'url' => '/images/facilities/training_A.png',
                'caption' => 'Conference Room B',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'facility_id' => 3,
                'url' => '/images/facilities/training_B.png',
                'caption' => 'BGC Training',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'facility_id' => 4,
                'url' => '/images/facilities/multipurpose_A.png',
                'caption' => 'Conference Room C',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}
