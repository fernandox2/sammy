<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Cotizacion;
use App\DetalleCotizacion;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;


class CotizacionController extends Controller
{
    public function index(Request $request)
    {

        $buscar = $request->buscar;

        if ($buscar==''){

            $cotizaciones = Cotizacion::select('cotizaciones.*')
            ->orderBy('cotizaciones.id', 'desc')->paginate(10);
        }
        else{
            
            $cotizaciones = Cotizacion::where('cotizaciones.nombre_cliente', 'like', '%'. $buscar . '%')
            ->orWhere('cotizaciones.contacto_cliente', 'like', '%'. $buscar . '%')
            ->orderBy('cotizaciones.id', 'desc')->paginate(10);
            
        }
        

        return [
            'pagination' => [
                'total'        => $cotizaciones->total(),
                'current_page' => $cotizaciones->currentPage(),
                'per_page'     => $cotizaciones->perPage(),
                'last_page'    => $cotizaciones->lastPage(),
                'from'         => $cotizaciones->firstItem(),
                'to'           => $cotizaciones->lastItem(),
            ],
            'cotizaciones' => $cotizaciones
        ];
    }
 
    public function store(Request $request)
    {
        date_default_timezone_set('America/Santiago');
        $cotizacion = new Cotizacion();
        $cotizacion->nombre_cliente = $request->nombre_cliente;
        $cotizacion->rut_cliente = $request->rut_cliente;
        $cotizacion->direccion_cliente = $request->direccion_cliente;
        $cotizacion->contacto_cliente = $request->contacto_cliente;
        $cotizacion->forma_de_pago = $request->forma_de_pago;
        $cotizacion->plazo_entrega = $request->plazo_entrega;
        $cotizacion->validez_cotizacion = $request->validez_cotizacion;
        $cotizacion->lugar_entrega = $request->lugar_entrega;
        $cotizacion->valor_neto = $request->valor_neto;
        $cotizacion->valor_total = $request->valor_total;
        $cotizacion->comentario = $request->comentario;
        $cotizacion->tipo = $request->tipo;
        $cotizacion->save();

        // Agregar Detalles
        $ultimo_id = Cotizacion::max('id');
        $vuelta = 0;
        // Recorrer Array Detalles
        foreach ($request->detalles as $d) {
            // Guardar Detalles
            $vuelta = $vuelta + 1;
            $detalle = new DetalleCotizacion();
            $detalle->id_cotizacion = $ultimo_id;
            $detalle->descripcion = $d['descripcion'];
            $detalle->item = $vuelta;
            $detalle->cantidad = $d['cantidad'];
            $detalle->precio_unidad = $d['precio_unidad'];
            $detalle->valores = $d['valores'];
            $detalle->save();
        }


    }

    public function update(Request $request)
    {
        // Buscar Cotizacion
        $cotizacion = Cotizacion::where('id', $request->id)->firstOrFail();
        // Actualizar Cotizacion
        $cotizacion->nombre_cliente = $request->nombre_cliente;
        $cotizacion->rut_cliente = $request->rut_cliente;
        $cotizacion->direccion_cliente = $request->direccion_cliente;
        $cotizacion->contacto_cliente = $request->contacto_cliente;
        $cotizacion->forma_de_pago = $request->forma_de_pago;
        $cotizacion->plazo_entrega = $request->plazo_entrega;
        $cotizacion->validez_cotizacion = $request->validez_cotizacion;
        $cotizacion->lugar_entrega = $request->lugar_entrega;
        $cotizacion->valor_neto = $request->valor_neto;
        $cotizacion->valor_total = $request->valor_total;
        $cotizacion->comentario = $request->comentario;
        $cotizacion->tipo = $request->tipo;
        // Guardar
        $cotizacion->save();

        // Borrar Detalles Antiguos
        DetalleCotizacion::where('id_cotizacion', $request->id)->delete();        
        // Contador de Item para la Cotización
        $vuelta = 0;
        // Recorrer Array Detalles
        foreach ($request->detalles as $d) {
            $vuelta = $vuelta + 1;
            $detalle = new DetalleCotizacion();
            $detalle->id_cotizacion = $request->id;
            $detalle->descripcion = $d['descripcion'];
            $detalle->item = $vuelta;
            $detalle->cantidad = $d['cantidad'];
            $detalle->precio_unidad = $d['precio_unidad'];
            $detalle->valores = $d['valores'];
            $detalle->save();
        }
    }
 
    public function eliminar(Request $request)
    {
        $rol =  Auth::user()->rol;

        if ($rol == "Administrador"){
            $servicio = Cotizacion::findOrFail($request->id);
            $servicio->delete();
        }


    }



    public function DetallesPorCotizacion($id)
    {
        $detalles = DetalleCotizacion::where('id_cotizacion', $id)
        ->OrderBy('item','asc')
        ->get();

        return $detalles;
    }
}
