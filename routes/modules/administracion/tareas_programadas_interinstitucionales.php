<?php
/* CALCULO DE AVANCES DE COMPROMISOS PRESIDENCIALES 00:01 DIARIO  DESACTIVADO POR PETICION DE LA DIRECTORA*/
Route::get('tareas/calculoAvancesCompromisos','Admin\TareasProgramadasInterinstitucionalController@calculoAvancesCompromisos');

/* REMITIR NOTIFICACIONES AGENDA TERRITORIAL 00:01 DIARIO */
Route::get('tareas/enviarNotificacionesAgendaTerritorial','Admin\TareasProgramadasInterinstitucionalController@enviarNotificacionesAgendaTerritorial');

/* REMITIR NOTIFICACIONES AGENDA TERRITORIAL 23:59 DIARIO */
Route::get('tareas/enviarNotificacionesTransaccionalesAgendaTerritorial','Admin\TareasProgramadasInterinstitucionalController@enviarNotificacionesTransaccionalesAgendaTerritorial');


/*TERMINAR LA EXPORTACIONES DE COMPROMISOS*/
Route::get('tareas/finalizacionExportacion','Admin\TareasProgramadasInterinstitucionalController@finalizacionExportacion');

/* CAMBIO DE ESTADO DE COMPROMISOS */
Route::get('tareas/cambioEstadoCompromisos', 'Admin\TareasProgramadasInterinstitucionalController@cambioEstadoCompromisos');
