<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProveProdottiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('proveprodotti', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('id_prova');
            $table->string('id_listino');
            $table->string('prezzo')->nullable();
            $table->string('quantita');
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
        Schema::dropIfExists('proveprodotti');
    }
}
