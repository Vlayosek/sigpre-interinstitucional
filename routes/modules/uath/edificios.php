<?php


use App\Http\Controllers\Uath\Areas\AreasController;
use App\Http\Controllers\Uath\Edificios\EdificiosController;
use Illuminate\Support\Facades\Route;

Route::group([
    'middleware' => ['auth'],
    'middleware' => ['role:ADMINISTRADOR DE EDIFICIOS'],
    'as' => 'edificios.', 'prefix' => 'edificios'
], function () {

    Route::get('index', 'Uath\Edificios\EdificiosController@index');
    Route::get('getDatatablEdificiosServerSide', 'Uath\Edificios\EdificiosController@getDatatablEdificiosServerSide');
    Route::get('getDatatablAreaServerSide/{edificio_id}', 'Uath\Edificios\EdificiosController@getDatatablAreaServerSide');
    Route::post('guardarEdificio', 'Uath\Edificios\EdificiosController@guardarEdificio');
    Route::post('editarEdificio', 'Uath\Edificios\EdificiosController@editarEdificio');
    Route::post('eliminarEdificio', 'Uath\Edificios\EdificiosController@eliminarEdificio');
    Route::post('inactivarAsignacionAreaEdificio', 'Uath\Edificios\EdificiosController@inactivarAsignacionAreaEdificio');
    Route::post('editarAsignacionAreaEdificio', 'Uath\Edificios\EdificiosController@editarAsignacionAreaEdificio');
    Route::post('agregarArea', 'Uath\Edificios\EdificiosController@agregarArea');
    Route::post('actualizarArea', 'Uath\Edificios\EdificiosController@actualizarArea');
    Route::post('guardarArea', 'Uath\Areas\AreasController@guardarArea');
    Route::post('eliminarArea', 'Uath\Areas\AreasController@eliminarArea');
    Route::get('historialArea/{id}', 'Uath\Areas\AreasController@historial');
    Route::post('consultaAreaHistorial', 'Uath\Areas\AreasController@consultaAreaHistorial');
});
