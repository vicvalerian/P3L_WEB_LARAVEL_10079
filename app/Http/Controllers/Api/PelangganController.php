<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Validator;
use App\Models\Pelanggan_10079;
use Illuminate\Support\Facades\Hash;

class PelangganController extends Controller
{
    public function index(){
        $pelanggan = Pelanggan_10079::all();

        if(count($pelanggan)>0){
            return response ([
                'message' => 'Retrieve All Pelanggan Success',
                'data' => $pelanggan
            ], 200);
        }

        return response([
            'message' => 'Empty',
            'data' => null
        ], 400);
    }

    public function show($id_pelanggan){
        $pelanggan = Pelanggan_10079::where('id_pelanggan', $id_pelanggan)->first();

        if(!is_null($pelanggan)){
            return response ([
                'message' => 'Retrieve Pelanggan Success',
                'data' => $pelanggan
            ],200);
        }

        return response([
            'message' => 'Pelanggan Not Found',
            'data' => null
        ],404);
    }

    public function store (Request $request){
        $storeData = $request->all();

        $get_data = Pelanggan_10079::orderBy('id_pelanggan','DESC')->first();
        if(is_null($get_data)) {
            $id_pelanggan = 'CUS'.date('ymd').'-'.sprintf('%03d', 1);
        } else {
            $find = substr($get_data->id_pelanggan, -3);
            $increment = $find + 1;
            $id_pelanggan = 'CUS'.date('ymd').'-'.sprintf('%03d', $increment);
        }

        $validate = Validator::make($storeData, [
            'nama_pelanggan' => 'required|regex:/^[\pL\s]+$/u',
            'alamat_pelanggan' => 'required',
            'tgl_lahir_pelanggan' => 'required|date:ymd',
            'jenis_kelamin_pelanggan' => 'required',
            'email_pelanggan' => 'required|email:rfc,dns|unique:pelanggan_10079s',
            'notelp_pelanggan' => 'required|numeric|digits_between:0,13|starts_with:08',
            'no_ktp_pelanggan' => 'required|numeric|digits:16',
            'no_sim_pelanggan' => 'nullable|numeric|digits:13'
        ]);

        $passwordPelanggan = Hash::make($request->tgl_lahir_pelanggan);

        $statusPelanggan = 'Belum Verifikasi';

        if($validate->fails()){
            return response(['message' => $validate->errors()], 400);
        }

        $pelanggan = Pelanggan_10079::create([
            'id_pelanggan' => $id_pelanggan,
            'nama_pelanggan' => $request->nama_pelanggan,
            'alamat_pelanggan' => $request->alamat_pelanggan,
            'tgl_lahir_pelanggan' => $request->tgl_lahir_pelanggan,
            'jenis_kelamin_pelanggan' => $request->jenis_kelamin_pelanggan,
            'email_pelanggan' => $request->email_pelanggan,
            'notelp_pelanggan' => $request->notelp_pelanggan,
            'no_ktp_pelanggan' => $request->no_ktp_pelanggan,
            'no_sim_pelanggan' => $request->no_sim_pelanggan,
            'password_pelanggan' => $passwordPelanggan,
            'status_pelanggan' => $statusPelanggan,
        ]);
        return response([
            'message' => 'Add Pelanggan Success',
            'data' => $pelanggan
        ],200);
    }

    public function destroy($id_pelanggan){
        $pelanggan = Pelanggan_10079::where('id_pelanggan', $id_pelanggan)->first();

        if (is_null($pelanggan)) {
            return response([
                'message' => 'Pelanggan Not Found',
                'data' => null
            ],404);
        }

        if($pelanggan->delete()) {
            return response([
                'message' => 'Delete Pelanggan Success',
                'data' => $pelanggan
            ],200);
        }

        return response([
            'message' => 'Delete Pelanggan Failed',
            'data' => null,
        ],400);
    }

    public function update(Request $request, $id_pelanggan){
        $pelanggan = Pelanggan_10079::where('id_pelanggan', $id_pelanggan)->first();
        if(is_null($pelanggan)){
            return response([
                'message' => 'Pelanggan Not Found',
                'data' => null
            ], 404);
        }

        $updateData = $request->all();
        $validate = Validator::make($updateData, [
            'nama_pelanggan' => 'required|regex:/^[\pL\s]+$/u',
            'alamat_pelanggan' => 'required',
            'tgl_lahir_pelanggan' => 'required|date:ymd',
            'jenis_kelamin_pelanggan' => 'required',
            'email_pelanggan' => ['required', 'email:rfc,dns', Rule::unique('pelanggan_10079s')->ignore($pelanggan)],
            'notelp_pelanggan' => 'required|numeric|digits_between:0,13|starts_with:08',
            'no_ktp_pelanggan' => 'required|numeric|digits:16',
            'no_sim_pelanggan' => 'nullable|max:13',
            'password_pelanggan' => 'nullable',
            'status_pelanggan' => 'required',
        ]);

        if($validate->fails())
            return response(['message' => $validate->errors()], 400);

        $pelanggan->nama_pelanggan = $updateData['nama_pelanggan'];
        $pelanggan->alamat_pelanggan = $updateData['alamat_pelanggan'];
        $pelanggan->tgl_lahir_pelanggan = $updateData['tgl_lahir_pelanggan'];
        $pelanggan->jenis_kelamin_pelanggan = $updateData['jenis_kelamin_pelanggan'];
        $pelanggan->email_pelanggan = $updateData['email_pelanggan'];
        $pelanggan->notelp_pelanggan = $updateData['notelp_pelanggan'];
        $pelanggan->no_ktp_pelanggan = $updateData['no_ktp_pelanggan'];
        $pelanggan->no_sim_pelanggan = $updateData['no_sim_pelanggan'];
        $pelanggan->status_pelanggan = $updateData['status_pelanggan'];
 
        if(isset($request->password_pelanggan)){
            $updateData['password_pelanggan'] = bcrypt($request->password_pelanggan);
            $pelanggan->password_pelanggan = $updateData['password_pelanggan'];
        }

        if($pelanggan->save()){
            return response([
                'message' => 'Update Pelanggan Success',
                'data' => $pelanggan
            ], 200);
        }

        return response([
            'message' => 'Update Pelanggan Success',
            'data' => null,
        ], 400);
    }
}
