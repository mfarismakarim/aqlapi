<?php

namespace App\Http\Controllers;

use App\Models\Volunteer;
use Illuminate\Http\Request;
use Exception;

class VolunteerController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
    */

    public function __construct()
    {
        
    }

    public function getVolunteer(Request $request)
    {
        $token = $request->get('token', null);
        if($token !== 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC9sb2NhbGhvc3Q6ODAwMFwvbG9naW4iLCJpYXQiOjE2MTA0MjgzNzgsImV4cCI6MTYxMDQzMTk3OCwibmJmIjoxNjEwNDI4Mzc4LCJqdGkiOiJWSTFEZkVORjZWc3luNHB2Iiwic3ViIjoxMDAxLCJwcnYiOiIyM2JkNWM4OTQ5ZjYwMGFkYjM5ZTcwMWM0MDA4NzJkYjdhNTk3NmY3In0.awgkdKJarKGTxP_0HIldNI7CnG_xtJoxnzhALuFGIPc') return response()->json('Unauthorized');
        $models = Volunteer::all();
        return response()->json($models);
    }

    public function addVolunteer(Request $request)
    {
        $token = $request->get('token', null);
        if($token !== 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC9sb2NhbGhvc3Q6ODAwMFwvbG9naW4iLCJpYXQiOjE2MTA0MjgzNzgsImV4cCI6MTYxMDQzMTk3OCwibmJmIjoxNjEwNDI4Mzc4LCJqdGkiOiJWSTFEZkVORjZWc3luNHB2Iiwic3ViIjoxMDAxLCJwcnYiOiIyM2JkNWM4OTQ5ZjYwMGFkYjM5ZTcwMWM0MDA4NzJkYjdhNTk3NmY3In0.awgkdKJarKGTxP_0HIldNI7CnG_xtJoxnzhALuFGIPc') return response()->json('Unauthorized');
        $model = new Volunteer;
        $model->NIK = $request->get('NIK');
        $model->namaLengkap = $request->get('namaLengkap');
        $model->namaPanggilan = $request->get('namaPanggilan');
        $model->tempatLahir = $request->get('tempatLahir');
        $model->tanggalLahir = $request->get('tanggalLahir');
        $model->umur = $request->get('umur');
        $model->status = $request->get('status', null);
        $model->jumlahSaudara = $request->get('jumlahSaudara', null);
        $model->jenisKelamin = $request->get('jenisKelamin', null);
        $model->anakKe = $request->get('anakKe', null);
        $model->alamat = $request->get('alamat', null);
        $model->facebook = $request->get('facebook', null);
        $model->instagram = $request->get('instagram', null);
        $model->twitter = $request->get('twitter', null);
        $model->noHp = $request->get('noHp');
        $model->whatsapp = $request->get('whatsapp', null);
        $model->email = $request->get('email');
        $model->tempatMengaji = $request->get('tempatMengaji', null);
        $model->motivasi = $request->get('motivasi', null);
        $model->harapan = $request->get('harapan', null);
        $model->komitmen = $request->get('komitmen', null);
        try{
            $model->save();
            return response()->json(["success" => true, "message" => "Data Berhasil Disimpan"]);
        } catch(Exception $err){
            return response()->json(["success" => false, "message" => $err]);
        }
    }

    public function editVolunteer(Request $request)
    {
        $token = $request->get('token', null);
        if($token !== 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC9sb2NhbGhvc3Q6ODAwMFwvbG9naW4iLCJpYXQiOjE2MTA0MjgzNzgsImV4cCI6MTYxMDQzMTk3OCwibmJmIjoxNjEwNDI4Mzc4LCJqdGkiOiJWSTFEZkVORjZWc3luNHB2Iiwic3ViIjoxMDAxLCJwcnYiOiIyM2JkNWM4OTQ5ZjYwMGFkYjM5ZTcwMWM0MDA4NzJkYjdhNTk3NmY3In0.awgkdKJarKGTxP_0HIldNI7CnG_xtJoxnzhALuFGIPc') return response()->json('Unauthorized');
        if(!$model = Volunteer::find($request->get('id'))){
            return response()->json('Data Tidak Ditemukan');
        }
        if($request->get('NIK') !== null) $model->NIK = $request->get('NIK');
        if($request->get('namaLengkap') !== null) $model->namaLengkap = $request->get('namaLengkap');
        if($request->get('namaPanggilan') !== null) $model->namaPanggilan = $request->get('namaPanggilan');
        if($request->get('tempatLahir') !== null) $model->tempatLahir = $request->get('tempatLahir');
        if($request->get('tanggalLahir') !== null) $model->tanggalLahir = $request->get('tanggalLahir');
        if($request->get('umur') !== null) $model->umur = $request->get('umur');
        if($request->get('status') !== null) $model->status = $request->get('status');
        if($request->get('jumlahSaudara') !== null) $model->jumlahSaudara = $request->get('jumlahSaudara');
        if($request->get('jenisKelamin') !== null) $model->jenisKelamin = $request->get('jenisKelamin');
        if($request->get('anakKe') !== null) $model->anakKe = $request->get('anakKe');
        if($request->get('alamat') !== null) $model->alamat = $request->get('alamat');
        if($request->get('facebook') !== null) $model->facebook = $request->get('facebook');
        if($request->get('instagram') !== null) $model->instagram = $request->get('instagram');
        if($request->get('twitter') !== null) $model->twitter = $request->get('twitter');
        if($request->get('noHp') !== null) $model->noHp = $request->get('noHp');
        if($request->get('whatsapp') !== null) $model->whatsapp = $request->get('whatsapp');
        if($request->get('email') !== null) $model->email = $request->get('email');
        if($request->get('tempatMengaji') !== null) $model->tempatMengaji = $request->get('tempatMengaji');
        if($request->get('motivasi') !== null) $model->motivasi = $request->get('motivasi');
        if($request->get('harapan') !== null) $model->harapan = $request->get('harapan');
        if($request->get('komitmen') !== null) $model->komitmen = $request->get('komitmen');
        try{
            $model->save();
            return response()->json(["success" => true, "message" => "Data Berhasil Dirubah"]);
        } catch(Exception $err){
            return response()->json(["success" => false, "message" => $err]);
        }
    }

    public function deleteVolunteer(Request $request)
    {
        $token = $request->get('token', null);
        if($token !== 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC9sb2NhbGhvc3Q6ODAwMFwvbG9naW4iLCJpYXQiOjE2MTA0MjgzNzgsImV4cCI6MTYxMDQzMTk3OCwibmJmIjoxNjEwNDI4Mzc4LCJqdGkiOiJWSTFEZkVORjZWc3luNHB2Iiwic3ViIjoxMDAxLCJwcnYiOiIyM2JkNWM4OTQ5ZjYwMGFkYjM5ZTcwMWM0MDA4NzJkYjdhNTk3NmY3In0.awgkdKJarKGTxP_0HIldNI7CnG_xtJoxnzhALuFGIPc') return response()->json('Unauthorized');
        $id = $request->get('id', null);
        $model = Volunteer::find($id);
        if($model === null) return response()->json(["success" => false, "message" => "Data Tidak Ditemukan"]);
        try{
            $model->delete();
            return response()->json(["success" => true, "message" => "Data Berhasil Dihapus"]);
        } catch(Exception $err){
            return response()->json(["success" => false, "message" => $err]);
        }
    }

    public function getVolunteerAuth(Request $request)
    {
        $models = Volunteer::all();
        return response()->json($models);
    }

    public function addVolunteerAuth(Request $request)
    {
        $model = new Volunteer;
        $model->NIK = $request->get('NIK');
        $model->namaLengkap = $request->get('namaLengkap');
        $model->namaPanggilan = $request->get('namaPanggilan');
        $model->tempatLahir = $request->get('tempatLahir');
        $model->tanggalLahir = $request->get('tanggalLahir');
        $model->umur = $request->get('umur');
        $model->status = $request->get('status', null);
        $model->jumlahSaudara = $request->get('jumlahSaudara', null);
        $model->jenisKelamin = $request->get('jenisKelamin', null);
        $model->anakKe = $request->get('anakKe', null);
        $model->alamat = $request->get('alamat', null);
        $model->facebook = $request->get('facebook', null);
        $model->instagram = $request->get('instagram', null);
        $model->twitter = $request->get('twitter', null);
        $model->noHp = $request->get('noHp');
        $model->whatsapp = $request->get('whatsapp', null);
        $model->email = $request->get('email');
        $model->tempatMengaji = $request->get('tempatMengaji', null);
        $model->motivasi = $request->get('motivasi', null);
        $model->harapan = $request->get('harapan', null);
        $model->komitmen = $request->get('komitmen', null);
        try{
            $model->save();
            return response()->json(["success" => true, "message" => "Data Berhasil Disimpan"]);
        } catch(Exception $err){
            return response()->json(["success" => false, "message" => $err]);
        }
    }
}