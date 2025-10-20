<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TestUserSeeder extends Seeder
{
    public function run(): void
    {
        // Create test user if not exists
        if (!User::where('email', 'testuser@charymeld.com')->exists()) {
            User::create([
                'name' => 'Test User',
                'email' => 'testuser@charymeld.com',
                'password' => Hash::make('password'),
                'role' => 'advertiser',
                'email_verified_at' => now(),
                'is_active' => true,
            ]);

            echo "✅ Test user created: testuser@charymeld.com (password: password)\n";
        } else {
            echo "ℹ️ Test user already exists\n";
        }
    }
}
