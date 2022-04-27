<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Mobil_10079 extends Model
{
    use HasFactory;
    protected $primaryKey = 'id_mobil';

    protected $fillable = [
        'id_mobil', 'plat_mobil', 'id_pemilik', 'nama_mobil', 'tipe_mobil',
        'jenis_transmisi', 'jenis_bahan_bakar', 'volume_bahan_bakar', 'warna_mobil',
        'kapasitas_penumpang', 'fasilitas_mobil', 'no_stnk', 'kategori_aset',
        'sewa_harian_mobil', 'volume_bagasi'
    ];

    public function Mobil_Pemilik(){
        return $this->belongsTo(Pemilik_10079::class, 'no_ktp_pemilik', 'no_ktp_pemilik');
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
