<?php

namespace App\Listeners;

use App\Events\NewProspecting;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotificationProspecting
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
     * @param  NewProspecting  $event
     * @return void
     */
    public function handle(NewProspecting $event)
    {
        //
        return $event;
    }
}
