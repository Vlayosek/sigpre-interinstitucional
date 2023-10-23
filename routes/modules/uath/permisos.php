<?php

use App\Http\Controllers\Uath\Permisos\API\PermisosController as APIPermisosController;
use App\Http\Controllers\Uath\Permisos\PermisosAdministradorController;
use App\Http\Controllers\Uath\Permisos\PermisosAnulacionController;
use App\Http\Controllers\Uath\Permisos\PermisosAprobacionController;
use App\Http\Controllers\Uath\Permisos\PermisosController;
use App\Http\Controllers\Uath\Permisos\PermisosRevisionController;
use Illuminate\Support\Facades\Route;

Route::group([
    'middleware' => ['auth'],
    'as' => 'permisos.', 'prefix' => 'permisos'
], function () {
    /* REGISTRO DE PERMISOS */
    Route::get('index', [PermisosController::class, 'index']);
    Route::post('guardarRegistro', [PermisosController::class, 'guardarRegistro']);
    Route::post('guardarNuevoArchivo', [PermisosController::class, 'guardarNuevoArchivo']);

    Route::post('editarPermiso', [PermisosController::class, 'editarPermiso']);
    Route::post('eliminarPermiso', [PermisosController::class, 'eliminarPermiso']);
    Route::get('getDatatablePermisosServerSide/{fecha_inicio}/{fecha_fin}', [PermisosController::class, 'getDatatablePermisosServerSide']);
    Route::get('datatableHistoricoPermiso/{id}', [PermisosController::class, 'datatableHistoricoPermiso']);
    Route::post('anularPermiso', [PermisosController::class, 'anularPermiso']);
    Route::post('cargarPDF', [PermisosController::class, 'cargarPDF']);
});
Route::group([
    'middleware' => ['auth'],
    'middleware' => ['role:JEFE DE AREA'],
    'as' => 'permisos.', 'prefix' => 'permisos'
], function () {
    /* APROBACION DE PERMISOS */
    Route::get('aprobacion', [PermisosAprobacionController::class, 'index']);
    Route::get('getDatatablePermisosAprobacionServerSide/{tipo}/{fecha_inicio}/{fecha_fin}', [PermisosAprobacionController::class, 'getDatatablePermisosAprobacionServerSide']);
    Route::post('aprobarPermisoJefe', [PermisosAprobacionController::class, 'aprobarPermisoJefe']);
    Route::post('rechazarPermisoJefe', [PermisosAprobacionController::class, 'rechazarPermisoJefe']);
    Route::post('consultaEstados', [PermisosAprobacionController::class, 'consultaEstados']);
    Route::post('aprobarPermisosSeleccionados', [PermisosAprobacionController::class, 'aprobarPermisosSeleccionados']);
});
Route::group([
    'middleware' => ['auth'],
    'middleware' => ['role:SEGURIDAD OCUPACIONAL'],
    'as' => 'permisos.', 'prefix' => 'permisos'
], function () {
    /* APROBACION DE PERMISOS */
    Route::get('revision', [PermisosRevisionController::class, 'index']);
    Route::get('getDatatablePermisosRevisionServerSide/{tipo}/{fecha_inicio}/{fecha_fin}', [PermisosRevisionController::class, 'getDatatablePermisosRevisionServerSide']);
    Route::post('aprobarRevisionPermisoJefe', [PermisosRevisionController::class, 'aprobarPermisoJefe']);
    Route::post('rechazarRevisionPermisoJefe', [PermisosRevisionController::class, 'rechazarPermisoJefe']);
    Route::post('consultaEstadosRevision', [PermisosRevisionController::class, 'consultaEstados']);
    Route::post('regresarEsperaRevision', [PermisosRevisionController::class, 'regresarEsperaRevision']);
});
Route::group([
    'middleware' => ['auth'],
    'middleware' => ['role:ANULAR PERMISOS TH'],
    'as' => 'permisos.', 'prefix' => 'permisos'
], function () {
    /* APROBACION DE PERMISOS */
    Route::get('anulaciones', [PermisosAnulacionController::class, 'index']);
    Route::get(
        'getDatatablePermisosAnulacionServerSide/{tipo}/{fecha_inicio}/{fecha_fin}',
        [PermisosAnulacionController::class, 'getDatatablePermisosAnulacionServerSide']
    );
    Route::post('aprobarAnulacionPermisoJefe', [PermisosAnulacionController::class, 'aprobarPermisoJefe']);
    Route::post('rechazarAnulacionPermisoJefe', [PermisosAnulacionController::class, 'rechazarPermisoJefe']);
    Route::post('consultaEstadosAnulacion', [PermisosAnulacionController::class, 'consultaEstados']);
});
Route::group([
    'middleware' => ['auth'],
    //   'middleware' => ['role:ADMINISTRADOR DE UATH'],
    'as' => 'permisos.', 'prefix' => 'permisos'
], function () {
    /* CONSULTARPERMISOS */
    Route::get('consultar', [PermisosAdministradorController::class, 'index']);
    Route::get('getDatatablePermisosConsultarServerSide/{id}/{filtro}/{fecha_inicio}/{fecha_fin}', [PermisosAdministradorController::class, 'getDatatablePermisosConsultarServerSide']);
    Route::post('actaPDF', [PermisosAdministradorController::class, 'actaPDF']);
    Route::post('generar_acta_vacaciones', [PermisosAdministradorController::class, 'generar_acta_vacaciones']);
});


/*EXTERNO DE APROBACION Y RECHAZO DE PERMISOS */
Route::get('api/aprobarPermiso/{id}/{identificacion}', [APIPermisosController::class, 'aprobarPermiso']);
Route::get('api/reprobarPermiso/{id}/{identificacion}', [APIPermisosController::class, 'reprobarPermiso']);
/*EXTERNO DE APROBACION Y RECHAZO DE PERMISOS */
