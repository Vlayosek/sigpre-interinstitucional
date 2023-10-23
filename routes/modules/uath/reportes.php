<?php

use App\Http\Controllers\Uath\Reportes\ReporteAtrasosController;
use Illuminate\Support\Facades\Route;

Route::group([
    'middleware' => ['auth'],
    'as' => 'reportes.', 'prefix' => 'reportes/uath'
], function () {

    Route::get('familiares', 'Uath\Reportes\ReporteCargasFamiliaresController@index');
});

/* REPORTE DE MARCACIONES */
Route::group([
    'middleware' => ['auth'],
    'middleware' => ['role:ADMINISTRADOR DE MARCACIONES|CONSULTA ASISTENCIA'],
    'as' => 'reportes.', 'prefix' => 'reportes'
], function () {

    Route::get('marcaciones', 'Uath\Reportes\ReporteMarcacionController@index');
    Route::get('getDatatableMarcacionesControllerServerSide/{fecha_inicio}/{fecha_fin}/{filtro}', 'Uath\Reportes\ReporteMarcacionController@getDatatableMarcacionesControllerServerSide');
    Route::get('asistencia/exportar/{inicio}/{fin}', 'Uath\Reportes\ReporteMarcacionController@exportarExcel');
    Route::get('asistencia/exportarExcelGET/{inicio}/{fin}/{filtro}/{area}', 'Uath\Reportes\ReportesController@marcacionExportarExcelGET');
    Route::get('asistencia/exportarExcelGETCollect/{inicio}/{fin}/{filtro}/{generar}/{meses}/{anios}', 'Uath\Reportes\ReportesController@marcacionExportarCollectExcelGET');
    Route::post('asistencia/exportarExcelGETCollectPOST', 'Uath\Reportes\ReportesController@exportarExcelGETCollectPOST');
    /*MARCACIONES GENERADAS */
    Route::get('consultaMarcacionesGeneradas', 'Uath\Reportes\ReporteMarcacionController@consultaMarcacionesGeneradas');
});

Route::group([
    'middleware' => ['auth'],
    'as' => 'reportes.', 'prefix' => 'reportes'
], function () {

    Route::get('revision/teletrabajo/exportarExcelGET/{inicio}/{fin}/{filtro}', 'Uath\Reportes\ReportesController@revisionExportarExcelGET');
    Route::get('teletrabajo/exportarExcelGET/{inicio}/{fin}/{filtro}', 'Uath\Reportes\ReportesController@planificacionExportarExcelGET');

    //   Route::post('getDatatableMarcacionesControllerServerSide','Uath\Reportes\ReporteMarcacionController@getDatatableMarcacionesControllerServerSidePOST');

});

Route::group([
    'middleware' => ['auth'],
    'as' => 'reportes.', 'prefix' => 'reportes'
], function () {

    Route::get('domicilios', 'Uath\Reportes\ReportesController@domicilios');
    Route::get('getDatatableReporteDomicilio', 'Uath\Reportes\ReportesController@getDatatableReporteDomicilio');

    //   Route::post('getDatatableMarcacionesControllerServerSide','Uath\Reportes\ReporteMarcacionController@getDatatableMarcacionesControllerServerSidePOST');

});

Route::group([
    'middleware' => ['auth'],
    'middleware' => ['role:ADMINISTRADOR DE MARCACIONES|CONSULTA ASISTENCIA'],
    'as' => 'reportes.', 'prefix' => 'reportes'
], function () {

    Route::get('atrasos', [ReporteAtrasosController::class, 'index']);
    Route::get('consultaMarcacionesGeneradasporAtrasos/{mes}/{anio}/{maximo_atrasos}', [ReporteAtrasosController::class, 'consultaMarcacionesGeneradasporAtrasos']);

    // Route::get('generarPDFMarcacionesAtrasado/{identificacion}/{mes}/{anio}/{area}/{cargo}/{atrasos}',[ReporteAtrasosController::class,'generarPDFMarcacionesAtrasado']);
    Route::get('generarPDFMarcacionesAtrasado/{identificacion}/{mes}/{anio}', [ReporteAtrasosController::class, 'generarPDFMarcacionesAtrasado']);
});
