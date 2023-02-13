<?php

namespace App\Listeners;

use App\Events\AcceptedIntern;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

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
     * @param  \App\Events\AcceptedIntern  $event
     * @return void
     */
    public function handle(AcceptedIntern $event)
    {
        //
    }
}
