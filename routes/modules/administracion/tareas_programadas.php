<?php
/* VERIFICACIÓN DE METODOS  */
Route::get('tareas/metodos','Admin\TareasProgramadasController@metodos');
//* CONVERTIR IMAGEN DIARIA  */
Route::get('tareas/convertirImagenPrincipal','Admin\TareasProgramadasController@convertirImagenPrincipal');
require __DIR__ . '/tareas_programadas_base.php';
require __DIR__ . '/tareas_programadas_interinstitucionales.php';
