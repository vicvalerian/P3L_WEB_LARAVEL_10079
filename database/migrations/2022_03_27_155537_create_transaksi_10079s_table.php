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
        Schema::create('transaksi_10079s', function (Blueprint $table) {
            $table->string('id_transaksi', 50)->primary(); //varchar(50), pk
            
            $table->string('id_pelanggan'); //foreign key
            $table->foreign('id_pelanggan')->references('id_pelanggan')->on('pelanggan_10079s'); //foreign key dari tabel pelanggan

            $table->unsignedBigInteger('id_promo')->nullable(); //foreign key
            $table->foreign('id_promo')->references('id_promo')->on('promo_10079s'); //foreign key dari tabel promo

            $table->unsignedBigInteger('id_pegawai'); //foreign key
            $table->foreign('id_pegawai')->references('id_pegawai')->on('pegawai_10079s'); //foreign key dari tabel pegawai

            $table->date('tgl_transaksi'); //date
            $table->string('metode_pembayaran', 30); //varchar(30)
            $table->string('status_transaksi', 50); //varchar(50)
            $table->string('status_dokumen', 50); //varchar(50)
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
        Schema::dropIfExists('transaksi_10079s');
    }
};
