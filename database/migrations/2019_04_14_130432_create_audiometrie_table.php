<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAudiometrieTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('audiometrie', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('_250d')->nullable();
            $table->string('_500d')->nullable();
            $table->string('_1000d')->nullable();
            $table->string('_2000d')->nullable();
            $table->string('_4000d')->nullable();
            $table->string('_8000d')->nullable();
            $table->string('_250s')->nullable();
            $table->string('_500s')->nullable();
            $table->string('_1000s')->nullable();
            $table->string('_2000s')->nullable();
            $table->string('_4000s')->nullable();
            $table->string('_8000s')->nullable();
            $table->string('id_cliente')->nullable();
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
        Schema::dropIfExists('audiometrie');
    }
}
