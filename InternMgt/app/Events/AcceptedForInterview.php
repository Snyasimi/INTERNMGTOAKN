<?php

namespace App\Events;

use App\Models\Applicants;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AcceptedForInterview
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $Name;
    public $Email ;
    public $PhoneNumber;
    public $Position;
    public $Role;
    public $Email_body;
    
    public function __construct(Applicants $Applicant,$Email_body)
    {
        $this->Name = $Applicant->Name;
        $this->Email = $Applicant->Email;
        $this->PhoneNumber = $Applicant->PhoneNumber;
        $this->Position = $Applicant->Position;
        $this->Email_body = $Email_body;
        
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
