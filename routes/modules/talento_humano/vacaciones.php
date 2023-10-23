<?php

use Illuminate\Support\Facades\Route;

Route::group([
    'middleware' => ['auth'],
    'middleware' => ['role:ADMINISTRADOR DE TALENTO HUMANO'],
    'as' => 'vacaciones.', 'prefix' => 'vacaciones'
], function () {
    Route::get('index', 'TalentoHumano\Vacaciones\VacacionesController@index');
    Route::get('getDatatableVacacionesServerSide', 'TalentoHumano\Vacaciones\VacacionesController@getDatatableVacacionesServerSide');
    Route::get('getDatatableHistorialServerSide/{per_id}', 'TalentoHumano\Personas\PersonasController@getDatatableHistorialServerSide');
    Route::post('editarPersona', 'TalentoHumano\Personas\PersonasController@editarPersona');
    Route::post('desactivarRegistro', 'TalentoHumano\Permisos\PermisosController@desactivarRegistro');

    Route::post('guardarRegistro', 'TalentoHumano\Permisos\PermisosController@guardarRegistro');
});
