<?php

namespace App\Http\Controllers\AccesoInternet;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Ajax\SelectController;
use App\Http\Controllers\AccesoInternet\RepositorioController as RP;
use App\Core\Entities\AccesoInternet\Solicitud;
use App\Core\Entities\AccesoInternet\Dato_tecnico;
use App\Core\Entities\AccesoInternet\Red_byod;
use App\Core\Entities\AccesoInternet\Solicitud_byod;
use Yajra\DataTables\CollectionDataTable;
use App\Core\Entities\TalentoHumano\Distributivo\Persona;

use Auth;
use App\User;
use DB;

class AsignacionesController extends Controller
{
    protected function actualizacionDatos()
    {
        $objSelect = new RP();
        $objSelect->updateSolicitudBorrador();
    }

    public function index()
    {
        $this->actualizacionDatos();
        $funcionario = '--';
        $jefe_inmediato = '--';
        $area = '--';
        $area_mision = '--';
        $cargo = '--';
        $historial = null;
        $objTipo = new SelectController();
        $sistema_operativo = $objTipo->getParametro('SISTEMA_OPERATIVO_REVISA', 'http');
        $descargas = $objTipo->getParametro('SECCION-DESCARGAS', 'http');
        $videos_en_linea = $objTipo->getParametro('SECCION-VIDEOS', 'http');
        $redes_sociales = $objTipo->getParametro('SECCION-REDES-SOCIALES', 'http');
        $objTipo = new SelectController();
        $tecnico_id = $objTipo->consultaUsuariosRol('TECNICO DE ACCESO A INTERNET');
        $cqlTecnico = User::select('id', 'nombres')->whereIn('id', $tecnico_id)->pluck('nombres', 'id');
        //ENVIAR TODAS LAS AREAS REGISTRADAS
        $cqlAreaFuncionario = Solicitud::select('area_funcionario')
            ->where('eliminado', false)
            ->where('estado', 'ATENDIDO')
            ->distinct('area_funcionario')
            ->pluck('area_funcionario');
        $cqlFuncionarioReporte = Solicitud::select('solicitudes.identificacion', 'solicitudes.id', 'persona.apellidos_nombres')
            ->leftjoin('sc_distributivo_.personas as persona', 'persona.identificacion', 'solicitudes.identificacion')
            ->where('solicitudes.eliminado', false)
            ->where('solicitudes.estado', 'ATENDIDO')
            ->distinct('solicitudes.identificacion')
            ->pluck('persona.apellidos_nombres', 'solicitudes.identificacion');
        $mensajeria = $objTipo->getParametro('SECCION-MENSAJERIA', 'http');

        ksort($videos_en_linea);
        ksort($redes_sociales);
        ksort($mensajeria);
        $perfiles = [];

        $perfiles = array_merge($videos_en_linea, $redes_sociales, $mensajeria, $descargas);

        return view('modules.acceso_internet.asignaciones.index', compact(
            'mensajeria',
            'cqlTecnico',
            'area_mision',
            'funcionario',
            'jefe_inmediato',
            'area',
            'cargo',
            'historial',
            'sistema_operativo',
            'descargas',
            'videos_en_linea',
            'redes_sociales',
            'cqlAreaFuncionario',
            'cqlFuncionarioReporte',
            'perfiles'
        ));
    }
    public function getDatatableServerSide($fecha_inicio, $fecha_fin, $tipoActual, $filtro_area_rep, $filtro_funcionario_rep, $fecha_atendido, $filtro_perfiles, $filtro)
    {
        $rol_consulta = Auth::user()->evaluarole(['CONSULTA ACCESO A INTERNET']);
        $tecnico_login = false;
        $tecnico_jefe_login = false;
        $objSelect = new SelectController();
        $tecnicos = $objSelect->consultaUsuariosRol("TECNICO DE ACCESO A INTERNET");
        foreach ($tecnicos as $tecnico) {
            if (Auth::user()->id == $tecnico) $tecnico_login = true;
        }
        $jefe = $objSelect->consultaUsuariosRol("ASIGNADOR DE ACCESO A INTERNET");
        foreach ($jefe as $jefe_) {
            if (Auth::user()->id == $jefe_) $tecnico_jefe_login = true;
        }
        $data = Solicitud::select(
            'solicitudes.id as id',
            'solicitudes.codigo_solicitud as codigo_solicitud',
            'solicitudes.fecha_solicitud as fecha_solicitud',
            'solicitudes.estado as estado',
            'solicitudes.area_funcionario as area_funcionario',
            'solicitudes.identificacion as identificacion',
            'solicitudes.fecha_atendido as fecha_atendido',
            'parametro.descripcion as descripcion',
            'funcionario.apellidos_nombres as apellidos_nombres',
            'funcionario_.apellidos_nombres as jefe_inmediato',
            'dato_tecnico.perfiles as perfiles',
            'solicitudes.ip_address_wifi',
            'solicitudes.ip_address_ethernet',
            'solicitudes.mac_address_wifi',
            'solicitudes.mac_address_ethernet',
        )
            ->leftjoin('sc_acceso_internet.datos_tecnicos as dato_tecnico', 'dato_tecnico.solicitud_id', 'solicitudes.id')
            ->leftjoin('sc_distributivo_.personas as funcionario', 'funcionario.identificacion', 'solicitudes.identificacion')
            ->leftjoin('sc_distributivo_.personas as funcionario_', 'funcionario_.identificacion', 'solicitudes.identificacion_jefe_aprueba')
            ->leftjoin('core.tb_parametro as parametro', 'parametro.id', 'solicitudes.sistema_operativo_id');
        if ($filtro == 'true') {
            if ($filtro_area_rep != '--') $data = $data->where('solicitudes.area_funcionario', $filtro_area_rep);
            if ($filtro_funcionario_rep != '--') $data = $data->where('solicitudes.identificacion', $filtro_funcionario_rep);
            if ($fecha_atendido != '--') $data = $data->whereDate('solicitudes.fecha_atendido', $fecha_atendido);
            if ($filtro_perfiles != '--') {
                $item = explode(',', $filtro_perfiles);
                $data = $data->where('dato_tecnico.perfiles', $item);
                if (count($item) > 1) {
                    for ($i = 1; $i < count($item); $i++) {
                        $data = $data->orWhere('dato_tecnico.perfiles', $item[$i]);
                    }
                }
            }
        } else {
            if ($tipoActual == 'ATENDIDO') {
                $data = $data->whereDate('fecha_solicitud', '>=', $fecha_inicio)
                    ->whereDate('fecha_solicitud', '<=', $fecha_fin);
            }
        }
        if ($tipoActual == 'PENDIENTE')
            $data = $data->where('solicitudes.estado', 'APROBADO-SI');
        else
            $data = $data->where('solicitudes.estado', $tipoActual);
        if ($tecnico_login == true && $tecnico_jefe_login == false && !$rol_consulta)
            $data = $data->where('dato_tecnico.tecnico', Auth::user()->id);
        $data = $data
            //->where('parametro.descripcion','WINDOWS')
            ->where('solicitudes.eliminado', false)
            ->where('solicitudes.revisado_soporte', true)
            ->distinct('solicitudes.id')
            ->get();
        return (new CollectionDataTable($data))
            ->addIndexColumn()
            ->addColumn('', function ($row) use ($rol_consulta, $tipoActual) {
                if ($rol_consulta)
                    return 'ROL DE CONSULTA';
                $btn = ' <table style="margin: 0 auto;border-collapse: collapse; width:100%">';
                $btn .= '<tr>';
                $btn .= '<td style="padding:1px"><button class="btn btn-default btn-xs btn-block"  onclick="app.verSolicitudSegInformatica(\'' . $row->id . '\')" data-toggle="modal" data-target="#modal-formulario">SOLICITUD</button></td>';
                if ($tipoActual == 'PENDIENTE')
                    $btn .= '<td style="padding:1px"><button class="btn btn-warning btn-xs btn-block"  onclick="app.retornarAutorizador(\'' . $row->id . '\')" >RETORNAR</button></td>';
                $btn .= ' </tr></table>';
                return $btn;
            })
            ->addColumn('estado_solicitud', function ($row) {
                if ($row->estado == 'APROBADO-SI') $estado_solicitud = 'AUTORIZADO PARA EJECUCIÓN';
                if ($row->estado == 'ASIGNADO') $estado_solicitud = 'SOLICITUD ASIGNADA';
                if ($row->estado == 'ATENDIDO') $estado_solicitud = 'SOLICITUD ATENDIDA';
                return $estado_solicitud;
            })
            ->addColumn('fecha_atendido_', function ($row) {
                $fecha_atendido_ = $row->fecha_atendido == null ? 'N/A' : $row->fecha_atendido;
                return $fecha_atendido_;
            })
            ->rawColumns([''])
            ->toJson();
    }
    public function estadoGestion(request $request)
    {
        $tecnico_login = false;
        $tecnico_jefe_login = false;
        $objSelect = new SelectController();
        $tecnicos = $objSelect->consultaUsuariosRol("TECNICO DE ACCESO A INTERNET");
        foreach ($tecnicos as $tecnico) {
            if (Auth::user()->id == $tecnico) $tecnico_login = true;
        }
        $jefe = $objSelect->consultaUsuariosRol("ASIGNADOR DE ACCESO A INTERNET");
        foreach ($jefe as $jefe_) {
            if (Auth::user()->id == $jefe_) $tecnico_jefe_login = true;
        }
        if ($tecnico_jefe_login == true) {
            $array_response['pendientes_si'] = Solicitud::select('solicitudes.id')
                ->whereIn('solicitudes.estado', ['APROBADO-SI'])
                ->where('solicitudes.eliminado', false)
                ->get()->count();
        }
        $cqlAsignados = Solicitud::select('solicitudes.id')
            ->leftjoin('sc_acceso_internet.datos_tecnicos as dato_tecnico', 'dato_tecnico.solicitud_id', 'solicitudes.id')
            ->where('solicitudes.estado', 'ASIGNADO')
            ->where('solicitudes.eliminado', false);
        if ($tecnico_login == true && $tecnico_jefe_login == false) {
            $cqlAsignados = $cqlAsignados->where('dato_tecnico.tecnico', Auth::user()->id);
        }
        $cqlAsignados = $cqlAsignados->get()->count();
        $array_response['asignados_si'] = $cqlAsignados;
        $cqlAtendidos = Solicitud::select('solicitudes.id')
            ->leftjoin('sc_acceso_internet.datos_tecnicos as dato_tecnico', 'dato_tecnico.solicitud_id', 'solicitudes.id')
            ->where('solicitudes.fecha_solicitud', '>=', $request->fecha_inicio)
            ->where('solicitudes.fecha_solicitud', '<=', $request->fecha_fin)
            ->where('solicitudes.estado', 'ATENDIDO')
            ->where('solicitudes.eliminado', false);
        if ($tecnico_login == true && $tecnico_jefe_login == false) {
            $cqlAtendidos = $cqlAtendidos->where('dato_tecnico.tecnico', Auth::user()->id);
        }
        $cqlAtendidos = $cqlAtendidos->get()->count();
        $array_response['atendidos_si'] = $cqlAtendidos;

        $array_response['status'] = 200;
        return response()->json($array_response, 200);
    }
    public function verSolicitudSegInformatica(request $request)
    {
        $array_response['status'] = 200;
        /*$array_response['datos'] = Solicitud::
        leftjoin('sc_distributivo_.personas as persona','persona.identificacion','solicitudes.identificacion_jefe_aprueba')
        ->with(['funcionarios','tipo_','historial_completo'=>function($q){
            $q->with(['area','cargo']);
        }])
        ->where('solicitudes.id',$request->id)->get()->first();  */
        $array_response['datos'] = Solicitud::with([
            'funcionarios', 'tipo_',
            'historial_completo' => function ($q) {
                $q->with(['area', 'cargo']);
            }
        ])
            ->where('id', $request->id)->first();

        $cql =  $array_response['datos'];
        $funcionario = '--';
        $jefe_inmediato = '--';
        $area = '--';
        $cargo = '--';
        $area_mision = '--';
        $historial = null;

        if ($cql->funcionarios != null) $funcionario = $cql->funcionarios->apellidos_nombres;

        $historial = $cql->historial_completo->count() > 0 ? $cql->historial_completo[0] : null;
        if ($historial != null) {
            $area = $historial->area != null ? $historial->area->nombre : '--';
            $cargo = $historial->cargo != null ? $historial->cargo->nombre : '--';
            $area_mision = $historial->area->mision != null ? $historial->area->mision : 'MISIÓN NO DISPONIBLE';
        }
        $array_response['datos_tecnicos'] = Dato_tecnico::select(
            'datos_tecnicos.id as id',
            'datos_tecnicos.perfiles as perfiles',
            'datos_tecnicos.observacion as observacion',
            'datos_tecnicos.tecnico as tecnico',
            'datos_tecnicos.observacion_informatica as observacion_informatica',
            'datos_tecnicos.fecha_accion as fecha_accion',
            'persona.apellidos_nombres as apellidos_nombres',
            'datos_tecnicos.observacion_analista as observacion_analista',
        )
            ->leftjoin('sc_distributivo_.personas as persona', 'persona.identificacion', 'datos_tecnicos.identificacion_seg_informacion')
            ->where('datos_tecnicos.solicitud_id', $request->id)
            //->where('datos_tecnicos.estado','ACT')
            ->where('datos_tecnicos.eliminado', false)
            ->first();
        $objTipo = new SelectController();
        $jefe = $objTipo->buscarDatosUATH($cql->identificacion_jefe_aprueba);
        $jefe_inmediato = $jefe['nombres_'];

        $array_response['tecnico_login'] = Auth::user()->id;
        $array_response['area_mision'] = $area_mision;
        $array_response['funcionario'] = $funcionario;
        $array_response['jefe_inmediato'] = $jefe_inmediato;
        $array_response['area'] = $area;
        $array_response['cargo'] = $cargo;
        $array_response['id_user_login'] = Auth::user()->id;
        return response()->json($array_response, 200);
    }
    public function guardarTicket(request $request)
    {
        try {
            DB::connection('pgsql_presidencia')->beginTransaction();
            $objSelectRepositorio = new RP();
            $cqlTecnico = Dato_tecnico::find($request->id);
            $cambios_tecnicos = $objSelectRepositorio->verificarCambios($request, $cqlTecnico);

            $cqlTecnico->usuario_modifica = Auth::user()->name;
            $cqlTecnico->fecha_modifica = date("Y-m-d H:i:s");
            $cqlTecnico->save();
            $cqlTecnico->fill($request->all())->save();
            //Update solicitud
            $cqlSolicitud = Solicitud::find($cqlTecnico->solicitud_id);
            $cambios = $objSelectRepositorio->verificarCambios($request, $cqlSolicitud);

            $cqlSolicitud->usuario_modifica = Auth::user()->name;
            $cqlSolicitud->fecha_modifica = date("Y-m-d H:i:s");
            $cqlSolicitud->estado = 'ASIGNADO';
            $cqlSolicitud->save();
            //datos del correo
            $solicitud_id = $cqlSolicitud->id;
            $descripcion = 'El funcionario :' . Auth::user()->nombres . ' asigno el técnico ' . $request->tecnico_nombre . ' a la solicitud ' . $cqlSolicitud->codigo_solicitud;
            $tabla = 'solicitudes';
            $objSelectRepositorio->crearTransaccion($descripcion, $solicitud_id);
            $objSelectRepositorio->guardarLogs($cambios, $cqlTecnico->id, $tabla);
            $objSelectRepositorio->guardarLogs($cambios_tecnicos, $request->id, 'datos_tecnicos');
            $objSelectRepositorio->guardarEstado($cqlSolicitud->id, 11, $descripcion);

            $objTipo = new SelectController();
            $correo_envia = config('app_acceso_internet.MAIL_S_TECNICO');
            //notificar
            $objTipo->NotificarSinRegistro(' El funcionario: ' . Auth::user()->nombres . ' ha asignado un ticket con codigo ' . $cqlSolicitud->codigo_solicitud . ' al técnico el Sr. ' . $request->tecnico_nombre . ' ', $correo_envia);

            $array_response['status'] = 200;
            $array_response['message'] = 'REGISTRO AGREGADO, CON EXTIO';
            DB::connection('pgsql_presidencia')->commit();
        } catch (\Exception $e) {
            DB::connection('pgsql_presidencia')->rollBack();
            $array_response['status'] = 300;
            $array_response['datos'] = $e->getMessage();
        }

        return response()->json($array_response, 200);
    }
    public function atenderRegistro(request $request)
    {
        $objSelectRepositorio = new RP();

        $cqlAtender = Dato_tecnico::find($request->id);
        $cambios_tecnicos = $objSelectRepositorio->verificarCambios($request, $cqlAtender);

        $cqlAtender->observacion_analista = $request->observacion_analista;
        $cqlAtender->usuario_modifica = Auth::user()->name;
        $cqlAtender->fecha_modifica = date("Y-m-d H:i:s");
        $cqlAtender->save();

        $cqlSolicitud = Solicitud::find($cqlAtender->solicitud_id);
        $cambios = $objSelectRepositorio->verificarCambios($request, $cqlSolicitud);

        $cqlSolicitud->fecha_atendido = date("Y-m-d H:i:s");
        $cqlSolicitud->fecha_vigencia = date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s") . "+ 365 days"));
        $cqlSolicitud->fecha_vigencia = date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s") . "+ 365 days"));
        $cqlSolicitud->estado = 'ATENDIDO';
        $cqlSolicitud->usuario_modifica = Auth::user()->name;
        $cqlSolicitud->fecha_modifica = date("Y-m-d H:i:s");
        $cqlSolicitud->save();

        $solicitud_id = $cqlSolicitud->id;
        $descripcion = 'El funcionario :' . Auth::user()->nombres . ' atendio la solicitud ' . $cqlSolicitud->codigo_solicitud;
        $tabla = 'solicitudes';
        $objSelectRepositorio->crearTransaccion($descripcion, $solicitud_id);
        $objSelectRepositorio->guardarLogs($cambios, $cqlSolicitud->id, $tabla);
        $objSelectRepositorio->guardarLogs($cambios_tecnicos, $request->id, 'datos_tecnicos');
        $objSelectRepositorio->guardarEstado($cqlSolicitud->id, 12, $descripcion);

        //Notificacion
        $objTipo = new SelectController();
        $datosUsuario = $objTipo->buscarDatosUATH($cqlSolicitud->identificacion);
        $nombres = $datosUsuario['nombres_'];
        $area = $datosUsuario['nombre_area'];
        $correo_jefe = $datosUsuario['correo_institucional_jefe'];
        $correo_funcionario = $datosUsuario['correo_institucional'];
        //$codigo_solicitud = $request->codigo_solicitud;
        //notificar al solicitante     
        $notificar = $objTipo->NotificarSinRegistro(' de parte del área de gestión de la DTIC, se le notifica que su solicitud 
        No. ' . $cqlSolicitud->codigo_solicitud . ' ha sido atendida. \n  Se le notifica que el permiso de acceso a internet tiene una vigencia de 365 días a partir
        del día de su activación (' . date("d-m-Y") . ') y mientras no exista un cambio administrativo o de Jefe Inmediato lo que ocasionará también la finalización de
        la vigencia de los permisos otorgados.', $correo_funcionario);
        //notificar al jefe inmediato
        $notificar_JI = $objTipo->NotificarSinRegistro(' de parte del área de gestión de la DTIC, se le notifica que la solicitud 
        No. ' . $cqlSolicitud->codigo_solicitud . ' del funcionario ' . $nombres . ' ha sido atendida. \n  Se le notifica que el permiso de acceso a internet tiene una vigencia de 365 días a partir
        del día de su activación (' . date("d-m-Y") . ') y mientras no exista un cambio administrativo o de Jefe Inmediato lo que ocasionará también la finalización de
        la vigencia de los permisos otorgados.', $correo_jefe);
        $correo_seginformatica = config('app_acceso_internet.MAIL_GESTION');
        $notificar_SI = $objTipo->NotificarSinRegistro(' de parte del área de gestión de la DTIC, se le notifica que la solicitud 
        No. ' . $cqlSolicitud->codigo_solicitud . ' del funcionario ' . $nombres . ' ha sido atendida. \n  Se le notifica que el permiso de acceso a internet tiene una vigencia de 365 días a partir
        del día de su activación (' . date("d-m-Y") . ') y mientras no exista un cambio administrativo o de Jefe Inmediato lo que ocasionará también la finalización de
        la vigencia de los permisos otorgados.', $correo_seginformatica);

        $array_response['status'] = 200;
        $array_response['message'] = 'REGISTRO ATENDIDO, CON ÉXITO';
        return response()->json($array_response, 200);
    }
    //REPORTE PASTEL
    public function reporteDinamicoP(request $request)
    {
        $array_response['status'] = 200;
        $array_response['redes'] = Solicitud::select('solicitudes.id')
            ->leftjoin('sc_acceso_internet.datos_tecnicos as datos', 'datos.solicitud_id', 'solicitudes.id')
            ->where('solicitudes.area_funcionario', $request->area_funcionario)
            ->where('solicitudes.fecha_atendido', '>=', $request->fecha_incio_atendido)
            ->where('solicitudes.fecha_atendido', '<=', $request->fecha_atendido_fin)
            ->where('datos.perfiles', 'like', '%' . 'REDES SOCIALES' . '%')
            ->where('solicitudes.estado', 'ATENDIDO')
            ->where('solicitudes.eliminado', false)
            ->count();
        $array_response['descargas'] = Solicitud::select('solicitudes.id')
            ->leftjoin('sc_acceso_internet.datos_tecnicos as datos', 'datos.solicitud_id', 'solicitudes.id')
            ->where('solicitudes.area_funcionario', $request->area_funcionario)
            ->where('solicitudes.fecha_atendido', '>=', $request->fecha_incio_atendido)
            ->where('solicitudes.fecha_atendido', '<=', $request->fecha_atendido_fin)
            ->where('datos.perfiles', 'like', '%' . 'DESCARGAS' . '%')
            ->where('solicitudes.estado', 'ATENDIDO')
            ->where('solicitudes.eliminado', false)
            ->count();
        $array_response['youtube'] = Solicitud::select('solicitudes.id')
            ->leftjoin('sc_acceso_internet.datos_tecnicos as datos', 'datos.solicitud_id', 'solicitudes.id')
            ->where('solicitudes.area_funcionario', $request->area_funcionario)
            ->where('solicitudes.fecha_atendido', '>=', $request->fecha_incio_atendido)
            ->where('solicitudes.fecha_atendido', '<=', $request->fecha_atendido_fin)
            ->where('datos.perfiles', 'like', '%' . 'YOUTUBE' . '%')
            ->where('solicitudes.estado', 'ATENDIDO')
            ->where('solicitudes.eliminado', false)
            ->count();
        $array_response['streaming'] = Solicitud::select('solicitudes.id')
            ->leftjoin('sc_acceso_internet.datos_tecnicos as datos', 'datos.solicitud_id', 'solicitudes.id')
            ->where('solicitudes.area_funcionario', $request->area_funcionario)
            ->where('solicitudes.fecha_atendido', '>=', $request->fecha_incio_atendido)
            ->where('solicitudes.fecha_atendido', '<=', $request->fecha_atendido_fin)
            ->where('datos.perfiles', 'like', '%' . 'STREAMING' . '%')
            ->where('solicitudes.estado', 'ATENDIDO')
            ->where('solicitudes.eliminado', false)
            ->count();
        //dd($array_response['redes'], $array_response['descargas'],$array_response['youtube'],$array_response['streaming']);
        return response()->json($array_response, 200);
    }
    //PESTAÑA DE RED BEYOD
    public function getDatatableSegInformaticaByodServerSide($fecha_inicio, $fecha_fin, $tipoActual)
    {
        $data = Solicitud_byod::select(
            'solicitudes_byod.id as id',
            'solicitudes_byod.identificacion_jefe as identificacion_jefe',
            'solicitudes_byod.fecha_aprueba_jefe as fecha_aprueba_jefe',
            'solicitudes_byod.politica_seguridad as politica_seguridad',
            'solicitudes_byod.estado_solicitud as estado_solicitud',
            'solicitudes_byod.tecnico_byod as tecnico_byod',
            'solicitudes_byod.observacion_tecnico as observacion_tecnico',
            'solicitudes_byod.justificacion_solicitante as justificacion_solicitante',
            'solicitante.apellidos_nombres as solicitante_apellidos_nombres', //nombres del colicitante
            'persona.apellidos_nombres as apellidos_nombres', //jefe
            'users_.nombres as nombres_tecnico',
        )
            ->leftjoin('sc_distributivo_.personas as persona', 'persona.identificacion', 'solicitudes_byod.identificacion_jefe')
            ->leftjoin('sc_distributivo_.personas as solicitante', 'solicitante.identificacion', 'solicitudes_byod.identificacion_solicitante')
            ->leftjoin('core.users as users_', 'users_.id', 'solicitudes_byod.tecnico_byod');

        if ($tipoActual == 'APROBADO SI') {
            $data = $data->where('solicitudes_byod.estado_solicitud', 'APROBADO SI')
                ->whereNull('tecnico_byod');
        }
        if ($tipoActual == 'ASIGNADO') {
            $data = $data->where('solicitudes_byod.estado_solicitud', 'ASIGNADO')
                ->whereNotNull('tecnico_byod');
        }
        if ($tipoActual == 'ATENDIDO') {
            $data = $data->where('solicitudes_byod.estado_solicitud', 'ATENDIDO')
                ->whereNotNull('tecnico_byod')
                ->whereNotNull('observacion_tecnico')
                ->whereDate('solicitudes_byod.fecha_aprueba_jefe', '>=', $fecha_inicio)
                ->whereDate('solicitudes_byod.fecha_aprueba_jefe', '<=', $fecha_fin);
        }
        $data = $data->where('solicitudes_byod.eliminado', false)
            ->where('solicitudes_byod.estado', 'ACT')
            ->get();
        return (new CollectionDataTable($data))
            ->addIndexColumn()
            ->addColumn('politica_seguridad_', function ($row) {
                if ($row->politica_seguridad == true) $politica_seguridad_ = 'ACEPTADA';
                return $politica_seguridad_;
            })
            ->addColumn('estado_solicitud_', function ($row) {
                if ($row->estado_solicitud == 'APROBADO SI') $estado_solicitud_ = 'AUTORIZADO PARA EJECUCIÓN';
                if ($row->estado_solicitud == 'ASIGNADO') $estado_solicitud_ = 'ASIGNADO A: ' . strtoupper($row->nombres_tecnico);
                if ($row->estado_solicitud == 'ATENDIDO') $estado_solicitud_ = 'ATENDIDO POR: ' . strtoupper($row->nombres_tecnico);
                return $estado_solicitud_;
            })
            ->addColumn('', function ($row) {
                $btn = ' <table style="margin: 0 auto;border-collapse: collapse; width:100%"><tr>';
                if ($row->estado_solicitud == 'APROBADO') {
                    $btn .= '<td style="padding:1px"><button class="btn btn-primary btn-xs btn-block"  onclick="app.verSolicitudByodSIN(\'' . $row->id . '\')" data-toggle="modal" data-target="#modal-formulario_byod_SIN"><i class="fa fa-cog"></i>&nbsp;SOLICITUD</button></td>';
                } else
                    $btn .= '<td style="padding:1px"><button class="btn btn-default btn-xs btn-block"  onclick="app.verSolicitudByodSIN(\'' . $row->id . '\')" data-toggle="modal" data-target="#modal-formulario_byod_SIN"><i class="fa fa-cog"></i>&nbsp;SOLICITUD</button></td>';
                $btn .= ' </tr></table>';
                return $btn;
            })
            ->rawColumns([''])
            ->toJson();
    }
    //DATATABLE PARA MODAL TABLA BYOD
    public function getDatatableRedByodServerSide($id)
    {
        $data = Red_byod::select(
            'redes_byod.id as id',
            'redes_byod.mac_address as mac_address',
            'redes_byod.tipo_dispositivo as tipo_dispositivo',
        )
            ->where('redes_byod.solicitud_byod_id', $id)
            ->where('redes_byod.eliminado', false)
            ->where('redes_byod.estado', 'ACT')
            ->get();
        return (new CollectionDataTable($data))
            ->addIndexColumn()
            ->rawColumns([''])
            ->toJson();
    }
    //ESTADO SOLICITUDES BYOD
    public function estadoGestionByod(request $request)
    {
        $array_response['pendientes'] = Solicitud_byod::select('id')
            ->where('fecha_aprueba_seg_informacion', '>=', $request->fecha_inicio)
            ->where('fecha_aprueba_seg_informacion', '<=', $request->fecha_fin)
            ->where('estado_solicitud', 'APROBADO SI')
            ->whereNull('tecnico_byod')
            ->where('eliminado', false)
            ->get()->count();
        $array_response['asignados'] = Solicitud_byod::select('id')
            ->where('fecha_aprueba_seg_informacion', '>=', $request->fecha_inicio)
            ->where('fecha_aprueba_seg_informacion', '<=', $request->fecha_fin)
            ->where('estado_solicitud', 'ASIGNADO')
            ->whereNotNull('tecnico_byod')
            ->where('eliminado', false)
            ->get()->count();
        $array_response['atendidos'] = Solicitud_byod::select('id')
            ->where('fecha_aprueba_seg_informacion', '>=', $request->fecha_inicio)
            ->where('fecha_aprueba_seg_informacion', '<=', $request->fecha_fin)
            ->where('estado_solicitud', 'ATENDIDO')
            ->whereNotNull('tecnico_byod')
            ->whereNotNull('observacion_tecnico')
            ->where('eliminado', false)
            ->get()->count();
        $array_response['status'] = 200;
        return response()->json($array_response, 200);
    }
    //ABRIR MODAL PARA VER FUNCIONARIOS DE LA SOLICITUD BYOD
    public function verSolicitudByodSIN(request $request)
    {
        $array_response['datos_tecnico_id'] = Auth::user()->id;
        $array_response['datos_byod'] = Solicitud_byod::select(
            'solicitudes_byod.id',
            'solicitudes_byod.estado_solicitud',
            'solicitudes_byod.observacion_seg_informacion',
            'solicitudes_byod.tecnico_byod',
            'solicitudes_byod.observacion_seg_informatica',
            'funcionario_.apellidos_nombres as funcionario',
            'funcionario_jefe.apellidos_nombres as apellidos_nombres_jefe',
            'funcionario_seg.apellidos_nombres as apellidos_nombres_seg_informacion',
            'solicitudes_byod.fecha_aprueba_jefe',
            'solicitudes_byod.fecha_aprueba_seg_informacion',
            'solicitudes_byod.observacion_tecnico',
            'solicitudes_byod.fecha_atendido',
            'solicitudes_byod.fecha_vigencia',
        )
            ->leftjoin('sc_distributivo_.personas as funcionario_jefe', 'funcionario_jefe.identificacion', 'solicitudes_byod.identificacion_jefe')
            ->leftjoin('sc_distributivo_.personas as funcionario_seg', 'funcionario_seg.identificacion', 'solicitudes_byod.identificacion_seg_informacion')
            ->leftjoin('sc_distributivo_.personas as funcionario_', 'funcionario_.identificacion', 'solicitudes_byod.identificacion_solicitante')
            ->where('solicitudes_byod.id', $request->id)
            ->where('solicitudes_byod.eliminado', false)
            ->where('solicitudes_byod.estado', 'ACT')
            ->first();
        $array_response['status'] = 200;
        return response()->json($array_response, 200);
    }
    //ASIGNAR TECNICO
    public function asginarTecnicoByod(request $request)
    {
        $objSelectRepositorio = new RP();

        $cqlTecnico = Solicitud_byod::find($request->id);
        $cambios = $objSelectRepositorio->verificarCambios($request, $cqlTecnico);

        $cqlTecnico->usuario_modifica = Auth::user()->name;
        $cqlTecnico->fecha_modifica = date("Y-m-d H:i:s");
        $cqlTecnico->estado_solicitud = 'ASIGNADO';
        $cqlTecnico->save();
        $cqlTecnico->fill($request->except('estado_solicitud'))->save();

        $solicitud_id = $cqlTecnico->id;

        $descripcion = 'El funcionario :' . Auth::user()->nombres . ' asigno al tecnico ' . $request->tecnico_nombre . ', la solicitud ' . $solicitud_id;
        $tabla = 'solicitudes_byod';
        $objSelectRepositorio->guardarLogs($cambios, $solicitud_id, $tabla);
        //datos del correo
        $objTipo = new SelectController();
        $correo_envia = config('app_acceso_internet.MAIL_S_TECNICO');
        //notificar
        $descripcion = 'Se asignó el tecnico a la solicitud #. ' . $cqlTecnico->id . ', por el funcionario: ' . Auth::user()->nombres . ' ha asignado un ticket para acceso a la Red Byod al técnico el Sr. ' . $request->tecnico_nombre . ' ';
        $notificar = $objTipo->NotificarSinRegistro($descripcion, $correo_envia);
        $objSelectRepositorio->guardarEstado($cqlTecnico->id, 6, $descripcion, 'BYOD');

        $array_response['status'] = 200;
        $array_response['message'] = 'REGISTRO AGREGADO, CON EXTIO';
        return response()->json($array_response, 200);
    }
    //OBSERVACION DEL ANALISTA CUANDO FINALIZA SU ASIGNACION DEL TICKET
    public function atenderTicketByod(request $request)
    {
        $objSelectRepositorio = new RP();

        $cqlAtenderByod = Solicitud_byod::find($request->id);
        $cambios = $objSelectRepositorio->verificarCambios($request, $cqlAtenderByod);

        $cqlAtenderByod->fecha_atendido = date("Y-m-d");
        $cqlAtenderByod->fecha_vigencia = date("Y-m-d", strtotime(date("Y-m-d") . "+ 365 days"));
        $cqlAtenderByod->usuario_modifica = Auth::user()->name;
        $cqlAtenderByod->fecha_modifica = date("Y-m-d H:i:s");
        $cqlAtenderByod->estado_solicitud = 'ATENDIDO';
        $cqlAtenderByod->save();
        $cqlAtenderByod->fill($request->except('estado_solicitud', 'fecha_atendido', 'fecha_vigencia'))->save();
        $solicitud_id = $cqlAtenderByod->id;
        $tabla = 'solicitudes_byod';
        $objSelectRepositorio->guardarLogs($cambios, $solicitud_id, $tabla);

        //Notificacion
        $objTipo = new SelectController();
        $datosUsuario = $objTipo->buscarDatosUATH($cqlAtenderByod->identificacion_jefe);
        $nombres = $datosUsuario['nombres_'];
        $area = $datosUsuario['nombre_area'];
        $jefe = $datosUsuario['nombres_'];
        $correo = $datosUsuario['correo_institucional'];
        //$codigo_solicitud = $cqlAtenderByod->codigo_solicitud;
        //notificar        
        $notificar = $objTipo->NotificarSinRegistro(' de parte del área de gestión de la DTIC, se le notifica que su solicitud 
        de acceso a la Red Byod ha sido atendida. \n  Se le notifica que el permiso de acceso a internet tiene una vigencia de 365 días a partir
        del día de su activación (' . date("d-m-Y") . ') y mientras no exista un cambio administrativo o de Jefe Inmediato lo que ocasionará también la finalización de
        la vigencia de los permisos otorgados.', $correo);
        $objSelectRepositorio->guardarEstado($cqlAtenderByod->id, 7, '', 'BYOD');

        $array_response['status'] = 200;
        $array_response['message'] = 'REGISTRO APROBADO, CON EXTIO';
        return response()->json($array_response, 200);
    }
    public function retornarAutorizador(request $request)
    {
        $notificar = new SelectController();
        $objSelectRepositorio = new RP();

        $cqlAprobacion = Solicitud::find($request->id); //buscar en bd el id para pasarlo a la variable
        $cambios = $objSelectRepositorio->verificarCambios($request, $cqlAprobacion);

        $cqlAprobacion->usuario_modifica = Auth::user()->name; //Obtengo el login para agregarlo al campo usuario modifica
        $cqlAprobacion->fecha_modifica = date("Y-m-d H:i:s"); //Obtengo el date time para agregarlo al campo fecha modifica
        //   $cqlAprobacion->revisado_soporte = false;
        $cqlAprobacion->estado = 'APROBADOJI';
        $cqlAprobacion->save(); //guarda los datos del cqlAprobacion
        //$cqlAprobacion->fill($request->all())->save(); //llena los campos guardados y posteriormente los guarda en la tabla

        $notificar->consultaUsuariosRolCorreo('AUTORIZADOR DE ACCESO A INTERNET');
        $descripcion = 'La solicitud ' . $cqlAprobacion->codigo_solicitud . ' ha sido retorna de la bandeja de OPERADOR hacia AUTORIZADOR por :' . Auth::user()->nombres;
        foreach ($notificar as $value) {
            $correo_institucional = $value;
            $notificar->NotificarSinRegistro($descripcion, $correo_institucional);
        }

        $solicitud_id = $request->id;
        $objSelectRepositorio->crearTransaccion($descripcion, $cqlAprobacion->id);
        $objSelectRepositorio->guardarLogs($cambios, $cqlAprobacion->id);
        $objSelectRepositorio->guardarEstado($cqlAprobacion->id, [10, 9, 8], $descripcion, 'NAVEGACION', true);

        $array_response['status'] = 200;
        $array_response['message'] = 'RETORNADO';
        return response()->json($array_response, 200);
    }
}
