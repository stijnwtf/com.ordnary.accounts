<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Maak een admin gebruiker aan als deze nog niet bestaat
        if (!User::where('email', 'admin@numblio.com')->exists()) {
            User::create([
                'username' => 'admin',
                'email' => 'admin@numblio.com',
                'first_name' => 'Admin',
                'last_name' => 'User',
                'password' => Hash::make('password'),
                'is_admin' => true,
                'lang' => 'nl',
                'email_verified_at' => now(),
            ]);
            
            $this->command->info('Admin gebruiker aangemaakt: admin@numblio.com (wachtwoord: password)');
        } else {
            $this->command->info('Admin gebruiker bestaat al');
        }
    }
} 