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

use Auth;
use DB;

class AutorizadorController extends Controller
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
        //ENVIAR TODAS LAS AREAS REGISTRADAS
        $cqlAreaFuncionario = Solicitud::select('area_funcionario')
            ->where('eliminado', false)
            ->where('estado', 'ATENDIDO')
            ->distinct('area_funcionario')
            ->pluck('area_funcionario');
        //ENVIAR TODAS LAS AREAS REGISTRADAS
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

        return view('modules.acceso_internet.autorizador.index', compact(
            'mensajeria',
            'perfiles',
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
            'cqlFuncionarioReporte'
        ));
    }
    public function getDatatableServerSide(
        $fecha_inicio,
        $fecha_fin,
        $tipoActual,
        $filtro_area_rep,
        $filtro_funcionario_rep,
        $fecha_atendido,
        $filtro_perfil,
        $filtro
    ) {

        $objRepo = new Rp();

        switch ($tipoActual) {
            case 'PENDIENTE':
                $estado = ['AUTORIZADO', 'RECHAZADO'];
                $data = $objRepo->selectDatatableAutorizar(null, null, $estado)->where('solicitudes.eliminado', false);
                break;
            default:
                //  $data = $data->whereDate('solicitudes.fecha_inserta', '>=', $fecha_inicio)->whereDate('solicitudes.fecha_inserta', '<=', $fecha_fin);
                switch ($tipoActual) {
                    case 'APROBADO':
                        $estado = 'AUTORIZADO';
                        $data = $objRepo->selectDatatableAutorizar($fecha_inicio, $fecha_fin, $estado)->where('solicitudes.eliminado', false);
                        break;

                    case 'RECHAZADO':
                        $estado = 'RECHAZADO';
                        $data = $objRepo->selectDatatableAutorizar($fecha_inicio, $fecha_fin, $estado)->where('solicitudes.eliminado', false);

                        break;
                    case 'ATENDIDO':
                        $estado = 'ATENDIDO';
                        $data = $objRepo->selectDatatableAutorizar($fecha_inicio, $fecha_fin, $estado);
                        break;
                }
                break;
        }

        if ($filtro == 'true') {
            if ($filtro_area_rep != '--') $data = $data->where('solicitudes.area_funcionario', $filtro_area_rep);
            if ($filtro_funcionario_rep != '--') $data = $data->where('solicitudes.identificacion', $filtro_funcionario_rep);
            if ($fecha_atendido != '--') $data = $data->whereDate('solicitudes.fecha_atendido', $fecha_atendido);
            if ($filtro_perfil != '--') {
                $item = explode(',', $filtro_perfil);
                $data = $data->where('dato_tecnico.perfiles', $item);
                if (count($item) > 1) {
                    for ($i = 1; $i < count($item); $i++) {
                        $data = $data->orWhere('dato_tecnico.perfiles', $item[$i]);
                    }
                }
            }
        }
        $data = $data->where('solicitudes.revisado_soporte', true)
            ->distinct('solicitudes.id')->get();
        return (new CollectionDataTable($data))
            ->addIndexColumn()
            ->addColumn('', function ($row) use ($tipoActual) {
                $btn = ' <table style="margin: 0 auto;border-collapse: collapse; width:100%"><tr>';
                $btn .= '<td style="padding:1px"><button class="btn btn-default btn-xs btn-block"  onclick="app.verSolicitudSI(\'' . $row->id . '\')" data-toggle="modal" data-target="#modal-solicitud"><i class="fa fa-eye"></i>&nbsp;VER SOLICITUD</button></td>';
                $btn .= ' </tr></table>';
                return $btn;
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

        $objRepo = new Rp();
        $fecha_inicio = $request->fecha_inicio;
        $fecha_fin = $request->fecha_fin;
        $array_response['status'] = 200;
        $estado = ['AUTORIZADO', 'RECHAZADO'];
        $array_response['pendientes'] = $objRepo->selectDatatableAutorizar(null, null, $estado)->where('solicitudes.eliminado', false)->get()->count();
        $estado = 'AUTORIZADO';
        $array_response['aprobados']  = $objRepo->selectDatatableAutorizar($fecha_inicio, $fecha_fin, $estado)->where('solicitudes.eliminado', false)->get()->count();
        $estado = 'RECHAZADO';
        $array_response['rechazados'] = $objRepo->selectDatatableAutorizar($fecha_inicio, $fecha_fin, $estado)->where('solicitudes.eliminado', false)->get()->count();
        $estado = 'ATENDIDO';
        $array_response['atendidos'] = $objRepo->selectDatatableAutorizar($fecha_inicio, $fecha_fin, $estado)->where('solicitudes.eliminado', false)->get()->count();
        $array_response['status'] = 200;
        return response()->json($array_response, 200);
    }
    public function verSolicitudSI(request $request)
    {
        $array_response['status'] = 200;
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
        $array_response['datos_tecnicos'] = Dato_tecnico::select('id', 'perfiles', 'observacion')
            ->where('solicitud_id', $request->id)
            ->where('estado', 'ACT')
            ->where('eliminado', false)
            ->first();

        $objTipo = new SelectController();
        $jefe = $objTipo->buscarDatosUATH($cql->identificacion);
        $jefe_inmediato = $jefe['apellidos_nombres_jefe'];

        //VALIDA SI LA MISION DEL AREA ESTA EN NULL
        $array_response['area_mision'] = $area_mision;
        $array_response['funcionario'] = $funcionario;
        $array_response['jefe_inmediato'] = $jefe_inmediato;
        $array_response['area'] = $area;
        $array_response['cargo'] = $cargo;
        return response()->json($array_response, 200);
    }
    public function aprobarSolicitudSI(request $request)
    {
        $objSelectRepositorio = new RP();
        $cqlAprobacion = Solicitud::find($request->id);
        $cqlAprobacion->usuario_modifica = Auth::user()->name;
        $cqlAprobacion->fecha_modifica = date("Y-m-d H:i:s");
        $cqlAprobacion->estado = 'APROBADO-SI';
        $cqlAprobacion->save();

        $cqlDatosTecnicos = Dato_tecnico::where('solicitud_id', $request->id)->where('eliminado', false)->first();
        if (is_null($cqlDatosTecnicos))
            $cqlDatosTecnicos = new Dato_tecnico();
        $cqlDatosTecnicos->observacion = $request->observacion_seguridad_informacion;
        $cqlDatosTecnicos->perfiles = implode(",", $request->arreglo_perfiles);
        $cqlDatosTecnicos->fecha_accion = date("Y-m-d H:i:s");
        $cqlDatosTecnicos->solicitud_id = $request->id;
        $cqlDatosTecnicos->identificacion_seg_informacion = Auth::user()->identificacion;
        $cqlDatosTecnicos->fecha_accion = date("Y-m-d");
        $cqlDatosTecnicos->usuario_ingresa = Auth::user()->name;
        $cqlDatosTecnicos->fecha_ingresa = date("Y-m-d H:i:s");
        $cqlDatosTecnicos->estado = 'ACT';
        $cqlDatosTecnicos->save();

        //datos del correo
        $objTipo = new SelectController();
        $datosUsuario = $objTipo->buscarDatosUATH($cqlAprobacion->identificacion);
        $nombres = $datosUsuario['nombres_'];
        $area = $datosUsuario['nombre_area'];
        $jefe = $datosUsuario['apellidos_nombres_jefe'];
        $gestion = $cqlAprobacion->gestion != null ? $cqlAprobacion->gestion : 'N/A';
        $analistaSeguridad = strtoupper(Auth::user()->nombres);
        $correo_envia = config('app_acceso_internet.MAIL_GESTION');
        //notificar
        $objTipo->NotificarSinRegistro(' que la solicitud del funcionario: ' . $nombres . ' del área: ' . $area . ', y Gestión: ' . $gestion . ' aprobado por el Jefe inmediato ' . $jefe . ' y autorizado por el Funcionario ' . $analistaSeguridad . ' .', $correo_envia);
        $descripcion = 'Se autorizo la solicitud # ' . $cqlAprobacion->id . ', por el funcionario  :' . Auth::user()->nombres;
        $solicitud_id = $cqlAprobacion->id;
        $objSelectRepositorio->crearTransaccion($descripcion, $solicitud_id);
        $objSelectRepositorio->guardarEstado($cqlAprobacion->id, [8, 10], [$descripcion, '']);

        $array_response['status'] = 200;
        $array_response['message'] = 'REGISTRO APROBADO, CON EXTIO';
        return response()->json($array_response, 200);
    }
    public function rechazarSolicitudSI(request $request)
    {
        $objSelectRepositorio = new RP();

        $cqlRechazo = Solicitud::find($request->id); //buscar en bd el id para pasarlo a la variable
        $cqlRechazo->usuario_modifica = Auth::user()->name; //Obtengo el login para agregarlo al campo usuario modifica
        $cqlRechazo->fecha_modifica = date("Y-m-d H:i:s"); //Obtengo el date time para agregarlo al campo fecha modifica
        $cqlRechazo->estado = 'RECHAZADO-SI';
        $cqlRechazo->save();

        $cqlDatosTecnicos = new Dato_tecnico();
        $cqlDatosTecnicos->observacion = $request->observacion_seguridad_informacion;
        $cqlDatosTecnicos->solicitud_id = $request->id;
        $cqlDatosTecnicos->identificacion_seg_informacion = Auth::user()->identificacion;
        $cqlDatosTecnicos->fecha_accion = date("Y-m-d");
        $cqlDatosTecnicos->usuario_ingresa = Auth::user()->name;
        $cqlDatosTecnicos->fecha_ingresa = date("Y-m-d H:i:s");
        $cqlDatosTecnicos->estado = 'ACT';
        $cqlDatosTecnicos->save();

        $descripcion = 'El Funcionario ' . Auth::user()->nombres . ', rechazó la solicitud # ' . $cqlRechazo->id . ' de acceso a internet por ' . $request->observacion_seguridad_informacion;
        $objSelectRepositorio->crearTransaccion($descripcion, $cqlRechazo->id);
        $objSelectRepositorio->guardarEstado($cqlRechazo->id, 9, $descripcion);

        $array_response['status'] = 200;
        $array_response['message'] = 'REGISTRO RECHAZADO';
        return response()->json($array_response, 200);
    }
    //REPORTE PASTEL
    public function reporteDinamico(request $request)
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
    public function getDatatableSegInformacionByodServerSide($fecha_inicio, $fecha_fin, $tipoActual)
    {
        $data = Solicitud_byod::select(
            'solicitudes_byod.id as id',
            'solicitudes_byod.identificacion_jefe as identificacion_jefe',
            'solicitudes_byod.fecha_aprueba_jefe as fecha_aprueba_jefe',
            'solicitudes_byod.politica_seguridad as politica_seguridad',
            'solicitudes_byod.estado_solicitud as estado_solicitud',
            'solicitudes_byod.estado_solicitud as estado_solicitud',
            'solicitudes_byod.justificacion_solicitante as justificacion_solicitante',
            'solicitante.apellidos_nombres as solicitante_apellidos_nombres', //nombres del colicitante
            'persona.apellidos_nombres as apellidos_nombres', //nombres del jefe inmediato
        )

            ->leftjoin('sc_distributivo_.personas as persona', 'persona.identificacion', 'solicitudes_byod.identificacion_jefe')
            ->leftjoin('sc_distributivo_.personas as solicitante', 'solicitante.identificacion', 'solicitudes_byod.identificacion_solicitante');
            if($tipoActual!='APROBADO'){
              $data=$data  ->whereDate('solicitudes_byod.fecha_aprueba_jefe', '>=', $fecha_inicio)
                ->whereDate('solicitudes_byod.fecha_aprueba_jefe', '<=', $fecha_fin);
            }

            $data=$data->where('solicitudes_byod.estado_solicitud', $tipoActual)
            ->where('solicitudes_byod.eliminado', false)
            ->where('solicitudes_byod.estado', 'ACT')
            ->get();
        return (new CollectionDataTable($data))
            ->addIndexColumn()
            ->addColumn('politica_seguridad_', function ($row) {
                if ($row->politica_seguridad == true) $politica_seguridad_ = 'ACEPTADA';
                return $politica_seguridad_;
            })
            ->addColumn('estado_solicitud_', function ($row) {
                if ($row->estado_solicitud == 'APROBADO') $estado_solicitud_ = 'APROBADO POR JEFE INMEDIATO';
                if ($row->estado_solicitud == 'APROBADO SI') $estado_solicitud_ = 'AUTORIZADO PARA EJECUCIÓN';
                if ($row->estado_solicitud == 'RECHAZADO SI') $estado_solicitud_ = 'NO AUTORIZADO PARA EJECUCIÓN';
                return $estado_solicitud_;
            })
            ->addColumn('', function ($row) {
                $btn = ' <table style="margin: 0 auto;border-collapse: collapse; width:100%"><tr>';
                if ($row->estado_solicitud == 'APROBADO') {
                    $btn .= '<td style="padding:1px"><button class="btn btn-primary btn-xs btn-block"  onclick="app.verSolicitudByodSI(\'' . $row->id . '\')" data-toggle="modal" data-target="#modal-formulario_byod_SI"><i class="fa fa-cog"></i>&nbsp;SOLICITUD</button></td>';
                } else
                    $btn .= '<td style="padding:1px"><button class="btn btn-default btn-xs btn-block"  onclick="app.verSolicitudByodSI(\'' . $row->id . '\')" data-toggle="modal" data-target="#modal-formulario_byod_SI"><i class="fa fa-cog"></i>&nbsp;SOLICITUD</button></td>';
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
            ->where('estado_solicitud', 'APROBADO')
            ->where('eliminado', false)
            ->get()->count();
        $array_response['aprobados'] = Solicitud_byod::select('id')
            ->where('fecha_aprueba_jefe', '>=', $request->fecha_inicio)
            ->where('fecha_aprueba_jefe', '<=', $request->fecha_fin)
            ->where('estado_solicitud', 'APROBADO SI')
            ->where('eliminado', false)
            ->get()->count();
        $array_response['rechazados'] = Solicitud_byod::select('id')
            ->where('fecha_aprueba_jefe', '>=', $request->fecha_inicio)
            ->where('fecha_aprueba_jefe', '<=', $request->fecha_fin)
            ->where('estado_solicitud', 'RECHAZADO SI')
            ->where('eliminado', false)
            ->get()->count();
        $array_response['status'] = 200;
        return response()->json($array_response, 200);
    }
    //ABRIR MODAL PARA VER FUNCIONARIOS DE LA SOLICITUD BYOD
    public function verSolicitudByodSI(request $request)
    {
        $cql = Solicitud_byod::select(
            'solicitudes_byod.id',
            'solicitudes_byod.estado_solicitud',
            'solicitudes_byod.observacion_seg_informacion',
            'solicitudes_byod.fecha_aprueba_jefe as fecha_atencion',
            'funcionario.apellidos_nombres as funcionario',
            'jefe_inmediato.apellidos_nombres as jefe_inmediato',
        )
            ->join('sc_distributivo_.personas as funcionario', 'funcionario.identificacion', 'solicitudes_byod.identificacion_solicitante')
            ->join('sc_distributivo_.personas as jefe_inmediato', 'jefe_inmediato.identificacion', 'solicitudes_byod.identificacion_jefe')
            ->where('solicitudes_byod.id', $request->id)
            ->where('solicitudes_byod.eliminado', false)
            ->where('solicitudes_byod.estado', 'ACT')
            ->first();

        $objSelect = new SelectController();
        $dia = $objSelect->saber_dia($cql->fecha_atencion);
        setlocale(LC_TIME, "spanish");
        $fecha_atencion = $dia . ", " . strftime("%d de %B de %Y", strtotime($cql->fecha_atencion));

        $array_response['status'] = 200;
        $array_response['datos_byod'] = $cql;
        $array_response['fecha_atencion'] = strtoupper($fecha_atencion);
        return response()->json($array_response, 200);
    }
    //APROBAR LA SOLICITUD BYOD
    public function aprobarSolicitudByod(request $request)
    {
        $objSelectRepositorio = new RP();

        $cqlFuncionario = Solicitud_byod::find($request->id);
        $cqlFuncionario->identificacion_seg_informacion = Auth::user()->identificacion;
        $cqlFuncionario->fecha_aprueba_seg_informacion = date("Y-m-d");
        $cqlFuncionario->estado_solicitud = 'APROBADO SI';
        $cqlFuncionario->usuario_modifica = Auth::user()->name;
        $cqlFuncionario->fecha_modifica = date("Y-m-d H:i:s");
        $cqlFuncionario->save();
        $cqlFuncionario->fill($request->except('estado_solicitud'))->save();
        //NOTIFICANDO
        $objSelect = new SelectController();
        $jefe = $objSelect->buscarDatosUATH($cqlFuncionario->identificacion_jefe);
        $jefe_inmediato_mail = $jefe['correo_institucional_jefe'];
        $descripcion = ' Se autorizo una solicitud # ' . $cqlFuncionario->id . ' por el funcionario. ' . strtoupper(Auth::user()->nombres) . ' , sobre acceso a la red Byod ';
        $objSelect->NotificarSinRegistro($descripcion, $jefe_inmediato_mail);
        $objSelectRepositorio->guardarEstado($cqlFuncionario->id, [3, 5], [$descripcion, ''], 'BYOD');

        $array_response['status'] = 200;
        $array_response['message'] = 'REGISTRO APROBADO';
        return response()->json($array_response, 200);
    }
    public function rechazarSolicitudByod(request $request)
    {
        $objSelectRepositorio = new RP();

        $cqlFuncionario = Solicitud_byod::find($request->id);
        $cqlFuncionario->identificacion_seg_informacion = Auth::user()->identificacion;
        $cqlFuncionario->fecha_aprueba_seg_informacion = date("Y-m-d");
        $cqlFuncionario->estado_solicitud = 'RECHAZADO SI';
        $cqlFuncionario->usuario_modifica = Auth::user()->name;
        $cqlFuncionario->fecha_modifica = date("Y-m-d H:i:s");
        $cqlFuncionario->save();
        $cqlFuncionario->fill($request->except('estado_solicitud'))->save();
        //NOTIFICANDO
        $objSelect = new SelectController();
        $jefe = $objSelect->buscarDatosUATH($cqlFuncionario->identificacion_jefe);
        $jefe_inmediato_mail = $jefe['correo_institucional_jefe'];
        $descripcion = ' La solicitud # ' . $cqlFuncionario->id . ', fue rechazada por el funcionario: ' . strtoupper(Auth::user()->nombres) . ' , sobre acceso a la red Byod ';
        $objSelect->NotificarSinRegistro($descripcion, $jefe_inmediato_mail);
        $objSelectRepositorio->guardarEstado($cqlFuncionario->id, 4, $descripcion, 'BYOD');
        $array_response['status'] = 200;
        $array_response['message'] = 'REGISTRO APROBADO';
        return response()->json($array_response, 200);
    }
}
