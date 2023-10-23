<?php

use App\Http\Controllers\Uath\Asistencia\AsistenciaController;
use App\Http\Controllers\Uath\Asistencia\MarcacionesAdminController;
use App\Http\Controllers\Uath\Asistencia\MarcacionesAreaController;
use Illuminate\Support\Facades\Route;

Route::group([
    'middleware' => ['auth'],
    'as' => 'asistencia.', 'prefix' => 'asistencia'
], function () {
    Route::get('index', [AsistenciaController::class, 'index']);
    Route::post('guardarRegistro', [AsistenciaController::class, 'guardarRegistro']);
    Route::get('getDatatableAsistenciaServerSide/{fecha_inicio}/{fecha_fin}/{identificacion}', [AsistenciaController::class, 'getDatatableAsistenciaServerSide']);
    Route::get('getDatatableAsistenciaMultipleServerSide/{fecha_inicio}/{fecha_fin}/{identificacion}', [AsistenciaController::class, 'getDatatableAsistenciaMultipleServerSide']);
});
Route::group([
    'middleware' => ['auth'],
    'as' => 'asistencia.', 'prefix' => 'asistencia'
], function () {
    Route::get('completa', [AsistenciaController::class, 'completa']);
    Route::post('guardarRegistro', [AsistenciaController::class, 'guardarRegistro']);
    Route::get('getDatatable_fn_marcaciones_dias_ServerSide/{fecha_inicio}/{fecha_fin}/{identificacion}', [AsistenciaController::class, 'getDatatable_fn_marcaciones_dias_ServerSide']);
});

Route::group([
    'middleware' => ['auth'],
    'middleware' => ['role:JEFE DE AREA'],
    'as' => 'marcaciones.area.', 'prefix' => 'marcaciones/area'
], function () {
    Route::get('index', [MarcacionesAreaController::class, 'index']);
    Route::get('getDatatableMarcacionesAreaControllerServerSide/{fecha_inicio}/{fecha_fin}/{identificacion}', [MarcacionesAreaController::class, 'getDatatableMarcacionesAreaControllerServerSide']);
    Route::get('getDatatableMarcacionesMultiplesAreaControllerServerSide/{fecha_inicio}/{fecha_fin}/{identificacion}', [MarcacionesAreaController::class, 'getDatatableMarcacionesMultiplesAreaControllerServerSide']);
});



Route::group([
    'middleware' => ['auth'],
    'middleware' => ['role:ADMINISTRADOR DE MARCACIONES'],
    'as' => 'marcaciones.', 'prefix' => 'marcaciones'
], function () {
    Route::get('admin', [MarcacionesAdminController::class, 'admin']);
    Route::get('admin/getDatatableMarcacionesMultiplesAreaControllerServerSide/{fecha_inicio}/{fecha_fin}/{identificacion}/{filtro}', [MarcacionesAdminController::class, 'getDatatableMarcacionesMultiplesAreaControllerServerSide']);
    Route::post('grabarMarcacionManual', [MarcacionesAdminController::class, 'grabarMarcacionManual']);
    Route::post('eliminarMarcacion', [MarcacionesAdminController::class, 'eliminarMarcacion']);
    Route::post('guardarRegistro', [AsistenciaController::class, 'guardarRegistro']);
    Route::post('guardarRegistroManual', [AsistenciaController::class, 'guardarRegistroManual']);
    Route::post('buscarMarcacionAnterior', [MarcacionesAdminController::class, 'buscarMarcacionAnterior']);
});

/* MARCACIONES MULTIPLES*/
Route::group([
    'middleware' => ['auth'],
    'middleware' => ['role:JEFE DE AREA'],
    'as' => 'marcaciones.multiple.area.', 'prefix' => 'marcaciones/multiple/area'
], function () {
    Route::get('index', [MarcacionesAreaController::class, 'indexMultiple']);
});
