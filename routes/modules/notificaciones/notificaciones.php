<?php

//use App\Http\Controllers\Notificaciones\NotificacionesTeletrabajoController;
use App\Http\Controllers\Notificaciones\NotificacionesCompromisoController;
use Illuminate\Support\Facades\Route;

Route::group([
    'middleware' => ['auth'],
    'as' => 'notificaciones.', 'prefix' => 'notificaciones'
], function () {
    //Route::post('consultarNotificacionesTeletrabajo', [NotificacionesTeletrabajoController::class, 'consultarNotificacionesTeletrabajo']);
    Route::post('consultarNotificacionesCompromisos', [NotificacionesCompromisoController::class, 'consultarNotificacionesCompromisos']);
    Route::get('getDatatableNotificacionesCompromisosServerSide/{tipoActual}', [NotificacionesCompromisoController::class, 'getDatatableNotificacionesCompromisosServerSide']);
    Route::post('cambiarEstadoLeido', [NotificacionesCompromisoController::class, 'cambiarEstadoLeido']);
});
