<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Ajax\SelectController;
use App\Core\Entities\AdministracionGrafico\Grafico;

use App\Core\Entities\Compromisos\Exportacion;
use App\Core\Entities\Compromisos\Compromiso;
use App\Core\Entities\Compromisos\Periodo;
use App\Core\Entities\Compromisos\Objetivo;
use App\Core\Entities\Compromisos\EstadoPorcentaje;
use App\Core\Entities\Compromisos\Estado;
use App\Core\Entities\Compromisos\CambioEstado;

use App\Http\Controllers\Compromisos\RepositorioController as RPC;

/* AGENDA TERRITORIAL */
use App\Core\Entities\Agenda_territorial\Transaccion;


class TareasProgramadasInterinstitucionalController extends Controller
{
  protected $FECHA_CONTROL_TELETRABAJO = '2021-12-01';
  protected $notificacion_fecha_corte_teletrabajo = false;
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


  protected function fechasSemanales($dia)
  {
    $hoy = date('Y-m-d');
    $date = new \DateTime($hoy);
    if ($dia == 'viernes') {
      $date->modify('-4 day');
      $end = date('Y-m-d');
      $start = $date->format('Y-m-d');
    } else {
      $date->modify('+4 day');
      $start = date('Y-m-d');
      $end = $date->format('Y-m-d');
    }
    $array_response['start'] = $start;
    $array_response['end'] = $end;
    $range = array();
    if (is_string($start) === true) $start = strtotime($start);
    if (is_string($end) === true) $end = strtotime($end);

    do {
      $range[] = date('Y-m-d', $start);
      $start = strtotime("+ 1 day", $start);
    } while ($start <= $end);

    $array_response['range'] = $range;

    return $array_response;
  }

  public function finalizacionExportacion()
  {
    $cqlConsultaRango = Exportacion::whereDate('created_at', date('Y-m-d'))
      ->where('fin', '>=', date('H:i:s'))
      ->where('estado', 'ACT')
      ->first();

    if (!is_null($cqlConsultaRango)) {
      $cqlConsultaRango->estado = 'INA';
      $cqlConsultaRango->save();
    }
    return date('Y-m-d');
  }
  /*Cambio de estado de gestion y estado de compromiso*/
  /* public function cambioEstadoCompromisos()
  {
    $compromisos = "<br>Compromisos afectados > ";
    $dias_1 = -2;

    //Cambio de estado porcentaje EN PLANIFICACION a EN EJECUCION
   // $compromisos_afectados_1 = $this->ejecucionCambioPorcentaje($dias_1, 'PLA', 'EJE');
   $compromisos_afectados_1=[];
    $compromisos_afectados_2 = $this->ejecucionCambiosEstadosPorFechaReporte('EJE');

    return array_merge($compromisos_afectados_1,$compromisos_afectados_2);
  }*/

  /* protected function ejecucionCambio($estadoPorcentaje, $estadoActual, $estadoPropuesto)
  {
    $cqlEstadoPorcentaje = EstadoPorcentaje::where('abv', $estadoPorcentaje)->pluck('id')->toArray();
    $cqlEstadoGestion = Estado::where('abv', $estadoActual)->pluck('id')->toArray();
    $fecha_limite = Estado::where('abv', $estadoPropuesto)->pluck('tiempo_dias');
    $calculo = strtotime("-$fecha_limite[0] days");
    $fecha = date("Y-m-d", $calculo);
    //Estado de compromiso en ejecucion y estado de gestion
    $cqlCompromisos = Compromiso::whereIn('estado_porcentaje_id', $cqlEstadoPorcentaje)->where('estado_id', $cqlEstadoGestion);
    if ($estadoActual != '--')
      $cqlCompromisos = $cqlCompromisos->where('fecha_reporte', $fecha)->get();
    else
      $cqlCompromisos = $cqlCompromisos->where(\DB::RAW('to_char(created_at, \'yyyy-mm-dd\')'), $fecha)->get();

    //dd($estadoActual,"fecha",$fecha,$cqlCompromisos->toArray());
    foreach ($cqlCompromisos as $value) {
      //Aqui actualizo los estados de los compromisos
      // (new RPC())->validarCompromiso($value);
      $cqlEstadoPropuesto = Estado::where('abv', $estadoPropuesto)->pluck('id');
      $value->estado_id = $cqlEstadoPropuesto[0];
      $value->updated_at = date('Y-m-d H:i:s');
      $value->save();
    }
    return $cqlCompromisos;
  }*/
  /*Cambio de estado con respecto a la fecha de inicio del compromiso*/
  /* protected function ejecucionCambioPorcentaje($dias, $estadoPorcentaje, $estadoPropuesto)
  {
    $cqlEstadoPorcentaje = EstadoPorcentaje::where('abv', $estadoPorcentaje)->pluck('id');
    // $cqlEstadoGestion = Estado::where('abv', '--')->pluck('id');
    $calculo = strtotime("$dias days");
    $fecha = date("Y-m-d", $calculo);

    //Estado de compromiso en ejecucion y estado de gestion
    $cqlCompromisos = Compromiso::whereIn('estado_porcentaje_id', $cqlEstadoPorcentaje)
      ->whereNotNull('codigo')
      ->where('estado', 'ACT')
      ->where('fecha_inicio', '<=', $fecha)->get();
      dd($cqlCompromisos);
    $compromisosAfectados = [];

    foreach ($cqlCompromisos as $value) {
      //Aqui actualizo los estados de los compromisos
      $cqlEstado = EstadoPorcentaje::where('abv', $estadoPropuesto)->select('id')->first();
      $propuesto = EstadoPorcentaje::find($cqlEstado->id)->descripcion;
      $propuesto = is_null($propuesto) ? '' : $propuesto;

      $anterior = EstadoPorcentaje::find($value->estado_porcentaje_id)->descripcion;
      $anterior = is_null($anterior) ? '' : $anterior;

      if ($propuesto != $anterior) {
        array_push($compromisosAfectados, $value->id);

        $value->estado_porcentaje_id =
          (new RPC())->comparaCampos(
            $cqlEstado->id,
            $value->estado_porcentaje_id,
            $value->id,
            ('Estado de Gestión de ' . $anterior . ' al ' . $propuesto . ''),
            true,
            true,
            true,
            true
          );
        // $value->estado_porcentaje_id = $cqlEstado->id;
        $value->updated_at = date('Y-m-d H:i:s');
        $value->save();
      }
    }

    return $compromisosAfectados;
  }

  protected function ejecucionCambiosEstadosPorFechaReporte($estadoPorcentaje)
  {
    $diferencia = 0;
    $estadoPropuesto = 0;
    $cqlEstadoPorcentaje = EstadoPorcentaje::where('abv', $estadoPorcentaje)->pluck('id')->toArray();
    //$cqlEstadoGestion = Estado::where('abv', $estadoActual)->pluck('id')->toArray();
    $fecha = strtotime(date("Y-m-d"));
    //Consulto todos los compromisos en EJE
    $cqlCompromisos = Compromiso::whereIn('estado_porcentaje_id', $cqlEstadoPorcentaje)
      ->whereNotNull('fecha_reporte')
      ->whereNotNull('codigo')
      ->where('estado', 'ACT')

      ->get();

    $compromisosAfectados = [];
    //dd($estadoActual,"fecha",$fecha,$cqlCompromisos->toArray());
    foreach ($cqlCompromisos as $value) {
      //Calculo la diferencia de dias desde hoy a la fecha del reporte del compromiso
      $diferencia = ($fecha - strtotime($value->fecha_reporte)) / 86400;
      //Aqui actualizo los estados de los compromisos
      if ($diferencia >= 21)  $estadoPropuesto = 'GRA';
      elseif ($diferencia >= 14)  $estadoPropuesto = 'MOD';
      elseif ($diferencia >= 7) $estadoPropuesto = 'LEV';
      elseif ($diferencia >= 2) $estadoPropuesto = 'BUE';
      if ($estadoPropuesto != 0) {
        $cqlEstadoPropuesto = Estado::where('abv', $estadoPropuesto)->first();
        $propuesto = Estado::find($cqlEstadoPropuesto->id)->descripcion;
        $propuesto = is_null($propuesto) ? '' : $propuesto;

        $anterior = Estado::find($value->estado_id)->descripcion;
        $anterior = is_null($anterior) ? '' : $anterior;

	if ($propuesto != $anterior) {
          array_push($compromisosAfectados, $value->id);
          $value->estado_id =
          (new RPC())->comparaCampos(
              $cqlEstadoPropuesto->id,
              $value->estado_id,
              $value->id,
              ('Estado del Compromiso de ' . $anterior . ' al ' . $propuesto),
              true,
              true,
              true,
              true
            );
          //  $value->estado_id = $cqlEstadoPropuesto->id;
          $value->updated_at = date('Y-m-d H:i:s');
	  $value->save();
       }
      }
    }
    return $compromisosAfectados;
  }*/
  public function cambioEstadoCompromisos()
  {
    $compromisos = "<br>Compromisos afectados > ";
    $dias_1 = -2;

    //Cambio de estado porcentaje EN PLANIFICACION a EN EJECUCION
    $compromisos_afectados_1 = $this->ejecucionCambioPorcentaje($dias_1, 'PLA', 'EJE');
    $compromisos_afectados_2 = $this->ejecucionCambiosEstadosPorFechaReporte('EJE');

    return array_merge($compromisos_afectados_1, $compromisos_afectados_2);
  }

  protected function ejecucionCambioPorcentaje($dias, $estadoPorcentaje, $estadoPropuesto)
  {
    $cqlEstadoPorcentaje = EstadoPorcentaje::where('abv', $estadoPorcentaje)->pluck('id');
    // $cqlEstadoGestion = Estado::where('abv', '--')->pluck('id');
    $calculo = strtotime("$dias days");
    $fecha = date("Y-m-d", $calculo);

    //Estado de compromiso en ejecucion y estado de gestion
    $cqlCompromisos = Compromiso::whereIn('estado_porcentaje_id', $cqlEstadoPorcentaje)
      ->whereNotNull('codigo')
      ->where('estado', 'ACT')
      ->where('fecha_inicio', '<=', $fecha)->get();
    $compromisosAfectados = [];

    foreach ($cqlCompromisos as $value) {
      //Aqui actualizo los estados de los compromisos
      $cqlEstado = EstadoPorcentaje::where('abv', $estadoPropuesto)->select('id')->first();
      $propuesto = EstadoPorcentaje::find($cqlEstado->id)->descripcion;
      $propuesto = is_null($propuesto) ? '' : $propuesto;

      $anterior = EstadoPorcentaje::find($value->estado_porcentaje_id)->descripcion;
      $anterior = is_null($anterior) ? '' : $anterior;

      if ($propuesto != $anterior) {
        array_push($compromisosAfectados, $value->id);
        $cqlCambioEstado = new CambioEstado();
        $cqlCambioEstado->fecha_reporte = $value->fecha_reporte;
        $cqlCambioEstado->propuesto = $propuesto;
        $cqlCambioEstado->anterior = $anterior;
        $cqlCambioEstado->fecha_inserta = date('Y-m-d H:i:s');
        $cqlCambioEstado->compromiso_id = $value->id;
        $cqlCambioEstado->estado_propuesto = $estadoPropuesto;
        $cqlCambioEstado->diferencia = 0;
        $cqlCambioEstado->tipo = 'PLANIFICACION';
        $cqlCambioEstado->save();

        $value->estado_porcentaje_id =
          (new RPC())->comparaCampos(
            $cqlEstado->id,
            $value->estado_porcentaje_id,
            $value->id,
            ('Estado de Gestión de ' . $anterior . ' al ' . $propuesto . ''),
            true,
            true,
            true,
            true
          );
        // $value->estado_porcentaje_id = $cqlEstado->id;
        $value->updated_at = date('Y-m-d H:i:s');
        $value->save();
      }
    }

    return $compromisosAfectados;
  }

  protected function ejecucionCambiosEstadosPorFechaReporte($estadoPorcentaje)
  {
    $diferencia = 0;
    $estadoPropuesto = 0;
    $cqlEstadoPorcentaje = EstadoPorcentaje::where('abv', $estadoPorcentaje)->pluck('id')->toArray();
    //$cqlEstadoGestion = Estado::where('abv', $estadoActual)->pluck('id')->toArray();
    $fecha = strtotime(date("Y-m-d"));
    //Consulto todos los compromisos en EJE
    $cqlCompromisos = Compromiso::whereIn('estado_porcentaje_id', $cqlEstadoPorcentaje)
      ->whereNotNull('fecha_reporte')
      ->whereNotNull('codigo')
      ->where('estado', 'ACT')

      ->get();

    $compromisosAfectados = [];
    //dd($estadoActual,"fecha",$fecha,$cqlCompromisos->toArray());
    foreach ($cqlCompromisos as $value) {
      $estadoPropuesto = 0;

      //Calculo la diferencia de dias desde hoy a la fecha del reporte del compromiso
      $diferencia = ($fecha - strtotime($value->fecha_reporte)) / 86400;
      //Aqui actualizo los estados de los compromisos
      if ($diferencia >= 21)  $estadoPropuesto = 'GRA';
      elseif ($diferencia >= 14)  $estadoPropuesto = 'MOD';
      elseif ($diferencia >= 7) $estadoPropuesto = 'LEV';
      elseif ($diferencia >= 2) $estadoPropuesto = 'BUE';
      if ($estadoPropuesto != 0) {
        $cqlEstadoPropuesto = Estado::where('abv', $estadoPropuesto)->first();
        $propuesto = Estado::find($cqlEstadoPropuesto->id)->descripcion;
        $propuesto = is_null($propuesto) ? '' : $propuesto;

        $anterior = Estado::find($value->estado_id)->descripcion;
        $anterior = is_null($anterior) ? '' : $anterior;
        /*  if($value->id==167)
        dd($propuesto,$anterior,$estadoPropuesto);*/
	if ($propuesto != $anterior) {
	  if( ($anterior == 'BUENO' && ($estadoPropuesto =='LEV' || $estadoPropuesto =='MOD' || $estadoPropuesto =='GRA'))
            || ($anterior == 'ATRASO LEVE' && ($estadoPropuesto =='MOD' || $estadoPropuesto =='GRA'))
            || ($anterior == 'ATRASO MODERADO' && $estadoPropuesto =='GRA')
            || $anterior == '--' || $estadoPropuesto == 'EJE'){
          array_push($compromisosAfectados, $value->id);
          $cqlCambioEstado = new CambioEstado();
          $cqlCambioEstado->fecha_reporte = $value->fecha_reporte;
          $cqlCambioEstado->propuesto = $propuesto;
          $cqlCambioEstado->anterior = $anterior;
          $cqlCambioEstado->fecha_inserta = date('Y-m-d H:i:s');
          $cqlCambioEstado->compromiso_id = $value->id;
          $cqlCambioEstado->estado_propuesto = $estadoPropuesto;
          $cqlCambioEstado->diferencia = $diferencia;
          $cqlCambioEstado->tipo = 'EJECUCION';
          $cqlCambioEstado->save();

          $value->estado_id =
            (new RPC())->comparaCampos(
              $cqlEstadoPropuesto->id,
              $value->estado_id,
              $value->id,
              ('Estado del Compromiso de ' . $anterior . ' al ' . $propuesto),
              true,
              true,
              true,
              true
            );
          //  $value->estado_id = $cqlEstadoPropuesto->id;
          $value->updated_at = date('Y-m-d H:i:s');
          $value->save();
	  }
	}
      }
    }
    return $compromisosAfectados;
  }
}
