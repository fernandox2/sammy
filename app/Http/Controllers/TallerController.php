<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Taller;
use App\ServicioDetalle;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;






use App\Http\Controllers\Controller;

class TallerController extends Controller
{
    public function index(Request $request)
    {

        $buscar = $request->buscar;

        if ($buscar==''){

            $servicios = Taller::join('vehiculos', 'vehiculos.id', '=', 'servicios.patente_vehiculo')
            ->select('servicios.*', 'vehiculos.patente as patente', 'vehiculos.nombre_propietario as propietario', 'vehiculos.marca as marca', 'vehiculos.modelo as modelo' )
            ->orderBy('servicios.created_at', 'desc')->paginate(10);
        }
        else{
            
            $servicios = Taller::where('vehiculos.patente', 'like', '%'. $buscar . '%')
            ->orWhere('vehiculos.nombre_propietario', 'like', '%'. $buscar . '%')
            ->join('vehiculos', 'vehiculos.id', '=', 'servicios.patente_vehiculo')
            ->select('servicios.*', 'vehiculos.patente as patente', 'vehiculos.nombre_propietario as propietario', 'vehiculos.marca as marca', 'vehiculos.modelo as modelo' )
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
            $detalle->tipo = $d['tipo'];
            $detalle->save();
        }


    }

    public function update(Request $request)
    {
        date_default_timezone_set('America/Santiago');
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
            $detalle->tipo = $d['tipo'];
            $detalle->save();
        }
    }
 
    public function eliminar(Request $request)
    {
        $rol =  Auth::user()->rol;
        if ($rol == "Administrador"){
            $servicio = Taller::findOrFail($request->id);
            $servicio->delete();
        }
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


    public function momdeldia()
    {
        
        $inicio = date("Y-m-01");
        $fin = date("Y-m-31");

        $mom = DB::table('servicios')
        ->join('detalles_servicios', 'detalles_servicios.id_servicio', '=', 'servicios.id')
        ->where('detalles_servicios.tipo','Mano de Obra Mecánica')
        ->where('servicios.estado','Terminada')
        ->whereBetween('servicios.created_at', [$inicio." 00:00:00", $fin." 23:59:59"])
        ->sum('detalles_servicios.valor_neto');
        echo $mom;

    }

    public function moe()
    {
        
        $inicio = date("Y-m-01");
        $fin = date("Y-m-31");

        $moe = DB::table('servicios')
        ->join('detalles_servicios', 'detalles_servicios.id_servicio', '=', 'servicios.id')
        ->where('detalles_servicios.tipo','Mano de Obra Eléctrica')
        ->where('servicios.estado','Terminada')
        ->whereBetween('servicios.created_at', [$inicio." 00:00:00", $fin." 23:59:59"])
        ->sum('detalles_servicios.valor_neto');
        echo $moe;

    }

    public function vys()
    {
        
        $inicio = date("Y-m-01");
        $fin = date("Y-m-31");

        $vys = DB::table('servicios')
        ->join('detalles_servicios', 'detalles_servicios.id_servicio', '=', 'servicios.id')
        ->where('detalles_servicios.tipo','Ventas o Servicios')
        ->where('servicios.estado','Terminada')
        ->whereBetween('servicios.created_at', [$inicio." 00:00:00", $fin." 23:59:59"])
        ->sum('detalles_servicios.valor_neto');
        echo $vys;

    }

    public function ingresosaldia()
    {

        $inicio = date("Y-m-01");
        $fin = date("Y-m-31");

        $servicios = DB::table('servicios')
        ->count();

        echo $servicios;

    }

    public function serviciosporfecha(Request $request)
    {

       
        $total = DB::table('servicios')
        ->join('detalles_servicios', 'detalles_servicios.id_servicio', '=', 'servicios.id')
        ->where('servicios.estado', $request->estado)
        ->whereBetween('servicios.created_at', [$request->inicio." 00:00:00", $request->fin." 23:59:59"])
        ->select(DB::raw('DATE(servicios.created_at) as fecha'), DB::raw('(SELECT count(servicios.id) FROM servicios WHERE DATE(servicios.created_at) = fecha) as numero_servicios'),  DB::raw('(SELECT sum(detalles_servicios.valor_neto) FROM detalles_servicios INNER JOIN servicios ON servicios.id = detalles_servicios.id_servicio WHERE detalles_servicios.tipo = "Mano de Obra Mecánica" AND  DATE(servicios.created_at) = fecha) as mom'), DB::raw('(SELECT sum(detalles_servicios.valor_neto) FROM detalles_servicios INNER JOIN servicios ON servicios.id = detalles_servicios.id_servicio WHERE detalles_servicios.tipo = "Mano de Obra Eléctrica" AND  DATE(servicios.created_at) = fecha) as moe'), DB::raw('(SELECT sum(detalles_servicios.valor_neto) FROM detalles_servicios INNER JOIN servicios ON servicios.id = detalles_servicios.id_servicio WHERE detalles_servicios.tipo = "Ventas o Servicios" AND  DATE(servicios.created_at) = fecha) as vys'))
        ->groupBy('fecha')
        ->get();

        echo $total;

    }
 
 
}
