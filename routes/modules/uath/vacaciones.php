<?php

use Illuminate\Support\Facades\Route;

Route::group([
    'middleware' => ['role:LIQUIDACIONES'],
    'middleware' => ['auth'],
    'as' => 'vacaciones.', 'prefix' => 'vacaciones'
], function () {
    /* REGISTRO DE PERMISOS */
    Route::get('liquidaciones', 'Uath\Vacaciones\VacacionesController@index');
    Route::post('cargarPDF', 'Uath\Vacaciones\VacacionesController@cargarPDF');
    Route::post('onChangeIdentificacion', 'Uath\Vacaciones\VacacionesController@onChangeIdentificacion');
    Route::get('saldo', 'Uath\Vacaciones\VacacionesController@saldo');

    Route::get('getDatatableSaldoServerSide/{persona}', 'Uath\Vacaciones\VacacionesController@getDatatableSaldoServerSide');

    Route::post('guardarSaldo', 'Uath\Vacaciones\VacacionesController@guardarSaldo');
    Route::post('eliminarSaldo', 'Uath\Vacaciones\VacacionesController@eliminarSaldo');

    Route::get('saldo_manual', 'Uath\Vacaciones\VacacionesController@saldo_manual');
    Route::get('getDatatableSaldoManualesServerSide/{persona}', 'Uath\Vacaciones\VacacionesController@getDatatableSaldoManualesServerSide');
});
