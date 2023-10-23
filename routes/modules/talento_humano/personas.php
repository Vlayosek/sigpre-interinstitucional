<?php

use Illuminate\Support\Facades\Route;

Route::group([
    'middleware' => ['auth'],
    'middleware' => ['role:ADMINISTRADOR DE TALENTO HUMANO'],
    'as' => 'personas.', 'prefix' => 'personas'
], function () {
    Route::get('index', 'TalentoHumano\Personas\PersonasController@index');
    Route::get('getDatatablePersonasServerSide', 'TalentoHumano\Personas\PersonasController@getDatatablePersonasServerSide');
    Route::get('getDatatableHistorialServerSide/{per_id}', 'TalentoHumano\Personas\PersonasController@getDatatableHistorialServerSide');
    Route::post('editarPersona', 'TalentoHumano\Personas\PersonasController@editarPersona');
    Route::post('desactivarRegistro', 'TalentoHumano\Permisos\PermisosController@desactivarRegistro');

    Route::post('guardarRegistro', 'TalentoHumano\Permisos\PermisosController@guardarRegistro');
});
