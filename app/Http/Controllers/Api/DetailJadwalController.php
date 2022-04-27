<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Validator;
use App\Models\Detail_Jadwal_10079;
use DB;

class DetailJadwalController extends Controller
{
    public function index(){
        // $detailJadwal = Detail_Jadwal_10079::all();

        $detailJadwal = DB::table('detail__jadwal_10079s') 
                        ->join('pegawai_10079s', 'pegawai_10079s.id_pegawai', '=', 'detail__jadwal_10079s.id_pegawai')
                        ->join('jadwal_10079s', 'jadwal_10079s.id_jadwal_pegawai', '=', 'detail__jadwal_10079s.id_jadwal_pegawai')
                        ->select('detail__jadwal_10079s.*', 'pegawai_10079s.id_pegawai', 'pegawai_10079s.nama_pegawai', 'jadwal_10079s.id_jadwal_pegawai')
                        ->get();

        if(count($detailJadwal)>0){
            return response ([
                'message' => 'Retrieve All Detail Jadwal Success',
                'data' => $detailJadwal
            ], 200);
        }

        return response([
            'message' => 'Empty',
            'data' => null
        ], 400);
    }

    public function show($id_detail_jadwal){
        $detailJadwal = Detail_Jadwal_10079::where('id_detail_jadwal', $id_detail_jadwal)->first();

        if(!is_null($detailJadwal)){
            return response ([
                'message' => 'Retrieve Detail Jadwal Success',
                'data' => $detailJadwal
            ],200);
        }

        return response([
            'message' => 'Detail Jadwal Not Found',
            'data' => null
        ],404);
    }

    public function store (Request $request){
        $storeData = $request->all();

        $validate = Validator::make($storeData, [
            'id_pegawai' => 'required',
            'id_jadwal_pegawai' => 'required',
            'keterangan_detail_jadwal' => 'required'
        ]);

        if($validate->fails()){
            return response(['message' => $validate->errors()], 400);
        }
            
        $detailJadwal = Detail_Jadwal_10079::create($storeData);
        return response([
           'message' => 'Add Detail Jadwal Success',
            'data' => $detailJadwal
        ],200);
    }

    public function destroy($id_detail_jadwal){
        $detailJadwal = Detail_Jadwal_10079::where('id_detail_jadwal', $id_detail_jadwal)->first();

        if (is_null($detailJadwal)) {
            return response([
                'message' => 'Detail Jadwal Not Found',
                'data' => null
            ],404);
        }

        if($detailJadwal->delete()) {
            return response([
                'message' => 'Delete Detail Jadwal Success',
                'data' => $detailJadwal
            ],200);
        }

        return response([
            'message' => 'Delete Detail Jadwal Failed',
            'data' => null,
        ],400);
    }

    public function update(Request $request, $id_detail_jadwal){
        $detailJadwal = Detail_Jadwal_10079::where('id_detail_jadwal', $id_detail_jadwal)->first();
        if(is_null($detailJadwal)){
            return response([
                'message' => 'Detail Jadwal Not Found',
                'data' => null
            ], 404);
        }

        $updateData = $request->all();
        $validate = Validator::make($updateData, [
            'id_pegawai' => 'required',
            'id_jadwal_pegawai' => 'required',
            'keterangan_detail_jadwal' => 'required'
        ]);

        if($validate->fails())
            return response(['message' => $validate->errors()], 400);

        $detailJadwal->id_pegawai = $updateData['id_pegawai'];
        $detailJadwal->id_jadwal_pegawai = $updateData['id_jadwal_pegawai'];
        $detailJadwal->keterangan_detail_jadwal = $updateData['keterangan_detail_jadwal'];

        if($detailJadwal->save()){
            return response([
                'message' => 'Update Detail Jadwal Success',
                'data' => $detailJadwal
            ], 200);
        }

        return response([
            'message' => 'Update Detail Jadwal Success',
            'data' => null,
        ], 400);
    }
}
