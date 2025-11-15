<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class EquipmentTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        $items = [
            'Projector',
            'TV Monitor',
            'Whiteboard',
            'Microphone',
            'Speaker System',
            'Refreshments',
        ];

        foreach ($items as $item) {
            DB::table('equipment')->insert([
                'name' => $item,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }
}
