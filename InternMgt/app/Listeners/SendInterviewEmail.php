<?php

namespace App\Listeners;

use App\Events\InterviewStatus;
use App\Mail\InternAccepted;
use App\Models\Applicants;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendInterviewEmail
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
     * @param  \App\Events\InterviewStatus  $event
     * @return void
     */
    public function handle(InterViewStatus $event)
    {
        Mail::to($event->Email)->send(new InternAccepted($event->Email_body));
        //Applicants::where('Email',$event->Email)->update(['ApplicationStatus' => 'Accepted For Interview']);

        if($event->Email_body=='Declined')
        {
            Applicants::where('Email',$event->Email)->update(['ApplicationStatus' => 'Declined']);
        }
        else
        {
            Applicants::where('Email',$event->Email)->update(['ApplicationStatus' => 'Accepted For Interview']);
        }
        
    }
}
