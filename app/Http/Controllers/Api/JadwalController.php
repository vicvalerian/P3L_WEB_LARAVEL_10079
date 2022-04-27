<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Validator;
use App\Models\Jadwal_10079;

class JadwalController extends Controller
{
    public function index(){
        $jadwal = Jadwal_10079::all();

        if(count($jadwal)>0){
            return response ([
                'message' => 'Retrieve All Jadwal Success',
                'data' => $jadwal
            ], 200);
        }

        return response([
            'message' => 'Empty',
            'data' => null
        ], 400);
    }

    public function show($id_jadwal_pegawai){
        $jadwal = Jadwal_10079::where('id_jadwal_pegawai', $id_jadwal_pegawai)->first();

        if(!is_null($jadwal)){
            return response ([
                'message' => 'Retrieve Jadwal Success',
                'data' => $jadwal
            ],200);
        }

        return response([
            'message' => 'Jadwal Not Found',
            'data' => null
        ],404);
    }

    public function store (Request $request){
        $storeData = $request->all();

        $validate = Validator::make($storeData, [
            'id_jadwal_pegawai' => 'required',
            'shift_pegawai' => 'required',
            'hari_pegawai' => 'required'
        ]);

        if($validate->fails()){
            return response(['message' => $validate->errors()], 400);
        }
            
        $jadwal = Jadwal_10079::create($storeData);
        return response([
           'message' => 'Add Jadwal Success',
            'data' => $jadwal
        ],200);
    }

    public function destroy($id_jadwal_pegawai){
        $jadwal = Jadwal_10079::where('id_jadwal_pegawai', $id_jadwal_pegawai)->first();

        if (is_null($jadwal)) {
            return response([
                'message' => 'Jadwal Not Found',
                'data' => null
            ],404);
        }

        if($jadwal->delete()) {
            return response([
                'message' => 'Delete Jadwal Success',
                'data' => $jadwal
            ],200);
        }

        return response([
            'message' => 'Delete Jadwal Failed',
            'data' => null,
        ],400);
    }

    public function update(Request $request, $id_jadwal_pegawai){
        $jadwal = Jadwal_10079::where('id_jadwal_pegawai', $id_jadwal_pegawai)->first();
        if(is_null($jadwal)){
            return response([
                'message' => 'Jadwal Not Found',
                'data' => null
            ], 404);
        }

        $updateData = $request->all();
        $validate = Validator::make($updateData, [
            'shift_pegawai' => 'required',
            'hari_pegawai' => 'required'
        ]);

        if($validate->fails())
            return response(['message' => $validate->errors()], 400);

        $jadwal->shift_pegawai = $updateData['shift_pegawai'];
        $jadwal->hari_pegawai = $updateData['hari_pegawai'];

        if($jadwal->save()){
            return response([
                'message' => 'Update Jadwal Success',
                'data' => $jadwal
            ], 200);
        }

        return response([
            'message' => 'Update Jadwal Success',
            'data' => null,
        ], 400);
    }
}
