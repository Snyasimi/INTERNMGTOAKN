<?php

namespace App\Listeners;

use App\Events\AssignedSupervisor;
use App\Mail\AssignedSupervisorEmail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SupervisorAssigned
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
     * @param  \App\Events\AssignedSupervisor  $event
     * @return void
     */
    public function handle(AssignedSupervisor $event)
    {
        Mail::to($event->attachee_email)->send(new AssignedSupervisorEmail($event->supervisor_email,$event->supervisorName));
    }
}
