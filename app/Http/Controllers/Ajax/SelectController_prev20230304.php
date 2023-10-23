<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

/* ENVIO DE  CORREOS */
use App\Core\Entities\Admin\Notificacion;
use App\Mail\DemoEmail as Notificar;
use App\Mail\DemoEmailSR as NotificarSR;
use App\Mail\DemoEmailAdjuntoSR as NotificarAdjuntoSR;
use App\Mail\DemoEmailExterno as NotificarExterno;
use App\Mail\DemoEmailHTML as NotificarHTML;
use App\Mail\EmailPermiso as NotificarPermiso;
/* ENVIO DE  CORREOS */
use App\Core\Entities\Portal\Subusuario_;
use App\Core\Entities\Portal\Usuario;
use App\Core\Entities\Admin\IPS;
use App\Core\Entities\Admin\IPSFuncionarioRestringido;

use App\Core\Entities\Admin\mhr;
use App\Core\Entities\Admin\Role;

use App\Core\Entities\Admin\AsignacionFuncionario;
use App\Core\Entities\Admin\Gobierno;
use Auth;
use Mail;
use App\User;

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

    public function getParametro($parametro, $type = 'json', $v = 0)
    {
        $result1 = DB::connection('pgsql_presidencia')
            ->table('core.tb_parametro AS C')
            ->where('C.descripcion', $parametro)
            ->where('C.estado', 'A')
            ->select('C.id as id')->first();
        if ($result1 != null) {
            $result = DB::connection('pgsql_presidencia')
                ->table('core.tb_parametro AS C')
                ->where('C.parametro_id', $result1->id)
                ->where('C.estado', 'A')
                ->orderBy('id', 'ASC');
            if ($v == 1) {
                $result = $result->whereNotIn('C.descripcion', ['SOLICITUD INGRESADA', 'SOLICITUD PREGRABADA', 'SOLICITUD CORREGIDA']);
            }
            $result = $result->groupBy('C.descripcion', 'C.id')
                ->orderBy('C.id', 'desc')
                ->select('C.id as id', 'C.descripcion as descripcion');

            if ($type == 'json') {
                $result = $result->get('descripcion', 'id');
                $lista['data'] = $result;
                return response()->json($lista, 200);
            } else {
                if ($v == 4) {
                    $result = $result
                        ->pluck('descripcion', 'descripcion')->toArray();
                } else {
                    if ($v == 3) {
                        $result = $result->pluck('id', 'descripcion')->toArray();
                    } else {
                        $result = $result->pluck('descripcion', 'id')->toArray();
                    }
                }

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
        if (!env('NOTIFICAR'))
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
        /*if(!env('NOTIFICAR'))
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
        if (!config('app.NOTIFICAR'))
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
        if (!config('app.NOTIFICAR'))
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
        if (!env('NOTIFICAR'))
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
    public function apiEnvioCorreoLocal($html, $asunto, $for, $copia)
    {
        if (!env('NOTIFICAR'))
            return true;
        $destinatario = [$for];
        Mail::to($destinatario)->send(new NotificarHTML($html, $asunto));
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

        $cql = new AsignacionFuncionario();
        $cql->fecha_inserta = date('Y-m-d H:i:s');
        $cql->usuario_inserta = Auth::user() == null ? $funcionario->name : Auth::user()->name;
        $cql->funcionario_inserta = Auth::user() == null ? $funcionario->nombres : Auth::user()->nombres;
        $cql->identificacion = $funcionario->identificacion;
        $cql->funcionario = $funcionario->nombres;
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
        $dias = array('DOMINGO', 'LUNES', 'MARTES', 'MIERCOLES', 'JUEVES', 'VIERNES', 'SABADO', 'DOMINGO');
        $dia = $dias[date('N', strtotime($fecha))];
        return  $dia;
    }

    public function saber_mes($fecha)
    {
        $meses = array('', 'ENERO', 'FEBRERO', 'MARZO', 'ABRIL', 'MAYO', 'JUNIO', 'JULIO', 'AGOSTO', 'SEPTIEMBRE', 'OCTUBRE', 'NOVIEMBRE', 'DICIEMBRE');
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
}
