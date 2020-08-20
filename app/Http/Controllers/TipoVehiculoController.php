<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\TipoVehiculo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


use App\Http\Controllers\Controller;

class TipoVehiculoController extends Controller
{
    public function index(Request $request)
    {

        $buscar = $request->buscar;

        if ($buscar==''){
            $tipos = TipoVehiculo::orderBy('id', 'desc')->paginate(10);
        }
        else{
            
            $tipos = TipoVehiculo::where('nombre', 'like', '%'. $buscar . '%')
            ->orderBy('id', 'desc')->paginate(10);
            
        }
        

        return [
            'pagination' => [
                'total'        => $tipos->total(),
                'current_page' => $tipos->currentPage(),
                'per_page'     => $tipos->perPage(),
                'last_page'    => $tipos->lastPage(),
                'from'         => $tipos->firstItem(),
                'to'           => $tipos->lastItem(),
            ],
            'tipos' => $tipos
        ];
    }
 
    public function store(Request $request)
    {
        $producto = new TipoVehiculo();
        $producto->nombre = $request->nombre;
        $producto->save();
    }

    public function update(Request $request)
    {
        $producto = TipoVehiculo::where('id', $request->id)->firstOrFail();
        $producto->nombre = $request->nombre;
        $producto->save();
    }
 
    public function eliminar(Request $request)
    {
        $producto = TipoVehiculo::findOrFail($request->id);
        $producto->delete();
    }

    public function getAllTiposVehiculos(Request $request)
    {
        $tipos = DB::table('tipos_vehiculos')
        ->OrderBy('nombre','asc')
        ->get();

        return $tipos;
    }
 
}
