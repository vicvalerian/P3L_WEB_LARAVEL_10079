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
        Schema::create('promo_10079s', function (Blueprint $table) {
            $table->id('id_promo'); //auto inkremen, primary key
            $table->string('kode_promo', 20); //varchar(20)
            $table->string('jenis_promo', 50); //varchar(50)
            $table->string('keterangan_promo'); //varchar
            $table->double('diskon_promo'); //double
            $table->string('status_promo', 30); //varchar(30)
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
        Schema::dropIfExists('promo_10079s');
    }
};
