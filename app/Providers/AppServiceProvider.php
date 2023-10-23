<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use App\Menu;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Ajax\SelectController;
use App\Core\Entities\AdministracionGrafico\Grafico;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        Schema::defaultStringLength(191);


        view()->composer('*', function ($view) {
            $view->with([
                'retornarLogin' => Auth::user(),
            ]);
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191);

        view()->composer('*', function ($view) {


            $objSelect = new SelectController();
            $enfermedades_catastroficas = $objSelect->getParametro('ENFERMEDADES CATASTROFICAS', 'http', 4);
            $genero = $objSelect->getParametro('GENERO', 'http', 4);
            $estado_civil = $objSelect->getParametro('ESTADO CIVIL', 'http', 4);
            $grupo_etnico = $objSelect->getParametro('GRUPO ETNICO', 'http', 4);
            $tipo_sangre = $objSelect->getParametro('TIPO DE SANGRE', 'http', 4);
            $tipo_cuenta = $objSelect->getParametro('TIPOS DE CUENTA BANCARIA', 'http', 4);
            $tipo_discapacidad = $objSelect->getParametro('TIPO DE DISCAPACIDAD', 'http', 4);
            $tipos_accidente_laboral = $objSelect->getParametro('TIPO DE ACCIDENTES LABORAL', 'http', 4);
            $parte_accidente_laboral = $objSelect->getParametro('PARTE DEL CUERPO', 'http', 4);
            $parentesco = $objSelect->getParametro('PARENTESCO', 'http', 4);
            $tipo_certificado = $objSelect->getParametro('TIPO CERTIFICADO', 'http', 4);
            $tipos_estudios = $objSelect->getParametro('TIPOS DE ESTUDIOS', 'http', 4);

            $graficos = Grafico::select('imagen', 'tipo')->where('eliminado', false)->whereIn('tipo', ['LOGIN LOGO', 'LOGIN PORTADA'])->pluck('imagen', 'tipo')->toArray();
            $view->with([
                'genero' => $genero,
                'enfermedades_catastroficas' => $enfermedades_catastroficas,
                'estado_civil' => $estado_civil,
                'grupo_etnico' => $grupo_etnico,
                'tipo_sangre' => $tipo_sangre,
                'tipo_cuenta' => $tipo_cuenta,
                'tipo_discapacidad' => $tipo_discapacidad,
                'tipos_accidente_laboral' => $tipos_accidente_laboral,
                'parte_accidente_laboral' => $parte_accidente_laboral,
                'parentesco' => $parentesco,
                'tipo_certificado'  => $tipo_certificado,
                'tipos_estudios' => $tipos_estudios,
                'graficos' => $graficos,
            ]);
        });
        view()->composer('partials.sidebar', function ($view) {
            $view->with('menus', Menu::menus());
        });
    }
}
