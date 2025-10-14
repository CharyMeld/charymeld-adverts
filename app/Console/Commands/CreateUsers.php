<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreateUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:create-users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create default admin and advertiser users';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Create or update admin user
        User::updateOrCreate(
            ['email' => 'admin@charymeld.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('Admin@123'),
                'phone' => '+234 800 000 0000',
                'role' => 'admin',
                'email_verified_at' => now(),
                'is_active' => true,
            ]
        );

        $this->info('Admin created: admin@charymeld.com / Admin@123');

        // Create or update demo advertiser
        User::updateOrCreate(
            ['email' => 'advertiser@demo.com'],
            [
                'name' => 'Demo Advertiser',
                'password' => Hash::make('Demo@123'),
                'phone' => '+234 800 000 0001',
                'role' => 'advertiser',
                'email_verified_at' => now(),
                'is_active' => true,
            ]
        );

        $this->info('Advertiser created: advertiser@demo.com / Demo@123');
        $this->info('Users created successfully!');
    }
}
