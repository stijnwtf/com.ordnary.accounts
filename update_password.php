<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$user = App\Models\User::where('email', 'stijn@ordnary.com')->first();

if ($user) {
    $user->password = Hash::make('1234');
    $user->save();
    echo "Password updated successfully for {$user->email}\n";
} else {
    echo "User not found\n";
}
