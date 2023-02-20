<?php

namespace App\Listeners;

use App\Events\AcceptedIntern;
use App\Models\Applicants;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class AddApplicantToUser
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
     * @param  \App\Events\AcceptedIntern  $event
     * @return void
     */
    public function handle(AcceptedIntern $event)
    {
        User::create([
            'Name' => $event->Name,
            'Email' => $event->Email,
            'PhoneNumber'=> $event->PhoneNumber,
            'Role' => $event->Role,
        ]);

        Applicants::where('Email',$event->Email)->update(['ApplicationStatus' => 'Accepted']);
    }
}
