<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\Donation;
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

    public function addDonation(Request $request){
        $model = new Donation;
        $model->id_campaign = $request->get('id_campaign');
        $model->name = $request->get('name');
        $model->email = $request->get('email');
        $model->phone_number = $request->get('phone_number');
        $model->amount = $request->get('amount');
        // $token = $request->get('token', null);
        try { 
            $check_model = Donation::whereIdCampaign($model->id_campaign)->wherePhoneNumber($model->phone_number)->orderBy('created_at', 'Desc')->first();
            if($check_model !== null){
                $differ = strtotime("now") - strtotime($check_model->created_at);
                if($differ < 300) return response()->json(["success" => false, "message" => "Anda Sudah Melakukan Pendaftaran Dengan Nomor Handphone Yang Sama 5 Menit Yang Lalu, Silahkan Tunggu 5 Menit Lagi Bila Ingin Melakukan Pendaftaran Ulang"]);
            }
            $model->save();
            return response()->json(["success" => true, "message" => "Data Berhasil Disimpan"]);
        } catch(Exception $err){ 
            return response()->json(["success" => false, "message" => $err]); 
        }
    }

    public function addCampaign(Request $request){
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
        if($token !== '516782930121') return response()->json('Unauthorized');
        $id_campaign = $request->get('id_campaign');
        $donations = Donation::select('id_campaign', 'name', 'amount')->whereIdCampaign($id_campaign)->get();
        return $donations;
    }

    public function getPaidDonation(Request $request){
        $id_campaign = $request->get('id_campaign');
        $donations = Donation::select('id_campaign', 'name', 'amount')->whereIdCampaign($id_campaign)->wherePaid(1)->get();
        return $donations;
    }

    public function setPaidDonation(Request $request){
        // $id = $request->get('id');
        return response()->json(["success" => true, "message" => "Berhasil Merubah Status Pembayaran"]);
    }

    public function getCampaign(Request $request){
        $campaigns = Campaign::all();
        return $campaigns;
    }
}
