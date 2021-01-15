<?php

namespace App\Http\Controllers;

use App\Models\Tokenwa;
use App\Models\Campaign;
use App\Models\Donation;
use App\Events\NewDonatorHasRegisteredEvent;
use Illuminate\Http\Request;
use Exception;

class MainController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function new(){
        $model = Donation::first();
        event(new NewDonatorHasRegisteredEvent($model));
        return $model;
        // $num = "-628129131231";
        // if(substr($num, 0, 1) === '0') {
        //     $number = '62'.substr($num, 1);
        // } else if(substr($num, 0, 1) === '+') {
        //     $number = substr($num, 1);
        // } else {
        //     $number = $num;
        // }
        // return $number;

        $token = $request->get('token', null);
        if($token !== 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC9sb2NhbGhvc3Q6ODAwMFwvbG9naW4iLCJpYXQiOjE2MTA0MjgzNzgsImV4cCI6MTYxMDQzMTk3OCwibmJmIjoxNjEwNDI4Mzc4LCJqdGkiOiJWSTFEZkVORjZWc3luNHB2Iiwic3ViIjoxMDAxLCJwcnYiOiIyM2JkNWM4OTQ5ZjYwMGFkYjM5ZTcwMWM0MDA4NzJkYjdhNTk3NmY3In0.awgkdKJarKGTxP_0HIldNI7CnG_xtJoxnzhALuFGIPc') return response()->json('Unauthorized');
        $donations = Donation::all();
        foreach($donations as $donation){
            $donation->differ = strtotime("now") - strtotime($donation->created_at);
        }
        $models = Donation::whereIdCampaign(1)->wherePhoneNumber('08391128921')->orderBy('created_at', 'ASC')->get();
        foreach($models as $model){
            $model->differ = strtotime("now") - strtotime($model->created_at);
        }
        return $models;
    }

    public function waBlast(){
        return "Under Construction";
        $chatApiToken = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJleHAiOjE2MTMyNzU4NjcsInVzZXIiOiI2Mjg1NzgyODEwNDAxIn0.nwF5eKTINc27Hy1yNZR8epg70sAQOD7Z_XRF9dOexV4"; // Get it from https://www.phphive.info/255/get-whatsapp-password/
 
        $number = "817045627138"; // Number
        // $number = "6282119331645"; // Number
        $message = "Hello Test From Lumen AQL...."; // Message
        
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'http://chat-api.phphive.info/message/send/text',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>json_encode(array("jid"=> $number."@s.whatsapp.net", "message" => $message)),
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer '.$chatApiToken,
                'Content-Type: application/json'
            ),
        ));
        
        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }

    public function setToken(Request $request){
        // $token = $request->get('token', null);
        // if($token !== 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC9sb2NhbGhvc3Q6ODAwMFwvbG9naW4iLCJpYXQiOjE2MTA0MjgzNzgsImV4cCI6MTYxMDQzMTk3OCwibmJmIjoxNjEwNDI4Mzc4LCJqdGkiOiJWSTFEZkVORjZWc3luNHB2Iiwic3ViIjoxMDAxLCJwcnYiOiIyM2JkNWM4OTQ5ZjYwMGFkYjM5ZTcwMWM0MDA4NzJkYjdhNTk3NmY3In0.awgkdKJarKGTxP_0HIldNI7CnG_xtJoxnzhALuFGIPc') return response()->json('Unauthorized');
        $model = new Tokenwa;
        $model->id = 1;
        $model->token = $request->get('token');
        try { 
            // $check_model = Tokenwa::whereIdCampaign($model->id_campaign)->wherePhoneNumber($model->phone_number)->orderBy('created_at', 'Desc')->first();
            // if($check_model !== null){
            //     $differ = strtotime("now") - strtotime($check_model->created_at);
            //     if($differ < 300) return response()->json(["success" => false, "message" => "Anda Sudah Melakukan Pendaftaran Dengan Nomor Handphone Yang Sama 5 Menit Yang Lalu, Silahkan Tunggu 5 Menit Lagi Bila Ingin Melakukan Pendaftaran Ulang"]);
            // }
            $model->save();

            return response()->json(["success" => true, "message" => "Wa API Token Berhasil Dirubah"]);
        } catch(Exception $err){ 
            return response()->json(["success" => false, "message" => $err]); 
        }
    }

    public function addDonation(Request $request){
        $token = $request->get('token', null);
        if($token !== 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC9sb2NhbGhvc3Q6ODAwMFwvbG9naW4iLCJpYXQiOjE2MTA0MjgzNzgsImV4cCI6MTYxMDQzMTk3OCwibmJmIjoxNjEwNDI4Mzc4LCJqdGkiOiJWSTFEZkVORjZWc3luNHB2Iiwic3ViIjoxMDAxLCJwcnYiOiIyM2JkNWM4OTQ5ZjYwMGFkYjM5ZTcwMWM0MDA4NzJkYjdhNTk3NmY3In0.awgkdKJarKGTxP_0HIldNI7CnG_xtJoxnzhALuFGIPc') return response()->json('Unauthorized');
        $model = new Donation;
        $model->id_campaign = $request->get('id_campaign');
        $model->name = $request->get('name');
        $model->email = $request->get('email');
        $model->phone_number = $request->get('phone_number');
        $model->amount = $request->get('amount');
        try { 
            $check_model = Donation::whereIdCampaign($model->id_campaign)->wherePhoneNumber($model->phone_number)->orderBy('created_at', 'Desc')->first();
            // if($check_model !== null){
            //     $differ = strtotime("now") - strtotime($check_model->created_at);
            //     if($differ < 300) return response()->json(["success" => false, "message" => "Anda Sudah Melakukan Pendaftaran Dengan Nomor Handphone Yang Sama 5 Menit Yang Lalu, Silahkan Tunggu 5 Menit Lagi Bila Ingin Melakukan Pendaftaran Ulang"]);
            // }
            $model->save();

            // event(new NewDonatorHasRegisteredEvent($model));
            return response()->json(["success" => true, "message" => "Data Berhasil Disimpan"]);
        } catch(Exception $err){ 
            return response()->json(["success" => false, "message" => $err]); 
        }
    }

    public function addCampaign(Request $request){
        $token = $request->get('token', null);
        if($token !== 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC9sb2NhbGhvc3Q6ODAwMFwvbG9naW4iLCJpYXQiOjE2MTA0MjgzNzgsImV4cCI6MTYxMDQzMTk3OCwibmJmIjoxNjEwNDI4Mzc4LCJqdGkiOiJWSTFEZkVORjZWc3luNHB2Iiwic3ViIjoxMDAxLCJwcnYiOiIyM2JkNWM4OTQ5ZjYwMGFkYjM5ZTcwMWM0MDA4NzJkYjdhNTk3NmY3In0.awgkdKJarKGTxP_0HIldNI7CnG_xtJoxnzhALuFGIPc') return response()->json('Unauthorized');
        $model = new Campaign;
        $model->campaign_name = $request->get('campaign_name');
        $model->target = $request->get('target');
        $model->deadline = $request->get('deadline');
        try{
            $model->save();
            return response()->json(["success" => true, "message" => "Data Berhasil Disimpan"]);
        } catch(Exception $err){
            return response()->json(["success" => false, "message" => $err]);
        }
    }

    public function getDonation(Request $request){
        $token = $request->get('token', null);
        if($token !== 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC9sb2NhbGhvc3Q6ODAwMFwvbG9naW4iLCJpYXQiOjE2MTA0MjgzNzgsImV4cCI6MTYxMDQzMTk3OCwibmJmIjoxNjEwNDI4Mzc4LCJqdGkiOiJWSTFEZkVORjZWc3luNHB2Iiwic3ViIjoxMDAxLCJwcnYiOiIyM2JkNWM4OTQ5ZjYwMGFkYjM5ZTcwMWM0MDA4NzJkYjdhNTk3NmY3In0.awgkdKJarKGTxP_0HIldNI7CnG_xtJoxnzhALuFGIPc') return response()->json('Unauthorized');
        $id_campaign = $request->get('id_campaign');
        $donations = Donation::select('id_campaign', 'name', 'amount')->whereIdCampaign($id_campaign)->get();
        return $donations;
    }

    public function getPaidDonation(Request $request){
        $token = $request->get('token', null);
        if($token !== 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC9sb2NhbGhvc3Q6ODAwMFwvbG9naW4iLCJpYXQiOjE2MTA0MjgzNzgsImV4cCI6MTYxMDQzMTk3OCwibmJmIjoxNjEwNDI4Mzc4LCJqdGkiOiJWSTFEZkVORjZWc3luNHB2Iiwic3ViIjoxMDAxLCJwcnYiOiIyM2JkNWM4OTQ5ZjYwMGFkYjM5ZTcwMWM0MDA4NzJkYjdhNTk3NmY3In0.awgkdKJarKGTxP_0HIldNI7CnG_xtJoxnzhALuFGIPc') return response()->json('Unauthorized');
        $id_campaign = $request->get('id_campaign');
        $donations = Donation::select('id_campaign', 'name', 'amount')->whereIdCampaign($id_campaign)->wherePaid(1)->get();
        return $donations;
    }

    public function setPaidDonation(Request $request){
        // $id = $request->get('id');
        return response()->json(["success" => true, "message" => "Berhasil Merubah Status Pembayaran"]);
    }

    public function getCampaign(Request $request){
        $token = $request->get('token', null);
        if($token !== 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC9sb2NhbGhvc3Q6ODAwMFwvbG9naW4iLCJpYXQiOjE2MTA0MjgzNzgsImV4cCI6MTYxMDQzMTk3OCwibmJmIjoxNjEwNDI4Mzc4LCJqdGkiOiJWSTFEZkVORjZWc3luNHB2Iiwic3ViIjoxMDAxLCJwcnYiOiIyM2JkNWM4OTQ5ZjYwMGFkYjM5ZTcwMWM0MDA4NzJkYjdhNTk3NmY3In0.awgkdKJarKGTxP_0HIldNI7CnG_xtJoxnzhALuFGIPc') return response()->json('Unauthorized');
        $campaigns = Campaign::all();
        return $campaigns;
    }
}
