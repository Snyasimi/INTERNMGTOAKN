<?php

namespace App\Listeners;

use App\Events\InterviewDeclined;
use App\Mail\InterviewDeclinedEmail;
use App\Models\Applicants;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\ModelNotFoundException;
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
        try
        {
            Applicants::where('id',$event->ApplicantId)->update(['ApplicationStatus' => 'Declined']);
            Mail::to($event->ApplicantEmail)->send(new InterviewDeclinedEmail());
        }
        catch(ModelNotFoundException)
        {
            return response()->json(['message' => 'Applicant not found'],404);
        }
    }
}
