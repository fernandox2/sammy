<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Taller extends Model
{
    protected $table = 'servicios';
    protected $fillable=['comentario', 'estado', 'patente_vehiculo', 'valor_neto', 'valor_total'];
}
