<?php

namespace App\Listeners;

use App\Events\InterviewDeclined;
use App\Mail\InterviewDeclinedEmail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendInterviewDeclinedEmail
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
     * @param  \App\Events\InterviewDeclined  $event
     * @return void
     */
    public function handle(InterviewDeclined $event)
    {
        Mail::to($event->ApplicantEmail)->send(new InterviewDeclinedEmail());
    }
}
