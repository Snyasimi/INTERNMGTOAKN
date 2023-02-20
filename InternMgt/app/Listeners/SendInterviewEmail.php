<?php

namespace App\Listeners;

use App\Events\AcceptedForInterview;
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
     * @param  \App\Events\AcceptedForInterview  $event
     * @return void
     */
    public function handle(AcceptedForInterview $event)
    {
        Mail::to($event->Email)->send(new InternAccepted($event->Email_body));
        Applicants::where('Email',$event->Email)->update(['ApplicationStatus' => 'Accepted For Interview']);
    }
}
