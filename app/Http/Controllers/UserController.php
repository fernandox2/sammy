<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Display a listing of the users
     *
     * @param  \App\User  $model
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $buscar = $request->buscar;

        if ($buscar==''){

            $usuarios = User::select('users.*')
            ->orderBy('users.name', 'desc')->paginate(10);
        }
        else{
            
            $usuarios = User::where('users.name', 'like', '%'. $buscar . '%')
            ->orWhere('users.email', 'like', '%'. $buscar . '%')
            ->orderBy('users.name', 'desc')->paginate(10);
            
        }
        

        return [
            'pagination' => [
                'total'        => $usuarios->total(),
                'current_page' => $usuarios->currentPage(),
                'per_page'     => $usuarios->perPage(),
                'last_page'    => $usuarios->lastPage(),
                'from'         => $usuarios->firstItem(),
                'to'           => $usuarios->lastItem(),
            ],
            'usuarios' => $usuarios
        ];
    }

    /**
     * Show the form for creating a new user
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Store a newly created user in storage
     *
     * @param  \App\Http\Requests\UserRequest  $request
     * @param  \App\User  $model
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        date_default_timezone_set('America/Santiago');
        $usuario = new User();
        $usuario->name = $request->name;
        $usuario->email = $request->email;
        $usuario->rol = $request->rol;
        $usuario->empresa_id = 1;
        if($request->psw1 != ""){
            $usuario->password = Hash::make($request->psw1);
        }else{
            $usuario->password = Hash::make(123456);
        }
        
        $usuario->save();
    }

    /**
     * Show the form for editing the specified user
     *
     * @param  \App\User  $user
     * @return \Illuminate\View\View
     */
    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    /**
     * Update the specified user in storage
     *
     * @param  \App\Http\Requests\UserRequest  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        // Buscar Usuario
        $usuario = User::where('id', $request->id)->firstOrFail();
        $usuario->name = $request->name;
        $usuario->email = $request->email;
        $usuario->rol = $request->rol;
        $usuario->empresa_id = 1;
        if($request->psw1 != ""){
            $usuario->password = Hash::make($request->psw1);
        }      
        $usuario->save();
    }

    /**
     * Remove the specified user from storage
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function eliminar(Request $request)
    {
        $usuario = User::findOrFail($request->id);
        $usuario->delete();
    }


    public function obtenerUsuarioSesion()
    {
        echo Auth::user()->id;
    }
}
