<?php

namespace App\Listeners;

use App\Models\Donation;
use App\Models\Campaign;
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
        // $email = $event->donator->email;
        // $campaign = Campaign::find($event->donator->id_campaign);
        // if($campaign !== null){
        //     if($campaign->banks !== []){
        //         $banks = Bank::whereIn('id', $campaign->banks)->get();
        //         $data = [
        //             'campaign' => $campaign->campaign_name,
        //             'name' => $event->donator->name,
        //             'amount' => number_format($event->donator->amount,0,',','.'),
        //             'banks' => $banks
        //         ];
        //         Mail::to($email)->send(new DonationMail($data));
        //         if(Mail::failures()){
        //             foreach(Mail::failures() as $email_address) {
        //                 echo " - $email_address <br />";
        //             }
        //         }
        //     } else {
        //         echo "Tidak Memiliki Akun Bank";
        //     }
        // } else {
        //     echo "Campaign Tidak Ditemukan";
        // }
        echo "Mail Under Service";
    }
}