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

class InterviewPassed
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $Name;
    public $Email;
    public $PhoneNumber;
    public $Position;
    public $Role;

    public function __construct(Applicants $applicant)
    {
        $this->Name = $applicant->Name;
        $this->Email = $applicant->Email;
        $this->PhoneNumber = $applicant->PhoneNumber;
        $this->Position = $applicant->Position;
        $this->Role = "INT";

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
