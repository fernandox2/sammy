<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateServiciosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('servicios', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('valor_neto')->nullable();
            $table->integer('valor_total')->nullable();
            $table->string('comentario',191)->nullable();
            $table->string('estado')->default('En curso');
            $table->bigInteger('patente_vehiculo')->unsigned();
            $table->timestamps();
            $table->foreign('patente_vehiculo')->references('id')->on('vehiculos')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('servicios');
    }
}
