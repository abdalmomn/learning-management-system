<?php

namespace App\Listeners;

use App\Events\WelcomeEvent;
use App\Mail\WelcomeMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class sendWelcomeMail implements shouldQueue
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(WelcomeEvent $event): void
    {
        Mail::to('basher11aldaas@gmail.com')->send(new WelcomeMail($event->welcome,$event->data));
    }
}
