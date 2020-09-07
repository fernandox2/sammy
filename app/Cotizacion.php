<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cotizacion extends Model
{
    protected $table = 'cotizaciones';
    protected $fillable=['nombre_cliente', 'rut_cliente', 'direccion_cliente', 'contacto_cliente', 'forma_de_pago', 'plazo_entrega', 'validez_cotizacion', 'lugar_entrega', 'valor_neto', 'valor_total', 'comentario', 'TIPO'];
}
