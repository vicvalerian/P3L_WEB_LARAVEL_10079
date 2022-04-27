<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Validator;
use DB;
use DateTime;
use App\Models\Detail_Transaksi_10079;
use App\Models\Mobil_10079;
use App\Models\Driver_10079;
use App\Models\Promo_10079;
use App\Models\Transaksi_10079;
use Carbon\Carbon;

class DetailTransaksiController extends Controller
{
    public function index(){
        // $detailTransaksi = Detail_Transaksi_10079::all();

        $detailTransaksi = DB::table('detail__transaksi_10079s') 
                            ->join('transaksi_10079s', 'transaksi_10079s.id_transaksi', '=', 'detail__transaksi_10079s.id_transaksi')
                            ->leftJoin('driver_10079s', 'driver_10079s.id_driver', '=', 'detail__transaksi_10079s.id_driver')
                            ->join('mobil_10079s', 'mobil_10079s.id_mobil', '=', 'detail__transaksi_10079s.id_mobil')
                            ->join('pelanggan_10079s', 'pelanggan_10079s.id_pelanggan', '=', 'transaksi_10079s.id_pelanggan')
                            ->select('detail__transaksi_10079s.*', 
                                    'transaksi_10079s.id_transaksi', 
                                    'driver_10079s.id_driver', 'driver_10079s.nama_driver', 
                                    'mobil_10079s.id_mobil', 'mobil_10079s.plat_mobil',
                                    'pelanggan_10079s.id_pelanggan', 'pelanggan_10079s.nama_pelanggan')
                            ->get();

        if(count($detailTransaksi)>0){
            return response ([
                'message' => 'Retrieve All Detail Transaksi Success',
                'data' => $detailTransaksi
            ], 200);
        }

        return response([
            'message' => 'Empty',
            'data' => null
        ], 400);
    }

    public function show($id_detail_transaksi){
        $detailTransaksi = Detail_Transaksi_10079::where('id_detail_transaksi', $id_detail_transaksi)->first();

        if(!is_null($detailTransaksi)){
            return response ([
                'message' => 'Retrieve Detail Transaksi Success',
                'data' => $detailTransaksi
            ],200);
        }

        return response([
            'message' => 'Detail Transaksi Not Found',
            'data' => null
        ],404);
    }

    public function store (Request $request){
        $storeData = $request->all();
        $transaksi = Transaksi_10079::where('id_transaksi', $request->id_transaksi)->first();
        
        $storeData['tgl_waktu_mulai_sewa'] = Carbon::parse($storeData['tgl_waktu_mulai_sewa'])->format('YYYY-MM-DDThh:mm');
        $storeData['tgl_waktu_akhir_sewa'] = Carbon::parse($storeData['tgl_waktu_akhir_sewa'])->format('YYYY-MM-DDThh:mm');
        $storeData['tgl_pengembalian'] = Carbon::parse($storeData['tgl_pengembalian'])->format('YYYY-MM-DDThh:mm');

        $validate = Validator::make($storeData, [
            'id_mobil' => 'required',
            'id_driver' => 'nullable',
            'id_transaksi' => 'required',
            'tgl_waktu_mulai_sewa' => 'required',
            'tgl_waktu_akhir_sewa' => 'required|after_or_equal:tgl_waktu_mulai_sewa',
            'tgl_pengembalian' => 'required|after_or_equal:tgl_waktu_akhir_sewa',
            'rating_driver_transaksi' => 'nullable|numeric|min:1|max:5'
        ]);

        $database = DB::table('detail__transaksi_10079s')->count();

        if($database == 0){
            if(!is_null($request->id_driver)){
                $id_detail_transaksi = 'TRN'.date('ymd').'01-'.sprintf('%03d',1);
                $jenis_transaksi = 'Dengan Driver';
            }
            else{
                $id_detail_transaksi = 'TRN'.date('ymd').'02-'.sprintf('%03d',1);
                $jenis_transaksi = 'Tanpa Driver';
            }
        }else{
            $get_data = Detail_Transaksi_10079::select(DB::raw('GROUP_CONCAT(distinct SUBSTRING(id_detail_transaksi,-3)) as new_id_detail_transaksi'))->get();

            foreach($get_data as $new_value){
                $find = substr($new_value['new_id_detail_transaksi'], -3);
            }

            $increment = $find + 1;

            if(!is_null($request->id_driver)){
                $id_detail_transaksi = 'TRN'.date('ymd').'01-'.sprintf('%03d', $increment);
                $jenis_transaksi = 'Dengan Driver';
            }
            else{
                $id_detail_transaksi = 'TRN'.date('ymd').'02-'.sprintf('%03d', $increment);
                $jenis_transaksi = 'Tanpa Driver';
            }
        }

        if(!is_null($request->id_driver)){
            $driver = Driver_10079::where('id_driver', $request->id_driver)->first();
            $tarifDriver = $driver->sewa_harian_driver;
        } else{
            $tarifDriver = 0;
        }
        

        if(!is_null($transaksi->id_promo)){
            $id_promo = Promo_10079::where('id_promo', $transaksi->id_promo)->first();
            $diskon = $id_promo->diskon_promo;
        } else{
            $diskon = 0;
        }

        $mobil = Mobil_10079::where('id_mobil', $request->id_mobil)->first();
        $tarifMobil = $mobil->sewa_harian_mobil;

        $tglMulai = $request->tgl_waktu_mulai_sewa;
        $tglAkhir = $request->tgl_waktu_akhir_sewa;
        $tglPengembalian = $request->tgl_pengembalian;
        $dateTimeMulai = new DateTime($tglMulai);
        $dateTimeAkhir = new DateTime($tglAkhir);
        $dateTimePengembalian = new DateTime($tglPengembalian);
        $interval = $dateTimeMulai->diff($dateTimeAkhir);
        $intervalDenda = $dateTimeAkhir->diff($dateTimePengembalian);
        $totalHariSewa = $interval->format('%a');
        $totalJamDenda = $intervalDenda->h;

        if($totalJamDenda >= 3){
            $dendaMobil = $tarifMobil;
            $dendaDriver = $tarifDriver;
        } else{
            $dendaMobil = 0;
            $dendaDriver = 0;
        }

        $dendaTransaksi = $dendaMobil + $dendaDriver;
        $tempJumlahPembayaran = ($tarifMobil * $totalHariSewa) + ($tarifDriver * $totalHariSewa) + $dendaTransaksi;
        $jumlahPembayaran = $tempJumlahPembayaran - ($tempJumlahPembayaran * $diskon);

        if($validate->fails()){
            return response(['message' => $validate->errors()], 400);
        }

        $detailTransaksi = Detail_Transaksi_10079::create([
            'id_detail_transaksi' => $id_detail_transaksi,
            'id_mobil' => $request->id_mobil,
            'id_driver' => $request->id_driver,
            'id_transaksi' => $request->id_transaksi,
            'tgl_waktu_mulai_sewa' => $request->tgl_waktu_mulai_sewa,
            'tgl_waktu_akhir_sewa' => $request->tgl_waktu_akhir_sewa,
            'tgl_pengembalian' => $request->tgl_pengembalian,
            'jenis_transaksi' => $jenis_transaksi,
            'rating_driver_transaksi' => $request->rating_driver_transaksi,
            'denda_transaksi' => $dendaTransaksi,
            'jumlah_pembayaran' => $jumlahPembayaran,
        ]);
        return response([
            'message' => 'Add Detail Transaksi Success',
            'data' => $detailTransaksi
        ],200);
    }

    public function destroy($id_detail_transaksi){
        $detailTransaksi = Detail_Transaksi_10079::where('id_detail_transaksi', $id_detail_transaksi)->first();

        if (is_null($detailTransaksi)) {
            return response([
                'message' => 'Detail Transaksi Not Found',
                'data' => null
            ],404);
        }

        if($detailTransaksi->delete()) {
            return response([
                'message' => 'Delete Detail Transaksi Success',
                'data' => $detailTransaksi
            ],200);
        }

        return response([
            'message' => 'Delete Detail Transaksi Failed',
            'data' => null,
        ],400);
    }

    public function update(Request $request, $id_detail_transaksi){
        $detailTransaksi = Detail_Transaksi_10079::where('id_detail_transaksi', $id_detail_transaksi)->first();
        if(is_null($detailTransaksi)){
            return response([
                'message' => 'Detail Transaksi Not Found',
                'data' => null
            ], 404);
        }

        $transaksi = Transaksi_10079::where('id_transaksi', $detailTransaksi->id_transaksi)->first();

        $updateData = $request->all();

        $detailTransaksi->tgl_waktu_mulai_sewa = Carbon::parse($updateData['tgl_waktu_mulai_sewa'])->format('YYYY-MM-DDThh:mm');
        $detailTransaksi->tgl_waktu_akhir_sewa = Carbon::parse($updateData['tgl_waktu_akhir_sewa'])->format('YYYY-MM-DDThh:mm');
        $detailTransaksi->tgl_pengembalian = Carbon::parse($updateData['tgl_pengembalian'])->format('YYYY-MM-DDThh:mm');

        $validate = Validator::make($updateData, [
            'id_mobil' => 'required',
            'tgl_waktu_mulai_sewa' => 'required',
            'tgl_waktu_akhir_sewa' => 'required|after_or_equal:tgl_waktu_mulai_sewa',
            'tgl_pengembalian' => 'required|after_or_equal:tgl_waktu_akhir_sewa',
            'rating_driver_transaksi' => 'nullable|numeric|min:1|max:5'
        ]);

        if(!is_null($detailTransaksi->id_driver)){
            $driver = Driver_10079::where('id_driver', $detailTransaksi->id_driver)->first();
            $tarifDriver = $driver->sewa_harian_driver;
        } else{
            $tarifDriver = 0;
        }

        if(!is_null($detailTransaksi->id_promo)){
            $id_promo = Promo_10079::where('id_promo', $transaksi->id_promo)->first();
            $diskon = $id_promo->diskon_promo;
        } else{
            $diskon = 0;
        }

        $mobil = Mobil_10079::where('id_mobil', $detailTransaksi->id_mobil)->first();
        $tarifMobil = $mobil->sewa_harian_mobil;

        $tglMulai = $request->tgl_waktu_mulai_sewa;
        $tglAkhir = $request->tgl_waktu_akhir_sewa;
        $tglPengembalian = $request->tgl_pengembalian;
        $dateTimeMulai = new DateTime($tglMulai);
        $dateTimeAkhir = new DateTime($tglAkhir);
        $dateTimePengembalian = new DateTime($tglPengembalian);
        $interval = $dateTimeMulai->diff($dateTimeAkhir);
        $intervalDenda = $dateTimeAkhir->diff($dateTimePengembalian);
        $totalHariSewa = $interval->format('%a');
        $totalJamDenda = $intervalDenda->h;

        if($totalJamDenda >= 3){
            $dendaMobil = $tarifMobil;
            $dendaDriver = $tarifDriver;
        } else{
            $dendaMobil = 0;
            $dendaDriver = 0;
        }

        $dendaTransaksi = $dendaMobil + $dendaDriver;
        $tempJumlahPembayaran = ($tarifMobil * $totalHariSewa) + ($tarifDriver * $totalHariSewa) + $dendaTransaksi;
        $jumlahPembayaran = $tempJumlahPembayaran - ($tempJumlahPembayaran * $diskon);

        if($validate->fails())
            return response(['message' => $validate->errors()], 400);

        $detailTransaksi->id_mobil = $updateData['id_mobil'];
        $detailTransaksi->tgl_waktu_mulai_sewa = $updateData['tgl_waktu_mulai_sewa'];
        $detailTransaksi->tgl_waktu_akhir_sewa = $updateData['tgl_waktu_akhir_sewa'];
        $detailTransaksi->tgl_pengembalian = $updateData['tgl_pengembalian'];

        if(isset($request->rating_driver_transaksi)){
            $detailTransaksi->rating_driver_transaksi = $request->rating_driver_transaksi;
        }

        $detailTransaksi->denda_transaksi = $dendaTransaksi;
        $detailTransaksi->jumlah_pembayaran = $jumlahPembayaran;

        if($detailTransaksi->save()){
            return response([
                'message' => 'Update Detail Transaksi Success',
                'data' => $detailTransaksi
            ], 200);
        }

        return response([
            'message' => 'Update Detail Transaksi Failed',
            'data' => null,
        ], 400);
    }
}
