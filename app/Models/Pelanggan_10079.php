<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Pelanggan_10079 extends Model
{
    use HasFactory;
    public $incrementing = false;
    protected $primaryKey = 'id_pelanggan';

    protected $fillable = [
        'id_pelanggan', 'nama_pelanggan', 'alamat_pelanggan', 'tgl_lahir_pelanggan',
        'jenis_kelamin_pelanggan', 'email_pelanggan', 'notelp_pelanggan', 'no_ktp_pelanggan',
        'no_sim_pelanggan', 'password_pelanggan', 'status_pelanggan'
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
