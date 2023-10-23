<?php

use App\Http\Controllers\AgendaTerritorial\AgendaController;
use App\Http\Controllers\AgendaTerritorial\AgendasAdministradorController;
use App\Http\Controllers\AgendaTerritorial\InstitucionController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AgendaTerritorial\MinistroController;
use App\Http\Controllers\AgendaTerritorial\ReportesController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AgendaTerritorial\DelegadoController;

/* RUTAS DE ADMINISTRADOR DE COMPROMISOS */

Route::controller(AgendasAdministradorController::class)
    ->prefix('agenda_territorial')
    ->as('agenda_territorial.')
    ->middleware(['auth'])
    ->group(function () {
        Route::get('asignacion', [AgendasAdministradorController::class, 'index']);
        Route::get(
            'getDatatableAsignacionMonitorServerSide',
            [AgendasAdministradorController::class, 'getDatatableAsignacionMonitorServerSide']
        );
        Route::post('guardarAsignacion', [AgendasAdministradorController::class, 'guardarAsignacion']);
        Route::post('editar', [AgendasAdministradorController::class, 'editar']);
        Route::post('eliminar', [AgendasAdministradorController::class, 'eliminar']);
    });

/*EXPORTAR EN EXCEL*/
Route::get(
    'reportes/agenda_territorial/exportarExcelGET/{fecha_inicio}/{fecha_fin}/{tipo}/{tabla}/{asignaciones}/{temporales}/{pendientes}/{filtro}/{institucion_id}/{gabinete_id}',
    [ReportesController::class, 'ExportarExcelGET']
);

Route::controller(AgendaController::class)
    ->prefix('agenda_territorial')
    ->as('agenda_territorial.')
    ->middleware(['auth'])
    ->group(function () {
        Route::get('gestion', [AgendaController::class, 'index']);
        Route::get('getDatatableAgendaServerSide', [AgendaController::class, 'getDatatableAgendaServerSide']);
        Route::post('guardarAgenda', [AgendaController::class, 'guardarAgenda']);
        Route::post('crearCodigo', [AgendaController::class, 'crearCodigo']);
        Route::post('getCargaDatosInstitucion', [AgendaController::class, 'getCargaDatosInstitucion']);
        Route::get('editar/agenda/{id}', [AgendaController::class, 'editarAgenda']);
        Route::get('getDatatableCargarObraComplementaria/{id}', [AgendaController::class, 'getDatatableCargarObraComplementaria']);
        Route::get('getDatatableCargarOrdenDia/{id}', [AgendaController::class, 'getDatatableCargarOrdenDia']);
        Route::post('guardarObraPrincipal', [AgendaController::class, 'guardarObraPrincipal']);
        Route::post('guardarOrdenDia', [AgendaController::class, 'guardarOrdenDia']);
        Route::post('editarOrdenDia', [AgendaController::class, 'editarOrdenDia']);

        Route::post('guardarArchivoParticipantes', [AgendaController::class, 'guardarArchivoParticipantes']);
        Route::post('guardarObraComplementaria', [AgendaController::class, 'guardarObraComplementaria']);
        Route::post('eliminarObraComplementaria', [AgendaController::class, 'eliminarObraComplementaria']);
        Route::post('eliminarOrdenDia', [AgendaController::class, 'eliminarOrdenDia']);
        Route::get('consulta/{asignaciones}', [AgendaController::class, 'consulta']);
        Route::post('getDatatableAgendaServerSidePOST', [AgendaController::class, 'getDatatableAgendaServerSidePOST']);
        Route::post('buscarResponsableMinistroAgenda', [AgendaController::class, 'buscarResponsableMinistroAgenda']);

        /* AUN NO DEPURADO*/
        Route::post('aprobar', [AgendaController::class, 'aprobar']);
        Route::post('negar', [AgendaController::class, 'negar']);
        Route::get('tipos', [AgendaController::class, 'tiposIndex']);

        Route::post('eliminar/tipo', [AgendaController::class, 'eliminarTipo']);
        Route::post('guardar/tipo', [AgendaController::class, 'guardarTipo']);
        Route::get('editar/tipo/{id}', [AgendaController::class, 'editarTipo']);
        Route::get('provincias', [HomeController::class, 'provincias']);
        Route::get('cantonParroquia/{arreglo}', [HomeController::class, 'cantonParroquia']);
        Route::get('getDatatableSolicitudServerSide/{tipo}', [AgendaController::class, 'getDatatableSolicitudServerSide']);
        Route::get('getDatatableCompromisosServerSide/{tipo}/{tabla}/{asignaciones}/{temporales}/{pendientes}/{filtro}/{institucion_id}/{gabinete_id}', [AgendaController::class, 'getDatatableCompromisosServerSide']);
        Route::post('cargarInstituciones', [AgendaController::class, 'cargarInstituciones']);
        Route::get('getDatatableAntecedentesServerSide/{tipo}', [AgendaController::class, 'getDatatableAntecedentesServerSide']);
        Route::get('getDatatableArchivosServerSide/{tipo}', [AgendaController::class, 'getDatatableArchivosServerSide']);
        Route::get('getDatatableHistoricoServerSide/{tipo}', [AgendaController::class, 'getDatatableHistoricoServerSide']);
        Route::get('getDatatableMensajeServerSide/{tipo}', [AgendaController::class, 'getDatatableMensajeServerSide']);
        Route::get('getDatatableObjetivosServerSide/{tipo}', [AgendaController::class, 'getDatatableObjetivosServerSide']);
        Route::get('getDatatablePeriodosServerSide/{tipo}', [AgendaController::class, 'getDatatablePeriodosServerSide']);
        Route::get('getDatatableAvancesServerSide/{tipo}', [AgendaController::class, 'getDatatableAvancesServerSide']);
        Route::post('getDatatableUbicacionesServerSide', [AgendaController::class, 'getDatatableUbicacionesServerSide']);
        Route::post('getCargaDatosInstitucionCorresponsables', [AgendaController::class, 'getCargaDatosInstitucionCorresponsables']);

        Route::post('guardarAntecedente', [AgendaController::class, 'guardarAntecedente']);
        Route::post('grabarArchivos', [AgendaController::class, 'grabarArchivos']);
        Route::post('grabarMensaje', [AgendaController::class, 'grabarMensaje']);
        Route::post('guardarObjetivos', [AgendaController::class, 'guardarObjetivos']);
        Route::post('guardarUbicacion', [AgendaController::class, 'guardarUbicacion']);
        Route::post('guardarAvance', [AgendaController::class, 'guardarAvance']);

        Route::post('eliminar/compromiso', [AgendaController::class, 'eliminarCompromiso']);
        Route::post('eliminar/archivo', [AgendaController::class, 'eliminarArchivo']);
        Route::post('eliminar/mensaje', [AgendaController::class, 'eliminarMensaje']);
        Route::post('eliminar/objetivo', [AgendaController::class, 'eliminarObjetivo']);
        Route::post('eliminar/antecedente', [AgendaController::class, 'eliminarAntecedente']);
        Route::post('eliminar/avance', [AgendaController::class, 'eliminarAvance']);
        Route::post('aprobar/avance', [AgendaController::class, 'aprobarAvance']);
        Route::post('negar/avance', [AgendaController::class, 'negarAvance']);

        Route::post('leerMensaje', [AgendaController::class, 'leerMensaje']);
        Route::post('descargarArchivo', [AgendaController::class, 'descargarArchivo']);
        Route::post('editaPeriodo', [AgendaController::class, 'editaPeriodo']);
        Route::post('guardarPeriodo', [AgendaController::class, 'guardarPeriodo']);
        Route::post('cargarImagenes', [AgendaController::class, 'cargarImagenes']);

        Route::post('rechazarObjetivo', [AgendaController::class, 'rechazarObjetivo']);
        Route::post('aprobarObjetivo', [AgendaController::class, 'aprobarObjetivo']);
        Route::post('imprimirFicha', [AgendaController::class, 'imprimirFicha']);
    });

Route::post('agenda_territorial/buscarInstitucion', [AgendaController::class, 'buscarInstitucion'])->name('agenda_territorial/buscarInstitucion');
Route::post('agenda_territorial/buscarResponsable', [AgendaController::class, 'buscarResponsable'])->name('agenda_territorial/buscarResponsable');
Route::post('agenda_territorial/buscarInstitucionCo', [AgendaController::class, 'buscarInstitucionCo'])->name('agenda_territorial/buscarInstitucionCo');
Route::post('agenda_territorial/buscarMonitor', [AgendaController::class, 'buscarMonitor'])->name('agenda_territorial/buscarMonitor');
Route::post('agenda_territorial/buscarInstitucionMonitor', [AgendaController::class, 'buscarInstitucionMonitor'])->name('agenda_territorial/buscarInstitucionMonitor');


Route::controller(AgendasAdministradorController::class)
    ->prefix('agenda_territorial')
    ->as('agenda_territorial.')
    ->middleware(['auth', 'role:ADMINISTRADOR AGENDA TERRITORIAL'])
    ->group(function () {
        Route::get('delegados', [DelegadoController::class, 'delegados']);
        Route::get('getDatatableAsignacionDelegadoServerSide', [DelegadoController::class, 'getDatatableAsignacionDelegadoServerSide']);
        Route::post('guardarDelegado', [DelegadoController::class, 'guardarDelegado']);
        Route::post('editarDelegado', [DelegadoController::class, 'editarDelegado']);
        Route::post('eliminarDelegado', [DelegadoController::class, 'eliminarDelegado']);
        Route::post('cambiarEstadoDelegado', [DelegadoController::class, 'cambiarEstadoDelegado']);
    });


Route::controller(InstitucionController::class)
    ->prefix('agenda_territorial')
    ->as('agenda_territorial.')
    ->middleware(['auth', 'role:ADMINISTRADOR AGENDA TERRITORIAL'])
    ->group(function () {
        Route::get('instituciones', [InstitucionController::class, 'delegados']);
        Route::get('getDatatableInstitucionServerSide', [InstitucionController::class, 'getDatatableInstitucionServerSide']);
        Route::post('guardarInstitucion', [InstitucionController::class, 'guardarInstitucion']);
        Route::post('guardarGabinete', [InstitucionController::class, 'guardarGabinete']);
        Route::post('consultaInstitucion', [InstitucionController::class, 'consultaInstitucion']);
        Route::get('getDatatableGabineteServerSide', [InstitucionController::class, 'getDatatableGabineteServerSide']);
        Route::post('eliminarInstitucion', [InstitucionController::class, 'eliminarInstitucion']);
    });


Route::controller(MinistroController::class)
    ->prefix('agenda_territorial')
    ->as('agenda_territorial.')
    ->middleware(['auth'])
    ->group(function () {
        Route::get('ministros', [MinistroController::class, 'index']);
        Route::get('getDatatableUsuariosMinistrosServerSide', [MinistroController::class, 'getDatatableUsuariosMinistrosServerSide']);
        Route::post('cambiarEstadoUsuario', [MinistroController::class, 'cambiarEstadoUsuario']);
        Route::post('guardarUsuario', [MinistroController::class, 'guardarUsuario']);
        Route::post('editarUsuario', [MinistroController::class, 'editarUsuario']);
        Route::post('generacion_clave', [MinistroController::class, 'generacion_clave']);
    });
