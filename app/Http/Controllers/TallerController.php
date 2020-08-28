<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Taller;
use App\ServicioDetalle;
use Illuminate\Support\Facades\DB;





use App\Http\Controllers\Controller;

class TallerController extends Controller
{
    public function index(Request $request)
    {

        $buscar = $request->buscar;

        if ($buscar==''){

            $servicios = Taller::join('vehiculos', 'vehiculos.id', '=', 'servicios.patente_vehiculo')
            ->select('servicios.*', 'vehiculos.patente as patente', 'vehiculos.nombre_propietario as propietario')
            ->orderBy('vehiculos.id', 'desc')->paginate(10);
        }
        else{
            
            $servicios = Taller::where('vehiculos.patente', 'like', '%'. $buscar . '%')
            ->join('vehiculos', 'vehiculos.id', '=', 'servicios.patente_vehiculo')
            ->select('servicios.*', 'vehiculos.patente as patente', 'vehiculos.nombre_propietario as propietario')
            ->orderBy('vehiculos.id', 'desc')->paginate(10);
            
        }
        

        return [
            'pagination' => [
                'total'        => $servicios->total(),
                'current_page' => $servicios->currentPage(),
                'per_page'     => $servicios->perPage(),
                'last_page'    => $servicios->lastPage(),
                'from'         => $servicios->firstItem(),
                'to'           => $servicios->lastItem(),
            ],
            'servicios' => $servicios
        ];
    }
 
    public function store(Request $request)
    {
        date_default_timezone_set('America/Santiago');
        $servicio = new Taller();
        $servicio->valor_neto = $request->valor_neto;
        $servicio->valor_total = $request->valor_total;
        $servicio->comentario = $request->comentario;
        $servicio->estado = $request->estado;
        $servicio->patente_vehiculo = $request->vehiculo;
        $servicio->save();

        // Agregar Detalles
        $ultimo_id = Taller::max('id');

        // Recorrer Array Detalles
        foreach ($request->detalles as $d) {
            // Guardar Detalles
            $detalle = new ServicioDetalle();
            $detalle->id_Servicio = $ultimo_id;
            $detalle->servicio = $d['servicio'];
            $detalle->valor_neto = $d['detalle_neto'];
            $detalle->valor_total = $d['detalle_neto'] * 1.19;
            $detalle->save();
        }


    }

    public function update(Request $request)
    {
        // Buscar Servicio
        $servicio = Taller::where('id', $request->id)->firstOrFail();
        // Actualizar Servicio
        $servicio->valor_neto = $request->valor_neto;
        $servicio->valor_total = $request->valor_total;
        $servicio->comentario = $request->comentario;
        $servicio->estado = $request->estado;
        $servicio->patente_vehiculo = $request->vehiculo;
        // Guardar
        $servicio->save();

        // Borrar Detalles Antiguos
        ServicioDetalle::where('id_Servicio', $request->id)->delete();        

        // Recorrer Array Detalles
        foreach ($request->detalles as $d) {
            // Guardar Detalles
            $detalle = new ServicioDetalle();
            $detalle->id_Servicio = $request->id;
            $detalle->servicio = $d['servicio'];
            $detalle->valor_neto = $d['detalle_neto'];
            $detalle->valor_total = $d['detalle_neto'] * 1.19;
            $detalle->save();
        }
    }
 
    public function eliminar(Request $request)
    {
        
        $servicio = Taller::findOrFail($request->id);
        $servicio->delete();
    }

    public function getAllVehiculos(Request $request)
    {
        $vehiculos = DB::table('vehiculos')
        ->OrderBy('patente','asc')
        ->get();

        return $vehiculos;
    }

    public function DetallesPorServicio($id)
    {
        $detalles = ServicioDetalle::where('id_servicio', $id)
        ->select('detalles_servicios.*', 'detalles_servicios.valor_neto as detalle_neto')
        ->OrderBy('id','asc')
        ->get();

        return $detalles;
    }
 
 
}
