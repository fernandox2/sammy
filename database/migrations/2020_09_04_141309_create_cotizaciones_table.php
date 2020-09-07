<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCotizacionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cotizaciones', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nombre_cliente',191)->nullable();
            $table->string('rut_cliente',191)->nullable();
            $table->string('direccion_cliente',191)->nullable();
            $table->string('contacto_cliente',191)->nullable();
            $table->string('forma_de_pago',191)->nullable();
            $table->string('plazo_entrega',191)->nullable();
            $table->string('validez_cotizacion',191)->nullable();
            $table->string('lugar_entrega',191)->nullable();
            $table->integer('valor_neto')->nullable();
            $table->integer('valor_total')->nullable();
            $table->string('comentario',500)->nullable();
            $table->string('tipo',191)->nullable();
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
        Schema::dropIfExists('cotizaciones');
    }
}
