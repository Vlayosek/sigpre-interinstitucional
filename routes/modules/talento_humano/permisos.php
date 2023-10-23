<?php

use Illuminate\Support\Facades\Route;

Route::group([
    'middleware' => ['auth'],
    'as' => 'permisos.', 'prefix' => 'permisos'
], function () {
    /* REGISTRO DE PERMISOS */
    Route::get('index', 'TalentoHumano\Permisos\PermisosController@index');
    Route::post('guardarRegistro', 'TalentoHumano\Permisos\PermisosController@guardarRegistro');
    Route::post('editarPermiso', 'TalentoHumano\Permisos\PermisosController@editarPermiso');
    Route::post('eliminarPermiso', 'TalentoHumano\Permisos\PermisosController@eliminarPermiso');
    Route::get('getDatatablePermisosServerSide/{fecha_inicio}/{fecha_fin}', 'TalentoHumano\Permisos\PermisosController@getDatatablePermisosServerSide');
});
