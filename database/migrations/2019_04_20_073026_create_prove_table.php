<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProveTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prove', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('id_audio')->nullable();
            $table->string('id_cliente')->nullable();
            $table->string('nr_ordine')->nullable();
            $table->string('tot')->nullable();
            $table->string('stato')->nullable();
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
        Schema::dropIfExists('prove');
    }
}
