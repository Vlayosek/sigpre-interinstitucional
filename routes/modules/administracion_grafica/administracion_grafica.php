<?php
use App\Http\Controllers\AdministracionGrafico\AdministracionGraficoController;
use Illuminate\Support\Facades\Route;

Route::group([
    'middleware' => ['auth'],
   'middleware' => ['role:ADMINISTRADOR GRAFICO'],
    'as'=>'administracion_grafico.',
    'prefix'=> 'administracion_grafico'],
    function(){
        //ACTIVIDAD
        Route::get('index', [AdministracionGraficoController::class,'index']);
        Route::post('verPlantilla', [AdministracionGraficoController::class,'verPlantilla']);
        Route::post('guardarAdministracionGraficaLogin', [AdministracionGraficoController::class,'guardarAdministracionGraficaLogin']);
        Route::get('getDatatableServerSide', [AdministracionGraficoController::class,'getDatatableServerSide']);
        Route::post('cambiarPrincipal', [AdministracionGraficoController::class,'cambiarPrincipal']);
       
});
