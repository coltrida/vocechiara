<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateListinoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('listino', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('categoria')->nullable();
            $table->string('descrizione')->nullable();
            $table->string('costo')->nullable();
            $table->string('prezzolistino')->nullable();
            $table->string('iva')->nullable();
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
        Schema::dropIfExists('listino');
    }
}
