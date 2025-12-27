<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'stijn@ordnary.com'],
            [
                'ordnary_id' => (string) Str::uuid(),
                'username' => 'stijn',
                'first_name' => 'Stijn',
                'last_name' => 'Ordnary',
                'email' => 'stijn@ordnary.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password'), // Change this password!
                'is_admin' => true,
            ]
        );
    }
}
