<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Validator;
use App\Models\Pegawai_10079;
use App\Models\Detail_Jadwal_10079;
use Illuminate\Support\Facades\Hash;
use DB;

class PegawaiController extends Controller
{
    public function index(){
        // $pegawai = Pegawai_10079::all();

        $pegawai = DB::table('pegawai_10079s') 
                    ->join('jabatan_10079s', 'pegawai_10079s.id_jabatan', '=', 'jabatan_10079s.id_jabatan')
                    ->select('pegawai_10079s.*', 'jabatan_10079s.id_jabatan', 'jabatan_10079s.nama_jabatan')
                    ->get();

        if(count($pegawai)>0){
            return response ([
                'message' => 'Retrieve All Pegawai Success',
                'data' => $pegawai
            ], 200);
        }

        return response([
            'message' => 'Empty',
            'data' => null
        ], 400);
    }

    public function getDetailJadwalPegawai(){
        $pegawai = DB::table('pegawai_10079s')
                        ->leftJoin('detail__jadwal_10079s', 'detail__jadwal_10079s.id_pegawai', '=', 'pegawai_10079s.id_pegawai')
                        ->leftJoin('jadwal_10079s', 'jadwal_10079s.id_jadwal_pegawai', '=', 'detail__jadwal_10079s.id_jadwal_pegawai')
                        ->select('pegawai_10079s.id_pegawai', 'pegawai_10079s.nama_pegawai', 'jadwal_10079s.id_jadwal_pegawai', 'jadwal_10079s.hari_pegawai', 'jadwal_10079s.shift_pegawai', DB::raw('count(detail__jadwal_10079s.id_pegawai) AS jumlah_shift'))
                        ->groupBy('pegawai_10079s.id_pegawai')
                        ->having(DB::raw('count(detail__jadwal_10079s.id_pegawai)'), '<', 6)
                        ->get();

        if(count($pegawai)>0){
            return response ([
                'message' => 'Retrieve All Pegawai Success',
                'data' => $pegawai
            ], 200);
        }

        return response([
            'message' => 'Empty',
            'data' => null
        ], 400);
    }

    public function show($id_pegawai){
        $pegawai = Pegawai_10079::where('id_pegawai', $id_pegawai)->first();

        if(!is_null($pegawai)){
            return response ([
                'message' => 'Retrieve Pegawai Success',
                'data' => $pegawai
            ],200);
        }

        return response([
            'message' => 'Pegawai Not Found',
            'data' => null
        ],404);
    }

    public function store (Request $request){
        $storeData = $request->all();

        $validate = Validator::make($storeData, [
            'id_jabatan' => 'required',
            'nama_pegawai' => 'required|regex:/^[\pL\s]+$/u',
            'alamat_pegawai' => 'required',
            'tgl_lahir_pegawai' => 'required|date:ymd',
            'jenis_kelamin_pegawai' => 'required',
            'email_pegawai' => 'required|email:rfc,dns|unique:pegawai_10079s',
            'notelp_pegawai' => 'required|numeric|digits_between:0,13|starts_with:08',
            'foto_pegawai' => 'required|max:1024|mimes:jpg,png,jpeg|image'
        ]);

        $uploadFotoPegawai = $request->foto_pegawai->store('img_pegawai', ['disk' => 'public']);

        $passwordPegawai = Hash::make($request->tgl_lahir_pegawai);

        if($validate->fails()){
            return response(['message' => $validate->errors()], 400);
        }
            
        $pegawai = Pegawai_10079::create([
            'id_jabatan' => $request->id_jabatan,
            'nama_pegawai' => $request->nama_pegawai,
            'alamat_pegawai' => $request->alamat_pegawai,
            'tgl_lahir_pegawai' => $request->tgl_lahir_pegawai,
            'jenis_kelamin_pegawai' => $request->jenis_kelamin_pegawai,
            'email_pegawai' => $request->email_pegawai,
            'notelp_pegawai' => $request->notelp_pegawai,
            'foto_pegawai' => $uploadFotoPegawai,
            'password_pegawai' => $passwordPegawai,
        ]);
        return response([
           'message' => 'Add Pegawai Success',
            'data' => $pegawai
        ],200);
    }

    public function destroy($id_pegawai){
        $pegawai = Pegawai_10079::where('id_pegawai', $id_pegawai)->first();

        if (is_null($pegawai)) {
            return response([
                'message' => 'Pegawai Not Found',
                'data' => null
            ],404);
        }

        if($pegawai->delete()) {
            return response([
                'message' => 'Delete Pegawai Success',
                'data' => $pegawai
            ],200);
        }

        return response([
            'message' => 'Delete Pegawai Failed',
            'data' => null,
        ],400);
    }

    public function update(Request $request, $id_pegawai){
        $pegawai = Pegawai_10079::where('id_pegawai', $id_pegawai)->first();
        if(is_null($pegawai)){
            return response([
                'message' => 'Pegawai Not Found',
                'data' => null
            ], 404);
        }

        $updateData = $request->all();
        $validate = Validator::make($updateData, [
            'id_jabatan' => 'required',
            'nama_pegawai' => 'required|regex:/^[\pL\s]+$/u',
            'alamat_pegawai' => 'required',
            'tgl_lahir_pegawai' => 'required|date:ymd',
            'jenis_kelamin_pegawai' => 'required',
            'email_pegawai' => ['required', 'email:rfc,dns', Rule::unique('pegawai_10079s')->ignore($pegawai)],
            'notelp_pegawai' => 'required|numeric|digits_between:0,13|starts_with:08',
            'foto_pegawai' => 'max:1024|mimes:jpg,png,jpeg|image',
            'password_pegawai' => 'nullable'
        ]);

        if($validate->fails())
            return response(['message' => $validate->errors()], 400);

        $pegawai->id_jabatan = $updateData['id_jabatan'];
        $pegawai->nama_pegawai = $updateData['nama_pegawai'];
        $pegawai->alamat_pegawai = $updateData['alamat_pegawai'];
        $pegawai->tgl_lahir_pegawai = $updateData['tgl_lahir_pegawai'];
        $pegawai->jenis_kelamin_pegawai = $updateData['jenis_kelamin_pegawai'];
        $pegawai->email_pegawai = $updateData['email_pegawai'];
        $pegawai->notelp_pegawai = $updateData['notelp_pegawai'];
        
        if(isset($request->foto_pegawai)){
            $uploadFotoPegawai = $request->foto_pegawai->store('img_pegawai', ['disk' => 'public']);
            $pegawai->foto_pegawai = $uploadFotoPegawai;
        }

        if(isset($request->password_pegawai)){
            $updateData['password_pegawai'] = bcrypt($request->password_pegawai);
            $pegawai->password_pegawai = $updateData['password_pegawai'];
        }

        if($pegawai->save()){
            return response([
                'message' => 'Update Pegawai Success',
                'data' => $pegawai
            ], 200);
        }

        return response([
            'message' => 'Update Pegawai Success',
            'data' => null,
        ], 400);
    }
}
