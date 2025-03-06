<?php

namespace App\Console\Commands;

use App\Mail\WelcomeEmail;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendWelcomeEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:send-welcome {--user=} {--all}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send welcome emails to users';

    /**
     * Execute the console command.
     */
    public function handle()
    {
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
            
            return 0;
        }
        
        $userId = $this->option('user');
        if ($userId) {
            $user = User::find($userId);
            
            if (!$user) {
                $this->error("User with ID {$userId} not found.");
                return 1;
            }
            
            Mail::to($user->email)->send(new WelcomeEmail($user));
            $this->info("Welcome email sent to {$user->email}!");
            
            return 0;
        }
        
        $this->error('Please specify either --user=ID or --all option.');
        return 1;
    }
} 