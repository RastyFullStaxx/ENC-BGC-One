<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Call other seeders here
        $this->call([
            DepartmentsSeeder::class,
            AdminSeeder::class,
            BuildingsTableSeeder::class,
            EquipmentTableSeeder::class,
            FacilitiesTableSeeder::class,
            OperatingHoursTableSeeder::class,
            FacilityPhotosTableSeeder::class,
            FacilityEquipmentTableSeeder::class,
            BookingsPreviewSeeder::class,
        ]);

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@ministry.gov',
            'password' => bcrypt('user123!'),
        ]);
    }
}
