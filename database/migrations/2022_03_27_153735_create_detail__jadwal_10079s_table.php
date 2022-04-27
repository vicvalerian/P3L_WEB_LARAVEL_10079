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
        Schema::create('detail__jadwal_10079s', function (Blueprint $table) {
            $table->id('id_detail_jadwal'); //auto increment, primary key

            $table->unsignedBigInteger('id_pegawai'); //foreign key
            $table->foreign('id_pegawai')->references('id_pegawai')->on('pegawai_10079s'); //foreign key dari tabel pegawai

            $table->string('id_jadwal_pegawai', 20); //foreign key
            $table->foreign('id_jadwal_pegawai')->references('id_jadwal_pegawai')->on('jadwal_10079s'); //foreign key dari tabel jadwal
            
            $table->string('keterangan_detail_jadwal');
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
        Schema::dropIfExists('detail__jadwal_10079s');
    }
};
