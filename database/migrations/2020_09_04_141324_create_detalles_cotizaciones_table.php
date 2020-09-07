<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDetallesCotizacionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detalles_cotizaciones', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('id_cotizacion')->unsigned();
            $table->integer('item');
            $table->string('descripcion',500);
            $table->integer('cantidad');
            $table->integer('precio_unidad');
            $table->integer('valores');
            $table->timestamps();
            $table->foreign('id_cotizacion')->references('id')->on('cotizaciones')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('detalles_cotizaciones');
    }
}
