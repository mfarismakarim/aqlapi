<?php

namespace App\Listeners;

use App\Models\Donation;
use App\Events\NewDonatorHasRegisteredEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

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
        sleep(5);
        $model = new Donation;
        $model->id_campaign = 9999;
        $model->name = 'tes';
        $model->email = 'tes@tes.com';
        $model->phone_number = '0812931212';
        $model->amount = 750000;
        $model->save();

        echo $model;
        // $chatApiToken = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJleHAiOjE2MTMyNzU4NjcsInVzZXIiOiI2Mjg1NzgyODEwNDAxIn0.nwF5eKTINc27Hy1yNZR8epg70sAQOD7Z_XRF9dOexV4"; // Get it from https://www.phphive.info/255/get-whatsapp-password/
        // $num = $event->donator->phone_number;
        // if(substr($num, 0, 1) === '0') {
        //     $number = '62'.substr($num, 1);
        // } else if(substr($num, 0, 1) === '+') {
        //     $number = substr($num, 1);
        // } else {
        //     $number = $num;
        // }
        
        // // echo $number;
        // $message = "Hello From Test AQL Lumen..."; // Message
        
        // $curl = curl_init();
        // curl_setopt_array($curl, array(
        //     CURLOPT_URL => 'http://chat-api.phphive.info/message/send/text',
        //     CURLOPT_RETURNTRANSFER => true,
        //     CURLOPT_ENCODING => '',
        //     CURLOPT_MAXREDIRS => 10,
        //     CURLOPT_TIMEOUT => 0,
        //     CURLOPT_FOLLOWLOCATION => true,
        //     CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        //     CURLOPT_CUSTOMREQUEST => 'POST',
        //     CURLOPT_POSTFIELDS =>json_encode(array("jid"=> $number."@s.whatsapp.net", "message" => $message)),
        //     CURLOPT_HTTPHEADER => array(
        //         'Authorization: Bearer '.$chatApiToken,
        //         'Content-Type: application/json'
        //     ),
        // ));
        
        // $response = curl_exec($curl);
        // curl_close($curl);
        // echo $response;

    }
}
