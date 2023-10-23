<?php

use Illuminate\Support\Facades\Route;

Route::group([
    'middleware' => ['auth'],
    //'middleware' => ['role:SERVIDOR|administrator'],
], function () {
    /*API ENVIO DE CORREOS */
    Route::get('admin/apiEnvioCorreoGET/{html}/{asunto}/{correo}/{copia}', 'Ajax\SelectController@apiEnvioCorreoGET');
    Route::get('admin/apiEnvioCorreoGETPOST/{html}/{asunto}/{correo}/{copia}', 'Ajax\SelectController@apiEnvioCorreoGETPOST');
    /*API ENVIO DE CORREOS */
});

Route::group([
    'middleware' => ['auth'],
    'middleware' => ['role:SERVIDOR|administrator'],
], function () {
    /* METODOS DE CONSUMO REGISTRO CIVIL */
    Route::post('consultaDatosRegistroCivil', 'HomeController@consultaDatosRegistroCivil')->name('consultaDatosRegistroCivil');
    Route::get('consultaDatosRegistroCivilGET/{cedula}', 'HomeController@consultaDatosRegistroCivilGET');
    Route::get('consultaDatosRegistroCivilGETSIGPRE/{cedula}', 'HomeController@consultaDatosRegistroCivilGETSIGPRE');

    Route::get('consultaDatosRegistroCivilGETPortal/{cedula}', 'HomeController@consultaDatosRegistroCivilGETPortal');
    Route::post('consultaDatosRegistroCivilGETPortal', 'HomeController@consultaDatosRegistroCivilGETPortal')->name('consultaDatosRegistroCivilGETPortal');
    /* METODOS DE CONSUMO REGISTRO CIVIL */
});
