<?php

use App\Http\Controllers\Compromisos\BusquedasAvanzadasController;
use App\Http\Controllers\Compromisos\CompromisosController;
use App\Http\Controllers\Compromisos\ReportesController;
use App\Http\Controllers\Compromisos\BusquedaSelectoresController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('getDatatableAntecedentesServerSide/{tipo}', [CompromisosController::class, 'getDatatableAntecedentesServerSide']);
Route::get('getDatatableArchivosServerSide/{tipo}', [CompromisosController::class, 'getDatatableArchivosServerSide']);
Route::get('getDatatableHistoricoServerSide/{tipo}', [CompromisosController::class, 'getDatatableHistoricoServerSide']);
Route::get('getDatatableMensajeServerSide/{tipo}', [CompromisosController::class, 'getDatatableMensajeServerSide']);
Route::get('getDatatableObjetivosServerSide/{tipo}', [CompromisosController::class, 'getDatatableObjetivosServerSide']);
Route::get('getDatatablePeriodosServerSide/{tipo}', [CompromisosController::class, 'getDatatablePeriodosServerSide']);
Route::get('getDatatableAvancesServerSide/{tipo}', [CompromisosController::class, 'getDatatableAvancesServerSide']);
Route::post('getDatatableUbicacionesServerSide', [CompromisosController::class, 'getDatatableUbicacionesServerSide']);
Route::get('getDatatableSolicitudServerSide/{tipo}', [CompromisosController::class, 'getDatatableSolicitudServerSide']);
Route::post('cargarInstituciones', [CompromisosController::class, 'cargarInstituciones']);
Route::post('cargarCantones', [CompromisosController::class, 'cargarCantones']);
Route::post('cargarParroquias', [CompromisosController::class, 'cargarParroquias']);
Route::post('getCargaDatosInstitucion', [CompromisosController::class, 'getCargaDatosInstitucion']);
Route::post('getCargaDatosInstitucionCorresponsables', [CompromisosController::class, 'getCargaDatosInstitucionCorresponsables']);
Route::get('getDatatableExportaciones', [ReportesController::class, 'getDatatableExportaciones']);


Route::post('guardarAntecedente', [CompromisosController::class, 'guardarAntecedente']);
Route::post('grabarArchivos', [CompromisosController::class, 'grabarArchivos']);
Route::post('grabarMensaje', [CompromisosController::class, 'grabarMensaje']);
Route::post('guardarObjetivos', [CompromisosController::class, 'guardarObjetivos']);
Route::post('guardarCompromiso', [CompromisosController::class, 'guardarCompromiso']);
Route::post('guardarUbicacion', [CompromisosController::class, 'guardarUbicacion']);
Route::post('guardarAvance', [CompromisosController::class, 'guardarAvance']);

Route::get('editar/compromiso/{id}', [CompromisosController::class, 'editarCompromiso']);
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

Route::get('tipos', [CompromisosController::class, 'tiposIndex']);
Route::post('eliminar/tipo', [CompromisosController::class, 'eliminarTipo']);
Route::post('guardar/tipo', [CompromisosController::class, 'guardarTipo']);
Route::get('editar/tipo/{id}', [CompromisosController::class, 'editarTipo']);
Route::get('provincias', [HomeController::class, 'provincias']);
Route::get('cantonParroquia/{arreglo}', [HomeController::class, 'cantonParroquia']);

Route::post('aprobar', [CompromisosController::class, 'aprobar']);
Route::post('negar', [CompromisosController::class, 'negar']);

Route::post('consultaNombreCodigoCompromisos', [BusquedaSelectoresController::class, 'consultaNombreCodigoCompromisos'])->name('consultaNombreCodigoCompromisos')->middleware(['auth', 'role:ADMINISTRADOR COMPROMISOS|MONITOR|MINISTRO']);
