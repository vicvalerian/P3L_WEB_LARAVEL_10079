<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Driver_10079 extends Model
{
    use HasFactory;
    public $incrementing = false;
    protected $primaryKey = 'id_driver';

    protected $fillable = [
        'id_driver', 'nama_driver', 'alamat_driver', 'tgl_lahir_driver',
        'jenis_kelamin_driver', 'bahasa_driver', 'foto_driver', 'notelp_driver', 
        'email_driver', 'sewa_harian_driver', 'status_driver', 'rating_driver', 'password_driver',
        'sim_driver', 'surat_bebas_napza', 'surat_jiwa_jasmani', 'skck_driver'
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
