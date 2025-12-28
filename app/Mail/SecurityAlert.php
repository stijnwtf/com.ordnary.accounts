<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SecurityAlert extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $alertTitle;
    public $alertMessage;
    public $activityTime;
    public $activityLocation;
    public $ipAddress;
    public $deviceInfo;

    /**
     * Create a new message instance.
     *
     * @param  \App\Models\User  $user
     * @param  string  $alertTitle
     * @param  string  $alertMessage
     * @param  string  $activityTime
     * @param  string  $activityLocation
     * @param  string  $ipAddress
     * @param  string  $deviceInfo
     * @return void
     */
    public function __construct(
        User $user,
        string $alertTitle,
        string $alertMessage,
        string $activityTime,
        string $activityLocation,
        string $ipAddress,
        string $deviceInfo
    ) {
        $this->user = $user;
        $this->alertTitle = $alertTitle;
        $this->alertMessage = $alertMessage;
        $this->activityTime = $activityTime;
        $this->activityLocation = $activityLocation;
        $this->ipAddress = $ipAddress;
        $this->deviceInfo = $deviceInfo;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Security Alert - Account Suspended Due to Failed Login Attempts')
                    ->view('emails.security-alert');
    }
}
