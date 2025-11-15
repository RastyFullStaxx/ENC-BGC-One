<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BuildingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        DB::table('buildings')->insert([
            [
                'name' => 'ENC Tower A',
                'description' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'ENC Tower B',
                'description' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}
