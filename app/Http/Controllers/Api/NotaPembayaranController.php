<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PDF;

use App\Models\Pelanggan_10079;
use App\Models\Pegawai_10079;
use App\Models\Driver_10079;
use App\Models\Promo_10079;
use App\Models\Mobil_10079;
use App\Models\Transaksi_10079;
use App\Models\Detail_Transaksi_10079;
use DB;
use DateTime;

class NotaPembayaranController extends Controller
{
    public function generatePDF($id_detail_transaksi){
        $nota = DB::table('detail__transaksi_10079s')
            ->join('transaksi_10079s', 'transaksi_10079s.id_transaksi', '=', 'detail__transaksi_10079s.id_transaksi')
            ->leftJoin('driver_10079s', 'detail__transaksi_10079s.id_driver', '=', 'driver_10079s.id_driver')
            ->join('mobil_10079s', 'detail__transaksi_10079s.id_mobil', '=', 'mobil_10079s.id_mobil')
            ->join('pelanggan_10079s', 'transaksi_10079s.id_pelanggan', '=', 'pelanggan_10079s.id_pelanggan')
            ->leftJoin('promo_10079s', 'transaksi_10079s.id_promo', '=', 'promo_10079s.id_promo')
            ->join('pegawai_10079s', 'transaksi_10079s.id_pegawai', '=', 'pegawai_10079s.id_pegawai')
            ->select(
                'transaksi_10079s.id_transaksi AS idTransaksi',
                'transaksi_10079s.tgl_transaksi AS tglTransaksi',
                'pelanggan_10079s.nama_pelanggan AS namaPelanggan',
                'pegawai_10079s.nama_pegawai AS namaPegawai',
                'driver_10079s.nama_driver AS namaDriver',
                'promo_10079s.kode_promo AS kodePromo',
                'detail__transaksi_10079s.id_detail_transaksi AS idDetTransaksi',
                'detail__transaksi_10079s.tgl_waktu_mulai_sewa AS tglMulai',
                'detail__transaksi_10079s.tgl_waktu_akhir_sewa AS tglAkhir',
                'detail__transaksi_10079s.tgl_pengembalian AS tglKembali',
                'mobil_10079s.nama_mobil AS namaMobil',
                'detail__transaksi_10079s.diskon_transaksi AS diskon',
                'detail__transaksi_10079s.denda_transaksi AS denda',
                'detail__transaksi_10079s.jumlah_pembayaran AS total',
                'mobil_10079s.sewa_harian_mobil AS sewaMobil',
                'driver_10079s.sewa_harian_driver AS sewaDriver',
                'promo_10079s.diskon_promo AS diskonPromo')
            ->where('detail__transaksi_10079s.id_detail_transaksi', '=', $id_detail_transaksi)
            ->first();

        $tanggalMulai = $nota->tglMulai;
        $tanggalAkhir = $nota->tglAkhir;
        $tanggalPengembalian = $nota->tglKembali;
        $dateTimeMulai = new DateTime($tanggalMulai);
        $dateTimeAkhir = new DateTime($tanggalAkhir);
        $dateTimePengembalian = new DateTime($tanggalPengembalian);
        $interval = $dateTimeMulai->diff($dateTimeAkhir);
        $totalHariSewa = $interval->format('%a');

        $subTotalMobil = $totalHariSewa * $nota->sewaMobil;
        $subTotalDriver =  $totalHariSewa * $nota->sewaDriver;
        $subTotalMobilDriver = $subTotalMobil + $subTotalDriver;
    
        $data = [
            'title1' => 'Nota Transaksi',
            'title2' => 'Atma Jogja Rental',
            'idTransaksi' => $nota->idTransaksi,
            'tglTransaksi' => $nota->tglTransaksi,
            'namaPelanggan' => $nota->namaPelanggan,
            'namaPegawai' => $nota->namaPegawai,
            'namaDriver' => $nota->namaDriver,
            'kodePromo' => $nota->kodePromo,
            'idDetTransaksi' => $nota->idDetTransaksi,
            'tglMulai' => $nota->tglMulai,
            'tglAkhir' => $nota->tglAkhir,
            'tglKembali' => $nota->tglKembali,
            'namaMobil' => $nota->namaMobil,
            'sewaMobil' => $nota->sewaMobil,
            'sewaDriver' => $nota->sewaDriver,
            'totalHariSewa' => $totalHariSewa,
            'subTotalMobil' => $subTotalMobil,
            'subTotalDriver' => $subTotalDriver,
            'subTotalMobilDriver' => $subTotalMobilDriver,
            'diskon' => $nota->diskon,
            'denda' => $nota->denda,
            'total' => $nota->total,
        ];
          
        $pdf = PDF::loadView('notapembayaran', $data);
    
        return $pdf->download('Nota Pembayaran.pdf');
        // return $pdf->stream('Nota Pembayaran.pdf');
    }
}
