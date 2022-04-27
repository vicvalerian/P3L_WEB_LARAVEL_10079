<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Detail_Jadwal_10079 extends Model
{
    use HasFactory;
    protected $primaryKey = 'id_detail_jadwal';

    protected $fillable = [
        'id_detail_jadwal', 'id_pegawai', 'id_jadwal_pegawai', 'keterangan_detail_jadwal'
    ];

    public function DetailJadwal_Pegawai(){
        return $this->belongsTo(Pegawai_10079::class, 'id_pegawai', 'id_pegawai');
    }

    public function DetailJadwal_JadwalPegawai(){
        return $this->belongsTo(Jadwal_10079::class, 'id_jadwal_pegawai', 'id_jadwal_pegawai');
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
