<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Transaksi_10079 extends Model
{
    use HasFactory;
    public $incrementing = false;
    protected $primaryKey = 'id_transaksi';

    protected $fillable = [
        'id_transaksi', 'id_pelanggan', 'id_promo', 'id_pegawai',
        'tgl_transaksi', 'metode_pembayaran'
    ];

    public function Transaksi_Pelanggan(){
        return $this->belongsTo(Pelanggan_10079::class, 'id_pelanggan', 'id_pelanggan');
    }

    public function Transaksi_Promo(){
        return $this->belongsTo(Promo_10079::class, 'id_promo', 'id_promo');
    }

    public function Transaksi_Pegawai(){
        return $this->belongsTo(Pegawai_10079::class, 'id_pegawai', 'id_pegawai');
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
