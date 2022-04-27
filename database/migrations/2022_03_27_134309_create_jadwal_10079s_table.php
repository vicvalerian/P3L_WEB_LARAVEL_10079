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
        Schema::create('jadwal_10079s', function (Blueprint $table) {
            $table->string('id_jadwal_pegawai', 20)->primary(); //varchar(20), primary key
            $table->string('shift_pegawai', 20); //varchar(20)
            $table->string('hari_pegawai', 20); //varchar(20)
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
        Schema::dropIfExists('jadwal_10079s');
    }
};
