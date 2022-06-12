<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\JabatanController;
use App\Http\Controllers\Api\PromoController;
use App\Http\Controllers\Api\PemilikController;
use App\Http\Controllers\Api\DriverController;
use App\Http\Controllers\Api\PelangganController;
use App\Http\Controllers\Api\JadwalController;
use App\Http\Controllers\Api\MobilController;
use App\Http\Controllers\Api\PegawaiController;
use App\Http\Controllers\Api\DetailJadwalController;
use App\Http\Controllers\Api\TransaksiController;
use App\Http\Controllers\Api\DetailTransaksiController;

//Tambahan PDF
use App\Http\Controllers\Api\NotaPembayaranController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

//Login
Route::post('login', 'App\Http\Controllers\Api\LoginController@login');
Route::post('loginMobile', 'App\Http\Controllers\Api\LoginController@loginMobile');

//Jabatan
Route::get('jabatan', 'App\Http\Controllers\Api\JabatanController@index');
Route::get('jabatan/{id_jabatan}', 'App\Http\Controllers\Api\JabatanController@show');
Route::post('jabatan', 'App\Http\Controllers\Api\JabatanController@store');
Route::post('jabatan/{id_jabatan}', [JabatanController::class,'update']);
Route::delete('jabatan/{id_jabatan}', 'App\Http\Controllers\Api\JabatanController@destroy');

//Promo
Route::get('promo', 'App\Http\Controllers\Api\PromoController@index');
Route::get('promo/{id_promo}', 'App\Http\Controllers\Api\PromoController@show');
Route::get('statusPromo', 'App\Http\Controllers\Api\PromoController@indexByStatus');
Route::post('promo', 'App\Http\Controllers\Api\PromoController@store');
Route::post('promo/{id_promo}', [PromoController::class,'update']);
Route::delete('promo/{id_promo}', 'App\Http\Controllers\Api\PromoController@destroy');

//Pemilik
Route::get('pemilik', 'App\Http\Controllers\Api\PemilikController@index');
Route::get('kontrakMobil', 'App\Http\Controllers\Api\PemilikController@getKontrakMobil');
Route::get('pemilik/{no_ktp_pemilik}', 'App\Http\Controllers\Api\PemilikController@show');
Route::post('pemilik', 'App\Http\Controllers\Api\PemilikController@store');
Route::post('pemilik/{no_ktp_pemilik}', [PemilikController::class,'update']);
Route::delete('pemilik/{no_ktp_pemilik}', 'App\Http\Controllers\Api\PemilikController@destroy');

//Driver
Route::get('driver', 'App\Http\Controllers\Api\DriverController@index');
Route::get('driver/{id_driver}', 'App\Http\Controllers\Api\DriverController@show');
Route::get('statusDriver', 'App\Http\Controllers\Api\DriverController@driverByStatus');
Route::get('transaksiDriver', 'App\Http\Controllers\Api\DriverController@driverByTransaksi');
Route::post('driver', 'App\Http\Controllers\Api\DriverController@store');
Route::post('driver/{id_driver}', [DriverController::class,'update']);
Route::post('driverMobile/{id_driver}', [DriverController::class,'updateDriverMobile']);
Route::delete('driver/{id_driver}', 'App\Http\Controllers\Api\DriverController@destroy');

//Pelanggan
Route::get('pelanggan', 'App\Http\Controllers\Api\PelangganController@index');
Route::get('pelanggan/{id_pelanggan}', 'App\Http\Controllers\Api\PelangganController@show');
Route::post('pelanggan', 'App\Http\Controllers\Api\PelangganController@store');
Route::post('pelanggan/{id_pelanggan}', [PelangganController::class,'update']);
Route::delete('pelanggan/{id_pelanggan}', 'App\Http\Controllers\Api\PelangganController@destroy');

//Jadwal
Route::get('jadwal', 'App\Http\Controllers\Api\JadwalController@index');
Route::get('jadwal/{id_jadwal_pegawai}', 'App\Http\Controllers\Api\JadwalController@show');
Route::post('jadwal', 'App\Http\Controllers\Api\JadwalController@store');
Route::post('jadwal/{id_jadwal_pegawai}', [JadwalController::class,'update']);
Route::delete('jadwal/{id_jadwal_pegawai}', 'App\Http\Controllers\Api\JadwalController@destroy');

//Mobil
Route::get('mobil', 'App\Http\Controllers\Api\MobilController@index');
Route::get('mobil/{plat_mobil}', 'App\Http\Controllers\Api\MobilController@show');
Route::get('statusMobil', 'App\Http\Controllers\Api\MobilController@mobilByStatus');
Route::post('mobil', 'App\Http\Controllers\Api\MobilController@store');
Route::post('mobil/{plat_mobil}', [MobilController::class,'update']);
Route::delete('mobil/{plat_mobil}', 'App\Http\Controllers\Api\MobilController@destroy');

//Pegawai
Route::get('pegawai', 'App\Http\Controllers\Api\PegawaiController@index');
Route::get('detailJadwalPegawai', 'App\Http\Controllers\Api\PegawaiController@getDetailJadwalPegawai');
Route::get('pegawai/{id_pegawai}', 'App\Http\Controllers\Api\PegawaiController@show');
Route::post('pegawai', 'App\Http\Controllers\Api\PegawaiController@store');
Route::post('pegawai/{id_pegawai}', [PegawaiController::class,'update']);
Route::delete('pegawai/{id_pegawai}', 'App\Http\Controllers\Api\PegawaiController@destroy');

//Detail Jadwal
Route::get('detailJadwal', 'App\Http\Controllers\Api\DetailJadwalController@index');
Route::get('detailJadwalPegawai/{id_pegawai}', 'App\Http\Controllers\Api\DetailJadwalController@showByPegawai');
Route::get('detailJadwal/{id_detail_jadwal}', 'App\Http\Controllers\Api\DetailJadwalController@show');
Route::post('detailJadwal', 'App\Http\Controllers\Api\DetailJadwalController@store');
Route::post('detailJadwal/{id_detail_jadwal}', [DetailJadwalController::class,'update']);
Route::delete('detailJadwal/{id_detail_jadwal}', 'App\Http\Controllers\Api\DetailJadwalController@destroy');

//Transaksi
Route::get('transaksi', 'App\Http\Controllers\Api\TransaksiController@index');
Route::get('transaksiPelanggan/{id_pelanggan}', 'App\Http\Controllers\Api\TransaksiController@showByPelanggan');
Route::get('transaksiVerifPelanggan/{id_pelanggan}', 'App\Http\Controllers\Api\TransaksiController@showVerifiedByPelanggan');
Route::get('transaksi/{id_transaksi}', 'App\Http\Controllers\Api\TransaksiController@show');
Route::post('transaksi', 'App\Http\Controllers\Api\TransaksiController@store');
Route::post('transaksi/{id_transaksi}', [TransaksiController::class,'update']);
Route::delete('transaksi/{id_transaksi}', 'App\Http\Controllers\Api\TransaksiController@destroy');

Route::post('transaksiPelanggan/{id_transaksi}', [TransaksiController::class,'updatePelanggan']);

//Detail Transaksi
Route::get('detailTransaksi', 'App\Http\Controllers\Api\DetailTransaksiController@index');
Route::get('detailTransaksiPelanggan/{id_pelanggan}', 'App\Http\Controllers\Api\DetailTransaksiController@showByPelanggan');
Route::get('detailTransaksiPelangganMobile/{id_pelanggan}', 'App\Http\Controllers\Api\DetailTransaksiController@showByPelangganMobile');
Route::get('detailTransaksiDriver/{id_driver}', 'App\Http\Controllers\Api\DetailTransaksiController@showByDriver');
Route::get('detailTransaksi/{id_detail_transaksi}', 'App\Http\Controllers\Api\DetailTransaksiController@show');
Route::post('detailTransaksi', 'App\Http\Controllers\Api\DetailTransaksiController@store');
Route::post('detailTransaksi/{id_detail_transaksi}', [DetailTransaksiController::class,'update']);
Route::delete('detailTransaksi/{id_detail_transaksi}', 'App\Http\Controllers\Api\DetailTransaksiController@destroy');

Route::post('detailTransaksiBeforePelanggan/{id_detail_transaksi}', [DetailTransaksiController::class,'updateBeforePelanggan']);
Route::post('detailTransaksiAfterPelanggan/{id_detail_transaksi}', [DetailTransaksiController::class,'updateAfterPelanggan']);

//PDF Nota Pembayaran
Route::get('generate-pdf/{id_detail_transaksi}', [NotaPembayaranController::class, 'generatePDF']);

//Laporan
Route::get('laporanPenyewaanMobil/{from}/{to}', 'App\Http\Controllers\Api\LaporanController@getLaporanPenyewaanMobil');
Route::get('laporanPendapatanTransaksi/{from}/{to}', 'App\Http\Controllers\Api\LaporanController@getLaporanPendapatanTransaksi');
Route::get('laporanTop5Driver/{from}/{to}', 'App\Http\Controllers\Api\LaporanController@getLaporanTop5Driver');
Route::get('laporanTop5Pelanggan/{from}/{to}', 'App\Http\Controllers\Api\LaporanController@getLaporanTop5Pelanggan');
Route::get('laporanPerformaDriver/{from}/{to}', 'App\Http\Controllers\Api\LaporanController@getLaporanPerformaDriver');