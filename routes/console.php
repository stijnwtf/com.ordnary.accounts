<?php

use App\Mail\WelcomeEmail;
use App\Models\User;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Mail;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('email:send-welcome {user?} {--all}', function ($user = null) {
    if ($this->option('all')) {
        $users = User::all();
        $this->info('Sending welcome emails to all users...');
        
        $bar = $this->output->createProgressBar(count($users));
        $bar->start();
        
        foreach ($users as $user) {
            Mail::to($user->email)->send(new WelcomeEmail($user));
            $bar->advance();
        }
        
        $bar->finish();
        $this->newLine();
        $this->info('Welcome emails sent to all users!');
        
        return;
    }
    
    if ($user) {
        $userModel = User::find($user);
        
        if (!$userModel) {
            $this->error("User with ID {$user} not found.");
            return;
        }
        
        Mail::to($userModel->email)->send(new WelcomeEmail($userModel));
        $this->info("Welcome email sent to {$userModel->email}!");
        
        return;
    }
    
    $this->error('Please specify either a user ID or use the --all option.');
})->purpose('Send welcome emails to users');
