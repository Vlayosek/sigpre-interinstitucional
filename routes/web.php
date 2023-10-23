<?php

use Illuminate\Support\Facades\Route;

ob_start();

Route::get('reloj_virtual/reloj_servidor', function () {
    $horaActual = date('H:i:s');
    echo json_encode(compact('horaActual'));
});

/* ADMINISTRACION */
require __DIR__ . '/modules/administracion/administrador_sistemas.php';
require __DIR__ . '/modules/administracion/consultas_generales.php';
require __DIR__ . '/modules/administracion/login.php';
require __DIR__ . '/modules/administracion/pwa.php';
require __DIR__ . '/modules/administracion/API.php';
require __DIR__ . '/modules/administracion/tareas_programadas.php';
require __DIR__ . '/modules/notificaciones/notificaciones.php';

/* ADMINISTRACION */

/* AGENDA TERRITORIAL */
require __DIR__ . '/modules/agenda_territorial/agenda.php';
/* FIN AGENDA TERRITORIAL */

/* COMPROMISOS */
require __DIR__ . '/modules/compromisos/compromisos.php';
/* FIN COMPROMISOS */

/*TALENTO HUMANO*/
require __DIR__ . '/modules/uath/funcionarios.php';
require __DIR__ . '/modules/uath/reportes.php';
require __DIR__ . '/modules/talento_humano/personas.php';
/*FIN TALENTO HUMANO*/

/*ADMINISTRACION GRAFICO*/
require __DIR__ . '/modules/administracion_grafica/administracion_grafica.php';
/* FIN ADMINISTRACION GRAFICO*/
