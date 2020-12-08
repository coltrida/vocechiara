<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFattureTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fatture', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('id_prova')->nullable();
            $table->dateTime('data_fattura')->nullable();
            $table->string('acconto')->nullable();
            $table->string('nr_rate')->nullable();
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
        Schema::dropIfExists('fatture');
    }
}
