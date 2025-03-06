<?php

use App\Mail\WelcomeEmail;
use App\Models\User;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('email:send-welcome {user?} {--all} {--force}', function ($user = null) {
    if ($this->option('all')) {
        $users = User::all();
        $this->info('Sending welcome emails to all users...');
        
        $bar = $this->output->createProgressBar(count($users));
        $bar->start();
        
        $successCount = 0;
        $failCount = 0;
        $failedEmails = [];
        
        foreach ($users as $user) {
            try {
                // Check if the email looks valid before sending
                if (!filter_var($user->email, FILTER_VALIDATE_EMAIL) && !$this->option('force')) {
                    $this->newLine();
                    $this->warn("Skipping invalid email: {$user->email} (User ID: {$user->id})");
                    $failCount++;
                    $failedEmails[] = [
                        'id' => $user->id,
                        'email' => $user->email,
                        'reason' => 'Invalid email format'
                    ];
                    $bar->advance();
                    continue;
                }
                
                Mail::to($user->email)->send(new WelcomeEmail($user));
                $successCount++;
            } catch (\Exception $e) {
                $this->newLine();
                $this->error("Failed to send email to {$user->email}: " . $e->getMessage());
                Log::error("Failed to send welcome email", [
                    'user_id' => $user->id,
                    'email' => $user->email,
                    'error' => $e->getMessage()
                ]);
                $failCount++;
                $failedEmails[] = [
                    'id' => $user->id,
                    'email' => $user->email,
                    'reason' => $e->getMessage()
                ];
            }
            $bar->advance();
        }
        
        $bar->finish();
        $this->newLine(2);
        $this->info("Welcome emails processed: {$successCount} succeeded, {$failCount} failed.");
        
        if ($failCount > 0 && $this->confirm('Do you want to see the list of failed emails?')) {
            $this->table(
                ['User ID', 'Email', 'Reason'],
                collect($failedEmails)->map(function ($item) {
                    return [
                        $item['id'],
                        $item['email'],
                        \Illuminate\Support\Str::limit($item['reason'], 50)
                    ];
                })->toArray()
            );
        }
        
        return;
    }
    
    if ($user) {
        $userModel = User::find($user);
        
        if (!$userModel) {
            $this->error("User with ID {$user} not found.");
            return;
        }
        
        // Check if the email looks valid
        if (!filter_var($userModel->email, FILTER_VALIDATE_EMAIL) && !$this->option('force')) {
            $this->warn("The email address '{$userModel->email}' appears to be invalid.");
            if (!$this->confirm('Do you want to send the email anyway?')) {
                $this->info('Operation cancelled.');
                return;
            }
        }
        
        try {
            Mail::to($userModel->email)->send(new WelcomeEmail($userModel));
            $this->info("Welcome email sent to {$userModel->email}!");
        } catch (\Exception $e) {
            $this->error("Failed to send email: " . $e->getMessage());
            Log::error("Failed to send welcome email", [
                'user_id' => $userModel->id,
                'email' => $userModel->email,
                'error' => $e->getMessage()
            ]);
        }
        
        return;
    }
    
    $this->error('Please specify either a user ID or use the --all option.');
})->purpose('Send welcome emails to users')
  ->addOption('force', null, null, 'Force sending to potentially invalid email addresses');
