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
        Schema::create('pegawai_10079s', function (Blueprint $table) {
            $table->id('id_pegawai'); //auto inkremen, primary key

            $table->unsignedBigInteger('id_jabatan'); //foreign key
            $table->foreign('id_jabatan')->references('id_jabatan')->on('jabatan_10079s'); //foreign key dari tabel jabatan
            
            $table->string('nama_pegawai', 50); //varchar(50)
            $table->string('alamat_pegawai'); //varchar(255)
            $table->date('tgl_lahir_pegawai'); //date
            $table->string('jenis_kelamin_pegawai', 20); //varchar(20)
            $table->string('email_pegawai', 50); //varchar(50)
            $table->string('notelp_pegawai', 14); //varchar(14)
            $table->string('foto_pegawai'); //varchar(50)
            $table->string('password_pegawai'); //varchar(50)
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
        Schema::dropIfExists('pegawai_10079s');
    }
};
