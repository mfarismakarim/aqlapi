<?php

namespace App\Listeners;

use App\Models\Donation;
use App\Models\Campaign;
use App\Events\NewDonatorHasRegisteredEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Mail\DonationMail;
use App\Mail\ThankMail;
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
        $campaign = Campaign::find($event->donator->id_campaign);
        if($campaign !== null){
            if($event->donator->paid !== 1){
                $data = [
                    'campaign' => $campaign->campaign_name,
                    'name' => $event->donator->name,
                    'amount' => number_format($event->donator->amount,0,',','.'),
                    'banks' => $event->payment->account_number,
                    'qrcode' => $event->payment->qrcode_url['qrcode']
                ];
                Mail::to($email)->send(new DonationMail($data));
                if(Mail::failures()){
                    foreach(Mail::failures() as $email_address) {
                        echo " - $email_address <br />";
                    }
                }
            } else {
                $data = [
                    'campaign' => $campaign->campaign_name,
                    'name' => $event->donator->name,
                    'amount' => number_format($event->donator->amount,0,',','.')
                ];
                Mail::to($email)->send(new ThankMail($data));
                if(Mail::failures()){
                    foreach(Mail::failures() as $email_address) {
                        echo " - $email_address <br />";
                    }
                }
            }
        } else {
            echo "Campaign Tidak Ditemukan";
        }
    }
}