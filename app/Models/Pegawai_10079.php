<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Pegawai_10079 extends Model
{
    use HasFactory;
    protected $primaryKey = 'id_pegawai';

    protected $fillable = [
        'id_pegawai', 'id_jabatan', 'nama_pegawai', 'alamat_pegawai',
        'tgl_lahir_pegawai', 'jenis_kelamin_pegawai', 'email_pegawai', 'notelp_pegawai',
        'foto_pegawai', 'password_pegawai'
    ];

    public function Pegawai_Jabatan(){
        return $this->belongsTo(Jabatan_10079::class, 'id_jabatan', 'id_jabatan');
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
