<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();

Route::group(['middleware'=>['guest']],function(){
    Route::get('/', function () { return view('welcome'); });
    Route::post('/login', 'Auth\LoginController@login')->name('login');
});




Route::group(['middleware'=>['auth']],function(){

	// Ruta Recaudación del Día en Mano de Obra Mecánica (MOM)
	Route::get('/momdeldia', 'TallerController@momdeldia');
	// Ruta Recaudación del Día en Mano de Obra Eléctrica (MOE)
	Route::get('/moe', 'TallerController@moe');
	// Ruta Recaudación del Mes de Ventas o Servicios (MOE)
	Route::get('/vys', 'TallerController@vys');
	//  Ruta Ingreso de Vehiculos del Día
	Route::get('/ingresosaldia', 'TallerController@ingresosaldia');
	// Ruta de Servicios por Fecha
	Route::post('/serviciosporfecha', 'TallerController@serviciosporfecha');

	Route::get('/empresadelusuario', 'EmpresaController@buscarempresalogin');
	Route::get('/obtenerusuario', 'UserController@obtenerUsuarioSesion');
	Route::get('/ventasdeldia', 'VentaController@ventasdeldia');
	Route::get('/productodelmes', 'ProductoController@productodelmes');
	Route::get('/recaudaciondeldia', 'VentaController@recaudaciondeldia');
	Route::get('/vendedordelmes', 'VentaController@vendedordelmes');
	Route::post('/ventasporfecha', 'VentaController@ventasPorFecha');
	Route::post('/comprasporfecha', 'CompraController@comprasPorFecha');
	Route::post('/comprasporfecha2', 'CompraController@comprasPorFecha2');

	Route::resource('user', 'UserController', ['except' => ['show']]);
	Route::get('profile', ['as' => 'profile.edit', 'uses' => 'ProfileController@edit']);
	Route::put('profile', ['as' => 'profile.update', 'uses' => 'ProfileController@update']);
	Route::put('profile/password', ['as' => 'profile.password', 'uses' => 'ProfileController@password']);
     
    Route::post('/logout', 'Auth\LoginController@logout')->name('logout');
     
	Route::get('/home', 'HomeController@index')->name('home');
 
    Route::group(['middleware' => ['Supervisor']], function () {
	   
		Route::get('/empresa', 'EmpresaController@index');
		Route::post('/empresa/registrar', 'EmpresaController@store');
		Route::post('/empresa/revisarFirma', 'EmpresaController@firmaOK');
		Route::post('/empresa/registrarFirma', 'EmpresaController@storeFirma');
		Route::put('/empresa/actualizar', 'EmpresaController@update');
		Route::put('/empresa/eliminar', 'EmpresaController@eliminar');
		Route::put('/empresa/activar', 'EmpresaController@activar');

		Route::get('/producto', 'ProductoController@index');
		Route::post('/producto/registrar', 'ProductoController@store');
		Route::post('/producto/revisarFirma', 'ProductoController@firmaOK');
		Route::post('/producto/registrarFirma', 'ProductoController@storeFirma');
		Route::put('/producto/actualizar', 'ProductoController@update');
		Route::put('/producto/eliminar', 'ProductoController@eliminar');
		Route::put('/producto/activar', 'ProductoController@activar');

		Route::get('/compra', 'CompraController@index');
		Route::post('/compra/registrar', 'CompraController@store');
		Route::post('/compra/revisarFirma', 'CompraController@firmaOK');
		Route::post('/compra/registrarFirma', 'CompraController@storeFirma');
		Route::put('/compra/actualizar', 'CompraController@update');
		Route::put('/compra/eliminar', 'CompraController@eliminar');
		Route::put('/compra/activar', 'CompraController@activar');
 
    });
 
    Route::group(['middleware' => ['Vendedor']], function () {
       
    });
 
    Route::group(['middleware' => ['Administrador']], function () {
         
		Route::get('/empresa', 'EmpresaController@index');
		Route::post('/empresa/registrar', 'EmpresaController@store');
		Route::post('/empresa/revisarFirma', 'EmpresaController@firmaOK');
		Route::post('/empresa/registrarFirma', 'EmpresaController@storeFirma');
		Route::put('/empresa/actualizar', 'EmpresaController@update');
		Route::put('/empresa/eliminar', 'EmpresaController@eliminar');
		Route::put('/empresa/activar', 'EmpresaController@activar');

		Route::get('/venta', 'VentaController@index');
		Route::post('/venta/registrar', 'VentaController@store');
		Route::put('/venta/actualizar', 'VentaController@update');
		Route::put('/venta/eliminar', 'VentaController@eliminar');
		Route::put('/venta/activar', 'VentaController@activar');

		Route::get('/tipovehiculo', 'TipoVehiculoController@index');
		Route::post('/tipovehiculo/registrar', 'TipoVehiculoController@store');
		Route::put('/tipovehiculo/actualizar', 'TipoVehiculoController@update');
		Route::put('/tipovehiculo/eliminar', 'TipoVehiculoController@eliminar');
		Route::put('/tipovehiculo/activar', 'TipoVehiculoController@activar');

		Route::get('/vehiculo', 'VehiculoController@index');
		Route::post('/vehiculo/registrar', 'VehiculoController@store');
		Route::put('/vehiculo/actualizar', 'VehiculoController@update');
		Route::put('/vehiculo/eliminar', 'VehiculoController@eliminar');
		Route::put('/vehiculo/activar', 'VehiculoController@activar');

		Route::get('/taller', 'TallerController@index');
		Route::post('/taller/registrar', 'TallerController@store');
		Route::put('/taller/actualizar', 'TallerController@update');
		Route::put('/taller/eliminar', 'TallerController@eliminar');
		Route::put('/taller/activar', 'TallerController@activar');
		Route::get('/detallesporservicio/{codigo}', 'TallerController@DetallesPorServicio');

		Route::get('/todoslostiposdevehiculos', 'TipoVehiculoController@getAllTiposVehiculos');
		Route::get('/todoslosvehiculos', 'TallerController@getAllVehiculos');

		Route::get('/cotizacion', 'CotizacionController@index');
		Route::post('/cotizacion/registrar', 'CotizacionController@store');
		Route::put('/cotizacion/actualizar', 'CotizacionController@update');
		Route::put('/cotizacion/eliminar', 'CotizacionController@eliminar');
		Route::put('/cotizacion/activar', 'CotizacionController@activar');
		Route::get('/detallesporcotizacion/{codigo}', 'CotizacionController@DetallesPorCotizacion');

		Route::get('/usuario', 'UserController@index');
		Route::post('/usuario/registrar', 'UserController@store');
		Route::put('/usuario/actualizar', 'UserController@update');
		Route::put('/usuario/eliminar', 'UserController@eliminar');
		Route::get('/detallesporusuario/{codigo}', 'CotizacionController@DetallesPorCotizacion');
		

    });
 
});
 
//Route::get('/home', 'HomeController@index')->name('home');


/*


Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::group(['middleware' => 'auth'], function () {
	Route::resource('user', 'UserController', ['except' => ['show']]);
	Route::get('profile', ['as' => 'profile.edit', 'uses' => 'ProfileController@edit']);
	Route::put('profile', ['as' => 'profile.update', 'uses' => 'ProfileController@update']);
	Route::put('profile/password', ['as' => 'profile.password', 'uses' => 'ProfileController@password']);
});

Route::get('/categoria', 'CategoriaController@index');
Route::post('/categoria/registrar', 'CategoriaController@store');
Route::put('/categoria/actualizar', 'CategoriaController@update');
Route::put('/categoria/eliminar', 'CategoriaController@eliminar');
Route::put('/categoria/activar', 'CategoriaController@activar');

Route::get('/empresa', 'EmpresaController@index');
Route::post('/empresa/registrar', 'EmpresaController@store');
Route::post('/empresa/revisarFirma', 'EmpresaController@firmaOK');
Route::post('/empresa/registrarFirma', 'EmpresaController@storeFirma');
Route::put('/empresa/actualizar', 'EmpresaController@update');
Route::put('/empresa/eliminar', 'EmpresaController@eliminar');
Route::put('/empresa/activar', 'EmpresaController@activar');

Route::get('/setDePruebas', 'SIIController@setDePruebas');
Route::get('/ConsumoFolios', 'SIIController@ConsumoFolios');
Route::get('/PDF_Boleta', 'SIIController@PDF_Boleta');


*/