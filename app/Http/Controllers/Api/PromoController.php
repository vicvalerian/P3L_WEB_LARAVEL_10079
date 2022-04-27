<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Validator;
use App\Models\Promo_10079;

class PromoController extends Controller
{
    public function index(){
        $promo = Promo_10079::all();

        if(count($promo)>0){
            return response ([
                'message' => 'Retrieve All Promo Success',
                'data' => $promo
            ], 200);
        }

        return response([
            'message' => 'Empty',
            'data' => null
        ], 400);
    }

    public function show($id_promo){
        $promo = Promo_10079::where('id_promo', $id_promo)->first();

        if(!is_null($promo)){
            return response ([
                'message' => 'Retrieve Promo Success',
                'data' => $promo
            ],200);
        }

        return response([
            'message' => 'Promo Not Found',
            'data' => null
        ],404);
    }

    public function store (Request $request){
        $storeData = $request->all();

        $validate = Validator::make($storeData, [
            'kode_promo' => 'required|unique:promo_10079s',
            'jenis_promo' => 'required',
            'keterangan_promo' => 'required',
            'diskon_promo' => 'required',
            'status_promo' => 'required'
        ]);

        if($validate->fails()){
            return response(['message' => $validate->errors()], 400);
        }
            
        $promo = Promo_10079::create($storeData);
        return response([
           'message' => 'Add Promo Success',
            'data' => $promo
        ],200);
    }

    public function destroy($id_promo){
        $promo = Promo_10079::where('id_promo', $id_promo)->first();

        if (is_null($promo)) {
            return response([
                'message' => 'Promo Not Found',
                'data' => null
            ],404);
        }

        if($promo->delete()) {
            return response([
                'message' => 'Delete Promo Success',
                'data' => $promo
            ],200);
        }

        return response([
            'message' => 'Delete Promo Failed',
            'data' => null,
        ],400);
    }

    public function update(Request $request, $id_promo){
        $promo = Promo_10079::where('id_promo', $id_promo)->first();
        if(is_null($promo)){
            return response([
                'message' => 'Promo Not Found',
                'data' => null
            ], 404);
        }

        $updateData = $request->all();
        $validate = Validator::make($updateData, [
            'kode_promo' => ['required', Rule::unique('promo_10079s')->ignore($promo)],
            'jenis_promo' => 'required',
            'keterangan_promo' => 'required',
            'diskon_promo' => 'required',
            'status_promo' => 'required'
        ]);

        if($validate->fails())
            return response(['message' => $validate->errors()], 400);

        $promo->kode_promo = $updateData['kode_promo'];
        $promo->jenis_promo = $updateData['jenis_promo'];
        $promo->keterangan_promo = $updateData['keterangan_promo'];
        $promo->diskon_promo = $updateData['diskon_promo'];
        $promo->status_promo = $updateData['status_promo'];

        if($promo->save()){
            return response([
                'message' => 'Update Promo Success',
                'data' => $promo
            ], 200);
        }

        return response([
            'message' => 'Update Promo Success',
            'data' => null,
        ], 400);
    }
}
