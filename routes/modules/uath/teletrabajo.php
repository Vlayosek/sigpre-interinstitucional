<?php

use Illuminate\Support\Facades\Route;

Route::group([
    'middleware' => ['auth'],
    'as' => 'teletrabajo.', 'prefix' => 'teletrabajo'
], function () {
    /* REGISTRO DE PLANIFICACION */
    Route::get('planificacion', 'Uath\Teletrabajo\PlanificacionTeletrabajoController@index');
    Route::get(
        'getDatatablePlanificacionServerSide/{mes}/{fecha_inicio}/{fecha_fin}',
        'Uath\Teletrabajo\PlanificacionTeletrabajoController@getDatatablePlanificacionServerSide'
    );
    Route::get(
        'getDatatablePlanificacionFuncionarioServerSide/{id}/{reinicia}',
        'Uath\Teletrabajo\PlanificacionTeletrabajoController@getDatatablePlanificacionFuncionarioServerSide'
    );

    Route::post(
        'guardarPlanificacion',
        'Uath\Teletrabajo\PlanificacionTeletrabajoController@guardarPlanificacion'
    );
    Route::post(
        'editarPlanificacion',
        'Uath\Teletrabajo\PlanificacionTeletrabajoController@editarPlanificacion'
    );
    Route::post(
        'eliminarPlanificacion',
        'Uath\Teletrabajo\PlanificacionTeletrabajoController@eliminarPlanificacion'
    );
});
Route::group([
    'middleware' => ['auth'],
    'as' => 'teletrabajo.', 'prefix' => 'teletrabajo'
], function () {
    /* REGISTRO DE ACTIVIDADES */

    Route::get('actividades', 'Uath\Teletrabajo\ActividadesTeletrabajoController@index');
    Route::get(
        'getDatatableActividadesTeletrabajoServerSide/{mes}/{fecha_inicio}/{fecha_fin}',
        'Uath\Teletrabajo\ActividadesTeletrabajoController@getDatatableActividadesTeletrabajoServerSide'
    );
    Route::post(
        'cargarFechasTeletrabajo',
        'Uath\Teletrabajo\ActividadesTeletrabajoController@cargarFechasTeletrabajo'
    );
    Route::post(
        'guardarActividadTeletrabajo',
        'Uath\Teletrabajo\ActividadesTeletrabajoController@guardarActividadTeletrabajo'
    );
    Route::post(
        'editarActividades',
        'Uath\Teletrabajo\ActividadesTeletrabajoController@editarActividades'
    );
    Route::get('reporteActividades/{fecha_inicio}/{fecha_fin}', 'Uath\Teletrabajo\ActividadesTeletrabajoController@reporteActividades');
});
Route::group([
    'middleware' => ['auth'],
    'as' => 'teletrabajo.', 'prefix' => 'teletrabajo'
], function () {
    /* REGISTRO DE APROBACION DE ACTIVIDADES */
    Route::get('aprobacion', 'Uath\Teletrabajo\AprobacionTeletrabajoController@index');
    Route::get(
        'getDatatableActividadesAprobadasTeletrabajoServerSide/{mes}/{estado}/{fecha_inicio}/{fecha_fin}',
        'Uath\Teletrabajo\AprobacionTeletrabajoController@getDatatableActividadesAprobadasTeletrabajoServerSide'
    );
    Route::post(
        'aprobarActividades',
        'Uath\Teletrabajo\AprobacionTeletrabajoController@aprobarActividades'
    );
    Route::post(
        'rechazarActividades',
        'Uath\Teletrabajo\AprobacionTeletrabajoController@rechazarActividades'
    );
    Route::post(
        'consultaEstados',
        'Uath\Teletrabajo\AprobacionTeletrabajoController@consultaEstados'
    );
    Route::post(
        'aprobarTeletrabajoSeleccionados',
        'Uath\Teletrabajo\AprobacionTeletrabajoController@aprobarTeletrabajoSeleccionados'
    );
    Route::get('reporteActividadesAprobados/{fecha_inicio}/{fecha_fin}', 'Uath\Teletrabajo\AprobacionTeletrabajoController@reporteActividadesAprobados');
});
Route::group([
    'middleware' => ['auth'],
    'as' => 'teletrabajo.', 'prefix' => 'teletrabajo'
], function () {
    /* REGISTRO DE APROBACION DE ACTIVIDADES */
    Route::get('revision', 'Uath\Teletrabajo\RevisionTeletrabajoController@index');
    Route::get(
        'consultaDatosRevisionGET',
        'Uath\Teletrabajo\RevisionTeletrabajoController@consultaDatosRevisionGET'
    );

    Route::get(
        'getDatatableActividadesRevisionTeletrabajoServerSide/{inicio}/{fin}/{area_id}/{estado}',
        'Uath\Teletrabajo\RevisionTeletrabajoController@getDatatableActividadesRevisionTeletrabajoServerSide'
    );
    Route::post(
        'consultaEstadosRevision',
        'Uath\Teletrabajo\RevisionTeletrabajoController@consultaEstadosRevision'
    );
    Route::post(
        'aprobarRevisionActividades',
        'Uath\Teletrabajo\RevisionTeletrabajoController@aprobarRevisionActividades'
    );
    Route::post(
        'rechazarRevisionActividades',
        'Uath\Teletrabajo\RevisionTeletrabajoController@rechazarRevisionActividades'
    );
    Route::get('reporteActividadesPorArea/{fecha_inicio}/{fecha_fin}/{area}', 'Uath\Teletrabajo\RevisionTeletrabajoController@reporteActividadesPorArea');
});

Route::group([
    'middleware' => ['auth'],
    'as' => 'reporte.', 'prefix' => 'reporte'
], function () {
    /*REPORTE DE TELETRABAJO */
    Route::get('marcaciones', 'Uath\Asistencia\ReportesController@index');
    Route::get('planificacion', 'Uath\Teletrabajo\ReporteController@index');
    Route::get(
        'getDatatableActividadesReporteTeletrabajoServerSide/{inicio}/{fin}/{area_id}/{estado}',
        'Uath\Teletrabajo\ReporteController@getDatatableActividadesReporteTeletrabajoServerSide'
    );
    Route::get(
        'consultaDatosReporteGET',
        'Uath\Teletrabajo\ReporteController@consultaDatosReporteGET'
    );
});
