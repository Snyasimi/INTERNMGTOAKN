<?php

namespace App\Listeners;
use App\Mail\SupervisorAdded;
use App\Events\CreatedSupervisor;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SupervisorCreated
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
     * @param  \App\Events\CreatedSupervisor  $event
     * @return void
     */
    public function handle(CreatedSupervisor $event)
    {
        Mail::to($event->email)->send(new SupervisorAdded);
    }
}
