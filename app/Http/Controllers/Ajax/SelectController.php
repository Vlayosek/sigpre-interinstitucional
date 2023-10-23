<?php

namespace App\Http\Controllers\Ajax;

use App\Core\Entities\Horarios\Grupos\Grupo;
use App\Core\Entities\Horarios\Grupos\GrupoPersona;
use App\Http\Controllers\Controller;
use Cassandra\Date;
use Illuminate\Support\Facades\DB;

/* ENVIO DE  CORREOS */
use App\Core\Entities\Admin\Notificacion;
use App\Mail\DemoEmail as Notificar;
use App\Mail\DemoEmailSR as NotificarSR;
use App\Mail\DemoEmailAdjuntoSR as NotificarAdjuntoSR;
use App\Mail\DemoEmailExterno as NotificarExterno;
use App\Mail\DemoEmailHTML as NotificarHTML;
use App\Mail\DemoEmailHTMLCompleto;
use App\Mail\EmailPermiso as NotificarPermiso;
/* ENVIO DE  CORREOS */
use App\Core\Entities\Portal\Subusuario_;
use App\Core\Entities\Portal\Usuario;
use App\Core\Entities\Admin\IPS;
use App\Core\Entities\Admin\IPSFuncionarioRestringido;

use App\Core\Entities\TalentoHumano\Distributivo\Persona as PersonaUath;
use App\Core\Entities\TalentoHumano\Distributivo\Historia_laboral;
use App\Core\Entities\TalentoHumano\Distributivo\Area;
use App\Core\Entities\TalentoHumano\Distributivo\Asignacion_area;
use App\Core\Entities\TalentoHumano\Distributivo\Tipo_contrato;
use App\Core\Entities\Admin\mhr;
use App\Core\Entities\Admin\Role;
use App\Core\Entities\Admin\AsignacionFuncionario;
use App\Core\Entities\Admin\Gobierno;
use App\Core\Entities\Sigap\Feriado;
use App\Core\Entities\TalentoHumano\Biometrico\Asistencia;
/* PERMISOS DE TALENTO HUMANO */
use App\Core\Entities\TalentoHumano\Permisos\Permiso;
use App\Core\Entities\TalentoHumano\Permisos\Estado;

use Auth;
use Mail;
use App\User;
use App\Core\Entities\TalentoHumano\Teletrabajo\Persona_planificacion;

class SelectController extends Controller
{
  private function transform($result, $response)
  {
    if (count($result) > 0) {
      if ($response == 'json') {
        return response()->json(['data' => $result], 200);
      } else {
        return $result;
      }
    } else {
      if ($response == 'json') {
        return response()->json(['No hay registros'], 404);
      } else {
        return response()->view('errors.503', [], 503);

        //abort(401);
      }
    }
  }

  public function dontBandeja($bandeja, $solicitud, $si, $verifica = 0)
  {

    $estadoVerificacion = DB::connection('pgsql_presidencia_solicitudes')
      ->table('bestados as be')
      ->join('nextcore.tb_parametro as tbp', 'tbp.id', 'be.departamento_id')
      ->join('nextcore.tb_parametro as tbp2', 'tbp2.id', 'be.estado_linea_id')
      ->join('nextcore.tb_parametro as tbp3', 'tbp3.id', 'tbp2.parametro_id')
      ->where(['be.estado' => 'A'])
      ->where('be.solicitud_id', $solicitud)
      ->select('tbp.descripcion as descripcion', 'be.estado_linea_id as estado', 'tbp2.verificacion as verificaEstado', 'tbp3.verificacion as verificabandeja')
      ->get()->toArray();
    //   dd($estadoVerificacion);
    if ($estadoVerificacion[0]->verificaEstado > $estadoVerificacion[0]->verificabandeja && $bandeja[0] == 'BANDEJA_VALIDACION') {

      return $this->transform(0, 'http');
    } elseif ($estadoVerificacion[0]->descripcion == $bandeja[0]) {
      return $this->transform(1, 'http');
    } else {
      $objBandeja = DB::connection('pgsql_presidencia')
        ->table('core.tb_parametro')
        ->whereIn('descripcion', $bandeja)
        ->select('id', 'descripcion')->get()->toArray();

      $arrayBandeja = array();
      foreach ($objBandeja as $item) {
        array_push($arrayBandeja, $item->id);
      }

      if ($si) {
        $verifica = DB::connection('pgsql_presidencia_solicitudes')
          ->table('bestados as a')
          ->join('nextcore.tb_parametro as tp', 'tp.id', 'a.estado_linea_id')
          ->join('solicitud as s', 'n_solicitud', 'a.solicitud_id')
          ->where(['a.estado' => 'A'])
          ->where('s.estado', 'A')
          ->where('tp.descripcion', 'SOLICITUD INGRESADA')
          ->where('a.solicitud_id', $solicitud)
          ->count();
        $var1 = 0;
        $var2 = 1;
      } else {
        $verifica = DB::connection('pgsql_presidencia_solicitudes')
          ->table('bestados')
          ->where(['estado' => 'A'])
          ->where('solicitud_id', $solicitud)
          ->whereIn('departamento_id', $arrayBandeja)
          ->count();
        $var1 = 1;
        $var2 = 0;
      }

      if ($verifica > 0) {
        return $this->transform($var2, 'http');
      } else {
        return $this->transform($var1, 'http');
      }
    }
  }

  public function searchCiudad($parametro, $type = 'json')
  {
    $result = DB::connection('pgsql_presidencia')
      ->table('core.tb_parametro AS C')
      ->where('C.parametro_id', $parametro)
      ->where('C.estado', 'A')
      ->groupBy('C.descripcion', 'C.id')
      ->orderBy('C.descripcion', 'desc')
      ->select('C.id as id', 'C.descripcion as descripcion')->get();

    //dd($result);

    if (count($result) > 0) {
      //$result = $result->get('descripcion', 'id');
      //$lista['data'] = $result;
      $array_response['status'] = 200;
      $array_response['message'] = $result;
    } else {
      $array_response['status'] = 404;
      $array_response['message'] = "No hay resultados";
    }



    return $array_response;
  }

  public function getPermission($id, $type = 'json')
  {
    $result = DB::connection('pgsql_presidencia')
      ->table('core.menus AS f')
      ->join('role_has_permission as h', 'h.permission_id', 'f.id')
      ->where('h.role_id', '=', $id)
      ->groupBy('f.id', 'f.name')
      ->orderBy('f.name', 'desc')
      ->select('f.id as id', 'f.name as name');
    if ($type == 'json') {
      $result = $result->get('id');
      $lista['data'] = $result;
      return response()->json($lista, 200);
    } else {
      $result = $result->pluck('id')->toArray();
      return $result;
    }
  }

  public function getParametro($parametro, $type = 'json', $v = 0, $excepciones = [])
  {
    $result1 = DB::connection('pgsql_presidencia')
      ->table('core.tb_parametro AS C')
      ->where('C.descripcion', $parametro)
      ->where('C.estado', 'A')
      ->select('C.id as id')->first();

    if (!is_null($result1)) {
      $result = DB::connection('pgsql_presidencia')
        ->table('core.tb_parametro AS C')
        ->where('C.parametro_id', $result1->id)
        ->where('C.estado', 'A')
        ->orderBy('id', 'ASC');
      if ($excepciones != [])  $result = $result->whereNotIn('C.descripcion', $excepciones);

      if ($v == 1)  $result = $result->whereNotIn('C.descripcion', ['SOLICITUD INGRESADA', 'SOLICITUD PREGRABADA', 'SOLICITUD CORREGIDA']);

      $result = $result->groupBy('C.descripcion', 'C.id')->orderBy('C.id', 'desc')->select('C.id as id', 'C.descripcion as descripcion');

      if ($type == 'json') {
        $result = $result->get('descripcion', 'id');
        $lista['data'] = $result;
        return response()->json($lista, 200);
      } else {
        if ($v == 4) $result = $result->pluck('descripcion', 'descripcion')->toArray();
        else if ($v == 3)  $result = $result->pluck('id', 'descripcion')->toArray();
        else  $result = $result->pluck('descripcion', 'id')->toArray();
        return $result;
      }
    } else
      return [];
  }

  public function getParameterFathera($parameter, $type = 'json')
  {
    $result = DB::connection('pgsql_presidencia')
      ->table('core.tb_parametro AS C')
      ->where('nivel', $parameter - 1)
      ->where('C.estado', 'A')
      ->groupBy('C.id', 'C.descripcion')
      ->orderBy('C.descripcion', 'desc')
      ->select('C.id as id', 'C.descripcion as descripcion');
    if ($type == 'json') {
      $result = $result->get('descripcion', 'id');
      $lista['data'] = $result;
      return response()->json($lista, 200);
    } else {
      $result = $result->pluck('descripcion', 'id')->toArray();
      return $result;
    }
  }

  public function getfatherparameter($response = 'http')
  {
    $result = DB::connection('pgsql_presidencia')
      ->table('core.tb_parametro AS F')
      ->where('F.estado', 'A')
      ->select('F.id AS id', 'F.descripcion as descripcion')
      ->orderBy('descripcion', 'desc')->pluck('descripcion', 'id')->toArray();

    return $this->transform($result, $response);
  }



  public function getGestores($role, $response = 'http')
  {
    $result = DB::connection('pgsql_presidencia')
      ->table('core.model_has_roles as r')
      ->join('core.users as u', 'r.model_id', 'u.id')
      ->join('core.solicitudes.empleados as emp', 'u.persona_id', 'emp.identificacion')
      ->where('r.role_id', $role)
      ->select('emp.identificacion as id', DB::raw("CONCAT(emp.apellidos,' ',emp.nombres) as name"))
      ->pluck('name', 'id')->toArray();
    return $this->transform($result, $response);
  }

  public function getFather($response = 'http')
  {
    $result = DB::connection('pgsql_presidencia')
      ->table('core.menus AS F')
      ->where('F.parent', '0')
      ->where('F.enabled', '1')
      ->select('F.id AS id', DB::raw('RTRIM(F.name) AS name'))
      ->orderBy('name', 'desc')->pluck('name', 'id')->toArray();

    return $this->transform($result, $response);
  }
  public function NotificarExterno($descripcion, $dirigido)
  {
    if (!config('app.NOTIFICAR'))
      return true;

    $ahora = date("Y-m-d H:i:s");
    $fecha = date("Y-m-d");
    $descripcion = 'Se le notifica que ' . $descripcion;
    $cql = new Notificacion();
    $cql->descripcion = $descripcion;
    //$cql->usuario_ingresa=Auth::user()->id;
    $cql->created_at = $ahora;
    $cql->fecha = $fecha;
    $for = $dirigido;
    $cqlConteo = Notificacion::where('fecha', $fecha)->get()->count();
    if ($cqlConteo >= config('app.notificaciones_diarias')) {
      $cql->estado = 'INA';
      $cql->save();
    } else {
      $cql->estado = 'INA';
      $cql->save();
      Mail::to($for)->send(new NotificarExterno($descripcion, '', $cql->id));
    }

    return $cql->estado == 'INA' ? false : true;
  }
  public function NotificarPermiso($descripcion, $html, $dirigido)
  {
    /*if(!config('app.NOTIFICAR'))
        return true;*/

    $ahora = date("Y-m-d H:i:s");
    $fecha = date("Y-m-d");
    $descripcion = 'Se le notifica que ' . $descripcion;
    $cql = new Notificacion();
    $cql->descripcion = $descripcion;
    $cql->usuario_ingresa = Auth::user()->id;
    $cql->created_at = $ahora;
    $cql->fecha = $fecha;
    $for = $dirigido;
    $cqlConteo = Notificacion::where('fecha', $fecha)->get()->count();
    $cql->estado = 'INA';
    $cql->save();
    Mail::to($for)->send(new NotificarPermiso($html, '', $cql->id));

        return $cql->estado == 'INA' ? false : true;
    }
    public function NotificarSinRegistro($descripcion, $dirigido)
    {
        if (!env('NOTIFICAR'))
            return true;
        try {

            $descripcion = 'Se le notifica que ,' . $descripcion;
            Mail::to($dirigido)->send(new NotificarSR($descripcion, '', 0));
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
    public function NotificarSinRegistroArray($descripcion, $dirigido)
    {
        if (!env('NOTIFICAR'))
            return true;
        if (!is_array($dirigido)) $dirigido = [$dirigido];
        foreach ($dirigido as $email) {
            try {
                if ($email != '' && is_null($email)) {
                    $descripcion = 'Se le notifica que ,' . $descripcion;
                    Mail::to($email)->send(new NotificarSR($descripcion, '', 0));
                    
                   // return true;
                }
            } catch (\Exception $e) {
               // return false;
            }
        }
        return true;
    }
    public function NotificarAdjuntoSinRegistro($descripcion, $dirigido, $file)
    {
        $file = str_replace("_", "/", $file);
        if (!env('NOTIFICAR'))
            return true;

    $descripcion = 'Se le notifica que ' . $descripcion;

    Mail::to($dirigido)->send(new NotificarAdjuntoSR($descripcion, '', 0, $file));

    return true;
  }
  public function Notificar($descripcion, $dirigido)
  {
    if (!config('app.NOTIFICAR'))
      return true;

    $ahora = date("Y-m-d H:i:s");
    $fecha = date("Y-m-d");
    $descripcion = 'Se le notifica que ' . $descripcion;
    $cql = new Notificacion();
    $cql->descripcion = $descripcion;
    $cql->usuario_ingresa = Auth::user()->id;
    $cql->created_at = $ahora;
    $cql->fecha = $fecha;
    $for = $dirigido;
    $cql->estado = 'INA';
    $cql->save();
    Mail::to($for)->send(new Notificar($descripcion, '', $cql->id));

    return $cql->estado == 'INA' ? false : true;
  }
  public function buscarSubusuarioAreaPortal($area_id)
  {
    $dataSubsuario = Subusuario_::leftjoin('login.usuario as u', 'distributivo.subusuario.usu_id', 'u.usu_id')
      ->leftjoin('distributivo.persona as p', 'p.per_id', 'u.per_id')
      ->leftjoin('distributivo.area as a', 'a.are_id', 'distributivo.subusuario.are_id')
      ->select(
        'u.usu_id as usu_id',
        'distributivo.subusuario.sus_id as sus_id',
      )
      ->where('distributivo.subusuario.are_id', $area_id)
      ->pluck('distributivo.subusuario.sus_id')->toArray();

    return $dataSubsuario;
  }
  public function buscarUsuarioPersonaPortal($per_id)
  {

    $dataSubsuario = Usuario::leftjoin('distributivo.persona as p', 'p.per_id', 'login.usuario.per_id')
      ->select(
        'login.usuario.usu_id as usu_id',
      )
      ->where('p.per_id', $per_id)
      ->pluck('login.usuario.usu_id')->toArray();
    return $dataSubsuario;
  }
  public function buscarDatosPortal($identificacion, $subusuario = false)
  {
    if ($subusuario == false) {
      $dataSubsuario = Subusuario_::leftjoin('login.usuario as u', 'distributivo.subusuario.usu_id', 'u.usu_id')
        ->leftjoin('distributivo.persona as p', 'p.per_id', 'u.per_id')
        ->leftjoin('distributivo.area as a', 'a.are_id', 'distributivo.subusuario.are_id')
        ->select(
          'u.usu_id as usu_id',
          'distributivo.subusuario.sus_id as sus_id',
          'distributivo.subusuario.are_id as are_id',
          'p.per_id as per_id',
          'p.per_mail as per_mail',
          'distributivo.subusuario.es_jefe_inmediato as jefe',
          'a.are_id_padre as are_id_padre',
          'a.are_nombre as nombre_area'
        )
        ->where('p.per_cedula', $identificacion)
        ->where('u.est_id', 1)
        ->where('distributivo.subusuario.est_id', 1)
        ->where('distributivo.subusuario.es_principal', 't')
        ->get()->first();
      $dataSubsuario2 = Subusuario_::leftjoin('login.usuario as u', 'distributivo.subusuario.usu_id', 'u.usu_id')
        ->leftjoin('distributivo.persona as p', 'p.per_id', 'u.per_id')
        ->leftjoin('distributivo.area as a', 'a.are_id', 'distributivo.subusuario.are_id')
        ->select(
          'u.usu_id as usu_id',
          'distributivo.subusuario.sus_id as sus_id',
          'distributivo.subusuario.are_id as are_id',
          'p.per_id as per_id',
          'p.per_mail as per_mail',
          'distributivo.subusuario.es_jefe_inmediato as jefe',
          'a.are_id_padre as are_id_padre',
          'a.are_nombre as nombre_area'
        )
        ->where('p.per_cedula', $identificacion)
        ->where('u.est_id', 1)
        ->where('distributivo.subusuario.est_id', 1)
        ->where('distributivo.subusuario.es_principal', 'f')
        ->get()->first();
    } else {
      $dataSubsuario = Subusuario_::leftjoin('login.usuario as u', 'distributivo.subusuario.usu_id', 'u.usu_id')
        ->leftjoin('distributivo.persona as p', 'p.per_id', 'u.per_id')
        ->leftjoin('distributivo.area as a', 'a.are_id', 'distributivo.subusuario.are_id')
        ->select(
          'u.usu_id as usu_id',
          'distributivo.subusuario.sus_id as sus_id',
          'distributivo.subusuario.are_id as are_id',
          'p.per_id as per_id',
          'p.per_mail as per_mail',
          'distributivo.subusuario.es_jefe_inmediato as jefe',
          'a.are_id_padre as are_id_padre',
          'a.are_nombre as nombre_area'
        )
        ->where('distributivo.subusuario.sus_id', $identificacion)
        ->where('u.est_id', 1)
        ->where('distributivo.subusuario.est_id', 1)
        ->where('distributivo.subusuario.es_principal', 't')
        ->get()->first();
      $dataSubsuario2 = Subusuario_::leftjoin('login.usuario as u', 'distributivo.subusuario.usu_id', 'u.usu_id')
        ->leftjoin('distributivo.persona as p', 'p.per_id', 'u.per_id')
        ->leftjoin('distributivo.area as a', 'a.are_id', 'distributivo.subusuario.are_id')
        ->select(
          'u.usu_id as usu_id',
          'distributivo.subusuario.sus_id as sus_id',
          'distributivo.subusuario.are_id as are_id',
          'p.per_id as per_id',
          'p.per_mail as per_mail',
          'distributivo.subusuario.es_jefe_inmediato as jefe',
          'a.are_id_padre as are_id_padre',
          'a.are_nombre as nombre_area'
        )
        ->where('distributivo.subusuario.sus_id', $identificacion)
        ->where('u.est_id', 1)
        ->where('distributivo.subusuario.est_id', 1)
        ->where('distributivo.subusuario.es_principal', 'f')
        ->get()->first();
    }
    $usuario['usu_id'] = $dataSubsuario != null ? $dataSubsuario->usu_id : 0;
    $usuario['sus_id'] = $dataSubsuario != null ? $dataSubsuario->sus_id : 0;
    $usuario['are_id'] = $dataSubsuario != null ? $dataSubsuario->are_id : 0;
    $usuario['per_id'] = $dataSubsuario != null ? $dataSubsuario->per_id : 0;
    $usuario['per_mail'] = $dataSubsuario != null ? $dataSubsuario->per_mail : '';
    $usuario['jefe'] = $dataSubsuario != null ? $dataSubsuario->jefe : '';
    $usuario['are_id_padre'] = $dataSubsuario != null ? $dataSubsuario->are_id_padre : '';
    $usuario['nombre_area'] = $dataSubsuario != null ? $dataSubsuario->nombre_area : '';
    $usuario['are_id_2'] = $dataSubsuario2 != null ? $dataSubsuario2->are_id : 0;

    return $usuario;
  }
  public function buscarJefePortal($identificacion, $todos = false)
  {

    $dataSubsuario = $this->buscarDatosPortal($identificacion);
    if ($dataSubsuario['jefe']) {
      $usuario = Subusuario_::leftjoin('login.usuario as u', 'distributivo.subusuario.usu_id', 'u.usu_id')
        ->leftjoin('distributivo.persona as p', 'p.per_id', 'u.per_id')
        ->leftjoin('distributivo.area as a', 'a.are_id', 'distributivo.subusuario.are_id')
        ->select(
          'distributivo.subusuario.es_jefe_inmediato as jefe',
          'p.per_id as per_id',
          'distributivo.subusuario.sus_id as sus_id',
          'distributivo.subusuario.usu_id as usu_id',
          'p.nombre_completo as nombre_completo',
          'p.per_cedula as cedula',
          'p.per_mail as mail',
        )
        ->where('distributivo.subusuario.are_id', $dataSubsuario['are_id_padre'])
        ->where('u.est_id', 1)
        ->where('distributivo.subusuario.est_id', 1)
        ->where('distributivo.subusuario.es_principal', 't');
      if ($todos != false) {
        $usuario = $usuario->where('distributivo.subusuario.es_jefe_inmediato', true);
      }
    } else {

      $usuario = Subusuario_::leftjoin('login.usuario as u', 'distributivo.subusuario.usu_id', 'u.usu_id')
        ->leftjoin('distributivo.persona as p', 'p.per_id', 'u.per_id')
        ->leftjoin('distributivo.area as a', 'a.are_id', 'distributivo.subusuario.are_id')
        ->select(
          'distributivo.subusuario.es_jefe_inmediato as jefe',
          'p.per_id as per_id',
          'distributivo.subusuario.sus_id as sus_id',
          'distributivo.subusuario.usu_id as usu_id',
          'p.nombre_completo as nombre_completo',
          'p.per_cedula as cedula',
          'p.per_mail as mail',
        )
        ->where('distributivo.subusuario.are_id', $dataSubsuario['are_id'])
        ->where('u.est_id', 1)
        ->where('distributivo.subusuario.est_id', 1)
        ->where('distributivo.subusuario.es_principal', 't');
      if ($todos != false) {
        $usuario = $usuario->where('distributivo.subusuario.es_jefe_inmediato', true);
      }
    }


    return $usuario->get()->toArray();
  }
  public function verificarIP()
  {
    $ipaddress = '';
    if (getenv('HTTP_CLIENT_IP'))
      $ipaddress = getenv('HTTP_CLIENT_IP');
    else if (getenv('HTTP_X_FORWARDED_FOR'))
      $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
    else if (getenv('HTTP_X_FORWARDED'))
      $ipaddress = getenv('HTTP_X_FORWARDED');
    else if (getenv('HTTP_FORWARDED_FOR'))
      $ipaddress = getenv('HTTP_FORWARDED_FOR');
    else if (getenv('HTTP_FORWARDED'))
      $ipaddress = getenv('HTTP_FORWARDED');
    else if (getenv('REMOTE_ADDR'))
      $ipaddress = getenv('REMOTE_ADDR');
    else
      $ipaddress = 'UNKNOWN';
    return $ipaddress;
  }
  public function verificarDispositivo()
  {

    $agent = new Agent();
    $navegador = $agent->isDesktop();
    if ($navegador != true) {
      return 'Usted no puede registrar su asistencia, desde un dispositivo no establecido. Esta acción se informará a DTIC.';
    }
    return '';
  }
  public function grabarArchivos($archivos, $ubicacion)
  {
    //  dd($archivos);
    $arregloArchivos = [];
    foreach ($archivos as $archivo) {
      $file      = $archivo;
      $extension = $file->getClientOriginalExtension();
      $nombre = $file->getClientOriginalName();
      $nameFile  = uniqid() . '.' . $extension;
      \Storage::disk('local')->put("$ubicacion/$nameFile",  \File::get($file));

      $archivoSubido['descripcion'] = $nameFile;
      $archivoSubido['nombre'] = $nombre;

      array_push($arregloArchivos, $archivoSubido);
    }

    return $arregloArchivos;
  }
  public function grabarArchivosStorage($archivos, $ubicacion)
  {
    $arregloArchivos = [];
    foreach ($archivos as $archivo) {
      $file      = $archivo;
      $extension = $file->getClientOriginalExtension();
      $nombre = $file->getClientOriginalName();
      $nameFile  = uniqid() . '.' . $extension;
      \Storage::disk('storage')->put("$ubicacion/$nameFile",  \File::get($file));

      $archivoSubido['descripcion'] = $nameFile;
      $archivoSubido['nombre'] = $nombre;

      array_push($arregloArchivos, $archivoSubido);
    }

    return $arregloArchivos;
  }
  public function grabarArchivosStorageMb($archivos, $ubicacion)
  {
    $arregloArchivos = [];
    foreach ($archivos as $archivo) {
      $file      = $archivo;
      $extension = $file->getClientOriginalExtension();
      $nombre = $file->getClientOriginalName();
      $nameFile  = uniqid() . '.' . $extension;
      \Storage::disk('storage')->put("$ubicacion/$nameFile",  \File::get($file));

      $archivoSubido['descripcion'] = $nameFile;
      $archivoSubido['nombre'] = $nombre;

      array_push($arregloArchivos, $archivoSubido);
    }

    return $arregloArchivos;
  }
  public function buscarIP_PresencialTeletrabajo($ip)
  {
    $objetos = explode('.', $ip);
    if (count($objetos) > 0) {
      if ($objetos[0] == '186' && $objetos[1] == '47') {
        $cql = IPS::where('objeto1', $objetos[0])
          ->where('objeto2', $objetos[1])
          ->where('objeto3', $objetos[2])
          ->where('objeto4', $objetos[3])
          ->where('eliminado', false)
          ->get()->count();
      } else {
        $cql = IPS::where('objeto1', $objetos[0])
          ->where('objeto2', $objetos[1])
          ->where('eliminado', false)
          ->get()->count();
      }
      return $cql > 0 ? 'PRES' : 'TELE';
    } else
      return 'S/I';
  }

  public function calcularTiempoPermiso($permiso_id)
  {

    $cql = DB::connection('pgsql_portal_permisos')
      ->select('SELECT permisos."tiempo_permiso"(?)', [
        $permiso_id,
      ]);
    return $cql[0]->tiempo_permiso;
  }
  public function calcularTiempoPermisoFDS($permiso_id)
  {

    $cql = DB::connection('pgsql_portal_permisos')
      ->select('SELECT permisos."tiempo_fin_semana"(?)', [
        $permiso_id,
      ]);
    return $cql[0]->tiempo_fin_semana;
  }
  public function buscarDatosUath($identificacion, $persona_id = null,$compromisos=false)
  {
    $usuario['persona_id'] = '--';
    $usuario['identificacion_'] =  '--';
    $usuario['nombres_'] =  '--';
    $usuario['correo_institucional'] = '--';
    $usuario['tipo_contrato'] = '--';
    $usuario['nombre_area'] =  '--';
    $usuario['sigla_area'] = '--';
    $usuario['historia_laboral_id'] = '--';
    $usuario['areas_adicionales'] =  '--';
    $usuario['nombre_cargo'] =  '--';
    $usuario['jefe'] =  '--';
    $usuario['persona_id_jefe'] = '--';
    $usuario['apellidos_nombres_jefe'] =  '--';
    $usuario['nombre_area_jefe'] =  '--';
    $usuario['correo_institucional_jefe'] =  '--';
    $usuario['identificacion_jefe'] = '--';
    $usuario['horarioTrabajo'] =  '--';
    $usuario['are_id_padre'] = '--';
    $usuario['are_id_padre_2'] =  '--';
    $usuario['area_id'] = '--';
    $usuario['area_id_2'] =  '--';
    $usuario['historia_laboral_id_2'] =  '--';

    if(!$compromisos){
      $horario = '';
      $nombres_ = '';
      if ($persona_id != null) {
        $cqlPersona = PersonaUath::find($persona_id);
        $identificacion = $cqlPersona != null ? $cqlPersona->identificacion : '';
      } else {
        $cqlPersona = PersonaUath::where('identificacion', $identificacion)->get()->first();
      }
      $nombres_ = $cqlPersona != null ? $cqlPersona->apellidos_nombres : '';

      $consultaAsignaciones = Asignacion_area::where('identificacion', $identificacion)->where('eliminado', false)->pluck('area_id')->toArray();

      $dataSubsuario = Historia_laboral::leftjoin('sc_distributivo_.personas as p', 'p.id', 'historias_laborales.persona_id')
        ->leftjoin('sc_distributivo_.areas  as a', 'a.id', 'historias_laborales.area_id')
        ->leftjoin('sc_distributivo_.cargos  as c', 'c.id', 'historias_laborales.cargo_id')
        ->leftjoin('sc_distributivo_.tipos_contratos  as tp', 'tp.id', 'historias_laborales.tipo_contrato_id')
        ->select(
          'historias_laborales.id as historia_laboral_id',
          'historias_laborales.area_id',
          'p.id as persona_id',
          'p.correo_institucional as correo_institucional',
          'c.es_jefe_inmediato as jefe',
          'a.area_id as are_id_padre',
          'a.nombre as nombre_area',
          'a.sigla as sigla',
          'p.identificacion as identificacion_',
          'c.nombre as cargo',
          'tp.nombre as tipo_contrato'
        )
        ->where('p.identificacion', $identificacion)
        ->where('sc_distributivo_.historias_laborales.eliminado', false)
        ->where('sc_distributivo_.historias_laborales.es_principal', true)
        ->where('sc_distributivo_.historias_laborales.eliminado_por_reingreso', false)
        ->where('sc_distributivo_.historias_laborales.estado', 'ACT')
        ->orderby('sc_distributivo_.historias_laborales.fecha_ingreso', 'desc')
        ->get()->first();

      $dataSubsuario2 = Historia_laboral::leftjoin('sc_distributivo_.personas as p', 'p.id', 'historias_laborales.persona_id')
        ->leftjoin('sc_distributivo_.areas  as a', 'a.id', 'historias_laborales.area_id')
        ->leftjoin('sc_distributivo_.cargos  as c', 'c.id', 'historias_laborales.cargo_id')
        ->leftjoin('sc_distributivo_.tipos_contratos  as tp', 'tp.id', 'historias_laborales.tipo_contrato_id')
        ->select(
          'historias_laborales.id as historia_laboral_id',
          'historias_laborales.area_id',
          'p.id as persona_id',
          'p.correo_institucional as correo_institucional',
          'c.es_jefe_inmediato as jefe',
          'a.area_id as are_id_padre',
          'a.nombre as nombre_area',
          'a.sigla as sigla',
          'c.nombre as cargo',
          'tp.nombre as tipo_contrato'

        )
        ->where('p.identificacion', $identificacion)
        ->where('sc_distributivo_.historias_laborales.eliminado', false)
        ->where('sc_distributivo_.historias_laborales.es_principal', false)
        ->where('sc_distributivo_.historias_laborales.eliminado_por_reingreso', false)

        ->where('sc_distributivo_.historias_laborales.estado', 'ACT')
        ->orderby('historias_laborales.fecha_ingreso', 'desc')
        ->get()->first();



      $es_jefe = $dataSubsuario != null ? $dataSubsuario->jefe : false;
      $usuario['are_id_padre'] = $dataSubsuario != null ? $dataSubsuario->are_id_padre : 0;
      $usuario['are_id_padre_2'] = $dataSubsuario2 != null ? $dataSubsuario2->are_id_padre : 0;
      $usuario['area_id'] = $dataSubsuario != null ? $dataSubsuario->area_id : 0;
      $usuario['area_id_2'] = $dataSubsuario2 != null ? $dataSubsuario2->area_id : 0;
      $usuario['historia_laboral_id_2'] = $dataSubsuario2 != null ? $dataSubsuario2->historia_laboral_id : 0;

      $datosJefes = Historia_laboral::leftjoin('sc_distributivo_.personas as p', 'p.id', 'historias_laborales.persona_id')
        ->leftjoin('sc_distributivo_.areas  as a', 'a.id', 'historias_laborales.area_id')
        ->leftjoin('sc_distributivo_.cargos  as c', 'c.id', 'historias_laborales.cargo_id')
        ->select(
          'historias_laborales.id as historia_laboral_id',
          'historias_laborales.area_id',
          'p.id as persona_id',
          'p.correo_institucional as correo_institucional',
          'c.es_jefe_inmediato as jefe',
          'a.area_id as are_id_padre',
          'a.nombre as nombre_area',
          'p.identificacion as identificacion_',
          'p.apellidos_nombres as apellidos_nombres_',
        )
        ->where('sc_distributivo_.historias_laborales.eliminado', false);
      if ($es_jefe)
        $datosJefes = $datosJefes->where('sc_distributivo_.historias_laborales.area_id', $usuario['are_id_padre']);
      else
        $datosJefes = $datosJefes->where('sc_distributivo_.historias_laborales.area_id', $usuario['area_id']);

      $datosJefes = $datosJefes
        ->where('sc_distributivo_.historias_laborales.estado', 'ACT')
        //  ->where('sc_distributivo_.historias_laborales.es_principal',true)
        ->where('c.es_jefe_inmediato', true)
        ->where('sc_distributivo_.historias_laborales.eliminado_por_reingreso', false)
        ->orderby('historias_laborales.fecha_ingreso', 'desc')
        ->orderby('historias_laborales.id', 'desc')
        ->get()->first();


      $date = date('Y-m-d');
      $dia = strtolower($this->saber_dia(date('Y-m-d')));
      $persona_id_default = is_null($dataSubsuario) ? 0 : $dataSubsuario->persona_id;
      $area_id_default = is_null($dataSubsuario) ? 0 : $dataSubsuario->area_id;

      $existeHorarioPersonal = GrupoPersona::where('persona_id', $persona_id_default)->exists();
      $existeHorarioArea = GrupoPersona::where('area_id', $area_id_default)->exists();
      if ($existeHorarioPersonal) {
        $horario = DB::table('sc_horarios.grupos_personas')
          ->select('grupos_detalles.*')
          ->join('sc_horarios.grupos', 'grupo_id', 'grupos.id')
          ->join('sc_horarios.grupos_detalles', 'grupos.id', 'grupos_detalles.grupo_id')
          ->where('grupos_personas.persona_id', $persona_id_default)
          ->where('grupos_personas.estado', 'ACT')
          ->whereDate('grupos_detalles.fecha_inicio', '<=', $date)->first();
      } elseif ($existeHorarioArea) {
        $horario = DB::table('sc_horarios.grupos_personas')
          ->select('grupos_detalles.*')
          ->join('sc_horarios.grupos', 'grupos_personas.grupo_id', 'grupos.id')
          ->join('sc_horarios.grupos_detalles', 'grupos.id', 'grupos_detalles.grupo_id')
          ->where('grupos_personas.area_id', $area_id_default)
          ->where('grupos_personas.estado', 'ACT')
          ->whereDate('grupos_detalles.fecha_inicio', '<=', $date)->first();
      } else {
        $horario = Grupo::join('sc_horarios.grupos_detalles', 'grupos.id', 'grupos_detalles.grupo_id')
          ->where('grupos.id', 1)->first();
      }

      $horarioActual = is_null($horario) ? null : $horario->$dia;
      /* if (strcmp($dia, 'sabado') == 1 || strcmp($dia, 'domingo') == 1) {
              $horarioActual = Grupo::select('grupos_detalles.lunes')->join('sc_horarios.grupos_detalles', 'grupos.id', 'grupos_detalles.grupo_id')
                  ->where('grupos.id', 1)->first();
          } */

          $usuario['persona_id'] = $dataSubsuario != null ? $dataSubsuario->persona_id : 0;
          $usuario['identificacion_'] = $identificacion;
          $usuario['nombres_'] = $nombres_;
          $usuario['correo_institucional'] = $dataSubsuario != null ? $dataSubsuario->correo_institucional : '';
          $usuario['tipo_contrato'] = $dataSubsuario != null ? $dataSubsuario->tipo_contrato : ($dataSubsuario2 != null ? $dataSubsuario2->tipo_contrato : '');
          $usuario['nombre_area'] = $dataSubsuario != null ? $dataSubsuario->nombre_area : '';
          $usuario['sigla_area'] = $dataSubsuario != null ? $dataSubsuario->sigla : '';
          $usuario['historia_laboral_id'] = $dataSubsuario != null ? $dataSubsuario->historia_laboral_id : 0;
          $usuario['areas_adicionales'] = $consultaAsignaciones;
          $usuario['nombre_cargo'] = $dataSubsuario != null ? $dataSubsuario->cargo : '';
          $usuario['jefe'] = $dataSubsuario != null ? $dataSubsuario->jefe : '';
          $usuario['persona_id_jefe'] = $datosJefes != null ? $datosJefes->persona_id : '--';
          $usuario['apellidos_nombres_jefe'] = $datosJefes != null ? $datosJefes->apellidos_nombres_ : '--';
          $usuario['nombre_area_jefe'] = $datosJefes != null ? $datosJefes->nombre_area : '--';
          $usuario['correo_institucional_jefe'] = $datosJefes != null ? $datosJefes->correo_institucional : null;
          $usuario['identificacion_jefe'] = $datosJefes != null ? $datosJefes->identificacion_ : null;
          $usuario['horarioTrabajo'] = $horarioActual;

    }
    return $usuario;

  }

  public function buscarUsuariosNombresAreaUATH($identificacion, $area_id = 0, $persona_id = null, $codigo = null)
  {
    $cqlConsulta = Tipo_contrato::select('id')
      ->where('nombre', 'LOSEP')
      ->pluck('id')->toArray();

    $usuario["area_id"] = 0;
    $usuario["area_id_2"] = 0;
    $usuario["areas_adicionales"] = [0];
    if ($identificacion == null)
      $usuario["area_id"] = $area_id;
    else
      $usuario = $this->buscarDatosUath($identificacion);
    $resultado = array_merge($usuario['areas_adicionales'], [$usuario['area_id'], $usuario['area_id_2']]);
    //dd($resultado);
    if ($persona_id == null) {
      $data = Historia_laboral::join('sc_distributivo_.personas as p', 'p.id', 'historias_laborales.persona_id')
        ->join('sc_distributivo_.areas as a', 'a.id', 'historias_laborales.area_id')
        ->select(
          'p.id as id',
          'p.identificacion as identificacion',
          'p.apellidos_nombres as apellidos_nombres',
          'historias_laborales.area_id as area_id',
        )
        ->whereIn('historias_laborales.area_id', $resultado)
        ->where('sc_distributivo_.historias_laborales.estado', 'ACT')
        ->where('sc_distributivo_.historias_laborales.eliminado_por_reingreso', false)
        ->where('sc_distributivo_.historias_laborales.eliminado', false)
        ->orderby('p.apellidos', 'ASC');

      if ($codigo != null)
        $data = $data->whereIn('tipo_contrato_id', $cqlConsulta);

      $data = $data
        ->pluck('p.apellidos_nombres', 'identificacion')
        ->toArray();
    } else {
      $data = Historia_laboral::distinct()
        ->select('p.apellidos_nombres', 'p.id')
        ->join('sc_distributivo_.personas as p', 'p.id', 'historias_laborales.persona_id')
        ->join('sc_distributivo_.areas as a', 'a.id', 'historias_laborales.area_id')
        ->select(
          'p.id as id',
          'p.identificacion as identificacion',
          'p.apellidos_nombres as apellidos_nombres',
          'historias_laborales.area_id as area_id',
        )
        ->whereIn('historias_laborales.area_id', $resultado);
      if ($codigo != null)
        $data = $data->whereIn('tipo_contrato_id', $cqlConsulta);

      $data = $data
        ->where('sc_distributivo_.historias_laborales.estado', 'ACT')
        ->where('sc_distributivo_.historias_laborales.eliminado_por_reingreso', false)
        ->where('sc_distributivo_.historias_laborales.eliminado', false)
        ->orderby('p.apellidos_nombres', 'ASC')
        ->pluck('p.apellidos_nombres', 'p.id')
        ->toArray();
    }
    //  dd($data);
    // dd($data);
    return $data;
  }
  public function buscarUsuariosAreaUATH($identificacion, $area_id = 0)
  {
    $usuario["area_id"] = 0;
    $usuario["area_id_2"] = 0;
    $usuario["areas_adicionales"] = [0];
    if ($identificacion == null)
      $usuario["area_id"] = $area_id;
    else
      $usuario = $this->buscarDatosUath($identificacion);
    $resultado = array_merge($usuario['areas_adicionales'], [$usuario['area_id'], $usuario['area_id_2']]);
    //  dd($resultado);
    $data = Historia_laboral::join('sc_distributivo_.personas as p', 'p.id', 'historias_laborales.persona_id')
      ->join('sc_distributivo_.areas as a', 'a.id', 'historias_laborales.area_id')
      ->select(
        'p.identificacion as identificacion',
        'p.apellidos_nombres as apellidos_nombres',
        'historias_laborales.area_id as area_id'
      )
      ->where('sc_distributivo_.historias_laborales.estado', 'ACT')
      ->whereIn('sc_distributivo_.historias_laborales.area_id', $resultado)
      ->where('sc_distributivo_.historias_laborales.eliminado_por_reingreso', false)
      ->where('sc_distributivo_.historias_laborales.eliminado', false)
      ->pluck('identificacion', 'apellidos_nombres')->toArray();
    //  dd($usuario["area_id"],$usuario["area_id_2"]);
    return $data;
  }
  public function buscarDatosUathAreas($area_id, $jefe = false)
  {
    $cqlUsuario = Historia_laboral::join('sc_distributivo_.personas as p', 'p.id', 'sc_distributivo_.historias_laborales.persona_id')
      ->join('sc_distributivo_.areas as a', 'a.id', 'sc_distributivo_.historias_laborales.area_id')
      ->join('sc_distributivo_.cargos as c', 'c.id', 'sc_distributivo_.historias_laborales.cargo_id')
      ->select(
        'historias_laborales.id as historia_laboral_id',
        'historias_laborales.area_id',
        'p.id as persona_id',
        'p.correo_institucional as correo_institucional',
        'c.es_jefe_inmediato as jefe',
        'a.area_id as are_id_padre',
        'a.nombre as nombre_area',
        'c.nombre as nombre_cargo',
        'p.identificacion as identificacion',
        'p.apellidos_nombres as apellidos_nombres'
      )
      ->whereIn('sc_distributivo_.historias_laborales.area_id', [$area_id])
      ->where('sc_distributivo_.historias_laborales.eliminado', false)
      ->where('sc_distributivo_.historias_laborales.eliminado_por_reingreso', false)
      ->where('sc_distributivo_.historias_laborales.es_principal', true);

    if ($jefe) {
      $cqlUsuario = $cqlUsuario
        ->where('c.es_jefe_inmediato', true)
        ->where('historias_laborales.estado', 'ACT')
        ->orderby('historias_laborales.id', 'desc')
        ->orderby('historias_laborales.fecha_ingreso', 'desc')
        ->first();
    } else {
      $cqlUsuario = $cqlUsuario
        ->orderby('historias_laborales.id', 'desc')
        ->pluck('identificacion')
        ->toArray();
    }
    return  $cqlUsuario;
  }
  public function buscarArea($nombre = '', $area_id = 0)
  {
    if ($nombre == '')
      return Area::where('area_id', $area_id)->fisrt();
    else
      return Area::where('nombre', $nombre)->fisrt();
  }
  public function buscarAreasHijos($area_id)
  {
    $area_id = (is_array($area_id)) ? $area_id : [$area_id];
    return Area::whereIn('area_id', $area_id)->pluck('id', 'nombre')->toArray();
  }
  public function buscarJefeSIGPREIdentificacion($identificacion, $usuario = false, $codigo = null)
  {
    //////// METODO DESACTUALIZADO
    $cqlConsulta = Tipo_contrato::select('id')
      ->where('nombre', 'LOSEP')
      ->pluck('id')->toArray();

    $dataSubsuario = $this->buscarDatosUath($identificacion);
    $id_area_1 = $this->buscarAreasHijos($dataSubsuario['area_id']);
    $id_area_2 = $this->buscarAreasHijos($dataSubsuario['area_id_2']);
    $consultaAsignaciones = $dataSubsuario['areas_adicionales'];
    //    dd($id_area_1,$id_area_2,$consultaAsignaciones,$dataSubsuario['area_id'],$dataSubsuario['area_id_2'],$dataSubsuario);
    if ($dataSubsuario['jefe']) {
      $cqlUsuario = Historia_laboral::join('sc_distributivo_.personas as p', 'p.id', 'historias_laborales.persona_id')
        ->join('sc_distributivo_.areas as a', 'a.id', 'historias_laborales.area_id')
        ->join('sc_distributivo_.cargos as c', 'c.id', 'sc_distributivo_.historias_laborales.cargo_id')
        ->select(
          'historias_laborales.id as historia_laboral_id',
          'historias_laborales.area_id',
          'p.id as persona_id',
          'p.correo_institucional as correo_institucional',
          'c.es_jefe_inmediato as jefe',
          'a.area_id as are_id_padre',
          'a.nombre as nombre_area',
          'p.apellidos_nombres as apellidos_nombres',
          'p.identificacion as identificacion'
        )
        ->whereIn('historias_laborales.area_id', [$dataSubsuario['area_id'], $dataSubsuario['area_id_2']])
        ->where('sc_distributivo_.historias_laborales.eliminado', false)
        ->where('sc_distributivo_.historias_laborales.eliminado_por_reingreso', false)
        ->where('sc_distributivo_.historias_laborales.es_principal', true)
        ->where('sc_distributivo_.historias_laborales.estado', 'ACT')
        ->orderby('historias_laborales.id', 'desc');
      if ($codigo != null) $cqlUsuario = $cqlUsuario->whereIn('tipo_contrato_id', $cqlConsulta);

      $cqlUsuarioJefes_1 =
        Historia_laboral::join('sc_distributivo_.personas as p', 'p.id', 'historias_laborales.persona_id')
        ->join('sc_distributivo_.areas as a', 'a.id', 'historias_laborales.area_id')
        ->join('sc_distributivo_.cargos as c', 'c.id', 'sc_distributivo_.historias_laborales.cargo_id')
        ->select(
          'historias_laborales.id as historia_laboral_id',
          'historias_laborales.area_id',
          'p.id as persona_id',
          'p.correo_institucional as correo_institucional',
          'c.es_jefe_inmediato as jefe',
          'a.area_id as are_id_padre',
          'a.nombre as nombre_area',
          'p.apellidos_nombres as apellidos_nombres',
          'p.identificacion as identificacion'
        )
        ->whereIn('historias_laborales.area_id', $id_area_1)
        ->where('sc_distributivo_.historias_laborales.eliminado', false)
        ->where('sc_distributivo_.historias_laborales.eliminado_por_reingreso', false)
        //  ->where('sc_distributivo_.historias_laborales.es_principal',true)
        ->where('c.es_jefe_inmediato', true)
        ->where('sc_distributivo_.historias_laborales.estado', 'ACT')
        ->orderby('historias_laborales.id', 'desc');
      if ($codigo != null) $cqlUsuarioJefes_1 = $cqlUsuarioJefes_1->whereIn('tipo_contrato_id', $cqlConsulta);


      $cqlUsuarioJefes_2 =
        Historia_laboral::join('sc_distributivo_.personas as p', 'p.id', 'historias_laborales.persona_id')
        ->join('sc_distributivo_.areas as a', 'a.id', 'historias_laborales.area_id')
        ->join('sc_distributivo_.cargos as c', 'c.id', 'sc_distributivo_.historias_laborales.cargo_id')
        ->select(
          'historias_laborales.id as historia_laboral_id',
          'historias_laborales.area_id',
          'p.id as persona_id',
          'p.correo_institucional as correo_institucional',
          'c.es_jefe_inmediato as jefe',
          'a.area_id as are_id_padre',
          'a.nombre as nombre_area',
          'p.apellidos_nombres as apellidos_nombres',
          'p.identificacion as identificacion'
        )
        ->whereIn('historias_laborales.area_id', $id_area_2)
        ->where('sc_distributivo_.historias_laborales.eliminado', false)
        ->where('sc_distributivo_.historias_laborales.eliminado_por_reingreso', false)

        // ->where('sc_distributivo_.historias_laborales.es_principal',true)
        ->where('c.es_jefe_inmediato', true)
        ->where('sc_distributivo_.historias_laborales.estado', 'ACT')
        ->orderby('historias_laborales.id', 'desc');
      if ($codigo != null) $cqlUsuarioJefes_2 = $cqlUsuarioJefes_2->whereIn('tipo_contrato_id', $cqlConsulta);

      $cqlUsuarioJefes_3 =
        Historia_laboral::join('sc_distributivo_.personas as p', 'p.id', 'historias_laborales.persona_id')
        ->join('sc_distributivo_.areas as a', 'a.id', 'historias_laborales.area_id')
        ->join('sc_distributivo_.cargos as c', 'c.id', 'sc_distributivo_.historias_laborales.cargo_id')
        ->select(
          'historias_laborales.id as historia_laboral_id',
          'historias_laborales.area_id',
          'p.id as persona_id',
          'p.correo_institucional as correo_institucional',
          'c.es_jefe_inmediato as jefe',
          'a.area_id as are_id_padre',
          'a.nombre as nombre_area',
          'p.apellidos_nombres as apellidos_nombres',
          'p.identificacion as identificacion'
        )
        ->whereIn('historias_laborales.area_id', $consultaAsignaciones)
        ->where('sc_distributivo_.historias_laborales.eliminado', false)
        ->where('sc_distributivo_.historias_laborales.eliminado_por_reingreso', false)

        ->where('sc_distributivo_.historias_laborales.estado', 'ACT')
        ->orderby('historias_laborales.id', 'desc');
      if ($codigo != null) $cqlUsuarioJefes_3 = $cqlUsuarioJefes_3->whereIn('tipo_contrato_id', $cqlConsulta);
      //    dd($cqlUsuarioJefes_3->get()->toArray());
      // dd($id_area_1,$id_area_2,$cqlUsuario->get()->toArray(),$cqlUsuarioJefes_2->get()->toArray(),$cqlUsuarioJefes_1->get()->toArray());
      $cqlUsuario = $cqlUsuario->union($cqlUsuarioJefes_1)
        ->union($cqlUsuarioJefes_2)
        ->union($cqlUsuarioJefes_3);
      if (!$usuario) $cqlUsuario = $cqlUsuario->pluck('identificacion')->toArray();
      else $cqlUsuario = $cqlUsuario->pluck('p.apellidos_nombres', 'identificacion')->toArray();
      $array_response['identificaciones'] = $cqlUsuario;
    } else {
      $cqlUsuario = Historia_laboral::join('sc_distributivo_.personas as p', 'p.id', 'historias_laborales.persona_id')
        ->join('sc_distributivo_.areas as a', 'a.id', 'historias_laborales.area_id')
        ->join('sc_distributivo_.cargos as c', 'c.id', 'sc_distributivo_.historias_laborales.cargo_id')
        ->select(
          'historias_laborales.id as historia_laboral_id',
          'historias_laborales.area_id',
          'p.id as persona_id',
          'p.correo_institucional as correo_institucional',
          'c.es_jefe_inmediato as jefe',
          'a.area_id as are_id_padre',
          'a.nombre as nombre_area',
          'p.apellidos_nombres as apellidos_nombres',
          'p.identificacion as identificacion'
        )
        ->whereIn('historias_laborales.area_id', [$dataSubsuario['area_id'], $dataSubsuario['area_id_2']])
        ->where('sc_distributivo_.historias_laborales.eliminado', false)
        ->where('sc_distributivo_.historias_laborales.eliminado_por_reingreso', false)

        ->where('sc_distributivo_.historias_laborales.es_principal', true)
        ->where('sc_distributivo_.historias_laborales.estado', 'ACT')
        ->orderby('historias_laborales.id', 'desc');
      if ($codigo != null) $cqlUsuario = $cqlUsuario->whereIn('tipo_contrato_id', $cqlConsulta);


      $cqlUsuario_2 = Historia_laboral::join('sc_distributivo_.personas as p', 'p.id', 'historias_laborales.persona_id')
        ->join('sc_distributivo_.areas as a', 'a.id', 'historias_laborales.area_id')
        ->join('sc_distributivo_.cargos as c', 'c.id', 'sc_distributivo_.historias_laborales.cargo_id')
        ->select(
          'historias_laborales.id as historia_laboral_id',
          'historias_laborales.area_id',
          'p.id as persona_id',
          'p.correo_institucional as correo_institucional',
          'c.es_jefe_inmediato as jefe',
          'a.area_id as are_id_padre',
          'a.nombre as nombre_area',
          'p.apellidos_nombres as apellidos_nombres',
          'p.identificacion as identificacion'
        )
        ->whereIn('historias_laborales.area_id', $consultaAsignaciones)
        ->where('sc_distributivo_.historias_laborales.eliminado', false)
        ->where('sc_distributivo_.historias_laborales.eliminado_por_reingreso', false)

        ->where('sc_distributivo_.historias_laborales.es_principal', true)
        ->where('sc_distributivo_.historias_laborales.estado', 'ACT')
        ->orderby('historias_laborales.id', 'desc');
      if ($codigo != null) $cqlUsuario_2 = $cqlUsuario_2->whereIn('tipo_contrato_id', $cqlConsulta);

      $cqlUsuario = $cqlUsuario->union($cqlUsuario_2);
      if (!$usuario) $cqlUsuario = $cqlUsuario->pluck('identificacion')->toArray();
      else $cqlUsuario = $cqlUsuario->pluck('p.apellidos_nombres', 'identificacion')->toArray();

      $array_response['identificaciones'] = $cqlUsuario;
    }
    //  dd($array_response['identificaciones']);
    $array_response['jefe'] = $dataSubsuario['jefe'];
    return $array_response;
  }


  protected function restriccionesPermisos($data, $horario)
  {
    $start = is_null($horario[2]) ? date('Y-m-d') : $horario[2];
    $end = is_null($horario[3]) ? date('Y-m-d') : $horario[3];
    $cqlEstadoAP = Estado::select('id')->where('nombre', 'APROBADO')->get()->first();

    $data = $data->leftjoin('sc_permisos_vacaciones.permisos as per', function ($join) use ($cqlEstadoAP, $start, $end) {
      $join->on('per.identificacion', 'p.identificacion')
        ->where('per.estado', 'ACT')
        ->where('per.fecha_inicio', '>=', $start)
        ->where('per.fecha_fin', '<=', $end)
        ->whereDate('per.estado', 'ACT')
        ->where('per.estado', 'ACT')
        ->where('per.eliminado', false)
        ->where(function ($query) use ($cqlEstadoAP) {
          $query->select(DB::RAW('COUNT(pes.id)'))
            ->from('sc_permisos_vacaciones.permisos_estados as pes')
            ->whereColumn('pes.permiso_id', 'per.id')
            ->whereNotIn('pes.eliminado', [true])
            ->where('pes.estado_id', $cqlEstadoAP->id);
        }, '>', 0)->limit(1);
    });

    return $data;
  }
  protected function restriccionesGruposHorarios($data, $horario)
  {
    // dd($horario);
    $start = is_null($horario[2]) ? date('Y-m-d') : $horario[2];
    $end = is_null($horario[3]) ? date('Y-m-d') : $horario[3];

    return $data->join('sc_horarios.grupos_personas as gp', function ($join) {
      $join->on('gp.persona_id', 'p.id')
        ->where('gp.estado', 'ACT');
    })
      ->join('sc_horarios.grupos as grupo', function ($join) use ($horario) {
        $join->on('grupo.id', 'gp.grupo_id')
          ->where('grupo.estado', 'ACT')
          ->where('grupo.teletrabajo', $horario[1]);
      })

      ->join('sc_horarios.grupos_detalles as gp_d', function ($join) use ($start, $end) {
        $join->on('gp_d.grupo_id', 'gp.grupo_id')
          /* ->where(function ($q) use ($start, $end) {
                        $q
                            ->where(DB::RAW("CASE WHEN gp_d.fecha_inicio IS NULL THEN
                        to_char(now(),'YYYY-MM-DD')::date ELSE
                        gp_d.fecha_inicio
                    END
                    "), '>', $end)
                            ->orwhere(DB::RAW("CASE WHEN gp_d.fecha_fin IS NULL THEN
                        to_char(now(),'YYYY-MM-DD')::date ELSE
                        gp_d.fecha_fin
                    END
                    "), '>', $start)
                            ->orwhereBetween(DB::RAW("CASE WHEN gp_d.fecha_inicio IS NULL THEN
                                to_char(now(),'YYYY-MM-DD')::date ELSE
                                gp_d.fecha_inicio
                            END
                            "), [$start, $end])
                            ->orwhereBetween(DB::RAW("CASE WHEN gp_d.fecha_fin IS NULL THEN
                            to_char(now(),'YYYY-MM-DD')::date ELSE
                            gp_d.fecha_fin
                        END
                        "), [$start, $end]);
                    })
                    ->where(function ($q) use ($start, $end) {
                        $q->where(DB::RAW("CASE WHEN gp_d.fecha_inicio IS NULL THEN
                            to_char(now(),'YYYY-MM-DD')::date ELSE
                            gp_d.fecha_inicio
                        END
                        "), '<=', $start)
                            ->orwhere(function ($q_) use ($start, $end) {
                                $q_->orwhere(DB::RAW("CASE WHEN gp_d.fecha_inicio IS NULL THEN
                                to_char(now(),'YYYY-MM-DD')::date ELSE
                                gp_d.fecha_inicio
                            END
                            "), '>=', $start)->orwhere(DB::RAW("CASE WHEN gp_d.fecha_fin IS NULL THEN
                                to_char(now(),'YYYY-MM-DD')::date ELSE
                                gp_d.fecha_fin
                            END
                            "), '<=', $end);
                            });
                    })*/


          ->where('gp_d.estado', 'ACT');
      })
      ->where(function ($q) use ($start, $end) {
        $q->orwhere(function ($q_1) use ($start, $end) {
          $q_1->where(DB::RAW("CASE WHEN gp_d.fecha_inicio IS NULL THEN
                                to_char(now(),'YYYY-MM-DD')::date ELSE
                                gp_d.fecha_inicio
                            END
                            "), '<=', $start)
            ->where(DB::RAW("CASE WHEN gp_d.fecha_fin IS NULL THEN
                                to_char(now(),'YYYY-MM-DD')::date ELSE
                                gp_d.fecha_fin
                            END
                            "), '>=', $end);
        })

          ->orwhereBetween(DB::RAW("CASE WHEN gp_d.fecha_inicio IS NULL THEN
                                to_char(now(),'YYYY-MM-DD')::date ELSE
                                gp_d.fecha_inicio
                            END
                            "), [$start, $end])
          ->orwhereBetween(DB::RAW("CASE WHEN gp_d.fecha_fin IS NULL THEN
                            to_char(now(),'YYYY-MM-DD')::date ELSE
                            gp_d.fecha_fin
                        END
                        "), [$start, $end]);
      });
  }
  protected function restriccionesPlanificacionTeletrabajo($data, $planificacion_id)
  {
    return $data->whereNotIn('p.identificacion', Persona_planificacion::where('planificacion_id', $planificacion_id)
      ->pluck('identificacion'));
  }
  protected function selectDatatableHistoriaLaboral(
    $horario = [false, false, null, null],
    $restricciones = [false, false, false],
    $planificacion_id = 0
  ) {
    $data = Historia_laboral::join('sc_distributivo_.personas as p', 'p.id', 'historias_laborales.persona_id')
      ->join('sc_distributivo_.areas as a', 'a.id', 'historias_laborales.area_id')
      ->join('sc_distributivo_.cargos as c', 'c.id', 'sc_distributivo_.historias_laborales.cargo_id')
      ->where('sc_distributivo_.historias_laborales.eliminado', false)
      ->where('sc_distributivo_.historias_laborales.eliminado_por_reingreso', false)
      ->where('sc_distributivo_.historias_laborales.estado', 'ACT')
      ->orderby('historias_laborales.id', 'desc');
    /* RESTRICCIONES */
    if ($planificacion_id != 0)  $this->restriccionesPlanificacionTeletrabajo($data, $planificacion_id);
    if ($horario[0]) {
      $data = $this->restriccionesGruposHorarios($data, $horario);
      $data = $data->select(
        'historias_laborales.id as historia_laboral_id',
        'historias_laborales.area_id',
        'p.id as persona_id',
        'p.correo_institucional as correo_institucional',
        'c.es_jefe_inmediato as jefe',
        'a.area_id as are_id_padre',
        'a.nombre as nombre_area',
        'p.apellidos_nombres as apellidos_nombres',
        'p.identificacion as identificacion',
        'gp_d.lunes',
        'gp_d.martes',
        'gp_d.miercoles',
        'gp_d.jueves',
        'gp_d.viernes',
        'gp_d.sabado',
        'gp_d.domingo',
        DB::RAW("CONCAT(
                                    CASE WHEN gp_d.fecha_inicio IS NULL THEN
                                        to_char(now(),'YYYY-MM-DD')::date ELSE
                                        gp_d.fecha_inicio
                                    END,
                                    '|',
                                    CASE WHEN gp_d.fecha_fin IS NULL THEN
                                        to_char(now(),'YYYY-MM-DD')::date ELSE
                                        gp_d.fecha_fin
                                    END,
                                    '|',
                                    p.identificacion
                                    ) as concat_identificacion")
      );
    } else {
      $data = $data->select(
        'historias_laborales.id as historia_laboral_id',
        'historias_laborales.area_id',
        'p.id as persona_id',
        'p.correo_institucional as correo_institucional',
        'c.es_jefe_inmediato as jefe',
        'a.area_id as are_id_padre',
        'a.nombre as nombre_area',
        'p.apellidos_nombres as apellidos_nombres',
        'p.identificacion as identificacion'
      );
    }
    if ($restricciones[2]) {
      $fi = $horario[2];
      $ff = $horario[3];
      $ESTADOS_PERMISOS = ['APROBADO', 'CERTIFICADO VALIDO'];

      $data = $data->addSelect(
        [
          'permisos' =>
          Permiso::select(
            DB::RAW("array_to_string(ARRAY_AGG(DISTINCT(CONCAT(permisos.fecha_inicio,' ',permisos.fecha_fin))),',')")
          )
            ->whereColumn('sc_permisos_vacaciones.permisos.identificacion', 'p.identificacion')
            ->where('sc_permisos_vacaciones.permisos.estado', 'ACT')
            ->where('sc_permisos_vacaciones.permisos.anulado', false)
            ->where('sc_permisos_vacaciones.permisos.eliminado', false)
            ->where('sc_permisos_vacaciones.permisos.tipo_solicitud', 'DIAS')
            ->where(function ($q) use ($fi, $ff) {
              $q->orwhere(function ($q_1) use ($fi, $ff) {
                $q_1->where('sc_permisos_vacaciones.permisos.fecha_inicio', '<=', $fi)
                  ->where('sc_permisos_vacaciones.permisos.fecha_fin', '>=', $ff);
              })
                ->orwhereBetween('sc_permisos_vacaciones.permisos.fecha_inicio', [$fi, $ff])
                ->orwhereBetween('sc_permisos_vacaciones.permisos.fecha_fin', [$fi, $ff]);
            })
            ->where(function ($query) use ($ESTADOS_PERMISOS) {
              $query->select(DB::RAW('COUNT(pes.id)'))
                ->from('sc_permisos_vacaciones.permisos_estados as pes')
                ->whereColumn('pes.permiso_id', 'sc_permisos_vacaciones.permisos.id')
                ->whereNotIn('pes.eliminado', [true])
                ->where('pes.estado_id', Estado::select('id')->where('nombre', $ESTADOS_PERMISOS)->first()->id);
            }, '>', 0)
        ]
      );
    }




    return $data;
  }
  protected function buscarUsuarioPorArea(
    $areas,
    $principal = false,
    $restricciones = [false, false, false],
    $horario = [false, false, null, null],
    $planificacion_id = 0
  ) {
    $data = $this->selectDatatableHistoriaLaboral(
      $horario,
      $restricciones,
      $planificacion_id
    )
      ->whereIn('historias_laborales.area_id', $areas);
    if ($principal) $data = $data->where('sc_distributivo_.historias_laborales.es_principal', true);
    return $data;
  }
  protected function buscarUsuarioJefesAreaHijas(
    $areas,
    $restricciones = [false, false, false],
    $horario = [false, false, null, null],
    $planificacion_id
  ) {
    return  $this->selectDatatableHistoriaLaboral(
      $horario,
      $restricciones,
      $planificacion_id
    )
      ->whereIn('historias_laborales.area_id', $areas)
      ->where('c.es_jefe_inmediato', true);
  }
  //METODO SOLAMENTE LOSEP POR DEFECTO ,EXTRAE TODOS LOS FUNCIONARIOS DEL AREA
  /*
                 POR DEFECTO
                $identificacion, //CEDULA DE QUIEN REVISA
                $horario=[true,true],//SI DESEA CONSULTA CON INTERVENCION DE HORARIOS
                                     [0]=true //activa intervencion de horarios
                                     [1]=true //activa inervencion de horarios de teletrabajo

                                     [0]=false //desactiva intervencion de horarios
                                     [1]=false //activa inervencion de horarios normales

                $codigo=true,// null para todos los funcionarios
                $usuario = true // false para solo cedulas
            */
  public function buscarUsuariosUATH(
    $identificacion,
    $restricciones = [false, false, false],
    $horario = [false, false, null, null],
    $planificacion_id = 0
  ) {
    $codigo = $restricciones[0];
    $usuario = $restricciones[1];
    $permisos = $restricciones[2];

    $cqlConsulta = Tipo_contrato::select('id')
      ->where('nombre', 'LOSEP')
      ->pluck('id')->toArray();

    $dataHorario = $horario;

    $dataSubsuario = $this->buscarDatosUath($identificacion);
    $areas_distributivo = [$dataSubsuario['area_id'], $dataSubsuario['area_id_2']];
    $areas_hijas = $this->buscarAreasHijos($areas_distributivo);
    $consultaAsignaciones = $dataSubsuario['areas_adicionales'];

    $cqlUsuario = $this->buscarUsuarioPorArea($areas_distributivo, true, $restricciones, $dataHorario,  $planificacion_id);
    if (!$codigo) $cqlUsuario = $cqlUsuario->whereIn('tipo_contrato_id', $cqlConsulta);
    if ($dataSubsuario['jefe']) {

      $cqlUsuarioJefes_1 = $this->buscarUsuarioJefesAreaHijas($areas_hijas, $restricciones,  $dataHorario, $planificacion_id);
      if (!$codigo) $cqlUsuarioJefes_1 = $cqlUsuarioJefes_1->whereIn('tipo_contrato_id', $cqlConsulta);

      $cqlUsuarioJefes_3 = $this->buscarUsuarioPorArea($consultaAsignaciones, false, $restricciones, $dataHorario, $planificacion_id);
      if (!$codigo) $cqlUsuarioJefes_3 = $cqlUsuarioJefes_3->whereIn('tipo_contrato_id', $cqlConsulta);

      $cqlUsuario = $cqlUsuario->union($cqlUsuarioJefes_1)
        ->union($cqlUsuarioJefes_3);
    } else {
      if (!$codigo) $cqlUsuario = $cqlUsuario->whereIn('tipo_contrato_id', $cqlConsulta);
      $cqlUsuario_2 = $this->buscarUsuarioPorArea($consultaAsignaciones, true, $restricciones, $dataHorario, $planificacion_id);

      if (!$codigo) $cqlUsuario_2 = $cqlUsuario_2->whereIn('tipo_contrato_id', $cqlConsulta);
      $cqlUsuario = $cqlUsuario->union($cqlUsuario_2);
    }
    /*
        if (!$horario[0]) {
            if (!$usuario) $cqlUsuario = $cqlUsuario->pluck('identificacion')->toArray();
            else $cqlUsuario = $cqlUsuario->pluck('p.apellidos_nombres', 'identificacion')->toArray();
        } else {
            if (!$usuario) $cqlUsuario = $cqlUsuario->pluck('concat_identificacion')->toArray();
            else $cqlUsuario = $cqlUsuario->pluck('p.apellidos_nombres', 'concat_identificacion')->toArray();
        }
*/

    $array_response['identificaciones'] = $cqlUsuario->get();
    $array_response['jefe'] = $dataSubsuario['jefe'];
    return $array_response;
  }
  public function buscarJefeSIGPRE($identificacion, $jefe = false)
  {

    $dataSubsuario = $this->buscarDatosUath($identificacion);
    if ($dataSubsuario['jefe']) {

      $cqlUsuario = Historia_laboral::join('sc_distributivo_.personas as p', 'p.id', 'historias_laborales.persona_id')
        ->join('sc_distributivo_.areas as a', 'a.id', 'historias_laborales.area_id')
        ->join('sc_distributivo_.cargos as c', 'c.id', 'sc_distributivo_.historias_laborales.cargo_id')
        ->select(
          'historias_laborales.id as historia_laboral_id',
          'historias_laborales.area_id',
          'p.id as persona_id',
          'p.correo_institucional as correo_institucional',
          'c.es_jefe_inmediato as jefe',
          'a.area_id as are_id_padre',
          'a.nombre as nombre_area'
        )
        ->whereIn('historias_laborales.area_id', [$dataSubsuario['are_id_padre'], $dataSubsuario['are_id_padre_2']])
        ->where('sc_distributivo_.historias_laborales.eliminado', false)
        ->where('sc_distributivo_.historias_laborales.es_principal', true)
        ->where('sc_distributivo_.historias_laborales.estado', 'ACT');

      if ($jefe != false) {
        $cqlUsuario = $cqlUsuario->where('c.es_jefe_inmediato', true);
      }
    } else {
      $cqlUsuario = Historia_laboral::join('sc_distributivo_.personas as p', 'p.id', 'historias_laborales.persona_id')
        ->join('sc_distributivo_.areas as a', 'a.id', 'historias_laborales.area_id')
        ->join('sc_distributivo_.cargos as c', 'c.id', 'sc_distributivo_.historias_laborales.cargo_id')
        ->select(
          'historias_laborales.id as historia_laboral_id',
          'historias_laborales.area_id',
          'p.id as persona_id',
          'p.correo_institucional as correo_institucional',
          'c.es_jefe_inmediato as jefe',
          'a.area_id as are_id_padre',
          'a.nombre as nombre_area'
        )
        ->whereIn('historias_laborales.area_id', [$dataSubsuario['area_id'], $dataSubsuario['area_id_2']])
        ->where('sc_distributivo_.historias_laborales.eliminado', false)
        ->where('sc_distributivo_.historias_laborales.es_principal', true)
        ->where('sc_distributivo_.historias_laborales.estado', 'ACT');

      if ($jefe != false) {
        $cqlUsuario = $cqlUsuario->where('c.es_jefe_inmediato', true);
      }
    }


    return $cqlUsuario->get()->toArray();
  }
  public function buscarRol($rol)
  {
    $roles = Role::where('name', $rol)->get()->first();
    if ($roles == null)
      return null;

    $result = DB::connection('pgsql_presidencia')
      ->table('core.model_has_roles as r')
      ->join('core.users as u', 'r.model_id', 'u.id')
      ->where('r.role_id', $roles->id)
      ->select('identificacion', 'nombres')->get()->first();
    return $result;
  }

  public function calcularTiempoPermisoSIGPRE($permiso_id)
  {

    $cql = DB::connection('pgsql_presidencia')
      ->select('SELECT sc_permisos_vacaciones."tiempo_permiso"(?)', [
        $permiso_id,
      ]);
    return $cql[0]->tiempo_permiso;
  }
  public function calcularTiempoPermisoFDSSIGPRE($permiso_id)
  {

    $cql = DB::connection('pgsql_presidencia')
      ->select('SELECT sc_permisos_vacaciones."tiempo_fin_semana"(?)', [
        $permiso_id,
      ]);
    return $cql[0]->tiempo_fin_semana;
  }
  public function rangoFecha($inicio, $fin)
  {
    $start = $inicio;
    $end = $fin;
    $range = array();
    if (is_string($start) === true) $start = strtotime($start);
    if (is_string($end) === true) $end = strtotime($end);

    if ($start > $end) return $this->rangoFecha($end, $start);

    do {
      $range[] = date('Y-m-d', $start);
      $start = strtotime("+ 1 day", $start);
    } while ($start <= $end);
    return $range;
  }
  public function consultaUsuariosRol($tipo, $institucion_id = null)
  {
    $role = Role::where('name', $tipo)->get()->first();
    $role = $role != null ? $role->id : 0;

    $model = mhr::select('model_id')
      ->where('role_id', $role)
      ->pluck('model_id')
      ->toArray();

    if ($institucion_id != null) {
      $model = User::select('id')
        ->where('institucion_id', $institucion_id)
        ->whereIn('id', $model)
        ->where('estado', 'A')
        ->pluck('id')
        ->toArray();
    }
    return $model;
  }
  public function consultaUsuariosRolSelectArray($tipo, $institucion_id = null, $cedula = false)
  {
    if (!is_array($tipo)) $tipo = [$tipo];

    $role = Role::select('id')->whereIn('name', $tipo)->pluck('id')->toArray();

    $model = mhr::select('model_id')
      ->whereIn('role_id', $role)
      ->pluck('model_id')
      ->toArray();

    if ($institucion_id != null) {
      $model = User::select('id')
        ->where('institucion_id', $institucion_id)
        ->whereIn('id', $model)
        ->pluck('id')
        ->toArray();
    }
    if (!$cedula) {
      $usuarios = User::select('nombres', 'id')
        ->whereIn('id', $model)
        ->pluck('nombres', 'id')
        ->toArray();
    } else {
      $usuarios = User::select(DB::raw("CONCAT(identificacion,' - ',nombres) as name"), 'id')
        ->whereIn('id', $model)
        ->pluck('name', 'id')
        ->toArray();
    }

    return $usuarios;
  }
  public function consultaUsuariosRolSelect($tipo, $institucion_id = null, $cedula = false)
  {

    $role = Role::where('name', $tipo)->get()->first();
    $role = $role != null ? $role->id : 0;

    $model = mhr::select('model_id')
      ->where('role_id', $role)
      ->pluck('model_id')
      ->toArray();

    if ($institucion_id != null) {
      $model = User::select('id')
        ->where('institucion_id', $institucion_id)
        ->whereIn('id', $model)
        ->pluck('id')
        ->toArray();
    }
    if (!$cedula) {
      $usuarios = User::select('nombres', 'id')
        ->whereIn('id', $model)
        ->pluck('nombres', 'id')
        ->toArray();
    } else {
      $usuarios = User::select(DB::raw("CONCAT(identificacion,' - ',nombres) as name"), 'id')
        ->whereIn('id', $model)
        ->pluck('name', 'id')
        ->toArray();
    }

    return $usuarios;
  }

  public function consultaUsuariosRolCorreo($tipo, $institucion_id = null)
  {
    $role = Role::where('name', $tipo)->get()->first();
    $role = $role != null ? $role->id : 0;

    $model = mhr::select('model_id')
      ->where('role_id', $role)
      ->pluck('model_id')
      ->toArray();

    if ($institucion_id != null) {
      $model = User::select('id')
        ->where('institucion_id', $institucion_id)
        ->whereIn('id', $model)
        ->pluck('id')
        ->toArray();
    }

    $email = User::select('email')
      ->whereIn('id', $model)
      ->pluck('email')
      ->toArray();

    return $email;
  }
  public function eliminar_acentos($cadena)
  {

    //Reemplazamos la A y a
    $cadena = str_replace(
      array('Á', 'À', 'Â', 'Ä', 'á', 'à', 'ä', 'â', 'ª'),
      array('A', 'A', 'A', 'A', 'a', 'a', 'a', 'a', 'a'),
      $cadena
    );

    //Reemplazamos la E y e
    $cadena = str_replace(
      array('É', 'È', 'Ê', 'Ë', 'é', 'è', 'ë', 'ê'),
      array('E', 'E', 'E', 'E', 'e', 'e', 'e', 'e'),
      $cadena
    );

    //Reemplazamos la I y i
    $cadena = str_replace(
      array('Í', 'Ì', 'Ï', 'Î', 'í', 'ì', 'ï', 'î'),
      array('I', 'I', 'I', 'I', 'i', 'i', 'i', 'i'),
      $cadena
    );

    //Reemplazamos la O y o
    $cadena = str_replace(
      array('Ó', 'Ò', 'Ö', 'Ô', 'ó', 'ò', 'ö', 'ô'),
      array('O', 'O', 'O', 'O', 'o', 'o', 'o', 'o'),
      $cadena
    );

    //Reemplazamos la U y u
    $cadena = str_replace(
      array('Ú', 'Ù', 'Û', 'Ü', 'ú', 'ù', 'ü', 'û'),
      array('U', 'U', 'U', 'U', 'u', 'u', 'u', 'u'),
      $cadena
    );

    $cadena = str_replace(
      array('Ç', 'ç'),
      array('C', 'c'),
      $cadena
    );
    return $cadena;
  }
  public function eliminar_acentos_minuscula($cadena)
  {

    //Reemplazamos la A y a
    $cadena = str_replace(
      array('Á', 'À', 'Â', 'Ä', 'á', 'à', 'ä', 'â', 'ª'),
      array('a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a'),
      $cadena
    );

    //Reemplazamos la E y e
    $cadena = str_replace(
      array('É', 'È', 'Ê', 'Ë', 'é', 'è', 'ë', 'ê'),
      array('e', 'e', 'e', 'e', 'e', 'e', 'e', 'e'),
      $cadena
    );

    //Reemplazamos la I y i
    $cadena = str_replace(
      array('Í', 'Ì', 'Ï', 'Î', 'í', 'ì', 'ï', 'î'),
      array('i', 'i', 'i', 'i', 'i', 'i', 'i', 'i'),
      $cadena
    );

    //Reemplazamos la O y o
    $cadena = str_replace(
      array('Ó', 'Ò', 'Ö', 'Ô', 'ó', 'ò', 'ö', 'ô'),
      array('o', 'o', 'o', 'o', 'o', 'o', 'o', 'o'),
      $cadena
    );

    //Reemplazamos la U y u
    $cadena = str_replace(
      array('Ú', 'Ù', 'Û', 'Ü', 'ú', 'ù', 'ü', 'û'),
      array('u', 'u', 'u', 'u', 'u', 'u', 'u', 'u'),
      $cadena
    );

    //Reemplazamos la N, n, C y c
    $cadena = str_replace(
      array('Ñ', 'ñ', 'Ç', 'ç'),
      array('n', 'n', 'c', 'c'),
      $cadena
    );
    $cadena = str_replace(
      array('Ç', 'ç'),
      array('c', 'c'),
      $cadena
    );
    return $cadena;
  }
  /*  public function apiEnvioCorreo($html,$asunto,$correo){

        $client = new \GuzzleHttp\Client();
        $client->post(
            env('API_ENVIO_CORREO'),
            array(
                'html' =>$html, ///html
                'to' => $correo,  //correo destino
                'from' => env('MAIL_USERNAME_'),//correo origen
                'locals' => null,//correo origen
                'subject' => $asunto,//asunto
                'cc' => null,// correo copia
            )
        );

        $estado=$client->getStatusCode();
        if($estado=='200'){
           $resultado=$res->getBody()->getContents(); // recibe un json
           $resultado = json_decode($resultado); //paso el json recibido a array
           var_dump($resultado);
        }

    }*/
  public function apiEnvioCorreoGETPOST($html, $asunto, $correo, $copia)
  {
    $cc = null;
    if ($copia != "0")
      $cc = $copia;

    $client = new \GuzzleHttp\Client();
    /*  $res=$client->get(
            env('API_ENVIO_CORREO_GET').
                'html='.$html.
                '&to='.$correo.
                '&cc	='.$cc.
                '&from='.env('MAIL_USERNAME_').
                '&locals='.null
            );*/
    $res = $client->request(
      'POST',
      env('API_ENVIO_CORREO_GET') .
        'html=' . $html .
        '&to=' . $correo .
        '&cc	=' . $cc .
        '&from=' . env('MAIL_USERNAME_') .
        '&locals=' . null,
      []
    );
    $estado = $res->getStatusCode();
    if ($estado == '200') {
      $resultado = $res->getBody()->getContents(); // recibe un json
      $resultado = json_decode($resultado); //paso el json recibido a array
      var_dump($resultado);
    }
  }
  public function apiEnvioCorreoLocal($html, $asunto, $for, $copia, $enviar_correo = null)
  {
    if (!config('app.NOTIFICAR') && is_null($enviar_correo)) return true;
    $destinatario = [$for];
    Mail::to($destinatario)->send(new NotificarHTML($html, $asunto));
  }

  public function apiEnvioCorreoLocalCompleto($html, $asunto, $for, $enviar_correo = null)
  {

    if (!config('app.NOTIFICAR') && is_null($enviar_correo)) return true;
    $destinatario = [$for];
    Mail::to($destinatario)->send(new DemoEmailHTMLCompleto($html, $asunto));
  }
  public function apiEnvioCorreoGET($html, $asunto, $correo, $copia)
  {
    $cc = null;
    if ($copia != "0")
      $cc = $copia;

    $client = new \GuzzleHttp\Client();

    $res = $client->request(
      'GET',
      env('API_ENVIO_CORREO_GET') .
        'html=' . $html .
        '&to=' . $correo .
        '&cc	=' . $cc .
        '&from=' . env('MAIL_USERNAME_') .
        '&locals=' . null,
      []
    );
    $estado = $res->getStatusCode();
    if ($estado == '200') {
      $resultado = $res->getBody()->getContents(); // recibe un json
      $resultado = json_decode($resultado); //paso el json recibido a array
      var_dump($resultado);
    }
  }
  public function logsCRUDRegistro($descripcion, $funcionario, $tipo)
  {
    $compromisos=config('app.NOTIFICACIONES')!='COMPROMISO'?false:true;
    
    $cql = new AsignacionFuncionario();
    $cql->fecha_inserta = date('Y-m-d H:i:s');
    $cql->usuario_inserta = Auth::user() == null ? $funcionario->name : Auth::user()->name;
    $cql->funcionario_inserta = Auth::user() == null ? $funcionario->nombres : Auth::user()->nombres;
    $cql->identificacion = $funcionario->identificacion;
    $cql->funcionario = $funcionario->nombres;
    $cql->area = $this->buscarDatosUath($funcionario->identificacion,null,$compromisos)['nombre_area'];
    $cql->descripcion = $descripcion;
    $cql->tipo = $tipo;
    $cql->save();
  }
  public function verificarEQUIPO($ip)
  {
    $objetos = explode('.', $ip);
    if ($ip == "UNKNOWN") {
      $array_response['valida'] = true;
      $array_response['message'] = '127.0.0.1';
    } else {
      $cqlConsultaIps = IPS::where('tipo', 'RESTRINGIDO')
        ->where('objeto1', $objetos[0])
        ->where('objeto2', $objetos[1])
        ->where('objeto3', $objetos[2])
        ->where('objeto4', $objetos[3])
        ->where('eliminado', false)
        ->pluck('seccion')->toArray();

      $array_response['valida'] = false;
      $array_response['message'] = null;

      if (count($cqlConsultaIps) > 0) {
        $array_response['valida'] = true;
        $array_response['message'] = $cqlConsultaIps[0];
      }
    }


    return $array_response;
  }
  public function validaIPEquipo($ip, $identificacion)
  {
    if ($ip == "UNKNOWN") {
      return true;
    } else {
      $usuario_id = User::where('identificacion', $identificacion)->first();

      if (is_null($usuario_id))
        return true;

      $objetos = explode('.', $ip);
      $cqlConsultaIps = IPS::where('tipo', 'RESTRINGIDO')
        ->where('objeto1', $objetos[0])
        ->where('objeto2', $objetos[1])
        ->where('objeto3', $objetos[2])
        ->where('objeto4', $objetos[3])
        ->where('eliminado', false)
        ->pluck('id')->toArray();

      if (count($cqlConsultaIps) == 0)
        return true;

      $cqlConsultaIdentificacion =
        IPSFuncionarioRestringido::where('usuario_id', $usuario_id->id)
        ->whereIn('ips', $cqlConsultaIps)->get()->count();
      return $cqlConsultaIdentificacion > 0 ? true : false;
    }
  }
  protected function consultarIP()
  {
    $ipaddress = '';
    if (getenv('HTTP_CLIENT_IP'))
      $ipaddress = getenv('HTTP_CLIENT_IP');
    else if (getenv('HTTP_X_FORWARDED_FOR'))
      $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
    else if (getenv('HTTP_X_FORWARDED'))
      $ipaddress = getenv('HTTP_X_FORWARDED');
    else if (getenv('HTTP_FORWARDED_FOR'))
      $ipaddress = getenv('HTTP_FORWARDED_FOR');
    else if (getenv('HTTP_FORWARDED'))
      $ipaddress = getenv('HTTP_FORWARDED');
    else if (getenv('REMOTE_ADDR'))
      $ipaddress = getenv('REMOTE_ADDR');
    else
      $ipaddress = 'UNKNOWN';
    $ip = $ipaddress;
    return $ip;
  }
  public function registrarLogsModulo($conexion, $esquema, $id, $tabla, $tipo)
  {
    /* TIPO= ACTUALIZADO/INGRESADO/ELIMINADO*/
    $cqlInserta =
      DB::connection($conexion)
      ->table($esquema . '.logs')
      ->insert(
        [
          'fecha_inserta' => date('Y-m-d H:i:s'),
          'usuario_inserta' => Auth::user()->name,
          'identificacion_usuario_inserta' => Auth::user()->identificacion,
          'afectado_id' => $id,
          'tabla' => $tabla,
          'tipo' => $tipo,
          'ip_registro' => $this->consultarIP()
        ]
      );
  }
  public function clave_aleatoria()
  {
    $longitud = 10;
    $opcLetra = TRUE;
    $opcNumero = TRUE;
    $opcMayus = TRUE;
    $opcEspecial = FALSE;

    $letras = "abcdefghijklmnopqrstuvwxyz";
    $numeros = "1234567890";
    $letrasMayus = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $especiales = "|@#~$%()=^*+[]{}-_";
    $listado = "";
    $password = "";
    if ($opcLetra == TRUE) $listado .= $letras;
    if ($opcNumero == TRUE) $listado .= $numeros;
    if ($opcMayus == TRUE) $listado .= $letrasMayus;
    if ($opcEspecial == TRUE) $listado .= $especiales;

    for ($i = 1; $i <= $longitud; $i++) {
      $caracter = $listado[rand(0, strlen($listado) - 1)];
      $password .= $caracter;
      $listado = str_shuffle($listado);
    }
    return $password;
  }
  public function saber_dia($fecha)
  {
    $dias = array('', 'LUNES', 'MARTES', 'MIERCOLES', 'JUEVES', 'VIERNES', 'SABADO', 'DOMINGO');
    $dia = $dias[date('N', strtotime($fecha))];
    return  $dia;
  }

  public function saber_mes($fecha,$minusCorto=false)
  {
    $meses = array('', 'ENERO', 'FEBRERO', 'MARZO', 'ABRIL', 'MAYO', 'JUNIO', 'JULIO', 'AGOSTO', 'SEPTIEMBRE', 'OCTUBRE', 'NOVIEMBRE', 'DICIEMBRE');
    if ($minusCorto)
      $meses = array('', 'ene', 'feb', 'mar', 'abr', 'may', 'jun', 'jul', 'ago', 'sep', 'oct', 'nov', 'dic');
    $mes = $meses[date('n', strtotime($fecha))];
    return  $mes;
  }
  //devuelve fecha larga ej: Lunes, 29 de agosto de 2022
  public function fechaLarga($fecha)
  {
    $dia = $this->saber_dia($fecha);
    $mes = $this->saber_mes($fecha);
    return ucfirst(strtolower($dia . ", " . date("d", strtotime($fecha)) . " de " . $mes . " de " . date("Y", strtotime($fecha))));
  }
  public function apiLogin($usuario, $key, $consulta)
  {

    $seguridad = $this->consultaSeguridad($usuario, $key);
    if ($seguridad) {
      $usuario = User::where('name', $consulta)->first();
      $array_response['status'] = 200;
      $array_response['response'] = $usuario;
      return response()->json($array_response, 200);
    } else {
      $array_response['status'] = 300;
      $array_response['response'] = 'ACCESO DENEGADO';
      return response()->json($array_response, 200);
    }
  }
  protected function consultaSeguridad($usuario, $key)
  {
    $consulta_usuario = 'app_api_consumo.' . $usuario;
    $data = config($consulta_usuario);
    if (is_null($data))
      return false;
    $data = config($consulta_usuario);
    $data = $data['API_KEY'];
    if ($data != $key)
      return false;

    return true;
  }
  public function consultaGobierno()
  {
    $consulta = Gobierno::select(
      'id',
      'descripcion',
      'fecha_inicio',
      'fecha_fin',
      'abv'
    )
      ->where('eliminado', false)
      ->whereDate('fecha_inicio', '<=', date('Y-m-d'))
      ->whereDate('fecha_fin', '>=', date('Y-m-d'))
      ->first();
    return $consulta;
  }

  public function consultarFechaActual()
  {
    return date('Y-m-d H:i:s');
  }
  public function evaluarRestriccionDias($arreglo, $tiempo)
  {
    $arregloFecha = [];
    if ($tiempo == 1) {
      foreach ($arreglo as $value) {
        $dia = $this->saber_dia($value);

        switch ($dia) {
          case 'LUNES':
          case 'MARTES':
          case 'MIERCOLES':
          case 'JUEVES':
          case 'VIERNES':

            $dia_actual_5 = now()->format('Y-m-d');
            $fecha_registro_5 = date('Y-m-d', strtotime($value . ' + 1 days'));
            break;
          default:
            $dia_actual_5 = now()->format('Y-m-d');
            $fecha_registro_5 = date('Y-m-d', strtotime($value . ' + 3 days'));
            break;
        }

        if ($dia_actual_5 <= $fecha_registro_5)
          array_push($arregloFecha, $value);
      }
    }
    if ($tiempo == 2) {
      foreach ($arreglo as $value) {
        $dia = $this->saber_dia($value);

        switch ($dia) {
          case 'LUNES':
          case 'MARTES':
          case 'MIERCOLES':
          case 'JUEVES':

            $dia_actual_5 = now()->format('Y-m-d');
            $fecha_registro_5 = date('Y-m-d', strtotime($value . ' + 2 days'));
            break;
          default:
            $dia_actual_5 = now()->format('Y-m-d');
            $fecha_registro_5 = date('Y-m-d', strtotime($value . ' + 4 days'));
            break;
        }

        if ($dia_actual_5 <= $fecha_registro_5)
          array_push($arregloFecha, $value);
      }
    }
    if ($tiempo == 3) {
      $dia = '';
      $fecha = '';
      foreach ($arreglo as $value) {
        $dia = $this->saber_dia($value);
        $fecha = $value;
        switch ($dia) {
          case 'LUNES':
          case 'MARTES':
          case 'MIERCOLES':
            $dia_actual_5 = now()->format('Y-m-d');
            $fecha_registro_5 = date('Y-m-d', strtotime($value . ' + 3 days'));
            break;
          default:
            $dia_actual_5 = now()->format('Y-m-d');
            $fecha_registro_5 = date('Y-m-d', strtotime($value . ' + 5 days'));
            break;
        }

        if ($dia_actual_5 <= $fecha_registro_5)
          array_push($arregloFecha, $value);
      }
    }
    if ($tiempo == 4) {
      foreach ($arreglo as $value) {
        $dia = $this->saber_dia($value);
        switch ($dia) {
          case 'LUNES':
          case 'MARTES':

            $dia_actual_5 = now()->format('Y-m-d');
            $fecha_registro_5 = date('Y-m-d', strtotime($value . ' + 4 days'));
            break;
          default:
            $dia_actual_5 = now()->format('Y-m-d');
            $fecha_registro_5 = date('Y-m-d', strtotime($value . ' + 6 days'));
            break;
        }

        if ($dia_actual_5 <= $fecha_registro_5)
          array_push($arregloFecha, $value);
      }
    }
    if ($tiempo == 5) {
      foreach ($arreglo as $value) {
        $dia = $this->saber_dia($value);
        switch ($dia) {
          case 'LUNES':
            $dia_actual_5 = now()->format('Y-m-d');
            $fecha_registro_5 = date('Y-m-d', strtotime($value . ' + 5 days'));
            break;
          default:
            $dia_actual_5 = now()->format('Y-m-d');
            $fecha_registro_5 = date('Y-m-d', strtotime($value . ' + 7 days'));
            break;
        }

        if ($dia_actual_5 <= $fecha_registro_5)
          array_push($arregloFecha, $value);
      }
    }
    return $arregloFecha;
  }
  public function eliminarElementosArray($array, $excepciones)
  {
    $arregloSinElementos = [];
    for ($i = 0; $i < count($array); $i++) {

      if (array_search($array[$i], $excepciones) != false) {
        array_push($arregloSinElementos, $array[$i]);
      }
    }
    return $arregloSinElementos;
  }
  public function base64_to_imagen($base64_string, $ruta)
  {
    $rutaImagenSalida = $ruta;
    $imagenBinaria = base64_decode($base64_string);
    $bytes = file_put_contents($rutaImagenSalida, $imagenBinaria);

    return true;
  }
  public function archivo_to_base64($ruta)
  {
    return base64_encode($ruta);
  }
  public function consultarFeriados($dia)
  {
    $feriado = Feriado::select('fer_fecha')->where('fer_tipo', 1)->where('fer_fecha', $dia)->first();
    return $feriado;
  }
  public function formatearValorMoneda($valor)
  {

    $numeroFormateado = "$" . number_format(sprintf('%0.2f', preg_replace("/[^0-9.]/", "", $valor)), 2);
    return $numeroFormateado;
  }
}
