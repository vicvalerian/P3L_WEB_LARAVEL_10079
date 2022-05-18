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
        Schema::create('detail__transaksi_10079s', function (Blueprint $table) {
            $table->string('id_detail_transaksi', 50)->primary(); //varchar(50), pk

            $table->unsignedBigInteger('id_mobil'); //foreign key
            $table->foreign('id_mobil')->references('id_mobil')->on('mobil_10079s'); //foreign key dari tabel mobil

            $table->string('id_driver')->nullable(); //foreign key
            $table->foreign('id_driver')->references('id_driver')->on('driver_10079s'); //foreign key dari tabel driver

            $table->string('id_transaksi'); //foreign key
            $table->foreign('id_transaksi')->references('id_transaksi')->on('transaksi_10079s'); //foreign key dari tabel transaksi
            
            $table->dateTime('tgl_waktu_mulai_sewa'); //date time
            $table->dateTime('tgl_waktu_akhir_sewa'); //date time
            $table->dateTime('tgl_pengembalian')->nullable(); //date
            $table->string('jenis_transaksi', 30); //varchar(30)
            $table->double('rating_driver_transaksi')->nullable(); //double
            $table->double('diskon_transaksi'); //double
            $table->double('denda_transaksi'); //double
            $table->double('jumlah_pembayaran'); //double
            $table->string('bukti_pembayaran')->nullable();
            $table->string('status_transaksi');
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
        Schema::dropIfExists('detail__transaksi_10079s');
    }
};
