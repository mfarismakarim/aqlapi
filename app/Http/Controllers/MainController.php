<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\Donation;
use App\Models\User;
use App\Events\NewDonatorHasRegisteredEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\JWTAuth;
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

    // JWT Auth Route

    public function addDonationAuth(Request $request){
        $model = new Donation;
        $model->id_campaign = $request->get('id_campaign');
        $model->name = $request->get('name');
        $model->email = $request->get('email');
        $model->phone_number = $request->get('phone_number');
        $model->amount = $request->get('amount');
        $item = (object)[
            "qrcode_url" => $request->get('qrcode_url', null),
            "account_number" => $request->get('account_number', null)
        ];
        try { 
            $check_models = Donation::whereIdCampaign($model->id_campaign)->orderBy('created_at', 'Desc')->get();
            $check_phone = $check_models->where('phone_number', $model->phone_number)->first();
            $check_email = $check_models->where('email', $model->email)->first();
            if($check_phone !== null){
                $differ = strtotime("now") - strtotime($check_phone->created_at);
                if($differ < 300) return response()->json(["success" => false, "message" => "Anda Sudah Melakukan Pendaftaran Dengan Nomor Handphone Yang Sama 5 Menit Yang Lalu, Silahkan Tunggu 5 Menit Lagi Bila Ingin Melakukan Pendaftaran Ulang"]);
            }
            if($check_email !== null){
                $differ = strtotime("now") - strtotime($check_email->created_at);
                if($differ < 300) return response()->json(["success" => false, "message" => "Anda Sudah Melakukan Pendaftaran Dengan Email Yang Sama 5 Menit Yang Lalu, Silahkan Tunggu 5 Menit Lagi Bila Ingin Melakukan Pendaftaran Ulang"]);
            }
            $model->save();

            if(filter_var($model->email, FILTER_VALIDATE_EMAIL)){
                event(new NewDonatorHasRegisteredEvent($model, $item, 1));    
            }
            return response()->json(["success" => true, "message" => "Data Berhasil Disimpan"]);
        } catch(Exception $err){ 
            return response()->json(["success" => false, "message" => $err]); 
        }
    }

    public function addCampaignAuth(Request $request){
        $id = $request->get('id', null);
        $model = new Campaign;
        if($id !== null) $model->id = $id;
        $model->campaign_name = $request->get('campaign_name');
        $model->target = $request->get('target');
        $model->deadline = $request->get('deadline');
        $model->collected = 0;
        try{
            $model->save();
            return response()->json(["success" => true, "message" => "Data Berhasil Disimpan"]);
        } catch(Exception $err){
            return response()->json(["success" => false, "message" => $err]);
        }
    }

    public function getDonationAuth(Request $request){
        $id_campaign = $request->get('id_campaign', null);
        $donations = Donation::whereIdCampaign($id_campaign)->get();
        return $donations;
    }

    public function getPaidDonationAuth(Request $request){
        $id_campaign = $request->get('id_campaign', null);
        $donations = Donation::whereIdCampaign($id_campaign)->wherePaid(1)->get();
        return $donations;
    }

    public function setPaidDonationAuth(Request $request){
        $id = $request->get('id');
        $donation = Donation::find($id);
        if($donation === null) return response()->json(["success" => false, "message" => "Data Donasi Tidak Ditemukan"]);
        if($donation->paid === 1) return response()->json(["success" => false, "message" => "Donasi Sudah Terbayar"]);
        $donation->paid = true;
        $campaign = Campaign::find($donation->id_campaign);
        if($campaign === null) return response()->json(["success" => false, "message" => "Data Campaign Tidak Ditemukan"]);
        $campaign->collected += $donation->amount;
        try {
            $donation->save();
            $campaign->save();

            //Update collected value on strapi database
            $url = 'https://peaceful-meadow-45867.herokuapp.com/programs/'.$campaign->id;
            $ch = curl_init($url);
            $data = array(
                'totalterkumpulProgram' => $campaign->collected
            );
            $payload = json_encode($data);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
            curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $result = curl_exec($ch);
            curl_close($ch);

            if(filter_var($donation->email, FILTER_VALIDATE_EMAIL)){
                $item = (object)[];
                event(new NewDonatorHasRegisteredEvent($donation, $item, 2));    
            }
            return response()->json(["success" => true, "message" => "Berhasil Merubah Status Pembayaran"]);
        } catch(Exception $err){
            return response()->json(["success" => false, "message" => $err]);
        }
    }

    public function setUnPaidDonationAuth(Request $request){
        $id = $request->get('id');
        $donation = Donation::find($id);
        if($donation === null) return response()->json(["success" => false, "message" => "Data Donasi Tidak Ditemukan"]);
        if($donation->paid === 1){
            $donation->paid = false;
            $campaign = Campaign::find($donation->id_campaign);
            if($campaign === null) return response()->json(["success" => false, "message" => "Data Campaign Tidak Ditemukan"]);
            $campaign->collected -= $donation->amount;
            try {
                $donation->save();
                $campaign->save();

                //Update collected value on strapi database
                $url = 'https://peaceful-meadow-45867.herokuapp.com/programs/'.$campaign->id;
                $ch = curl_init($url);
                $data = array(
                    'totalterkumpulProgram' => $campaign->collected
                );
                $payload = json_encode($data);
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
                curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $result = curl_exec($ch);
                curl_close($ch);
                
                return response()->json(["success" => true, "message" => "Berhasil Merubah Status Pembayaran"]);
            } catch(Exception $err){
                return response()->json(["success" => false, "message" => $err]);
            }
        } else {
            return response()->json(["success" => false, "message" => "Donasi Belum Terbayar"]);
        }
    }

    public function getCampaignAuth(Request $request){
        $campaigns = Campaign::all();
        return $campaigns;
    }

    public function deleteDonationAuth(Request $request){
        $id = $request->get('id');
        $model = Donation::find($id);
        if($model === null) return response()->json(["success" => false, "message" => "Data Tidak Ditemukan"]);
        try{
            $model->delete();
            return response()->json(["success" => true, "message" => "Data Berhasil Dihapus"]);
        } catch(Exception $err){
            return response()->json(["success" => false, "message" => $err]);
        }
    }

    public function deleteCampaignAuth(Request $request){
        $id = $request->get('id');
        $model = Campaign::find($id);
        if($model === null) return response()->json(["success" => false, "message" => "Data Tidak Ditemukan"]);
        try{
            $model->delete();
            return response()->json(["success" => true, "message" => "Data Berhasil Dihapus"]);
        } catch(Exception $err){
            return response()->json(["success" => false, "message" => $err]);
        }
    }

    public function editCampaignAuth(Request $request){
        if(!$model = Campaign::find($request->get('id'))){
            return response()->json('Data Tidak Ditemukan');
        }
        if($request->get('campaign_name') !== null) $model->campaign_name = $request->get('campaign_name');
        if($request->get('target') !== null) $model->target = $request->get('target');
        if($request->get('deadline') !== null) $model->deadline = $request->get('deadline');
        if($request->get('collected') !== null) $model->collected = $request->get('collected');
        try{
            $model->save();
            if($request->get('collected') !== null){
                $url = 'https://peaceful-meadow-45867.herokuapp.com/programs/'.$model->id;
                $ch = curl_init($url);
                $data = array(
                    'totalterkumpulProgram' => $model->collected
                );
                $payload = json_encode($data);
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
                curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $result = curl_exec($ch);
                curl_close($ch);
            }
            return response()->json(["success" => true, "message" => "Data Berhasil Dirubah"]);
        } catch(Exception $err){
            return response()->json(["success" => false, "message" => $err]);
        }
    }

    // Normal Route

    public function addDonation(Request $request){
        $token = $request->get('token', null);
        if($token !== 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC9sb2NhbGhvc3Q6ODAwMFwvbG9naW4iLCJpYXQiOjE2MTA0MjgzNzgsImV4cCI6MTYxMDQzMTk3OCwibmJmIjoxNjEwNDI4Mzc4LCJqdGkiOiJWSTFEZkVORjZWc3luNHB2Iiwic3ViIjoxMDAxLCJwcnYiOiIyM2JkNWM4OTQ5ZjYwMGFkYjM5ZTcwMWM0MDA4NzJkYjdhNTk3NmY3In0.awgkdKJarKGTxP_0HIldNI7CnG_xtJoxnzhALuFGIPc') return response()->json('Unauthorized');
        $model = new Donation;
        $model->id_campaign = $request->get('id_campaign');
        $model->name = $request->get('name');
        $model->email = $request->get('email', "no-email");
        $model->phone_number = $request->get('phone_number');
        $model->amount = $request->get('amount');
        $item = (object)[
            "qrcode_url" => $request->get('qrcode_url', null),
            "account_number" => $request->get('account_number', null)
        ];
        try { 
            $check_models = Donation::whereIdCampaign($model->id_campaign)->orderBy('created_at', 'Desc')->get();
            $check_phone = $check_models->where('phone_number', $model->phone_number)->first();
            $check_email = $check_models->where('email', $model->email)->first();
            if($check_phone !== null){
                $differ = strtotime("now") - strtotime($check_phone->created_at);
                if($differ < 300) return response()->json(["success" => false, "message" => "Anda Sudah Melakukan Pendaftaran Dengan Nomor Handphone Yang Sama 5 Menit Yang Lalu, Silahkan Tunggu 5 Menit Lagi Bila Ingin Melakukan Pendaftaran Ulang"]);
            }
            if($check_email !== null){
                $differ = strtotime("now") - strtotime($check_email->created_at);
                if($differ < 300) return response()->json(["success" => false, "message" => "Anda Sudah Melakukan Pendaftaran Dengan Email Yang Sama 5 Menit Yang Lalu, Silahkan Tunggu 5 Menit Lagi Bila Ingin Melakukan Pendaftaran Ulang"]);
            }
            $model->save();
            
            if(filter_var($model->email, FILTER_VALIDATE_EMAIL)){
                event(new NewDonatorHasRegisteredEvent($model, $item, 1));    
            }
            
            return response()->json(["success" => true, "message" => "Data Berhasil Disimpan"]);
        } catch(Exception $err){ 
            return response()->json(["success" => false, "message" => $err]); 
        }
    }

    // public function addCampaign(Request $request){
    //     $token = $request->get('token', null);
    //     if($token !== 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC9sb2NhbGhvc3Q6ODAwMFwvbG9naW4iLCJpYXQiOjE2MTA0MjgzNzgsImV4cCI6MTYxMDQzMTk3OCwibmJmIjoxNjEwNDI4Mzc4LCJqdGkiOiJWSTFEZkVORjZWc3luNHB2Iiwic3ViIjoxMDAxLCJwcnYiOiIyM2JkNWM4OTQ5ZjYwMGFkYjM5ZTcwMWM0MDA4NzJkYjdhNTk3NmY3In0.awgkdKJarKGTxP_0HIldNI7CnG_xtJoxnzhALuFGIPc') return response()->json('Unauthorized');
    //     $id = $request->get('id', null);
    //     $model = new Campaign;
    //     if($id !== null) $model->id = $id;
    //     $model->campaign_name = $request->get('campaign_name');
    //     $model->target = $request->get('target');
    //     $model->deadline = $request->get('deadline');
    //     $model->collected = 0;
    //     try{
    //         $model->save();
    //         return response()->json(["success" => true, "message" => "Data Berhasil Disimpan"]);
    //     } catch(Exception $err){
    //         return response()->json(["success" => false, "message" => $err]);
    //     }
    // }

    // public function getDonation(Request $request){
    //     $token = $request->get('token', null);
    //     if($token !== 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC9sb2NhbGhvc3Q6ODAwMFwvbG9naW4iLCJpYXQiOjE2MTA0MjgzNzgsImV4cCI6MTYxMDQzMTk3OCwibmJmIjoxNjEwNDI4Mzc4LCJqdGkiOiJWSTFEZkVORjZWc3luNHB2Iiwic3ViIjoxMDAxLCJwcnYiOiIyM2JkNWM4OTQ5ZjYwMGFkYjM5ZTcwMWM0MDA4NzJkYjdhNTk3NmY3In0.awgkdKJarKGTxP_0HIldNI7CnG_xtJoxnzhALuFGIPc') return response()->json('Unauthorized');
    //     $id_campaign = $request->get('id_campaign', null);
    //     $donations = Donation::whereIdCampaign($id_campaign)->get();
    //     return $donations;
    // }

    // public function getPaidDonation(Request $request){
    //     $token = $request->get('token', null);
    //     if($token !== 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC9sb2NhbGhvc3Q6ODAwMFwvbG9naW4iLCJpYXQiOjE2MTA0MjgzNzgsImV4cCI6MTYxMDQzMTk3OCwibmJmIjoxNjEwNDI4Mzc4LCJqdGkiOiJWSTFEZkVORjZWc3luNHB2Iiwic3ViIjoxMDAxLCJwcnYiOiIyM2JkNWM4OTQ5ZjYwMGFkYjM5ZTcwMWM0MDA4NzJkYjdhNTk3NmY3In0.awgkdKJarKGTxP_0HIldNI7CnG_xtJoxnzhALuFGIPc') return response()->json('Unauthorized');
    //     $id_campaign = $request->get('id_campaign', null);
    //     $donations = Donation::whereIdCampaign($id_campaign)->wherePaid(1)->get();
    //     return $donations;
    // }

    // public function setPaidDonation(Request $request){
    //     $token = $request->get('token', null);
    //     if($token !== 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC9sb2NhbGhvc3Q6ODAwMFwvbG9naW4iLCJpYXQiOjE2MTA0MjgzNzgsImV4cCI6MTYxMDQzMTk3OCwibmJmIjoxNjEwNDI4Mzc4LCJqdGkiOiJWSTFEZkVORjZWc3luNHB2Iiwic3ViIjoxMDAxLCJwcnYiOiIyM2JkNWM4OTQ5ZjYwMGFkYjM5ZTcwMWM0MDA4NzJkYjdhNTk3NmY3In0.awgkdKJarKGTxP_0HIldNI7CnG_xtJoxnzhALuFGIPc') return response()->json('Unauthorized');
    //     $id = $request->get('id');
    //     $donation = Donation::find($id);
    //     if($donation === null) return response()->json(["success" => false, "message" => "Data Donasi Tidak Ditemukan"]);
    //     if($donation->paid === 1) return response()->json(["success" => false, "message" => "Donasi Sudah Terbayar"]);
    //     $donation->paid = true;
    //     $campaign = Campaign::find($donation->id_campaign);
    //     if($campaign === null) return response()->json(["success" => false, "message" => "Data Campaign Tidak Ditemukan"]);
    //     $campaign->collected += $donation->amount;
    //     try {
    //         $donation->save();
    //         $campaign->save();

    //         //Update collected value on strapi database
    //         $url = 'https://peaceful-meadow-45867.herokuapp.com/programs/'.$campaign->id;
    //         $ch = curl_init($url);
    //         $data = array(
    //             'totalterkumpulProgram' => $campaign->collected
    //         );
    //         $payload = json_encode($data);
    //         curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
    //         curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    //         curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
    //         curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    //         $result = curl_exec($ch);
    //         curl_close($ch);

    //         if(filter_var($donation->email, FILTER_VALIDATE_EMAIL)){
    //             $item = (object)[];
    //             event(new NewDonatorHasRegisteredEvent($donation, $item, 2));    
    //         }
    //         return response()->json(["success" => true, "message" => "Berhasil Merubah Status Pembayaran"]);
    //     } catch(Exception $err){
    //         return response()->json(["success" => false, "message" => $err]);
    //     }
    // }

    // public function setUnPaidDonation(Request $request){
    //     $token = $request->get('token', null);
    //     if($token !== 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC9sb2NhbGhvc3Q6ODAwMFwvbG9naW4iLCJpYXQiOjE2MTA0MjgzNzgsImV4cCI6MTYxMDQzMTk3OCwibmJmIjoxNjEwNDI4Mzc4LCJqdGkiOiJWSTFEZkVORjZWc3luNHB2Iiwic3ViIjoxMDAxLCJwcnYiOiIyM2JkNWM4OTQ5ZjYwMGFkYjM5ZTcwMWM0MDA4NzJkYjdhNTk3NmY3In0.awgkdKJarKGTxP_0HIldNI7CnG_xtJoxnzhALuFGIPc') return response()->json('Unauthorized');
    //     $id = $request->get('id');
    //     $donation = Donation::find($id);
    //     if($donation === null) return response()->json(["success" => false, "message" => "Data Donasi Tidak Ditemukan"]);
    //     if($donation->paid === 1){
    //         $donation->paid = false;
    //         $campaign = Campaign::find($donation->id_campaign);
    //         if($campaign === null) return response()->json(["success" => false, "message" => "Data Campaign Tidak Ditemukan"]);
    //         $campaign->collected -= $donation->amount;
    //         try {
    //             $donation->save();
    //             $campaign->save();

    //             //Update collected value on strapi database
    //             $url = 'https://peaceful-meadow-45867.herokuapp.com/programs/'.$campaign->id;
    //             $ch = curl_init($url);
    //             $data = array(
    //                 'totalterkumpulProgram' => $campaign->collected
    //             );
    //             $payload = json_encode($data);
    //             curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
    //             curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    //             curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
    //             curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    //             $result = curl_exec($ch);
    //             curl_close($ch);
    
    //             return response()->json(["success" => true, "message" => "Berhasil Merubah Status Pembayaran"]);
    //         } catch(Exception $err){
    //             return response()->json(["success" => false, "message" => $err]);
    //         }
    //     } else {
    //         return response()->json(["success" => false, "message" => "Donasi Belum Terbayar"]);
    //     }
    // }

    // public function getCampaign(Request $request){
    //     $token = $request->get('token', null);
    //     if($token !== 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC9sb2NhbGhvc3Q6ODAwMFwvbG9naW4iLCJpYXQiOjE2MTA0MjgzNzgsImV4cCI6MTYxMDQzMTk3OCwibmJmIjoxNjEwNDI4Mzc4LCJqdGkiOiJWSTFEZkVORjZWc3luNHB2Iiwic3ViIjoxMDAxLCJwcnYiOiIyM2JkNWM4OTQ5ZjYwMGFkYjM5ZTcwMWM0MDA4NzJkYjdhNTk3NmY3In0.awgkdKJarKGTxP_0HIldNI7CnG_xtJoxnzhALuFGIPc') return response()->json('Unauthorized');
    //     $campaigns = Campaign::all();
    //     return $campaigns;
    // }

    // public function deleteDonation(Request $request){
    //     $token = $request->get('token', null);
    //     if($token !== 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC9sb2NhbGhvc3Q6ODAwMFwvbG9naW4iLCJpYXQiOjE2MTA0MjgzNzgsImV4cCI6MTYxMDQzMTk3OCwibmJmIjoxNjEwNDI4Mzc4LCJqdGkiOiJWSTFEZkVORjZWc3luNHB2Iiwic3ViIjoxMDAxLCJwcnYiOiIyM2JkNWM4OTQ5ZjYwMGFkYjM5ZTcwMWM0MDA4NzJkYjdhNTk3NmY3In0.awgkdKJarKGTxP_0HIldNI7CnG_xtJoxnzhALuFGIPc') return response()->json('Unauthorized');
    //     $id = $request->get('id');
    //     $model = Donation::find($id);
    //     if($model === null) return response()->json(["success" => false, "message" => "Data Tidak Ditemukan"]);
    //     try{
    //         $model->delete();
    //         return response()->json(["success" => true, "message" => "Data Berhasil Dihapus"]);
    //     } catch(Exception $err){
    //         return response()->json(["success" => false, "message" => $err]);
    //     }
    // }

    // public function deleteCampaign(Request $request){
    //     $token = $request->get('token', null);
    //     if($token !== 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC9sb2NhbGhvc3Q6ODAwMFwvbG9naW4iLCJpYXQiOjE2MTA0MjgzNzgsImV4cCI6MTYxMDQzMTk3OCwibmJmIjoxNjEwNDI4Mzc4LCJqdGkiOiJWSTFEZkVORjZWc3luNHB2Iiwic3ViIjoxMDAxLCJwcnYiOiIyM2JkNWM4OTQ5ZjYwMGFkYjM5ZTcwMWM0MDA4NzJkYjdhNTk3NmY3In0.awgkdKJarKGTxP_0HIldNI7CnG_xtJoxnzhALuFGIPc') return response()->json('Unauthorized');
    //     $id = $request->get('id');
    //     $model = Campaign::find($id);
    //     if($model === null) return response()->json(["success" => false, "message" => "Data Tidak Ditemukan"]);
    //     try{
    //         $model->delete();
    //         return response()->json(["success" => true, "message" => "Data Berhasil Dihapus"]);
    //     } catch(Exception $err){
    //         return response()->json(["success" => false, "message" => $err]);
    //     }
    // }

    

}
