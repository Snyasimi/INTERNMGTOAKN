<?php

namespace App\Listeners;

use App\Events\InterviewPassed;
use App\Mail\PassedInterview;
use App\Models\Applicants;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

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
          
        $user = User::create([
            'Name' => $event->Name,
            'Email' => $event->Email,
            'PhoneNumber'=> $event->PhoneNumber,
            'Position' => $event->Position,
            'Role' => $event->Role,
            'Status' => false
            
        ]);
        Mail::to($event->Email)->send(new PassedInterview($user->user_id));
        Applicants::where('Email',$event->Email)->update(['ApplicationStatus' => 'Interview Passed']);
    }
}
