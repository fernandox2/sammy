<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Symfony\Component\Console\Helper\Table;

class CreateDetallesServiciosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detalles_servicios', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('id_servicio')->unsigned();
            $table->string('servicio',500);
            $table->integer('valor_neto');
            $table->integer('valor_total');
            $table->string('tipo')->nullable();
            $table->timestamps();
            $table->foreign('id_servicio')->references('id')->on('servicios')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('detalles_servicios');
    }
}
