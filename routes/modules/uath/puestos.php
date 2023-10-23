<?php

use Illuminate\Support\Facades\Route;

Route::group([
    'middleware' => ['auth'],
    'middleware' => ['role:CREACION DE PUESTOS'],
    'as' => 'uath.', 'prefix' => 'uath'
], function () {

    Route::get('puestos', 'Uath\Puestos\PuestosController@index');
    Route::get('puestos/getDatatablepPuestosServerSide', 'Uath\Puestos\PuestosController@getDatatablepPuestosServerSide');
    Route::post('eliminarPuesto', 'Uath\Puestos\PuestosController@eliminarPuesto');
    Route::post('guardarPuesto', 'Uath\Puestos\PuestosController@guardarPuesto');
    Route::post('editarPuesto', 'Uath\Puestos\PuestosController@editarPuesto');
});

Route::group([
    'middleware' => ['auth'],
    'middleware' => ['role:CREACION DE PUESTOS'],
    'as' => 'denominaciones.', 'prefix' => 'denominaciones'
], function () {

    Route::get('puestos', 'Uath\GrupoOcupacional\GrupoOcupacionalController@index');
    Route::get('puestos/getDatatablepPuestosServerSide', 'Uath\GrupoOcupacional\GrupoOcupacionalController@getDatatablepPuestosServerSide');
    Route::post('eliminarGrupoOcupacional', 'Uath\GrupoOcupacional\GrupoOcupacionalController@eliminarGrupoOcupacional');
    Route::post('guardarPuesto', 'Uath\GrupoOcupacional\GrupoOcupacionalController@guardarPuesto');
    Route::post('editarPuesto', 'Uath\GrupoOcupacional\GrupoOcupacionalController@editarPuesto');
});
