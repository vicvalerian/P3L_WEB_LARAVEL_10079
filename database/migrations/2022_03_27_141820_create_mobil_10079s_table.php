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
        Schema::create('mobil_10079s', function (Blueprint $table) {
            $table->id('id_mobil');
            $table->string('plat_mobil', 10); //varchar(10), primary key

            $table->unsignedBigInteger('id_pemilik')->nullable(); //varchar(16), foreign key
            $table->foreign('id_pemilik')->references('id_pemilik')->on('pemilik_10079s'); //foreign key dari tabel pemilik
            
            $table->string('nama_mobil', 50); //varchar(50)
            $table->string('tipe_mobil', 30); //varchar(30)
            $table->string('jenis_transmisi', 30); //varchar(30)
            $table->string('jenis_bahan_bakar', 30); //varchar(30)
            $table->double('volume_bahan_bakar'); //double
            $table->string('warna_mobil', 20); //varchar(20)
            $table->integer('kapasitas_penumpang'); //integer
            $table->string('fasilitas_mobil'); //varchar(255)
            $table->string('no_stnk', 20); //varchar(20)
            $table->string('kategori_aset', 20); //varchar(20)
            $table->double('sewa_harian_mobil'); //double
            $table->integer('volume_bagasi'); //integer
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
        Schema::dropIfExists('mobil_10079s');
    }
};
