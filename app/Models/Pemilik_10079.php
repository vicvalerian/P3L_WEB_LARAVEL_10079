<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Pemilik_10079 extends Model
{
    use HasFactory;
    protected $primaryKey = 'id_pemilik';

    protected $fillable = [
        'id_pemilik', 'no_ktp_pemilik', 'nama_pemilik', 'alamat_pemilik', 'notelp_pemilik',
        'periode_kontrak_mulai', 'periode_kontrak_akhir', 'tgl_servis_terakhir'
    ];

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
