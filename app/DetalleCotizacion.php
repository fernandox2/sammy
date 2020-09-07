<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DetalleCotizacion extends Model
{
    protected $table = 'detalles_cotizaciones';
    protected $fillable=['id_cotizacion', 'item', 'descripcion', 'cantidad', 'precio_unidad', 'valores'];
}
