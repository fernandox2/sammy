<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vehiculo extends Model
{
    protected $table = 'vehiculos';
    protected $fillable=['patente', 'marca', 'modelo', 'nombre_propietario', 'fono_propietario', 'correo_propietario', 'motor', 'vin', 'chasis', 'tipo_vehiculo'];
}
