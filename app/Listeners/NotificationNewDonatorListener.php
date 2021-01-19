<?php

namespace App\Listeners;

use App\Models\Donation;
use App\Events\NewDonatorHasRegisteredEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Mail\DonationMail;
use Illuminate\Support\Facades\Mail;

class NotificationNewDonatorListener implements ShouldQueue
{
    /**
     * Handle the event.
     *
     * @param  NewDonatorHasRegisteredEvent  $event
     * @return void
     */
    public function handle(NewDonatorHasRegisteredEvent $event)
    {
        $email = $event->donator->email;
        Mail::to($email)->send(new DonationMail());
    }
}
