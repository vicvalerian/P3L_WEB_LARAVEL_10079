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
        Schema::create('driver_10079s', function (Blueprint $table) {
            $table->string('id_driver', 50)->primary(); //varchar(50), pk
            $table->string('nama_driver', 50); //varchar(50)
            $table->string('alamat_driver'); //varchar(255)
            $table->date('tgl_lahir_driver'); //date
            $table->string('jenis_kelamin_driver', 30); //varchar(30)
            $table->string('bahasa_driver', 50); //varchar(50)
            $table->string('foto_driver'); //varchar(255)
            $table->string('notelp_driver', 14); //varchar(14)
            $table->string('email_driver', 50); //varchar(50)
            $table->double('sewa_harian_driver'); //double
            $table->string('status_driver', 30); //varchar(30)
            $table->double('rating_driver')->nullable(); //integer
            $table->string('password_driver'); //varchar(50)
            $table->string('sim_driver'); //varchar(255)
            $table->string('surat_bebas_napza'); //varchar(255)
            $table->string('surat_jiwa_jasmani'); //varchar(255)
            $table->string('skck_driver'); //varchar(255)
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
        Schema::dropIfExists('driver_10079s');
    }
};
