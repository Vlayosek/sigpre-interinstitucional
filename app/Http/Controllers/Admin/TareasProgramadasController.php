<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Ajax\SelectController;

/*COMPROMISOS PRESIDENCIALES*/
use App\Core\Entities\Compromisos\Compromiso;
use App\Core\Entities\Compromisos\Periodo;
use App\Core\Entities\Compromisos\Objetivo;
use App\Core\Entities\Compromisos\EstadoPorcentaje;
use App\Core\Entities\Compromisos\Estado;
/*COMPROMISOS PRESIDENCIALES*/

/* AGENDA TERRITORIAL */
use App\Core\Entities\Agenda_territorial\Transaccion;
/* AGENDA TERRITORIAL*/

use App\User;
use DB;
use App\Core\Entities\AdministracionGrafico\Grafico;
class TareasProgramadasController extends Controller
{

      public function metodos()
    {
        $metodos_clase = $this->get_public_methods($this);
        return $metodos_clase;
    }
    public function convertirImagenPrincipal()
    {
        $objSelect = new SelectController();
        $graficos = Grafico::select('imagen', 'tipo')->where('eliminado', false)->whereIn('tipo', ['LOGIN LOGO', 'LOGIN PORTADA'])->pluck('imagen', 'tipo')->toArray();
        $ruta = public_path() . "/images/fondo.jpg";
        if (array_key_exists('LOGIN PORTADA', $graficos)) {
            try {
                $objSelect->base64_to_imagen($graficos['LOGIN PORTADA'], $ruta);
                echo "resuelto 1";
            } catch (\Exception  $e) {
                dd($e);
                echo "1";
            }
        } else {
            unlink($ruta);
        }


        $ruta = public_path() . "/images/logo.jpg";
        if (array_key_exists('LOGIN LOGO', $graficos)) {
            try {
                $objSelect->base64_to_imagen($graficos['LOGIN LOGO'], $ruta);
                echo "resuelto 2";
            } catch (\Exception  $e) {
                dd($e);
                echo "2";
            }
        } else {
            unlink($ruta);
        }
    }
    function get_public_methods($className)
    {
        $returnArray = array();
        $excepciones =
            [
                "middleware", "getMiddleware", "callAction", "__call", "authorize", "authorizeForUser", "authorizeResource", "dispatchNow", "validateWith", "validate", "validateWithBag", "metodos", "get_public_methods"
            ];
        foreach (get_class_methods($className) as $method) {
            $reflect = new \ReflectionMethod($className, $method);
            if ($reflect->isPublic()) {
                $encontrado = array_search($method, $excepciones);
                if (!$encontrado && $method != 'middleware')
                    array_push($returnArray, $method);
            }
        }

        /* return the array to the caller */
        return $returnArray;
    }

    /*COMPROMISOS */
    public function calculoAvancesCompromisos()
    {
        //$cqlConsultaRango=Generado::whereDate('fecha_inicio',date('Y-m-d'))
        $hoy = date('Y-m-d');
        $arregloEstados = ['CUM', 'CER'];
        $cqlEstadoPorcentaje = EstadoPorcentaje::whereNotIn('abv', $arregloEstados)->pluck('id')->toArray();
        $cqlConsultaCompromisos = Compromiso::whereIn('estado_porcentaje_id', $cqlEstadoPorcentaje)->get();

        //$compromisos_modificados = [];
        foreach ($cqlConsultaCompromisos as $value) {
            $consulta = $this->consultaPeriodoPorCompromiso($value->id, $hoy);
            //  dd($consulta);
            $value->avance = $consulta['avance'];
            //$value->save();
            $abvEstado = 'GRA';
            //Aqui actualizo los estados de los compromisos
            $cumplimiento_acumulado_porcentaje = $consulta['cumplimiento_acumulado_porcentaje'];
            if ($cumplimiento_acumulado_porcentaje >= '90')  $abvEstado = 'OPT'; //OPTIMO
            else if ($cumplimiento_acumulado_porcentaje >= 75 && $cumplimiento_acumulado_porcentaje <= 89)  $abvEstado = 'BUE'; //BUENO
            else if ($cumplimiento_acumulado_porcentaje >= 60 && $cumplimiento_acumulado_porcentaje <= 74)  $abvEstado = 'LEV'; //LEVE
            else if ($cumplimiento_acumulado_porcentaje >= 30 && $cumplimiento_acumulado_porcentaje <= 59)  $abvEstado = 'MOD'; //LEVE

            $cqlEstado = Estado::where('abv', $abvEstado)->pluck('id');
            $value->estado_id = $cqlEstado[0];
            $value->updated_at = date('Y-m-d H:i:s');
            $value->save();
        }

        return date('Y-m-d');
    }

    protected function consultaPeriodoPorCompromiso($compromisos_id, $hoy)
    {
        $cqlObjetivo = Objetivo::where('compromiso_id', $compromisos_id)
            ->where('registro_cumplimiento', true)
            ->where('estado', 'ACT')
            ->where('aprobado', true)
            ->orderby('numero', 'asc')
            ->get();

        $avance = 0;
        $avance_acumulado = 0;
        $cumplimiento_acumulado_porcentaje = 0;

        foreach ($cqlObjetivo as $key => $value) {

            $cqlNumeroPeriodos = Periodo::where('eliminado', false)
                ->where('objetivo_id', $value->id)
                ->get()
                ->count();

            $cqlPeriodoACalcular = Periodo::where('eliminado', false)
                ->where('objetivo_id', $value->id)
                ->where('fecha_inicio_periodo', '<=', $hoy)
                ->where('fecha_fin_periodo', '>=', $hoy)
                ->first();

            if ($cqlPeriodoACalcular != null) {

                $cqlPeriodoACalcularObj = $cqlPeriodoACalcular->numero;
                if ($cqlPeriodoACalcular->meta_acumulada != 0) {
                    //Para contar con el cumplimiento acumulado en porcentaje del periodo
                    $cumplimiento_acumulado_porcentaje_periodo = round((($cqlPeriodoACalcular->cumplimiento_acumulado * 100) / $cqlPeriodoACalcular->meta_acumulada), 2);
                    $cumplimiento_acumulado_porcentaje = $cumplimiento_acumulado_porcentaje + $cumplimiento_acumulado_porcentaje_periodo;

                    $a = (100 / $cqlNumeroPeriodos);
                    $b = ($cqlPeriodoACalcular->cumplimiento_acumulado * 100);
                    $c = ($b / $cqlPeriodoACalcular->meta_acumulada);
                    $avance =  $a * $cqlPeriodoACalcularObj * ($c / 100);
                }
                $avance_acumulado = $avance_acumulado + $avance;
            }
        }
        if ($avance_acumulado != 0 && $cqlObjetivo->count() != 0) {
            //$consulta['avance'] = $avance_acumulado / ($cqlObjetivo->count());
            $consulta['avance'] = $b / $value->meta;
            $cumplimiento_acumulado_porcentaje = $cumplimiento_acumulado_porcentaje / ($cqlObjetivo->count());
        } else
            $consulta['avance'] = 0;

        $consulta['cumplimiento_acumulado_porcentaje'] = $cumplimiento_acumulado_porcentaje;

        return $consulta;
    }
    /*COMPROMISOS */
    /*AGENDA TERRITORIAL */

    public function enviarNotificacionesTransaccionalesAgendaTerritorial()
    {
        $habilitar = config('app_agenda_territorial.habilitar_notificacion_transaccional');
        if (!$habilitar)
            return 'NO HABILITADO';

        $objSelect = new SelectController();

        $hoy = date('Y-m-d');
        $html = '<table border="1" width="100%">';
        $html .= '<tr>';
        $html .= '<td>Fecha';
        $html .= '</td>';
        $html .= '<td>Descripcion';
        $html .= '</td>';
        $html .= '<td>Institución';
        $html .= '</td>';
        $html .= '<td>Usuario';
        $html .= '</td>';
        $html .= '</tr>';

        $cqlConsulta = Transaccion::select(
            'sc_agenda_territorial.transacciones.id',
            'sc_agenda_territorial.transacciones.created_at as fecha',
            'sc_agenda_territorial.transacciones.descripcion as descripcion',
            'inst.descripcion as institucion',
            'sc_agenda_territorial.transacciones.agenda_territorial_id',
            'sc_agenda_territorial.transacciones.visible',
            'user.nombres as usuario'
        )
            ->leftjoin('sc_agenda_territorial.instituciones as inst',  'sc_agenda_territorial.transacciones.usuario_ingresa', 'inst.ministro_usuario_id')
            ->leftjoin('core.users as user', 'user.id', 'sc_agenda_territorial.transacciones.usuario_ingresa')
            ->whereDate('sc_agenda_territorial.transacciones.created_at', $hoy)
            ->orderby('sc_agenda_territorial.transacciones.id', 'desc')->get();

        $html .= '</tr>';
        $conteo = 0;
        foreach ($cqlConsulta as $consulta) {
            $conteo = 1;

            $html .= '<td>' . $consulta->fecha;
            $html .= '</td>';
            $html .= '<td>' . $consulta->descripcion;
            $html .= '</td>';
            $html .= '<td>' . $consulta->institucion;
            $html .= '</td>';
            $html .= '<td>' . $consulta->usuario;
            $html .= '</td>';
        }
        if ($conteo == 0)
            return 'NO HAY DATOS PARA ENVIAR';

        $html .= '</tr>';
        $html .= '</table>';
        $asunto = 'Transacciones Diarias Agenda Territorial ' . $hoy;

        $model = $objSelect->consultaUsuariosRolCorreo('MONITOR AGENDA TERRITORIAL');
        $modelos_correos = '';
        foreach ($model as $value) {
            $modelos_correos .= '<br/>' . $value;

            $for = $value;
            $copia = '';
            $objSelect->apiEnvioCorreoLocal($html, $asunto, $for, $copia);
        }
        return $html . '<br/>' . $modelos_correos;
    }
    public function enviarNotificacionesAgendaTerritorial()
    {
        $habilitar = config('app_agenda_territorial.habilitar_notificacion_mensual');
        if (!$habilitar)
            return 'NO HABILITADO';
        //$cqlConsultaRango=Generado::whereDate('fecha_inicio',date('Y-m-d'))
        $hoy = date('Y-m-d');
        $dia_notificacion = date('Y-m-' . config('app_agenda_territorial.dia_notificacion'));
        $modelos_correos = '';
        $mensaje = config('app_agenda_territorial.mensaje');
        if ($hoy == $dia_notificacion) {
            $objSelect = new SelectController();
            $model = $objSelect->consultaUsuariosRolCorreo('MINISTRO');
            foreach ($model as $value) {
                $modelos_correos .= '<br/>' . $value;
                $objSelect->NotificarSinRegistro($mensaje, $value);
            }
        }

        return $dia_notificacion . '<br/>' . $modelos_correos . '<br/>' . $mensaje;
    }
    /*AGENDA TERRITORIAL */
}
