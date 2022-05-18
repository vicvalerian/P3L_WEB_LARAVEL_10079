<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Validator;
use App\Models\Transaksi_10079;
use DB;

class TransaksiController extends Controller
{
    public function index(){
        // $transaksi = Transaksi_10079::all();

        $transaksi = DB::table('transaksi_10079s') 
                        ->join('pelanggan_10079s', 'pelanggan_10079s.id_pelanggan', '=', 'transaksi_10079s.id_pelanggan')
                        ->leftJoin('promo_10079s', 'promo_10079s.id_promo', '=', 'transaksi_10079s.id_promo')
                        ->leftJoin('pegawai_10079s', 'pegawai_10079s.id_pegawai', '=', 'transaksi_10079s.id_pegawai')
                        ->select('transaksi_10079s.*', 'pelanggan_10079s.id_pelanggan', 'pelanggan_10079s.nama_pelanggan', 'promo_10079s.id_promo', 'promo_10079s.kode_promo', 'promo_10079s.diskon_promo', 'pegawai_10079s.id_pegawai', 'pegawai_10079s.nama_pegawai')
                        ->get();

        if(count($transaksi)>0){
            return response ([
                'message' => 'Retrieve All Transaksi Success',
                'data' => $transaksi
            ], 200);
        }

        return response([
            'message' => 'Empty',
            'data' => null
        ], 400);
    }

    public function showByPelanggan($id_pelanggan){
        // $transaksi = Transaksi_10079::where('id_pelanggan', $id_pelanggan)->get();

        $transaksi = DB::table('transaksi_10079s') 
                        ->join('pelanggan_10079s', 'pelanggan_10079s.id_pelanggan', '=', 'transaksi_10079s.id_pelanggan')
                        ->leftJoin('promo_10079s', 'promo_10079s.id_promo', '=', 'transaksi_10079s.id_promo')
                        ->leftJoin('pegawai_10079s', 'pegawai_10079s.id_pegawai', '=', 'transaksi_10079s.id_pegawai')
                        ->select('transaksi_10079s.*', 'pelanggan_10079s.id_pelanggan', 'pelanggan_10079s.nama_pelanggan', 'promo_10079s.id_promo', 'promo_10079s.kode_promo', 'promo_10079s.diskon_promo', 'pegawai_10079s.id_pegawai', 'pegawai_10079s.nama_pegawai')
                        ->where('transaksi_10079s.id_pelanggan', '=', $id_pelanggan)
                        ->get();

        if(count($transaksi)>0){
            return response ([
                'message' => 'Retrieve All Transaksi Pelanggan Success',
                'data' => $transaksi
            ], 200);
        }

        return response([
            'message' => 'Empty',
            'data' => null
        ], 400);
    }

    public function showVerifiedByPelanggan($id_pelanggan){
        // $transaksi = Transaksi_10079::where('id_pelanggan', $id_pelanggan)->get();

        $transaksi = DB::table('transaksi_10079s') 
                        ->join('pelanggan_10079s', 'pelanggan_10079s.id_pelanggan', '=', 'transaksi_10079s.id_pelanggan')
                        ->leftJoin('promo_10079s', 'promo_10079s.id_promo', '=', 'transaksi_10079s.id_promo')
                        ->leftJoin('pegawai_10079s', 'pegawai_10079s.id_pegawai', '=', 'transaksi_10079s.id_pegawai')
                        ->select('transaksi_10079s.*', 'pelanggan_10079s.id_pelanggan', 'pelanggan_10079s.nama_pelanggan', 'promo_10079s.id_promo', 'promo_10079s.kode_promo', 'promo_10079s.diskon_promo', 'pegawai_10079s.id_pegawai', 'pegawai_10079s.nama_pegawai')
                        ->where('transaksi_10079s.id_pelanggan', '=', $id_pelanggan)
                        ->whereNotNull('transaksi_10079s.id_pegawai')
                        ->get();

        if(count($transaksi)>0){
            return response ([
                'message' => 'Retrieve All Transaksi Pelanggan Success',
                'data' => $transaksi
            ], 200);
        }

        return response([
            'message' => 'Empty',
            'data' => null
        ], 400);
    }

    public function show($id_transaksi){
        $transaksi = Transaksi_10079::where('id_transaksi', $id_transaksi)->first();

        if(!is_null($transaksi)){
            return response ([
                'message' => 'Retrieve Transaksi Success',
                'data' => $transaksi
            ],200);
        }

        return response([
            'message' => 'Transaksi Not Found',
            'data' => null
        ],404);
    }

    public function store (Request $request){
        $storeData = $request->all();

        $validate = Validator::make($storeData, [
            'id_pelanggan' => 'required',
            'id_promo' => 'nullable',
            'id_pegawai' => 'nullable',
            'tgl_transaksi' => 'required|date:ymd',
            'metode_pembayaran' => 'required',
        ]);

        $get_data = Transaksi_10079::orderBy('id_transaksi','DESC')->first();
        if(is_null($get_data)) {
            $id_transaksi = 'NOTA'.date('dmy').'00-'.sprintf('%03d', 1);
        } else {
            $find = substr($get_data->id_transaksi, -3);
            $increment = $find + 1;
            $id_transaksi = 'NOTA'.date('dmy').'00-'.sprintf('%03d', $increment);
        }   

        if($validate->fails()){
            return response(['message' => $validate->errors()], 400);
        }

        $transaksi = Transaksi_10079::create([
            'id_transaksi' => $id_transaksi,
            'id_pelanggan' => $request->id_pelanggan,
            'id_promo' => $request->id_promo,
            'id_pegawai' => $request->id_pegawai,
            'tgl_transaksi' => $request->tgl_transaksi,
            'metode_pembayaran' => $request->metode_pembayaran,
        ]);
        return response([
            'message' => 'Add Transaksi Success',
            'data' => $transaksi
        ],200);
    }

    public function destroy($id_transaksi){
        $transaksi = Transaksi_10079::where('id_transaksi', $id_transaksi)->first();

        if (is_null($transaksi)) {
            return response([
                'message' => 'Transaksi Not Found',
                'data' => null
            ],404);
        }

        if($transaksi->delete()) {
            return response([
                'message' => 'Delete Transaksi Success',
                'data' => $transaksi
            ],200);
        }

        return response([
            'message' => 'Delete Transaksi Failed',
            'data' => null,
        ],400);
    }

    public function update(Request $request, $id_transaksi){
        $transaksi = Transaksi_10079::where('id_transaksi', $id_transaksi)->first();
        if(is_null($transaksi)){
            return response([
                'message' => 'Transaksi Not Found',
                'data' => null
            ], 404);
        }

        $updateData = $request->all();
        $validate = Validator::make($updateData, [
            'id_pelanggan' => 'required',
            'id_pegawai' => 'required',
            'tgl_transaksi' => 'required|date:ymd',
            'metode_pembayaran' => 'required',
        ]);

        if($validate->fails())
            return response(['message' => $validate->errors()], 400);

        $transaksi->id_pelanggan = $updateData['id_pelanggan'];
        $transaksi->id_pegawai = $updateData['id_pegawai'];
        $transaksi->tgl_transaksi = $updateData['tgl_transaksi'];
        $transaksi->metode_pembayaran = $updateData['metode_pembayaran'];

        if($transaksi->save()){
            return response([
                'message' => 'Update Transaksi Success',
                'data' => $transaksi
            ], 200);
        }

        return response([
            'message' => 'Update Transaksi Success',
            'data' => null,
        ], 400);
    }

    public function updatePelanggan(Request $request, $id_transaksi){
        $transaksi = Transaksi_10079::where('id_transaksi', $id_transaksi)->first();
        if(is_null($transaksi)){
            return response([
                'message' => 'Transaksi Not Found',
                'data' => null
            ], 404);
        }

        $updateData = $request->all();
        $validate = Validator::make($updateData, [
            'id_promo' => 'nullable',
            'tgl_transaksi' => 'required|date:ymd',
            'metode_pembayaran' => 'required',
        ]);

        if($validate->fails())
            return response(['message' => $validate->errors()], 400);

        $transaksi->id_promo = $updateData['id_promo'];
        $transaksi->tgl_transaksi = $updateData['tgl_transaksi'];
        $transaksi->metode_pembayaran = $updateData['metode_pembayaran'];

        if($transaksi->save()){
            return response([
                'message' => 'Update Transaksi Success',
                'data' => $transaksi
            ], 200);
        }

        return response([
            'message' => 'Update Transaksi Success',
            'data' => null,
        ], 400);
    }
}
