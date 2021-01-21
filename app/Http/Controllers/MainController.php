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

            event(new NewDonatorHasRegisteredEvent($model));
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
        $id_campaign = $request->get('id_campaign', null);
        $donations = Donation::whereIdCampaign($id_campaign)->get();
        return $donations;
    }

    public function getPaidDonation(Request $request){
        $token = $request->get('token', null);
        if($token !== 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC9sb2NhbGhvc3Q6ODAwMFwvbG9naW4iLCJpYXQiOjE2MTA0MjgzNzgsImV4cCI6MTYxMDQzMTk3OCwibmJmIjoxNjEwNDI4Mzc4LCJqdGkiOiJWSTFEZkVORjZWc3luNHB2Iiwic3ViIjoxMDAxLCJwcnYiOiIyM2JkNWM4OTQ5ZjYwMGFkYjM5ZTcwMWM0MDA4NzJkYjdhNTk3NmY3In0.awgkdKJarKGTxP_0HIldNI7CnG_xtJoxnzhALuFGIPc') return response()->json('Unauthorized');
        $id_campaign = $request->get('id_campaign', null);
        $donations = Donation::whereIdCampaign($id_campaign)->wherePaid(1)->get();
        return $donations;
    }

    public function setPaidDonation(Request $request){
        $token = $request->get('token', null);
        if($token !== 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC9sb2NhbGhvc3Q6ODAwMFwvbG9naW4iLCJpYXQiOjE2MTA0MjgzNzgsImV4cCI6MTYxMDQzMTk3OCwibmJmIjoxNjEwNDI4Mzc4LCJqdGkiOiJWSTFEZkVORjZWc3luNHB2Iiwic3ViIjoxMDAxLCJwcnYiOiIyM2JkNWM4OTQ5ZjYwMGFkYjM5ZTcwMWM0MDA4NzJkYjdhNTk3NmY3In0.awgkdKJarKGTxP_0HIldNI7CnG_xtJoxnzhALuFGIPc') return response()->json('Unauthorized');
        $id = $request->get('id');
        $model = Donation::find($id);
        if($model === null) return response()->json(["success" => false, "message" => "Data Tidak Ditemukan"]);
        $model->paid = true;
        try {
            $model->save();
            return response()->json(["success" => true, "message" => "Berhasil Merubah Status Pembayaran"]);
        } catch(Exception $err){
            return response()->json(["success" => false, "message" => $err]);
        }
    }

    public function getCampaign(Request $request){
        $token = $request->get('token', null);
        if($token !== 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC9sb2NhbGhvc3Q6ODAwMFwvbG9naW4iLCJpYXQiOjE2MTA0MjgzNzgsImV4cCI6MTYxMDQzMTk3OCwibmJmIjoxNjEwNDI4Mzc4LCJqdGkiOiJWSTFEZkVORjZWc3luNHB2Iiwic3ViIjoxMDAxLCJwcnYiOiIyM2JkNWM4OTQ5ZjYwMGFkYjM5ZTcwMWM0MDA4NzJkYjdhNTk3NmY3In0.awgkdKJarKGTxP_0HIldNI7CnG_xtJoxnzhALuFGIPc') return response()->json('Unauthorized');
        $campaigns = Campaign::all();
        return $campaigns;
    }

    public function deleteDonation(Request $request){
        $token = $request->get('token', null);
        if($token !== 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC9sb2NhbGhvc3Q6ODAwMFwvbG9naW4iLCJpYXQiOjE2MTA0MjgzNzgsImV4cCI6MTYxMDQzMTk3OCwibmJmIjoxNjEwNDI4Mzc4LCJqdGkiOiJWSTFEZkVORjZWc3luNHB2Iiwic3ViIjoxMDAxLCJwcnYiOiIyM2JkNWM4OTQ5ZjYwMGFkYjM5ZTcwMWM0MDA4NzJkYjdhNTk3NmY3In0.awgkdKJarKGTxP_0HIldNI7CnG_xtJoxnzhALuFGIPc') return response()->json('Unauthorized');
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

    public function deleteCampaign(Request $request){
        $token = $request->get('token', null);
        if($token !== 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC9sb2NhbGhvc3Q6ODAwMFwvbG9naW4iLCJpYXQiOjE2MTA0MjgzNzgsImV4cCI6MTYxMDQzMTk3OCwibmJmIjoxNjEwNDI4Mzc4LCJqdGkiOiJWSTFEZkVORjZWc3luNHB2Iiwic3ViIjoxMDAxLCJwcnYiOiIyM2JkNWM4OTQ5ZjYwMGFkYjM5ZTcwMWM0MDA4NzJkYjdhNTk3NmY3In0.awgkdKJarKGTxP_0HIldNI7CnG_xtJoxnzhALuFGIPc') return response()->json('Unauthorized');
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
}
