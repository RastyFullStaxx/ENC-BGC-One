<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DepartmentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departments = [
            'Administration',
            'Finance',
            'Human Resources',
            'Information Technology',
            'Operations',
            'Legal',
            'Other',
        ];

        $now = Carbon::now();

        foreach ($departments as $dept) {
            DB::table('departments')->insert([
                'name' => $dept,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }
}
