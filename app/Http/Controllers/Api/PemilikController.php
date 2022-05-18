<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Validator;
use App\Models\Pemilik_10079;
use Carbon\Carbon;
use DB;

class PemilikController extends Controller
{
    public function index(){
        $pemilik = Pemilik_10079::all();

        if(count($pemilik)>0){
            return response ([
                'message' => 'Retrieve All Pemilik Success',
                'data' => $pemilik
            ], 200);
        }

        return response([
            'message' => 'Empty',
            'data' => null
        ], 400);
    }

    public function getKontrakMobil(){
        $pemilik = DB::table('mobil_10079s')
                    ->join('pemilik_10079s', 'mobil_10079s.id_pemilik', '=', 'pemilik_10079s.id_pemilik')
                    ->select('mobil_10079s.*', 'pemilik_10079s.*')
                    ->whereRaw("DATEDIFF(pemilik_10079s.periode_kontrak_akhir, '".Carbon::now()."') < 30")
                    ->get();

        if(count($pemilik)>0){
            return response ([
                'message' => 'Retrieve All Kontrak Mobil Success',
                'data' => $pemilik
            ], 200);
        }

        return response([
            'message' => 'Empty',
            'data' => null
        ], 400);
    }

    public function show($id_pemilik){
        $pemilik = Pemilik_10079::where('id_pemilik', $id_pemilik)->first();

        if(!is_null($pemilik)){
            return response ([
                'message' => 'Retrieve Pemilik Success',
                'data' => $pemilik
            ],200);
        }

        return response([
            'message' => 'Pemilik Not Found',
            'data' => null
        ],404);
    }

    public function store (Request $request){
        $storeData = $request->all();

        $validate = Validator::make($storeData, [
            'no_ktp_pemilik' => 'required|numeric|digits:16|unique:pemilik_10079s',
            'nama_pemilik' => 'required|regex:/^[\pL\s]+$/u',
            'alamat_pemilik' => 'required',
            'notelp_pemilik' => 'required|numeric|digits_between:0,13|starts_with:08',
            'periode_kontrak_mulai' => 'required|date:ymd',
            'periode_kontrak_akhir' => 'required|date:ymd|after_or_equal:periode_kontrak_mulai',
            'tgl_servis_terakhir' => 'required|date:ymd'
        ]);

        if($validate->fails())
            return response(['message' => $validate->errors()], 400);
            $pemilik = Pemilik_10079::create($storeData);
            return response([
                'message' => 'Add Pemilik Success',
                'data' => $pemilik
            ],200);
    }

    public function destroy($id_pemilik){
        $pemilik = Pemilik_10079::where('id_pemilik', $id_pemilik)->first();

        if (is_null($pemilik)) {
            return response([
                'message' => 'Pemilik Not Found',
                'data' => null
            ],404);
        }

        if($pemilik->delete()) {
            return response([
                'message' => 'Delete Pemilik Success',
                'data' => $pemilik
            ],200);
        }

        return response([
            'message' => 'Delete Pemilik Failed',
            'data' => null,
        ],400);
    }

    public function update(Request $request, $id_pemilik){
        $pemilik = Pemilik_10079::where('id_pemilik', $id_pemilik)->first();
        if(is_null($pemilik)){
            return response([
                'message' => 'Pemilik Not Found',
                'data' => null
            ], 404);
        }

        $updateData = $request->all();
        $validate = Validator::make($updateData, [
            'nama_pemilik' => 'required|regex:/^[\pL\s]+$/u',
            'alamat_pemilik' => 'required',
            'notelp_pemilik' => 'required|numeric|digits_between:0,13|starts_with:08',
            'periode_kontrak_mulai' => 'required|date:ymd',
            'periode_kontrak_akhir' => 'required|date:ymd|after_or_equal:periode_kontrak_mulai',
            'tgl_servis_terakhir' => 'required|date:ymd'
        ]);

        if($validate->fails())
            return response(['message' => $validate->errors()], 400);

        $pemilik->nama_pemilik = $updateData['nama_pemilik'];
        $pemilik->alamat_pemilik = $updateData['alamat_pemilik'];
        $pemilik->notelp_pemilik = $updateData['notelp_pemilik'];
        $pemilik->periode_kontrak_mulai = $updateData['periode_kontrak_mulai'];
        $pemilik->periode_kontrak_akhir = $updateData['periode_kontrak_akhir'];
        $pemilik->tgl_servis_terakhir = $updateData['tgl_servis_terakhir'];

        if($pemilik->save()){
            return response([
                'message' => 'Update Pemilik Success',
                'data' => $pemilik
            ], 200);
        }

        return response([
            'message' => 'Update Pemilik Success',
            'data' => null,
        ], 400);
    }
}
