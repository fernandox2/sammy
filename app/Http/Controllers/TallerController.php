<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Taller;




use App\Http\Controllers\Controller;

class TallerController extends Controller
{
    public function index(Request $request)
    {

        $buscar = $request->buscar;

        if ($buscar==''){
            $vehiculos = Taller::join('tipos_vehiculos', 'vehiculos.tipo_vehiculo', '=', 'tipos_vehiculos.id')
            ->select('vehiculos.*', 'tipos_vehiculos.nombre as tipo')
            ->orderBy('id', 'desc')->paginate(10);
        }
        else{
            
            $vehiculos = Taller::where('patente', 'like', '%'. $buscar . '%')
            ->join('tipos_vehiculos', 'vehiculos.tipo_vehiculo', '=', 'tipos_vehiculos.id')
            ->select('vehiculos.*', 'tipos_vehiculos.nombre as tipo')
            ->orderBy('id', 'desc')->paginate(10);
            
        }
        

        return [
            'pagination' => [
                'total'        => $vehiculos->total(),
                'current_page' => $vehiculos->currentPage(),
                'per_page'     => $vehiculos->perPage(),
                'last_page'    => $vehiculos->lastPage(),
                'from'         => $vehiculos->firstItem(),
                'to'           => $vehiculos->lastItem(),
            ],
            'vehiculos' => $vehiculos
        ];
    }
 
    public function store(Request $request)
    {
        $vehiculo = new Taller();
        $vehiculo->patente = $request->patente;
        $vehiculo->marca = $request->marca;
        $vehiculo->modelo = $request->modelo;
        $vehiculo->marca = $request->marca;
        $vehiculo->nombre_propietario = $request->nombre_propietario;
        $vehiculo->fono_propietario = $request->fono_propietario;
        $vehiculo->correo_propietario = $request->correo_propietario;
        $vehiculo->motor = $request->motor;
        $vehiculo->vin = $request->vin;
        $vehiculo->chasis = $request->chasis;
        $vehiculo->tipo_vehiculo = $request->tipo;
        $vehiculo->save();
    }

    public function update(Request $request)
    {
        $vehiculo = Taller::where('id', $request->id)->firstOrFail();
        $vehiculo->patente = $request->patente;
        $vehiculo->marca = $request->marca;
        $vehiculo->modelo = $request->modelo;
        $vehiculo->marca = $request->marca;
        $vehiculo->nombre_propietario = $request->nombre_propietario;
        $vehiculo->fono_propietario = $request->fono_propietario;
        $vehiculo->correo_propietario = $request->correo_propietario;
        $vehiculo->motor = $request->motor;
        $vehiculo->vin = $request->vin;
        $vehiculo->chasis = $request->chasis;
        $vehiculo->tipo_vehiculo = $request->tipo;
        $vehiculo->save();
    }
 
    public function eliminar(Request $request)
    {
        $vehiculo = Taller::findOrFail($request->id);
        $vehiculo->delete();
    }


 
}
