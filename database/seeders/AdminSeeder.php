<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@ministry.gov'], // Admin email
            [
                'name' => 'Administrator',
                'phone' => null,
                'department' => 'Administration',
                'role' => 'admin',
                'password' => Hash::make('Admin123!'), // Set default password
            ]
        );
    }
}
