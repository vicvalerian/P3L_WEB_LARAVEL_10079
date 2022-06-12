<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;

class LaporanController extends Controller
{
    public function getLaporanPenyewaanMobil($from, $to){
        $laporan = DB::table('detail__transaksi_10079s')
                        ->join('transaksi_10079s', 'transaksi_10079s.id_transaksi', '=', 'detail__transaksi_10079s.id_transaksi')
                        ->join('mobil_10079s', 'mobil_10079s.id_mobil', '=', 'detail__transaksi_10079s.id_mobil')
                        ->select('mobil_10079s.tipe_mobil', 'mobil_10079s.nama_mobil', DB::raw('count(detail__transaksi_10079s.id_mobil) AS jumlah_peminjaman'), DB::raw('SUM(DATEDIFF(detail__transaksi_10079s.tgl_waktu_akhir_sewa, detail__transaksi_10079s.tgl_waktu_mulai_sewa)*mobil_10079s.sewa_harian_mobil) AS jumlah_pendapatan_mobil'))
                        ->whereBetween('detail__transaksi_10079s.tgl_waktu_mulai_sewa', [$from, $to])
                        ->groupBy('mobil_10079s.plat_mobil')
                        ->orderBy(DB::raw('SUM(DATEDIFF(detail__transaksi_10079s.tgl_waktu_akhir_sewa, detail__transaksi_10079s.tgl_waktu_mulai_sewa)*mobil_10079s.sewa_harian_mobil)'), 'DESC')
                        ->get();
        
        if(count($laporan)>0){
            return response ([
                'message' => 'Retrieve Laporan Penyewaan Mobil Success',
                'data' => $laporan
            ], 200);
        }

        return response([
            'message' => 'Empty',
            'data' => null
        ], 400);
    }

    public function getLaporanPendapatanTransaksi($from, $to){
        $laporan = DB::table('detail__transaksi_10079s')
                        ->join('transaksi_10079s', 'transaksi_10079s.id_transaksi', '=', 'detail__transaksi_10079s.id_transaksi')
                        ->join('mobil_10079s', 'mobil_10079s.id_mobil', '=', 'detail__transaksi_10079s.id_mobil')
                        ->join('pelanggan_10079s', 'pelanggan_10079s.id_pelanggan', '=', 'transaksi_10079s.id_pelanggan')
                        ->select('pelanggan_10079s.nama_pelanggan', 'mobil_10079s.nama_mobil', 'detail__transaksi_10079s.jenis_transaksi', DB::raw('COUNT(detail__transaksi_10079s.id_detail_transaksi) AS jumlah_transaksi'), DB::raw('SUM(detail__transaksi_10079s.jumlah_pembayaran) AS pendapatan'))
                        ->whereBetween('detail__transaksi_10079s.tgl_waktu_mulai_sewa', [$from, $to])
                        ->groupBy('pelanggan_10079s.nama_pelanggan', 'mobil_10079s.id_mobil', 'detail__transaksi_10079s.jenis_transaksi')
                        ->get();
        
        if(count($laporan)>0){
            return response ([
                'message' => 'Retrieve Laporan Pendapatan Transaksi Success',
                'data' => $laporan
            ], 200);
        }

        return response([
            'message' => 'Empty',
            'data' => null
        ], 400);
    }

    public function getLaporanTop5Driver($from, $to){
        $laporan = DB::table('detail__transaksi_10079s')
                        ->join('transaksi_10079s', 'transaksi_10079s.id_transaksi', '=', 'detail__transaksi_10079s.id_transaksi')
                        ->join('mobil_10079s', 'mobil_10079s.id_mobil', '=', 'detail__transaksi_10079s.id_mobil')
                        ->leftJoin('driver_10079s', 'driver_10079s.id_driver', '=', 'detail__transaksi_10079s.id_driver')
                        ->select('driver_10079s.id_driver', 'driver_10079s.nama_driver', DB::raw('COUNT(driver_10079s.nama_driver) AS jumlah_transaksi'))
                        ->whereBetween('detail__transaksi_10079s.tgl_waktu_mulai_sewa', [$from, $to])
                        ->groupBy('driver_10079s.id_driver')
                        ->orderBy(DB::raw('COUNT(detail__transaksi_10079s.id_detail_transaksi)'), 'DESC')
                        ->limit(5)
                        ->get();
        
        if(count($laporan)>0){
            return response ([
                'message' => 'Retrieve Laporan Top 5 Driver Success',
                'data' => $laporan
            ], 200);
        }

        return response([
            'message' => 'Empty',
            'data' => null
        ], 400);
    }

    public function getLaporanTop5Pelanggan($from, $to){
        $laporan = DB::table('detail__transaksi_10079s')
                        ->join('transaksi_10079s', 'transaksi_10079s.id_transaksi', '=', 'detail__transaksi_10079s.id_transaksi')
                        ->join('mobil_10079s', 'mobil_10079s.id_mobil', '=', 'detail__transaksi_10079s.id_mobil')
                        ->join('pelanggan_10079s', 'pelanggan_10079s.id_pelanggan', '=', 'transaksi_10079s.id_pelanggan')
                        ->select('pelanggan_10079s.nama_pelanggan', DB::raw('COUNT(detail__transaksi_10079s.id_detail_transaksi) AS jumlah_transaksi'))
                        ->whereBetween('detail__transaksi_10079s.tgl_waktu_mulai_sewa', [$from, $to])
                        ->groupBy('pelanggan_10079s.id_pelanggan')
                        ->orderBy(DB::raw('COUNT(detail__transaksi_10079s.id_detail_transaksi)'), 'DESC')
                        ->limit(5)
                        ->get();
        
        if(count($laporan)>0){
            return response ([
                'message' => 'Retrieve Laporan Top 5 Pelanggan Success',
                'data' => $laporan
            ], 200);
        }

        return response([
            'message' => 'Empty',
            'data' => null
        ], 400);
    }

    public function getLaporanPerformaDriver($from, $to){
        $laporan = DB::table('detail__transaksi_10079s')
                        ->join('transaksi_10079s', 'transaksi_10079s.id_transaksi', '=', 'detail__transaksi_10079s.id_transaksi')
                        ->join('mobil_10079s', 'mobil_10079s.id_mobil', '=', 'detail__transaksi_10079s.id_mobil')
                        ->leftJoin('driver_10079s', 'driver_10079s.id_driver', '=', 'detail__transaksi_10079s.id_driver')
                        ->select('driver_10079s.id_driver', 'driver_10079s.nama_driver', DB::raw('COUNT(detail__transaksi_10079s.id_detail_transaksi) AS jumlah_transaksi'), DB::raw('SUM(detail__transaksi_10079s.rating_driver_transaksi) / COUNT(detail__transaksi_10079s.rating_driver_transaksi) AS rerata_rating_driver'))
                        ->whereBetween('detail__transaksi_10079s.tgl_waktu_mulai_sewa', [$from, $to])
                        ->groupBy('driver_10079s.id_driver')
                        ->orderBy(DB::raw('COUNT(driver_10079s.id_driver)'), 'DESC')
                        ->get();
        
        if(count($laporan)>0){
            return response ([
                'message' => 'Retrieve Laporan Performa Driver Success',
                'data' => $laporan
            ], 200);
        }

        return response([
            'message' => 'Empty',
            'data' => null
        ], 400);
    }
}
