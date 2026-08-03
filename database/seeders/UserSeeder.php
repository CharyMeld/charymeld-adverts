<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Create admin user
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@charymeld.com',
            'password' => Hash::make('Admin@123'),
            'phone' => '+234 800 000 0000',
            'role' => 'admin',
            'email_verified_at' => now(),
            'is_active' => true,
        ]);

        // Create demo advertiser
        User::create([
            'name' => 'Demo Advertiser',
            'email' => 'advertiser@demo.com',
            'password' => Hash::make('Demo@123'),
            'phone' => '+234 800 000 0001',
            'role' => 'advertiser',
            'email_verified_at' => now(),
            'is_active' => true,
        ]);
    }
}
