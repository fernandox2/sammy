<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVehiculosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vehiculos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('patente',10)->unique();
            $table->string('nombre_propietario',100);
            $table->string('fono_propietario',12);
            $table->string('correo_propietario',100);
            $table->string('motor',30);
            $table->string('marca',30);
            $table->string('modelo',30);
            $table->string('vin',30);
            $table->string('chasis',30);
            $table->bigInteger('tipo_vehiculo')->unsigned();
            $table->timestamps();
            $table->foreign('tipo_vehiculo')->references('id')->on('tipos_vehiculos')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vehiculos');
    }
}
