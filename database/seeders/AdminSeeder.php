<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Department;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the Administration department ID
        $administrationDept = Department::where('name', 'Administration')->first();
        
        User::updateOrCreate(
            ['email' => 'admin@ministry.gov'], // Admin email
            [
                'name' => 'Administrator',
                'phone' => null,
                'department_id' => $administrationDept?->id,
                'role' => 'admin',
                'password' => Hash::make('Admin123!'), // Set default password
            ]
        );
    }
}
