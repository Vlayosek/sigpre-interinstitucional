<?php

use App\Http\Controllers\Compromisos\CompromisosAdministradorController;
use App\Http\Controllers\Compromisos\CompromisosController;
use App\Http\Controllers\Compromisos\DelegadoController;
use App\Http\Controllers\Compromisos\InstitucionController;
use App\Http\Controllers\Compromisos\MinistroController;
use App\Http\Controllers\Compromisos\ReportesController;
use App\Http\Controllers\Compromisos\BusquedasAvanzadasController;
use App\Http\Controllers\Compromisos\CalendarioController;
use App\Http\Controllers\Compromisos\MigracionController;
use App\Http\Controllers\Compromisos\BusquedaSelectoresController;
use App\Http\Controllers\HomeController;

use Illuminate\Support\Facades\Route;

/* RUTAS DE MONITOR DE COMPROMISOS */

Route::post('cargarHtmlLugares', [HomeController::class, 'cargarHtmlLugares'])->middleware(['auth']);

Route::group([
    'middleware' => ['auth'],
    'middleware' => ['role:MINISTRO'],
    'as' => 'compromisos_corresponsable.', 'prefix' => 'compromisos/corresponsable'
], function () {
    require __DIR__ . '/rutas_generales.php';

    Route::get('index', [CompromisosController::class, 'corresponsableIndex']);
    Route::get('consulta/{asignaciones}', [CompromisosController::class, 'consultaCorresponsable']);
    Route::get('getDatatableCompromisosServerSide/{tipo}/{tabla}/{asignaciones}/{temporales}/{pendientes}', [CompromisosController::class, 'getDatatableCorresponsableServerSide']);
    Route::post('getCargaDatosInstitucion', [CompromisosController::class, 'getCargaDatosInstitucion']);
    Route::post('getCargaDatosInstitucionCorresponsables', [CompromisosController::class, 'getCargaDatosInstitucionCorresponsables']);
});
Route::group([
  'middleware' => ['auth'],
  'middleware' => ['role:MONITOR|MINISTRO|CONSULTA COMPROMISOS|ADMINISTRADOR COMPROMISOS|administrator'],
  'as' => 'compromisos.', 'prefix' => 'compromisos'
], function () {
  Route::post('busquedaCompromisosporReporte', [ReportesController::class, 'busquedaCompromisosporReporte'])->name('busquedaCompromisosporReporte')->middleware(['auth', 'role:ADMINISTRADOR COMPROMISOS|administrator|MONITOR|MINISTRO|CONSULTA COMPROMISOS']);
});

Route::group([
    'middleware' => ['auth'],
    'middleware' => ['role:MONITOR|MINISTRO|CONSULTA COMPROMISOS'],
    'as' => 'compromisos.', 'prefix' => 'compromisos'
], function () {

    require __DIR__ . '/rutas_generales.php';
    Route::get('getDatatableCompromisosGETServerSide', [CompromisosController::class, 'getDatatableCompromisosGETServerSide']);
    Route::post('getDatatableCompromisosPOSTServerSide', [CompromisosController::class, 'getDatatableCompromisosPOSTServerSide']);
    Route::get('getDatatableCompromisosServerSide/{tipo}/{tabla}/{asignaciones}/{temporales}/{pendientes}/{filtro}/{institucion_id}/{gabinete_id}', [CompromisosController::class, 'getDatatableCompromisosServerSide']);
    Route::get('gestion', [CompromisosController::class, 'index']);
    Route::get('calendarios', [CalendarioController::class, 'index']);
    Route::get('calendario_finalizacion', [CalendarioController::class, 'calendario_finalizacion']);
    Route::get('calendario', [CalendarioController::class, 'calendario']);
    Route::get('calendarioReporte', [CalendarioController::class, 'calendarioReporte']);
    Route::get('getDatatableCompromisoDetalleCalendario/{id}/', [CalendarioController::class, 'getDatatableCompromisoDetalleCalendario']);
    Route::get('getDatatableCompromisoCalendarioFinalizacion/{id}/', [CalendarioController::class, 'getDatatableCompromisoCalendarioFinalizacion']);
    Route::post('exportarExcelCalendarioReportes', [ReportesController::class, 'exportarExcelCalendarioReportes']);


    Route::post('consultaEstados', [CompromisosController::class, 'consultaEstados']);
    Route::get('consulta/{asignaciones}', [CompromisosController::class, 'consulta']);
    Route::post('editarMensaje', [CompromisosController::class, 'editarMensaje']);
    Route::post('rechazarObjetivo', [CompromisosController::class, 'rechazarObjetivo']);
    Route::post('aprobarObjetivo', [CompromisosController::class, 'aprobarObjetivo']);
    Route::post('desbloquearObjetivo', [CompromisosController::class, 'desbloquearObjetivo']);
    /*BUSQUEDAS AVANZADAS*/
    Route::post('filtroInstitucionBusqueda', [BusquedasAvanzadasController::class, 'filtroInstitucionBusqueda']);
    Route::post('filtroGabineteBusqueda', [BusquedasAvanzadasController::class, 'filtroGabineteBusqueda']);
    Route::get('getDatatableBusquedasServerSide/{gabinete_id_busqueda}/{institucion_id_busqueda}/{monitor_busqueda}/{tipo}', [BusquedasAvanzadasController::class, 'getDatatableBusquedasServerSide']);
    Route::get('getDatatableBusquedaPorTipo/{id}/{tipo}', [BusquedasAvanzadasController::class, 'getDatatableBusquedaPorTipo']);

    Route::post('verBusqueda', [BusquedasAvanzadasController::class, 'verBusqueda']);
    Route::post('busquedaAvanzadaCompromisos', [BusquedasAvanzadasController::class, 'busquedaAvanzadaCompromisos']);
    /*BUSQUEDAS AVANZADAS*/
});


/* RUTAS DE ADMINISTRADOR DE COMPROMISOS */

Route::group([
    'middleware' => ['auth'],
    'middleware' => ['role:ADMINISTRADOR COMPROMISOS'],
    'as' => 'asignacion.', 'prefix' => 'asignacion'
], function () {
    Route::get('monitor', [CompromisosAdministradorController::class, 'index']);
    Route::get('getDatatableAsignacionMonitorServerSide', [CompromisosAdministradorController::class, 'getDatatableAsignacionMonitorServerSide']);
    Route::post('guardarAsignacion', [CompromisosAdministradorController::class, 'guardarAsignacion']);
    Route::post('editar', [CompromisosAdministradorController::class, 'editar']);
    Route::post('eliminar', [CompromisosAdministradorController::class, 'eliminar']);
    Route::get('getDatatableEliminadosServerSide/{institucion}/{fecha_inicio}/{fecha_fin}', [CompromisosAdministradorController::class, 'getDatatableEliminadosServerSide']);
    Route::get('getDatatableNotificacionesServerSide/{institucion}/{tipo}/{fecha_inicio}/{fecha_fin}', [CompromisosAdministradorController::class, 'getDatatableNotificacionesServerSide']);
});
/* RUTAS DE ADMINISTRADOR DE COMPROMISOS */

/*DELEGADOS POR INSTITUCION*/
Route::group([
    'middleware' => ['auth'],
    'middleware' => ['role:ADMINISTRADOR COMPROMISOS'],
    'as' => 'compromisos.', 'prefix' => 'compromisos'
], function () {
    Route::get('delegados', [DelegadoController::class, 'delegados']);
    Route::get('getDatatableDelegadoInstitucionServerSide', [DelegadoController::class, 'getDatatableDelegadoInstitucionServerSide']);
    Route::post('guardarDelegadoInstitucion', [DelegadoController::class, 'guardarDelegadoInstitucion']);
    Route::post('editarDelegadoInstitucion', [DelegadoController::class, 'editarDelegadoInstitucion']);
    Route::post('eliminarDelegadoInstitucion', [DelegadoController::class, 'eliminarDelegadoInstitucion']);
});

//REPORTES

Route::group([
    'middleware' => ['auth'],
    'middleware' => ['role:ADMINISTRADOR COMPROMISOS|MONITOR|CONSULTA COMPROMISOS'],
    'as' => 'compromisos.', 'prefix' => 'compromisos'
], function () {
    //DELEGADOS
    Route::get('instituciones', [InstitucionController::class, 'delegados']);
    Route::get('getDatatableInstitucionServerSide', [InstitucionController::class, 'getDatatableInstitucionServerSide']);
    Route::post('guardarInstitucion', [InstitucionController::class, 'guardarInstitucion']);
    Route::post('guardarGabinete', [InstitucionController::class, 'guardarGabinete']);
    Route::post('consultaInstitucion', [InstitucionController::class, 'consultaInstitucion']);
    Route::get('getDatatableGabineteServerSide', [InstitucionController::class, 'getDatatableGabineteServerSide']);
    Route::post('eliminarInstitucion', [InstitucionController::class, 'eliminarInstitucion']);
    Route::post('editaInstitucion', [InstitucionController::class, 'editaInstitucion']);
    Route::post('editaGabinete', [InstitucionController::class, 'editaGabinete']);
    Route::post('guardaEditaGabinete', [InstitucionController::class, 'guardaEditaGabinete']);
    //DELEGADOS
    //MINISTROS

    Route::get('ministros', [MinistroController::class, 'index']);
    Route::get('getDatatableUsuariosMinistrosServerSide', [MinistroController::class, 'getDatatableUsuariosMinistrosServerSide']);
    Route::post('cambiarEstadoUsuario', [MinistroController::class, 'cambiarEstadoUsuario']);
    Route::post('guardarUsuario', [MinistroController::class, 'guardarUsuario']);
    Route::post('editarUsuario', [MinistroController::class, 'editarUsuario']);
    Route::post('generacion_clave', [MinistroController::class, 'generacion_clave']);
    //MINISTROS

    //MIGRACIÓN
    Route::get('migracion', [MigracionController::class, 'migracion']);
    Route::get('getDatatableBuscarCompromisosServerSide/{id}', [MigracionController::class, 'getDatatableBuscarCompromisosServerSide']);
    Route::post('migrarCompromisos', [MigracionController::class, 'migrarCompromisos']);
    Route::get('getDatatableCodigosMigradosServerSide', [MigracionController::class, 'getDatatableCodigosMigradosServerSide']);
    //MIGRACIÓN

    //REPORTES

    Route::get('reportes', [ReportesController::class, 'reportes']);
    Route::post('filtro_institucion_cc', [ReportesController::class, 'filtro_institucion_cc']);
    Route::post('filtro_gabinete_cc', [ReportesController::class, 'filtro_gabinete_cc']);
    Route::post('consulta_compromiso', [ReportesController::class, 'consulta_compromiso']);
    Route::post('consulta_periodo_actual', [ReportesController::class, 'consulta_periodo_actual']);
    Route::get('reportes_ministerio', [ReportesController::class, 'reportes_ministerio']);
    Route::post('exportarExcelMinisterio', [ReportesController::class, 'exportarExcelMinisterio']);

    Route::post('exportarExcelResumenGabinete', [ReportesController::class, 'exportarExcelResumenGabinete']);
    Route::post('exportarExcelGabinete', [ReportesController::class, 'exportarExcelGabinete']);
    Route::post('exportarExcelEjecutivo', [ReportesController::class, 'exportarExcelEjecutivo']);
    Route::post('filtro_compromiso_consulta', [ReportesController::class, 'filtro_compromiso_consulta']);
    Route::get('getDatatableReporteCompromisoIndividualServerSide/{filtro_gestion}/{filtro_compromiso}/{filtro_ubicacion}/{filtro_gabinete}/{filtro_institucion}/{tipo_detalle}', [ReportesController::class, 'getDatatableReporteCompromisoIndividualServerSide']);
    Route::post('exportarExcelCumplidos', [ReportesController::class, 'exportarExcelCumplidos']);
    Route::post('consultaDatosUbicacionDashboard', [ReportesController::class, 'consultaDatosUbicacionDashboard']);

    //REPORTE MAPA CALOR2
    Route::post('consultaMostrarGraficoEstado', [ReportesController::class, 'consultaMostrarGraficoEstado']);
    Route::post('reporteDinamico_tc', [ReportesController::class, 'reporteDinamico_tc']);

    Route::post('generarPdf', [ReportesController::class, 'generarPdf']);
    Route::get('formatoImprimir', [ReportesController::class, 'formatoImprimir']);

    //REPORTES

});

Route::post('buscarInstitucion', [CompromisosController::class, 'buscarInstitucion'])->name('buscarInstitucion')->middleware(['auth', 'role:ADMINISTRADOR COMPROMISOS|MONITOR|MINISTRO']);
Route::post('buscarResponsable', [CompromisosController::class, 'buscarResponsable'])->name('buscarResponsable')->middleware(['auth', 'role:ADMINISTRADOR COMPROMISOS|MONITOR|MINISTRO']);
Route::post('buscarInstitucionCo', [CompromisosController::class, 'buscarInstitucionCo'])->name('buscarInstitucionCo')->middleware(['auth', 'role:ADMINISTRADOR COMPROMISOS|MONITOR|MINISTRO']);

Route::post('busquedaCompromisos', [BusquedaSelectoresController::class, 'busquedaCompromisos'])->name('busquedaCompromisos')->middleware(['auth', 'role:ADMINISTRADOR COMPROMISOS|MONITOR|MINISTRO']);

/* BUSCAR USUARIOS CON ROLES DE MONITOR */
Route::post('buscarMonitor', [CompromisosController::class, 'buscarMonitor'])->name('buscarMonitor')->middleware(['auth', 'role:ADMINISTRADOR COMPROMISOS|MONITOR|MINISTRO']);
/* BUSCAR INSTITUCIONES NO ASIGNADAS */
Route::post('buscarInstitucionMonitor', [CompromisosController::class, 'buscarInstitucionMonitor'])->name('buscarInstitucionMonitor')->middleware(['auth', 'role:ADMINISTRADOR COMPROMISOS|MONITOR|MINISTRO']);
Route::get(
    'reportes/compromisos/exportarExcelGET/{fecha_inicio}/{fecha_fin}/{tipo}/{tabla}/{asignaciones}/{temporales}/{pendientes}/{filtro}/{institucion_id}/{gabinete_id}',
    [ReportesController::class, 'ExportarExcelGET']
)->middleware(['auth', 'role:ADMINISTRADOR COMPROMISOS|MONITOR|MINISTRO']);
Route::post('reportes/compromisos/exportarExcelAvanzado', [ReportesController::class, 'exportarExcelAvanzado'])->middleware(['auth', 'role:ADMINISTRADOR COMPROMISOS|MONITOR|MINISTRO']);
