<?php

use App\Http\Controllers\Uath\Distributivo\AccionPersonalController;
use App\Http\Controllers\Uath\Distributivo\FuncionarioController;
use Illuminate\Support\Facades\Route;

Route::group([
    'middleware' => ['auth'],
    'middleware' => ['role:ADMINISTRADOR DE UATH|CONSULTA DE UATH|DIRECTOR TALENTO HUMANO|REVISOR DE LIQUIDACIONES|ADMINISTRADOR DE FUNCIONARIOS'],
    'as' => 'uath.', 'prefix' => 'uath'
], function () {
    Route::get('funcionarios', [FuncionarioController::class, 'index']);
    Route::get('historial/{id}/{tipo}', [FuncionarioController::class, 'historial']);
    Route::post('historial/consultaPersonaHistorial', [FuncionarioController::class, 'consultaPersonaHistorial']);
    Route::post('historial/actualizadoHistorial', [FuncionarioController::class, 'actualizadoHistorial']);
    Route::post('historial/cambiarPrincipal', [FuncionarioController::class, 'cambiarPrincipal']);
    Route::post('historial/cambiarEstado', [FuncionarioController::class, 'cambiarEstado']);
    Route::get('datatablehistorialPersona/{persona_id}/{tipo}', [FuncionarioController::class, 'datatablehistorialPersona']);
    Route::get('datatablehistorialPersona_/{persona_id}/{tipo}', [FuncionarioController::class, 'datatablehistorialPersona_']);
    Route::post('historial/{id}/editarHistorial_', [FuncionarioController::class, 'editarHistorial_']);
    Route::post('historial/guardarHistorial', [FuncionarioController::class, 'guardarHistorial']);
    Route::post('historial/{id}/guardarHistorial_', [FuncionarioController::class, 'guardarHistorial_']);
    Route::post('historial/eliminarHistorial', [FuncionarioController::class, 'eliminarHistorial']);

    //obtener los horarios del funcionario
    Route::get('datatablehistorialHorario/{persona_id}', [FuncionarioController::class, 'datatablehistorialHorario']);
});

Route::group([
    'middleware' => ['auth'],
    'as' => 'uath.', 'prefix' => 'uath'
], function () {

    Route::post('editarPersona', [FuncionarioController::class, 'editarPersona']);
    Route::post('eliminarPersona', [FuncionarioController::class, 'eliminarPersona']);
    Route::post('asignacionHorario', [FuncionarioController::class, 'asignacionHorario']);
    Route::post('obtenerDiaSemana', [FuncionarioController::class, 'obtenerDiaSemana']);

    Route::post('guardarGrupo', [FuncionarioController::class, 'guardarGrupo']);
    Route::post('editarGrupo', [FuncionarioController::class, 'editarGrupo']);
    Route::post('eliminarHorario', [FuncionarioController::class, 'eliminarHorario']);

    Route::post('getDatatablePersonasServerSide', [FuncionarioController::class, 'getDatatablePersonasServerSide']);
    Route::get('getDatatableDeclaracionesJuramentadasServerSide/{persona_id}', [FuncionarioController::class, 'getDatatableDeclaracionesJuramentadasServerSide']);
    Route::get('getDatatableEstudiosServerSide/{persona_id}', [FuncionarioController::class, 'getDatatableEstudiosServerSide']);
    Route::get('getDatatableCursosServerSide/{persona_id}', [FuncionarioController::class, 'getDatatableCursosServerSide']);
    Route::get('getDatatableCargasFamiliares/{persona_id}', [FuncionarioController::class, 'getDatatableCargasFamiliares']);
    Route::get('getDatatablePersonasDiscapacidadServerSide/{persona_id}', [FuncionarioController::class, 'getDatatablePersonasDiscapacidadServerSide']);
    Route::get('getDatatablePersonasEnfermedadServerSide/{persona_id}', [FuncionarioController::class, 'getDatatablePersonasEnfermedadServerSide']);
    /*HISTORIAL LABORAL*/
    Route::post('actualizadoHistorial', [FuncionarioController::class, 'actualizadoHistorial']);
    Route::post('cambiarPrincipal', [FuncionarioController::class, 'cambiarPrincipal']);
    Route::post('cambiarEstado', [FuncionarioController::class, 'cambiarEstado']);
    Route::get('datatablehistorialPersona/{persona_id}', [FuncionarioController::class, 'datatablehistorialPersona']);
    /* Route::post('editarHistorial',[FuncionarioController::class,'editarHistorial']); */
    Route::post('editarHistorial_', [FuncionarioController::class, 'editarHistorial_']);
    Route::post('guardarHistorial', [FuncionarioController::class, 'guardarHistorial']);
    Route::post('guardarHistorial_', [FuncionarioController::class, 'guardarHistorial_']);
    Route::post('eliminarHistorial', [FuncionarioController::class, 'eliminarHistorial']);
    /*FIN HISTORIAL LABORAL*/

    Route::post('cargarCanton', [FuncionarioController::class, 'cargarCanton']);
    Route::post('editarPerfil', [FuncionarioController::class, 'editarPerfil']);
    Route::post('guardarRegistro', [FuncionarioController::class, 'guardarRegistro']);


    Route::post('editarEstudio', [FuncionarioController::class, 'editarEstudio']);
    Route::post('guardarEstudio', [FuncionarioController::class, 'guardarEstudio']);
    Route::post('eliminarEstudio', [FuncionarioController::class, 'eliminarEstudio']);

    Route::post('editarCurso', [FuncionarioController::class, 'editarCurso']);
    Route::post('guardarCurso', [FuncionarioController::class, 'guardarCurso']);
    Route::post('eliminarCurso', [FuncionarioController::class, 'eliminarCurso']);

    Route::post('guardarBanco', [FuncionarioController::class, 'guardarBanco']);

    Route::post('editarCargaFamiliar', [FuncionarioController::class, 'editarCargaFamiliar']);
    Route::post('guardarCargaFamiliar', [FuncionarioController::class, 'guardarCargaFamiliar']);
    Route::post('eliminarCargaFamiliar', [FuncionarioController::class, 'eliminarCargaFamiliar']);

    Route::post('guardarBienestar', [FuncionarioController::class, 'guardarBienestar']);

    Route::post('editarPersonaDiscapacidad', [FuncionarioController::class, 'editarPersonaDiscapacidad']);
    Route::post('guardarPersonaDiscapacidad', [FuncionarioController::class, 'guardarPersonaDiscapacidad']);
    Route::post('eliminarPersonaDiscapacidad', [FuncionarioController::class, 'eliminarPersonaDiscapacidad']);

    Route::post('editarPersonaEnfermedad', [FuncionarioController::class, 'editarPersonaEnfermedad']);
    Route::post('guardarPersonaEnfermedad', [FuncionarioController::class, 'guardarPersonaEnfermedad']);
    Route::post('eliminarPersonaEnfermedad', [FuncionarioController::class, 'eliminarPersonaEnfermedad']);

    Route::post('guardarDeclaracionJuramentada', [FuncionarioController::class, 'guardarDeclaracionJuramentada']);
    Route::post('eliminarDeclaracionJuramentada', [FuncionarioController::class, 'eliminarDeclaracionJuramentada']);
    Route::post('descargarDeclaracionJuramentada', [FuncionarioController::class, 'descargarDeclaracionJuramentada']);

    Route::post('consultaEstados', [FuncionarioController::class, 'consultaEstados']);
    Route::post('consultaPersonaHistorial', [FuncionarioController::class, 'consultaPersonaHistorial']);

    Route::post('consultaDatosPersona', [FuncionarioController::class, 'consultaDatosPersona']);
});

Route::group([
    'middleware' => ['auth'],
    'middleware' => ['role:ADMINISTRADOR DE UATH|ADMINISTRADOR DE FUNCIONARIOS'],
    'as' => 'uath.', 'prefix' => 'uath/accion'
], function () {
    Route::post('guardarAccion', [AccionPersonalController::class, 'guardarAccion']);
    Route::get('personal', [AccionPersonalController::class, 'personal']);
    Route::post('filtro_edificio', [AccionPersonalController::class, 'filtro_edificio']);
});
