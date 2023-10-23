<?php

use Illuminate\Support\Facades\Route;

Route::group([
    'middleware' => ['auth'],
    'as' => 'asistencia.', 'prefix' => 'asistencia'
], function () {
    Route::get('index', 'TalentoHumano\Asistencia\AsistenciaController@index');
    Route::post('guardarRegistro', 'TalentoHumano\Asistencia\AsistenciaController@guardarRegistro');
    Route::get('getDatatableAsistenciaServerSide/{fecha_inicio}/{fecha_fin}', 'TalentoHumano\Asistencia\AsistenciaController@getDatatableAsistenciaServerSide');
    Route::get('getDatatableAsistenciaMultipleServerSide/{fecha_inicio}/{fecha_fin}', 'TalentoHumano\Asistencia\AsistenciaController@getDatatableAsistenciaMultipleServerSide');
});

Route::group([
    'middleware' => ['auth'],
    'as' => 'marcaciones.area.', 'prefix' => 'marcaciones/area'
], function () {
    Route::get('index', 'TalentoHumano\Asistencia\MarcacionesAreaController@index');
    Route::get('getDatatableMarcacionesAreaControllerServerSide/{fecha_inicio}/{fecha_fin}', 'TalentoHumano\Asistencia\MarcacionesAreaController@getDatatableMarcacionesAreaControllerServerSide');
    Route::get('getDatatableMarcacionesMultiplesAreaControllerServerSide/{fecha_inicio}/{fecha_fin}', 'TalentoHumano\Asistencia\MarcacionesAreaController@getDatatableMarcacionesMultiplesAreaControllerServerSide');
    Route::get('seleccion', 'TalentoHumano\Asistencia\MarcacionesAreaController@seleccion');
    Route::get('getDatatableMarcacionesAreaSeleccionControllerServerSide/{fecha_inicio}/{fecha_fin}', 'TalentoHumano\Asistencia\MarcacionesAreaController@getDatatableMarcacionesAreaSeleccionControllerServerSide');
});
Route::group([
    'middleware' => ['auth'],
    'as' => 'marcaciones.multiple.area.', 'prefix' => 'marcaciones/multiple/area'
], function () {
    Route::get('index', 'TalentoHumano\Asistencia\MarcacionesAreaController@indexMultiple');
});
