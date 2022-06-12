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
        Schema::create('pelanggan_10079s', function (Blueprint $table) {
            $table->string('id_pelanggan', 50)->primary(); //varchar(50)
            $table->string('nama_pelanggan', 50); //varchar(50)
            $table->string('alamat_pelanggan'); //varchar(255)
            $table->date('tgl_lahir_pelanggan'); //date
            $table->string('jenis_kelamin_pelanggan', 30); //varchar(30)
            $table->string('email_pelanggan', 50); //varchar(50)
            $table->string('notelp_pelanggan', 14); //varchar(14)
            $table->string('no_ktp_pelanggan', 16); //varchar(16)
            $table->string('no_sim_pelanggan', 16)->nullable(); //varchar(16)
            $table->string('password_pelanggan'); //varchar(50)
            $table->string('foto_ktp_pelanggan'); //varchar(255)
            $table->string('foto_sim_pelanggan'); //varchar(255)
            $table->string('status_pelanggan'); //varchar(50)
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
        Schema::dropIfExists('pelanggan_10079s');
    }
};
