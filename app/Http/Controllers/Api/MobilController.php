<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Validator;
use App\Models\Mobil_10079;
use DB;

class MobilController extends Controller
{
    public function index(){
        // $mobil = Mobil_10079::all();

        $mobil = DB::table('mobil_10079s') 
                    ->leftJoin('pemilik_10079s', 'mobil_10079s.id_pemilik', '=', 'pemilik_10079s.id_pemilik')
                    ->select('mobil_10079s.*', 'pemilik_10079s.id_pemilik', 'pemilik_10079s.no_ktp_pemilik', 'pemilik_10079s.nama_pemilik')
                    ->get();
        
        if(count($mobil)>0){
            return response ([
                'message' => 'Retrieve All Mobil Success',
                'data' => $mobil
            ], 200);
        }

        return response([
            'message' => 'Empty',
            'data' => null
        ], 400);
    }

    public function show($id_mobil){
        $mobil = Mobil_10079::where('id_mobil', $id_mobil)->first();

        if(!is_null($mobil)){
            return response ([
                'message' => 'Retrieve Mobil Success',
                'data' => $mobil
            ],200);
        }

        return response([
            'message' => 'Mobil Not Found',
            'data' => null
        ],404);
    }

    public function mobilByStatus(){
        $status = 'Tersedia';
        $mobil = Mobil_10079::where('status_mobil', $status)->get();

        if(count($mobil)>0){
            return response ([
                'message' => 'Retrieve All Mobil Success',
                'data' => $mobil
            ], 200);
        }

        return response([
            'message' => 'Empty',
            'data' => null
        ], 400);
    }

    public function store (Request $request){
        $storeData = $request->all();

        $validate = Validator::make($storeData, [
            'plat_mobil' => 'required|unique:mobil_10079s',
            'id_pemilik' => 'nullable',
            'nama_mobil' => 'required',
            'tipe_mobil' => 'required',
            'jenis_transmisi' => 'required',
            'jenis_bahan_bakar' => 'required',
            'volume_bahan_bakar' => 'required',
            'warna_mobil' => 'required',
            'kapasitas_penumpang' => 'required',
            'fasilitas_mobil' => 'required',
            'no_stnk' => 'required',
            'sewa_harian_mobil' => 'required',
            'volume_bagasi' => 'required',
            'foto_mobil' => 'required|max:1024|mimes:jpg,png,jpeg|image',
            'status_mobil' => 'required'
        ]);

        if(!is_null($request->id_pemilik)){
            $kategoriAset = 'Milik Mitra';
        } else{
            $kategoriAset = 'Milik AJR';
        }

        $uploadFotoMobil = $request->foto_mobil->store('img_mobil', ['disk' => 'public']);

        if($validate->fails()){
            return response(['message' => $validate->errors()], 400);
        }
            
        $mobil = Mobil_10079::create([
            'plat_mobil' => $request->plat_mobil,
            'id_pemilik' => $request->id_pemilik,
            'nama_mobil' => $request->nama_mobil,
            'tipe_mobil' => $request->tipe_mobil,
            'jenis_transmisi' => $request->jenis_transmisi,
            'jenis_bahan_bakar' => $request->jenis_bahan_bakar,
            'volume_bahan_bakar' => $request->volume_bahan_bakar,
            'warna_mobil' => $request->warna_mobil,
            'kapasitas_penumpang' => $request->kapasitas_penumpang,
            'fasilitas_mobil' => $request->fasilitas_mobil,
            'no_stnk' => $request->no_stnk,
            'kategori_aset' => $kategoriAset,
            'sewa_harian_mobil' => $request->sewa_harian_mobil,
            'volume_bagasi' => $request->volume_bagasi,
            'foto_mobil' => $uploadFotoMobil,
            'status_mobil' => $request->status_mobil,
        ]);
        return response([
           'message' => 'Add Mobil Success',
            'data' => $mobil
        ],200);
    }

    public function destroy($id_mobil){
        $mobil = Mobil_10079::where('id_mobil', $id_mobil)->first();

        if (is_null($mobil)) {
            return response([
                'message' => 'Mobil Not Found',
                'data' => null
            ],404);
        }

        if($mobil->delete()) {
            return response([
                'message' => 'Delete Mobil Success',
                'data' => $mobil
            ],200);
        }

        return response([
            'message' => 'Delete Mobil Failed',
            'data' => null,
        ],400);
    }

    public function update(Request $request, $id_mobil){
        $mobil = Mobil_10079::where('id_mobil', $id_mobil)->first();
        if(is_null($mobil)){
            return response([
                'message' => 'Mobil Not Found',
                'data' => null
            ], 404);
        }

        $updateData = $request->all();
        $validate = Validator::make($updateData, [
            'id_pemilik' => 'nullable',
            'nama_mobil' => 'required',
            'tipe_mobil' => 'required',
            'jenis_transmisi' => 'required',
            'jenis_bahan_bakar' => 'required',
            'volume_bahan_bakar' => 'required',
            'warna_mobil' => 'required',
            'kapasitas_penumpang' => 'required',
            'fasilitas_mobil' => 'required',
            'no_stnk' => 'required',
            'sewa_harian_mobil' => 'required',
            'volume_bagasi' => 'required',
            'foto_mobil' => 'max:1024|mimes:jpg,png,jpeg|image',
            'status_mobil' => 'required'
        ]);

        if(!is_null($request->id_pemilik)){
            $kategoriAset = 'Milik Mitra';
        } else{
            $kategoriAset = 'Milik AJR';
        }

        if(isset($request->foto_mobil)){
            $uploadFotoMobil = $request->foto_mobil->store('img_mobil', ['disk' => 'public']);
            $mobil->foto_mobil = $uploadFotoMobil;
        }

        if(!is_null($request->id_pemilik)){
            $mobil->id_pemilik = $updateData['id_pemilik'];
        } else{
            $mobil->id_pemilik = null;
        }

        if($validate->fails())
            return response(['message' => $validate->errors()], 400);
        
        $mobil->nama_mobil = $updateData['nama_mobil'];
        $mobil->tipe_mobil = $updateData['tipe_mobil'];
        $mobil->jenis_transmisi = $updateData['jenis_transmisi'];
        $mobil->jenis_bahan_bakar = $updateData['jenis_bahan_bakar'];
        $mobil->volume_bahan_bakar = $updateData['volume_bahan_bakar'];
        $mobil->warna_mobil = $updateData['warna_mobil'];
        $mobil->kapasitas_penumpang = $updateData['kapasitas_penumpang'];
        $mobil->fasilitas_mobil = $updateData['fasilitas_mobil'];
        $mobil->no_stnk = $updateData['no_stnk'];
        $mobil->kategori_aset = $kategoriAset;
        $mobil->sewa_harian_mobil = $updateData['sewa_harian_mobil'];
        $mobil->volume_bagasi = $updateData['volume_bagasi'];
        $mobil->status_mobil = $updateData['status_mobil'];

        if($mobil->save()){
            return response([
                'message' => 'Update Mobil Success',
                'data' => $mobil
            ], 200);
        }

        return response([
            'message' => 'Update Mobil Success',
            'data' => null,
        ], 400);
    }
}
