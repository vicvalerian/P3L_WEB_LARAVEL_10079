<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pemilik_10079s', function (Blueprint $table) {
            $table->id('id_pemilik');
            $table->string('no_ktp_pemilik', 16); //varchar(16), primary key
            $table->string('nama_pemilik', 50); //varchar(50)
            $table->string('alamat_pemilik'); //varchar(255)
            $table->string('notelp_pemilik', 14); //varchar(14)
            $table->date('periode_kontrak_mulai'); //date
            $table->date('periode_kontrak_akhir'); //date
            $table->date('tgl_servis_terakhir'); //date
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pemilik_10079s');
    }
};
