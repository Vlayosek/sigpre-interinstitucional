<?php

use App\Http\Controllers\Uath\Areas\AreaEdificioController;
use App\Http\Controllers\Uath\Areas\AreasController;
use App\Http\Controllers\Uath\Areas\AsignacionAreaController;
use Illuminate\Support\Facades\Route;

Route::group([
    'middleware' => ['auth'],
    'as' => 'areas.', 'prefix' => 'areas'
], function () {

    Route::get('asignacion', [AsignacionAreaController::class, 'index']);
    Route::get('getDatatableAsisgnacionServerSide', [AsignacionAreaController::class, 'getDatatableAsisgnacionServerSide']);
    Route::post('guardarAsignacion', [AsignacionAreaController::class, 'guardarAsignacion']);
    Route::post('eliminarAsignacion', [AsignacionAreaController::class, 'eliminarAsignacion']);

    Route::get('getDatatablAreasServerSide', [AreasController::class, 'getDatatablAreasServerSide']);
    Route::get('getDatatablAreasServerSide_', [AreasController::class, 'getDatatablAreasServerSide_']);
    Route::post('eliminarArea', [AreasController::class, 'eliminarArea']);
    Route::post('guardarArea', [AreasController::class, 'guardarArea']);
    Route::get('creacion', [AreasController::class, 'index']);
    Route::get('organigrama', [AreasController::class, 'organigrama']);
    Route::get('datatablehistorialArea_/{area_id}', [AreasController::class, 'datatablehistorialArea_']);


    /* Asignacion Area Edificio */
    Route::get('asignacionEdificio', [AreaEdificioController::class, 'index']);
    Route::get('getDatatableAsisgnacionEdificioServerSide', [AreaEdificioController::class, 'getDatatableAsisgnacionEdificioServerSide']);
    Route::post('guardarAsignacionAreaEdificio', [AreaEdificioController::class, 'guardarAsignacionAreaEdificio']);
    Route::post('eliminarAsignacionAreaEdificio', [AreaEdificioController::class, 'eliminarAsignacionAreaEdificio']);
    Route::post('actualizarActivos', [AreaEdificioController::class, 'actualizarActivos']);
});
