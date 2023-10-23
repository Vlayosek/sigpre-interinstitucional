<?php

use Illuminate\Support\Facades\Route;

Route::get('pruebas_correo_personalizado', 'PruebasSrvController@pruebasCorreoPersonalizado');
Route::get('test_mensaje/{descripcion}/{email}', 'Ajax\SelectController@Notificar');
Route::get('prueba', 'PruebasSrvController@prueba');
