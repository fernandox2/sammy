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
            $table->string('nombre_propietario',100)->nullable();
            $table->string('fono_propietario',12)->nullable();
            $table->string('correo_propietario',100)->nullable();
            $table->string('motor',30)->nullable();
            $table->string('marca',30)->nullable();
            $table->string('modelo',30)->nullable();
            $table->string('vin',30)->nullable();
            $table->string('chasis',30)->nullable();
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
