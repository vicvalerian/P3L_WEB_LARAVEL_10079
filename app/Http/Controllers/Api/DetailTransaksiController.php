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

    public function showByPelanggan($id_pelanggan){
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
                            ->where('transaksi_10079s.id_pelanggan', '=', $id_pelanggan)
                            ->get();

        if(count($detailTransaksi)>0){
            return response ([
                'message' => 'Retrieve All Detail Transaksi Pelanggan Success',
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

        $validate = Validator::make($storeData, [
            'id_mobil' => 'required',
            // 'id_driver' => 'nullable',
            'id_transaksi' => 'required',
            'tgl_waktu_mulai_sewa' => 'required|after_or_equal:' .$transaksi->tgl_transaksi,
            'tgl_waktu_akhir_sewa' => 'required|after_or_equal:tgl_waktu_mulai_sewa',
            'rating_driver_transaksi' => 'nullable|numeric|min:1|max:5',
            'jenis_transaksi' => 'required',
        ]);

        $database = DB::table('detail__transaksi_10079s')->count();

        if($database == 0){
            if(strcmp($request->jenis_transaksi, 'Dengan Driver') == 0){
                $id_detail_transaksi = 'TRN'.date('ymd').'01-'.sprintf('%03d',1);
                // $jenis_transaksi = 'Dengan Driver';
            }
            else{
                $id_detail_transaksi = 'TRN'.date('ymd').'02-'.sprintf('%03d',1);
                // $jenis_transaksi = 'Tanpa Driver';
            }
        }else{
            $get_data = Detail_Transaksi_10079::select(DB::raw('GROUP_CONCAT(distinct SUBSTRING(id_detail_transaksi,-3)) as new_id_detail_transaksi'))->get();

            foreach($get_data as $new_value){
                $find = substr($new_value['new_id_detail_transaksi'], -3);
            }

            $increment = $find + 1;

            if(strcmp($request->jenis_transaksi, 'Dengan Driver') == 0){
                $id_detail_transaksi = 'TRN'.date('ymd').'01-'.sprintf('%03d', $increment);
                // $jenis_transaksi = 'Dengan Driver';
            }
            else{
                $id_detail_transaksi = 'TRN'.date('ymd').'02-'.sprintf('%03d', $increment);
                // $jenis_transaksi = 'Tanpa Driver';
            }
        }

        $dendaTransaksi = 0;
        $diskonTransaksi = 0;
        $jumlahPembayaran = 0;
        $statusTransaksi = 'Belum Lunas Belum Verifikasi';

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
            'jenis_transaksi' => $request->jenis_transaksi,
            'rating_driver_transaksi' => $request->rating_driver_transaksi,
            'diskon_transaksi' => $diskonTransaksi,
            'denda_transaksi' => $dendaTransaksi,
            'jumlah_pembayaran' => $jumlahPembayaran,
            'status_transaksi' => $statusTransaksi,
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

    //Update untuk CS
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

        $validate = Validator::make($updateData, [
            'id_driver' => 'nullable',
            'tgl_pengembalian' => 'nullable|after_or_equal:' .$detailTransaksi->tgl_waktu_akhir_sewa,
            'status_transaksi' => 'required',
        ]);

        //Jumlah pembayaran, diskon, dan denda dihitung jika tanggal pengembalian sudah diinputkan oleh CS
        if(isset($request->tgl_pengembalian)){
            if(!is_null($detailTransaksi->id_driver)){
                $driver = Driver_10079::where('id_driver', $detailTransaksi->id_driver)->first();
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
    
            $mobil = Mobil_10079::where('id_mobil', $detailTransaksi->id_mobil)->first();
            $tarifMobil = $mobil->sewa_harian_mobil;
    
            $tglMulai = $detailTransaksi->tgl_waktu_mulai_sewa;
            $tglAkhir = $detailTransaksi->tgl_waktu_akhir_sewa;
            $tglPengembalian = $request->tgl_pengembalian;
            $dateTimeMulai = new DateTime($tglMulai);
            $dateTimeAkhir = new DateTime($tglAkhir);
            $dateTimePengembalian = new DateTime($tglPengembalian);
            $interval = $dateTimeMulai->diff($dateTimeAkhir);
            $intervalDenda = $dateTimeAkhir->diff($dateTimePengembalian);
            $totalHariSewa = $interval->format('%a');
            $totalJamDenda = $intervalDenda->h;
            $totalHariDenda = $intervalDenda->d;
    
            if($totalJamDenda >= 3 || $totalHariDenda >= 1){
                $dendaMobil = $tarifMobil;
                $dendaDriver = $tarifDriver;
            } else{
                $dendaMobil = 0;
                $dendaDriver = 0;
            }
    
            $dendaTransaksi = ($dendaMobil + $dendaDriver) * ($totalHariDenda + 1);
            $tempJumlahPembayaran = ($tarifMobil * $totalHariSewa) + ($tarifDriver * $totalHariSewa);
            $diskonTransaksi = $tempJumlahPembayaran * ($diskon/100);
            $jumlahPembayaran = ($tempJumlahPembayaran - $diskonTransaksi) + $dendaTransaksi;
        }else{
            $dendaTransaksi = 0;
            $diskonTransaksi = 0;
            $jumlahPembayaran = 0;
        }

        //fungsi untuk mengubah status mobil dan driver yg digunakan jika transaksi sudah diverif
        if(strcmp($updateData['status_transaksi'], 'Belum Lunas Sudah Verifikasi') == 0){
            Mobil_10079::where('id_mobil', $detailTransaksi->id_mobil)->update([
                'status_mobil' => "Tidak Tersedia",
            ]);

            if(!is_null($detailTransaksi->id_driver)){
                Driver_10079::where('id_driver', $detailTransaksi->id_driver)->update([
                    'status_driver' => "Tidak Tersedia",
                ]);
            }
        } else if(strcmp($updateData['status_transaksi'], 'Sudah Lunas Sudah Verifikasi') == 0){
            Mobil_10079::where('id_mobil', $detailTransaksi->id_mobil)->update([
                'status_mobil' => "Tersedia",
            ]);

            if(!is_null($detailTransaksi->id_driver)){
                Driver_10079::where('id_driver', $detailTransaksi->id_driver)->update([
                    'status_driver' => "Tersedia",
                ]);
            }
        }

        if($validate->fails())
            return response(['message' => $validate->errors()], 400);

        $detailTransaksi->id_driver = $updateData['id_driver'];
        $detailTransaksi->tgl_pengembalian = $updateData['tgl_pengembalian'];
        $detailTransaksi->status_transaksi = $updateData['status_transaksi'];
        $detailTransaksi->diskon_transaksi = $diskonTransaksi;
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

    //Update Detail Transaksi untuk Pelanggan sebelum diverifikasi CS
    public function updateBeforePelanggan(Request $request, $id_detail_transaksi){
        $detailTransaksi = Detail_Transaksi_10079::where('id_detail_transaksi', $id_detail_transaksi)->first();
        if(is_null($detailTransaksi)){
            return response([
                'message' => 'Detail Transaksi Not Found',
                'data' => null
            ], 404);
        }

        $transaksi = Transaksi_10079::where('id_transaksi', $detailTransaksi->id_transaksi)->first();

        $updateData = $request->all();

        $validate = Validator::make($updateData, [
            'id_mobil' => 'nullable',
            'tgl_waktu_mulai_sewa' => 'nullable|after_or_equal:' .$transaksi->tgl_transaksi,
            'tgl_waktu_akhir_sewa' => 'nullable|after_or_equal:tgl_waktu_mulai_sewa',
            'bukti_pembayaran' => 'max:1024|mimes:jpg,png,jpeg|image',
            'jenis_transaksi' => 'nullable',
        ]);

        if($validate->fails())
            return response(['message' => $validate->errors()], 400);

        $detailTransaksi->id_mobil = $updateData['id_mobil'];
        $detailTransaksi->tgl_waktu_mulai_sewa = $updateData['tgl_waktu_mulai_sewa'];
        $detailTransaksi->tgl_waktu_akhir_sewa = $updateData['tgl_waktu_akhir_sewa'];
        $detailTransaksi->jenis_transaksi = $updateData['jenis_transaksi'];

        if(isset($request->bukti_pembayaran)){
            $uploadBuktiPembayaran = $request->bukti_pembayaran->store('img_bukti_pembayaran', ['disk' => 'public']);
            $detailTransaksi->bukti_pembayaran = $uploadBuktiPembayaran;
        }

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

    //Update Detail Transaksi untuk Pelanggan setelah diverifikasi CS dan transaksi telah selesai
    public function updateAfterPelanggan(Request $request, $id_detail_transaksi){
        $detailTransaksi = Detail_Transaksi_10079::where('id_detail_transaksi', $id_detail_transaksi)->first();
        if(is_null($detailTransaksi)){
            return response([
                'message' => 'Detail Transaksi Not Found',
                'data' => null
            ], 404);
        }

        $updateData = $request->all();

        $validate = Validator::make($updateData, [
            'rating_driver_transaksi' => 'nullable|numeric|min:1|max:5',
        ]);

        if($validate->fails())
            return response(['message' => $validate->errors()], 400);

            if(isset($request->rating_driver_transaksi)){
                $detailTransaksi->rating_driver_transaksi = $request->rating_driver_transaksi;
            }

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
