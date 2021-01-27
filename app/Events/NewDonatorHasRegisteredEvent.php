<?php

namespace App\Events;
use App\Models\Donation;

class NewDonatorHasRegisteredEvent extends Event
{
    public $donator;
    public $payment;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Donation $donator, $payment)
    {
        $this->donator = $donator;
        $this->payment = $payment;
    }
}
