<?php

namespace App\Listeners;

use App\Events\InterviewPassed;
use App\Models\Applicants;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendAcceptanceEmail
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\InterviewPassed  $event
     * @return void
     */
    public function handle(InterviewPassed $event)
    {
          
        User::create([
            'Name' => $event->Name,
            'Email' => $event->Email,
            'PhoneNumber'=> $event->PhoneNumber,
            'Position' => $event->Position,
            'Role' => $event->Role,
            'Status' => false
            
        ]);

        Applicants::where('Email',$event->Email)->update(['ApplicationStatus' => 'Accepted']);
    }
}
