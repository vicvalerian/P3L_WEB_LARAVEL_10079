<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Detail_Transaksi_10079 extends Model
{
    use HasFactory;
    public $incrementing = false;
    protected $primaryKey = 'id_detail_transaksi';

    protected $fillable = [
        'id_detail_transaksi', 'id_mobil', 'id_driver', 'id_transaksi',
        'tgl_waktu_mulai_sewa', 'tgl_waktu_akhir_sewa', 'tgl_pengembalian', 'jenis_transaksi',
        'rating_driver_transaksi', 'denda_transaksi', 'jumlah_pembayaran'
    ];

    public function DetailTransaksi_Mobil(){
        return $this->belongsTo(Mobil_10079::class, 'plat_mobil', 'plat_mobil');
    }

    public function DetailTransaksi_Driver(){
        return $this->belongsTo(Driver_10079::class, 'id_driver', 'id_driver');
    }

    public function DetailTransaksi_Transaksi(){
        return $this->belongsTo(Transaksi_10079::class, 'id_transaksi', 'id_transaksi');
    }

    public function getCreatedAtAttribute(){
        if(!is_null($this->attributes['created_at'])) {
            return Carbon::parse($this->attributes['created_at'])->format('Y-m-d H:i:s');
        }
    }

    public function getUpdatedAtAttribute(){
        if(!is_null($this->attributes['updated_at'])){
            return Carbon::parse($this->attributes['updated_at'])->format('Y-m-d H:i:s');
        }
    }
}
