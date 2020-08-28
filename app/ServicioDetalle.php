<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ServicioDetalle extends Model
{
    protected $table = 'detalles_servicios';
    protected $fillable=['id_servicio', 'servicio','valor_neto','valor_total'];
}
