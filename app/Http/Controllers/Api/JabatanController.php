<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Validator;
use App\Models\Jabatan_10079;

class JabatanController extends Controller
{
    public function index(){
        $jabatan = Jabatan_10079::all();

        if(count($jabatan)>0){
            return response ([
                'message' => 'Retrieve All Jabatan Success',
                'data' => $jabatan
            ], 200);
        }

        return response([
            'message' => 'Empty',
            'data' => null
        ], 400);
    }

    public function show($id_jabatan){
        $jabatan = Jabatan_10079::where('id_jabatan', $id_jabatan)->first();

        if(!is_null($jabatan)){
            return response ([
                'message' => 'Retrieve Jabatan Success',
                'data' => $jabatan
            ],200);
        }

        return response([
            'message' => 'Jabatan Not Found',
            'data' => null
        ],404);
    }

    public function store (Request $request){
        $storeData = $request->all();

        $validate = Validator::make($storeData, [
            'nama_jabatan' => 'required|regex:/^[\pL\s]+$/u'
        ]);

        if($validate->fails())
            return response(['message' => $validate->errors()], 400);
            $jabatan = Jabatan_10079::create($storeData);
            return response([
                'message' => 'Add Jabatan Success',
                'data' => $jabatan
            ],200);
    }

    public function destroy($id_jabatan){
        $jabatan = Jabatan_10079::where('id_jabatan', $id_jabatan)->first();

        if (is_null($jabatan)) {
            return response([
                'message' => 'Jabatan Not Found',
                'data' => null
            ],404);
        }

        if($jabatan->delete()) {
            return response([
                'message' => 'Delete Jabatan Success',
                'data' => $jabatan
            ],200);
        }

        return response([
            'message' => 'Delete Jabatan Failed',
            'data' => null,
        ],400);
    }

    public function update(Request $request, $id_jabatan){
        $jabatan = Jabatan_10079::where('id_jabatan', $id_jabatan)->first();
        if(is_null($jabatan)){
            return response([
                'message' => 'Jabatan Not Found',
                'data' => null
            ], 404);
        }

        $updateData = $request->all();
        $validate = Validator::make($updateData, [
            'nama_jabatan' => 'required|regex:/^[\pL\s]+$/u'
        ]);

        if($validate->fails())
            return response(['message' => $validate->errors()], 400);

        $jabatan->nama_jabatan = $updateData['nama_jabatan'];

        if($jabatan->save()){
            return response([
                'message' => 'Update Jabatan Success',
                'data' => $jabatan
            ], 200);
        }

        return response([
            'message' => 'Update Jabatan Success',
            'data' => null,
        ], 400);
    }
}
