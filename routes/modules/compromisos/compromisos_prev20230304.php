<?php

use App\Http\Controllers\Compromisos\CompromisosAdministradorController;
use App\Http\Controllers\Compromisos\CompromisosController;
use App\Http\Controllers\Compromisos\DelegadoController;
use App\Http\Controllers\Compromisos\InstitucionController;
use App\Http\Controllers\Compromisos\MinistroController;
use App\Http\Controllers\Compromisos\ReportesController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Compromisos\MigracionController;

/* RUTAS DE ADMINISTRADOR DE COMPROMISOS */

Route::group([
    'middleware' => ['auth'],
    'middleware' => ['role:ADMINISTRADOR COMPROMISOS'],
    'as' => 'asignacion.', 'prefix' => 'asignacion'
], function () {
    Route::get('monitor', [CompromisosAdministradorController::class, 'index']);
    Route::get(
        'getDatatableAsignacionMonitorServerSide',
        [CompromisosAdministradorController::class, 'getDatatableAsignacionMonitorServerSide']
    );
    Route::post('guardarAsignacion', [CompromisosAdministradorController::class, 'guardarAsignacion']);
    Route::post('editar', [CompromisosAdministradorController::class, 'editar']);
    Route::post('eliminar', [CompromisosAdministradorController::class, 'eliminar']);
});

/* RUTAS DE MONITOR DE COMPROMISOS */

Route::group([
    'middleware' => ['auth'],
    'middleware' => ['role:MINISTRO'],
    'as' => 'compromisos_corresponsable.', 'prefix' => 'compromisos/corresponsable'
], function () {
    Route::get('getDatatableCompromisosGETServerSide', [CompromisosController::class, 'getDatatableCompromisosGETServerSide']);    Route::get('getDatatableCompromisosServerSide/{tipo}/{tabla}/{asignaciones}/{temporales}/{pendientes}/{filtro}/{institucion_id}/{gabinete_id}', [CompromisosController::class, 'getDatatableCompromisosServerSide']);

    Route::get('index', [CompromisosController::class, 'corresponsableIndex']);
    Route::get('consulta/{asignaciones}', [CompromisosController::class, 'consultaCorresponsable']);
    Route::post('aprobar', [CompromisosController::class, 'aprobar']);
    Route::post('negar', [CompromisosController::class, 'negar']);
    Route::get('tipos', [CompromisosController::class, 'tiposIndex']);

    Route::post('eliminar/tipo', [CompromisosController::class, 'eliminarTipo']);
    Route::post('guardar/tipo', [CompromisosController::class, 'guardarTipo']);
    Route::get('editar/tipo/{id}', [CompromisosController::class, 'editarTipo']);
    Route::get('provincias', [HomeController::class, 'provincias']);
    Route::get('cantonParroquia/{arreglo}', [HomeController::class, 'cantonParroquia']);

    Route::get('getDatatableSolicitudServerSide/{tipo}', [CompromisosController::class, 'getDatatableSolicitudServerSide']);
    Route::post('guardar/tipo', [CompromisosController::class, 'guardarTipo']);



    Route::get('getDatatableCompromisosServerSide/{tipo}/{tabla}/{asignaciones}/{temporales}/{pendientes}', [CompromisosController::class, 'getDatatableCorresponsableServerSide']);
    Route::get('getDatatableAntecedentesServerSide/{tipo}', [CompromisosController::class, 'getDatatableAntecedentesServerSide']);
    Route::get('getDatatableArchivosServerSide/{tipo}', [CompromisosController::class, 'getDatatableArchivosServerSide']);
    Route::get('getDatatableHistoricoServerSide/{tipo}', [CompromisosController::class, 'getDatatableHistoricoServerSide']);
    Route::get('getDatatableMensajeServerSide/{tipo}', [CompromisosController::class, 'getDatatableMensajeServerSide']);
    Route::get('getDatatableObjetivosServerSide/{tipo}', [CompromisosController::class, 'getDatatableObjetivosServerSide']);
    Route::get('getDatatablePeriodosServerSide/{tipo}', [CompromisosController::class, 'getDatatablePeriodosServerSide']);
    Route::get('getDatatableAvancesServerSide/{tipo}', [CompromisosController::class, 'getDatatableAvancesServerSide']);

    Route::post('getDatatableUbicacionesServerSide', [CompromisosController::class, 'getDatatableUbicacionesServerSide']);

    Route::post('getCargaDatosInstitucion', [CompromisosController::class, 'getCargaDatosInstitucion']);
    Route::post('getCargaDatosInstitucionCorresponsables', [CompromisosController::class, 'getCargaDatosInstitucionCorresponsables']);

    Route::get('editar/compromiso/{id}', [CompromisosController::class, 'editarCompromiso']);

    Route::post('guardarAntecedente', [CompromisosController::class, 'guardarAntecedente']);
    Route::post('grabarArchivos', [CompromisosController::class, 'grabarArchivos']);
    Route::post('grabarMensaje', [CompromisosController::class, 'grabarMensaje']);
    Route::post('guardarObjetivos', [CompromisosController::class, 'guardarObjetivos']);
    Route::post('guardarCompromiso', [CompromisosController::class, 'guardarCompromiso']);
    Route::post('guardarUbicacion', [CompromisosController::class, 'guardarUbicacion']);
    Route::post('guardarAvance', [CompromisosController::class, 'guardarAvance']);

    Route::post('eliminar/compromiso', [CompromisosController::class, 'eliminarCompromiso']);
    Route::post('eliminar/archivo', [CompromisosController::class, 'eliminarArchivo']);
    Route::post('eliminar/mensaje', [CompromisosController::class, 'eliminarMensaje']);
    Route::post('eliminar/objetivo', [CompromisosController::class, 'eliminarObjetivo']);
    Route::post('eliminar/antecedente', [CompromisosController::class, 'eliminarAntecedente']);
    Route::post('eliminar/avance', [CompromisosController::class, 'eliminarAvance']);
    Route::post('aprobar/avance', [CompromisosController::class, 'aprobarAvance']);
    Route::post('negar/avance', [CompromisosController::class, 'negarAvance']);

    Route::post('leerMensaje', [CompromisosController::class, 'leerMensaje']);
    Route::post('descargarArchivo', [CompromisosController::class, 'descargarArchivo']);
    Route::post('crearCodigo', [CompromisosController::class, 'crearCodigo']);
    Route::post('editaPeriodo', [CompromisosController::class, 'editaPeriodo']);
    Route::post('guardarPeriodo', [CompromisosController::class, 'guardarPeriodo']);
    Route::post('cargarImagenes', [CompromisosController::class, 'cargarImagenes']);
});
Route::get(
    'reportes/compromisos/exportarExcelGET/{fecha_inicio}/{fecha_fin}/{tipo}/{tabla}/{asignaciones}/{temporales}/{pendientes}/{filtro}/{institucion_id}/{gabinete_id}',
    [ReportesController::class, 'ExportarExcelGET']
);

Route::group([
    'middleware' => ['auth'],
    'middleware' => ['role:MONITOR|MINISTRO'],
    'as' => 'compromisos.', 'prefix' => 'compromisos'
], function () {
    Route::get('gestion', [CompromisosController::class, 'index']);
    Route::get('calendario', [CompromisosController::class, 'calendario']);

    Route::post('consultaEstados', [CompromisosController::class, 'consultaEstados']);

    Route::get('consulta/{asignaciones}', [CompromisosController::class, 'consulta']);
    Route::post('aprobar', [CompromisosController::class, 'aprobar']);
    Route::post('negar', [CompromisosController::class, 'negar']);
    Route::get('tipos', [CompromisosController::class, 'tiposIndex']);

    Route::post('editarMensaje', [CompromisosController::class, 'editarMensaje']);
    Route::post('eliminar/tipo', [CompromisosController::class, 'eliminarTipo']);
    Route::post('guardar/tipo', [CompromisosController::class, 'guardarTipo']);
    Route::get('editar/tipo/{id}', [CompromisosController::class, 'editarTipo']);
    Route::get('provincias', [HomeController::class, 'provincias']);
    Route::get('cantonParroquia/{arreglo}', [HomeController::class, 'cantonParroquia']);

    Route::post('getDatatableCompromisosPOSTServerSide', [CompromisosController::class, 'getDatatableCompromisosPOSTServerSide']);

    Route::get('getDatatableSolicitudServerSide/{tipo}', [CompromisosController::class, 'getDatatableSolicitudServerSide']);
    Route::get('getDatatableCompromisosGETServerSide', [CompromisosController::class, 'getDatatableCompromisosGETServerSide']);    Route::get('getDatatableCompromisosServerSide/{tipo}/{tabla}/{asignaciones}/{temporales}/{pendientes}/{filtro}/{institucion_id}/{gabinete_id}', [CompromisosController::class, 'getDatatableCompromisosServerSide']);
    Route::post('cargarInstituciones', [CompromisosController::class, 'cargarInstituciones']);


    Route::get('getDatatableAntecedentesServerSide/{tipo}', [CompromisosController::class, 'getDatatableAntecedentesServerSide']);
    Route::get('getDatatableArchivosServerSide/{tipo}', [CompromisosController::class, 'getDatatableArchivosServerSide']);
    Route::get('getDatatableHistoricoServerSide/{tipo}', [CompromisosController::class, 'getDatatableHistoricoServerSide']);
    Route::get('getDatatableMensajeServerSide/{tipo}', [CompromisosController::class, 'getDatatableMensajeServerSide']);
    Route::get('getDatatableObjetivosServerSide/{tipo}', [CompromisosController::class, 'getDatatableObjetivosServerSide']);
    Route::get('getDatatablePeriodosServerSide/{tipo}', [CompromisosController::class, 'getDatatablePeriodosServerSide']);
    Route::get('getDatatableAvancesServerSide/{tipo}', [CompromisosController::class, 'getDatatableAvancesServerSide']);

    Route::post('getDatatableUbicacionesServerSide', [CompromisosController::class, 'getDatatableUbicacionesServerSide']);

    Route::post('getCargaDatosInstitucion', [CompromisosController::class, 'getCargaDatosInstitucion']);
    Route::post('getCargaDatosInstitucionCorresponsables', [CompromisosController::class, 'getCargaDatosInstitucionCorresponsables']);

    Route::get('editar/compromiso/{id}', [CompromisosController::class, 'editarCompromiso']);

    Route::post('guardarAntecedente', [CompromisosController::class, 'guardarAntecedente']);
    Route::post('grabarArchivos', [CompromisosController::class, 'grabarArchivos']);
    Route::post('grabarMensaje', [CompromisosController::class, 'grabarMensaje']);
    Route::post('guardarObjetivos', [CompromisosController::class, 'guardarObjetivos']);
    Route::post('guardarCompromiso', [CompromisosController::class, 'guardarCompromiso']);
    Route::post('guardarUbicacion', [CompromisosController::class, 'guardarUbicacion']);
    Route::post('guardarAvance', [CompromisosController::class, 'guardarAvance']);

    Route::post('eliminarCompromiso', [CompromisosController::class, 'eliminarCompromiso']);
    Route::post('eliminar/compromiso', [CompromisosController::class, 'eliminarCompromiso']);
    Route::post('eliminar/archivo', [CompromisosController::class, 'eliminarArchivo']);
    Route::post('eliminar/mensaje', [CompromisosController::class, 'eliminarMensaje']);
    Route::post('eliminar/objetivo', [CompromisosController::class, 'eliminarObjetivo']);
    Route::post('eliminar/antecedente', [CompromisosController::class, 'eliminarAntecedente']);
    Route::post('eliminar/avance', [CompromisosController::class, 'eliminarAvance']);
    Route::post('aprobar/avance', [CompromisosController::class, 'aprobarAvance']);
    Route::post('negar/avance', [CompromisosController::class, 'negarAvance']);

    Route::post('leerMensaje', [CompromisosController::class, 'leerMensaje']);
    Route::post('descargarArchivo', [CompromisosController::class, 'descargarArchivo']);
    Route::post('crearCodigo', [CompromisosController::class, 'crearCodigo']);
    Route::post('editaPeriodo', [CompromisosController::class, 'editaPeriodo']);
    Route::post('guardarPeriodo', [CompromisosController::class, 'guardarPeriodo']);
    Route::post('cargarImagenes', [CompromisosController::class, 'cargarImagenes']);

    Route::post('rechazarObjetivo', [CompromisosController::class, 'rechazarObjetivo']);
    Route::post('aprobarObjetivo', [CompromisosController::class, 'aprobarObjetivo']);
    Route::post('desbloquearObjetivo', [CompromisosController::class, 'desbloquearObjetivo']);
    //pestaña busquedas
    Route::post('filtroInstitucionBusqueda', [CompromisosController::class, 'filtroInstitucionBusqueda']);
    Route::post('filtroGabineteBusqueda', [CompromisosController::class, 'filtroGabineteBusqueda']);
    Route::get('getDatatableBusquedasServerSide/{gabinete_id_busqueda}/{institucion_id_busqueda}/{monitor_busqueda}/{tipo}', [CompromisosController::class, 'getDatatableBusquedasServerSide']);
    Route::post('verBusqueda', [CompromisosController::class, 'verBusqueda']);

    Route::post('busquedaAvanzadaCompromisos', [CompromisosController::class, 'busquedaAvanzadaCompromisos']);

   //MIGRACIÓN
    Route::get('migracion', [MigracionController::class, 'migracion']);
    Route::get('getDatatableBuscarCompromisosServerSide/{id}', [MigracionController::class, 'getDatatableBuscarCompromisosServerSide']);
    Route::post('migrarCompromisos', [MigracionController::class, 'migrarCompromisos']);
    Route::get('getDatatableCodigosMigradosServerSide', [MigracionController::class, 'getDatatableCodigosMigradosServerSide']);
    //MIGRACIÓN

});
Route::post('buscarInstitucion', [CompromisosController::class, 'buscarInstitucion'])->name('buscarInstitucion');
Route::post('buscarResponsable', [CompromisosController::class, 'buscarResponsable'])->name('buscarResponsable');


Route::post('buscarInstitucionCo', [CompromisosController::class, 'buscarInstitucionCo'])->name('buscarInstitucionCo');

/* BUSCAR USUARIOS CON ROLES DE MONITOR */
Route::post('buscarMonitor', [CompromisosController::class, 'buscarMonitor'])
    ->name('buscarMonitor');
/* BUSCAR INSTITUCIONES NO ASIGNADAS */
Route::post('buscarInstitucionMonitor', [CompromisosController::class, 'buscarInstitucionMonitor'])
    ->name('buscarInstitucionMonitor');
/*DELEGADOS POR INSTITUCION*/
Route::group([
    'middleware' => ['auth'],
    //    'middleware' => ['role:ADMINISTRADOR COMPROMISOS'],
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
    //'middleware' => ['role:ADMINISTRADOR COMPROMISOS|MONITOR|MINISTRO'],
    'as' => 'compromisos.', 'prefix' => 'compromisos'
], function () {
    Route::get('reportes', [ReportesController::class, 'reportes']);
    Route::post('filtro_institucion_cc', [ReportesController::class, 'filtro_institucion_cc']);
    Route::post('filtro_gabinete_cc', [ReportesController::class, 'filtro_gabinete_cc']);
    Route::post('consulta_compromiso', [ReportesController::class, 'consulta_compromiso']);
    Route::post('consulta_periodo_actual', [ReportesController::class, 'consulta_periodo_actual']);

    /*Route::get('getDatatableDelegadoInstitucionServerSide', [DelegadoController::class,'getDatatableDelegadoInstitucionServerSide']);
    Route::post('guardarDelegadoInstitucion',[DelegadoController::class,'guardarDelegadoInstitucion']);
    Route::post('editarDelegadoInstitucion',[DelegadoController::class,'editarDelegadoInstitucion']);
    Route::post('eliminarDelegadoInstitucion',[DelegadoController::class,'eliminarDelegadoInstitucion']);*/
    Route::get('reportes_ministerio', [ReportesController::class, 'reportes_ministerio']);
    //Route::get('getDatatableReporteMinisterioServerSide/{institucion_filtro}',[ReportesController::class,'getDatatableReporteMinisterioServerSide']);
    Route::post('exportarExcelMinisterio', [ReportesController::class, 'exportarExcelMinisterio']);
    Route::post('exportarExcelGabinete', [ReportesController::class, 'exportarExcelGabinete']);
    Route::post('consultaReporteEjecutivo', [ReportesController::class, 'consultaReporteEjecutivo']);
    Route::post('generaReporteEjecutivo', [ReportesController::class, 'generaReporteEjecutivo']);
    Route::post('filtro_compromiso_consulta', [ReportesController::class, 'filtro_compromiso_consulta']);
    Route::get('getDatatableReporteCompromisoIndividualServerSide/{filtro_gestion}/{filtro_compromiso}/{filtro_ubicacion}/{filtro_gabinete}/{filtro_institucion}/{tipo_detalle}', [ReportesController::class, 'getDatatableReporteCompromisoIndividualServerSide']);
    Route::post('exportarExcelCumplidos', [ReportesController::class, 'exportarExcelCumplidos']);
    //REPORTE MAPA CALOR2
    Route::post('consultaMostrarGraficoEstado', [ReportesController::class, 'consultaMostrarGraficoEstado']);
    Route::post('reporteDinamico_tc', [ReportesController::class, 'reporteDinamico_tc']);
});

/**
 * instituciones
 */
Route::group([
    'middleware' => ['auth'],
    'middleware' => ['role:ADMINISTRADOR COMPROMISOS'],
    'as' => 'compromisos.', 'prefix' => 'compromisos'
], function () {
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
});

/**
 * ministros
 */
Route::group([
    'middleware' => ['auth'], 'middleware' => ['role:ADMINISTRADOR COMPROMISOS'], 'as' => 'compromisos.', 'prefix' => 'compromisos'
], function () {
    Route::get('ministros', [MinistroController::class, 'index']);
    Route::get('getDatatableUsuariosMinistrosServerSide', [MinistroController::class, 'getDatatableUsuariosMinistrosServerSide']);
    Route::post('cambiarEstadoUsuario', [MinistroController::class, 'cambiarEstadoUsuario']);
    Route::post('guardarUsuario', [MinistroController::class, 'guardarUsuario']);
    Route::post('editarUsuario', [MinistroController::class, 'editarUsuario']);
    Route::post('generacion_clave', [MinistroController::class, 'generacion_clave']);
});
