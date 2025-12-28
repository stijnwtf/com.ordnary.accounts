<?php

/**
 * Script to reset password for stijn.jakobs@ordnary.com to 1234
 * 
 * Usage in tinker:
 * php artisan tinker
 * Then paste the commands below (without the <?php tag)
 * 
 * Or run directly:
 * php artisan tinker < reset_password_tinker.php
 */

use App\Models\User;
use Illuminate\Support\Facades\Hash;

$user = User::where('email', 'stijn.jakobs@ordnary.com')->first();

if ($user) {
    $user->password = Hash::make('1234');
    $user->save();
    echo "Password successfully reset for {$user->email}\n";
    echo "New password: 1234\n";
} else {
    echo "User not found with email: stijn.jakobs@ordnary.com\n";
}
