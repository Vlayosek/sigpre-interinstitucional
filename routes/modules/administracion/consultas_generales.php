<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('admin/home', [HomeController::class, 'index']);
Route::post('storage/create', 'StorageController@save')->name('storage.create');
Route::group([
    'middleware' => ['auth'],
    'prefix' => 'gestion', 'as' => 'gestion.'
], function () {
    Route::post('consultaEmpleado', 'Admin\AuditoriaController@consultaEmpleado');
});
Route::get('formulario', 'StorageController@index');

Route::get('NotificarAdjuntoSinRegistro/{descripcion}/{dirigido}/{file}', 'Ajax\SelectController@NotificarAdjuntoSinRegistro');
Route::get('portal', [HomeController::class, 'grabarUsuarioAtencionCiudadana']);
Route::post('grabarUsuarioEncuesta', [HomeController::class, 'grabarUsuarioEncuesta']);
Route::post('getCargaDatosFuncionarioPersona', [HomeController::class, 'getCargaDatosFuncionarioPersona']);
Route::post('getCargaDatosFuncionario', [HomeController::class, 'getCargaDatosFuncionario'])->name('getCargaDatosFuncionario');
Route::post('getCargaDatosDepartamentos', [HomeController::class, 'getCargaDatosDepartamentos'])->name('getCargaDatosDepartamentos');

Route::post('getCargaDatosFuncionarioSIGPREActivos', [HomeController::class, 'getCargaDatosFuncionarioSIGPREActivos'])->name('getCargaDatosFuncionarioSIGPREActivos');
Route::post('getCargaDatosFuncionarioSIGPRE', [HomeController::class, 'getCargaDatosFuncionarioSIGPRE'])->name('getCargaDatosFuncionarioSIGPRE');
Route::post('getCargaDatosFuncionarioSIGPREOrden', [HomeController::class, 'getCargaDatosFuncionarioSIGPREOrden'])->name('getCargaDatosFuncionarioSIGPREOrden');
Route::post('getCargaDatosFuncionarioSIGPREOrdenChosen', [HomeController::class, 'getCargaDatosFuncionarioSIGPREOrdenChosen'])->name('getCargaDatosFuncionarioSIGPREOrdenChosen');
Route::post('getCargaDatosFuncionarioDIRECTORIO', [HomeController::class, 'getCargaDatosFuncionarioDIRECTORIO'])->name('getCargaDatosFuncionarioDIRECTORIO');
Route::post('getCargaDatosFuncionarioActualizadoSIGPRE', [HomeController::class, 'getCargaDatosFuncionarioActualizadoSIGPRE'])->name('getCargaDatosFuncionarioActualizadoSIGPRE');

/*
Route::get('r', function()
{
    header('Content-Type: application/excel');
    header('Content-Disposition: attachment; filename="routes.csv"');

    $routes = Route::getRoutes();
    $fp = fopen('php://output', 'w');
    fputcsv($fp, ['METHOD', 'URI', 'NAME', 'ACTION']);
    foreach ($routes as $route) {
        fputcsv($fp, [head($route->methods()) , $route->uri(), $route->getName(), $route->getActionName()]);
    }
    fclose($fp);
});
*/
