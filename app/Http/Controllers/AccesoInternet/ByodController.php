<?php

namespace App\Http\Controllers\AccesoInternet;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Ajax\SelectController;
use App\Http\Controllers\AccesoInternet\RepositorioController as RP;
use Yajra\DataTables\CollectionDataTable;
use Auth;
use App\User;
use App\Core\Entities\AccesoInternet\Red_byod;
use App\Core\Entities\AccesoInternet\Solicitud_byod;
use App\Core\Entities\TalentoHumano\Distributivo\Persona;
use DB;


class ByodController extends Controller
{
    protected function actualizacionDatos()
    {
        $objSelect = new RP();
        $objSelect->updateSolicitudBorrador();
    }
    public function red_byod()
    {
        $this->actualizacionDatos();
        //DATOS DEL JEFE
        $cqlPersona = Persona::select('apellidos_nombres', 'identificacion')
            ->where('identificacion', Auth::user()->identificacion)
            ->first();
        $jefe = $cqlPersona->apellidos_nombres;
        //OBTENER CEDULA DE INTEGRANTES DE AREA
        $objSelect = new SelectController();
        $historial = $objSelect->buscarJefeSIGPREIdentificacion(Auth::user()->identificacion);
        $array_identificaciones = $historial['identificaciones'];
        // OBTENER NOMBRES DE INTEGRANTES Y EL ID
        $array_integrantes = Persona::select('apellidos_nombres', 'identificacion')
            ->whereIn('identificacion', $array_identificaciones)
            ->pluck('apellidos_nombres', 'identificacion');

        return view('modules.acceso_internet.red_byod.index', compact('jefe', 'array_integrantes'));
    }
    //DATATABLE PARA SOLICITUD BYOD
    public function getDatatableByodServerSide($fecha_inicio, $fecha_fin)
    {
        $data = Solicitud_byod::select(
            'solicitudes_byod.id as id',
            'solicitudes_byod.identificacion_jefe as identificacion_jefe',
            'solicitudes_byod.fecha_aprueba_jefe as fecha_aprueba_jefe',
            'solicitudes_byod.politica_seguridad as politica_seguridad',
            'solicitudes_byod.estado_solicitud as estado_solicitud',
            'solicitudes_byod.justificacion_solicitante as justificacion_solicitante',
            'jefe.apellidos_nombres as jefe_apellidos_nombres',
            'solicitante.apellidos_nombres as solicitante_apellidos_nombres',
        )
            ->leftjoin('sc_distributivo_.personas as jefe', 'jefe.identificacion', 'solicitudes_byod.identificacion_jefe')
            ->leftjoin('sc_distributivo_.personas as solicitante', 'solicitante.identificacion', 'solicitudes_byod.identificacion_solicitante')
            ->whereDate('solicitudes_byod.fecha_aprueba_jefe', '>=', $fecha_inicio)
            ->whereDate('solicitudes_byod.fecha_aprueba_jefe', '<=', $fecha_fin)
            ->where('solicitudes_byod.identificacion_jefe',  Auth::user()->identificacion)
            ->where('solicitudes_byod.eliminado', false)
            ->where('solicitudes_byod.estado', 'ACT')
            ->get();
        return (new CollectionDataTable($data))
            ->addIndexColumn()
            ->addColumn('politica_seguridad_', function ($row) {
                if ($row->politica_seguridad == true) $politica_seguridad_ = 'ACEPTADA';
                return $politica_seguridad_;
            })
            ->addColumn('', function ($row) {
                $tipo='BYOD';
                $btn = ' <table style="margin: 0 auto;border-collapse: collapse; width:100%"><tr>';
                $btn .= '<td style="padding:1px"><button class="btn btn-warning btn-xs btn-block"  onclick="app.verSolicitudByod(\'' . $row->id . '\')" data-toggle="modal" data-target="#modal-formulario_byod"><i class="fa fa-cog"></i>&nbsp;SOLICITUD</button></td>';
                $btn .= '<td style="padding:1px"><button class="btn btn-info btn-xs btn-block"  onclick="app_seguimiento.consultarSeguimiento(\'' . $row->id . '\',\'' . $tipo . '\')" data-toggle="modal" data-target="#modal-seguimiento">SEGUIMIENTO</button></td>';
                if ($row->estado_solicitud == 'PENDIENTE' || $row->estado_solicitud == '' || $row->estado_solicitud == null) {
                    $btn .= '<td style="padding:1px"><button class="btn btn-danger btn-xs btn-block"  onclick="app.borrarSolicitudByod(\'' . $row->id . '\')"><i class="far fa-trash-alt"></i></button></td>';
                } 
                $btn .= ' </tr></table>';
                return $btn;
            })
            ->addColumn('estado_', function ($row) {
                if ($row->estado_solicitud == "APROBADO SI")
                    $estado = "AUTORIZADO";
                else {
                    if ($row->estado_solicitud == "RECHAZADO SI") {
                        $estado = "RECHAZADO";
                    } else {
                        $estado = $row->estado_solicitud;
                    }
                }
                return $estado;
            })
            ->rawColumns([''])
            ->toJson();
    }
    //DATATABLE PARA MODAL TABLA BYOD
    public function getDatatableByodAgregarServerSide($id)
    {
        $data = Red_byod::select(
            'redes_byod.id as id',
            'redes_byod.mac_address as mac_address',
            'redes_byod.tipo_dispositivo as tipo_dispositivo',
            'solicitud_byod.estado_solicitud as estado_solicitud',
        )
            ->leftjoin('sc_acceso_internet.solicitudes_byod as solicitud_byod', 'solicitud_byod.id', 'redes_byod.solicitud_byod_id')
            ->where('redes_byod.solicitud_byod_id', $id)
            ->where('redes_byod.eliminado', false)
            ->where('redes_byod.estado', 'ACT')
            ->get();
        return (new CollectionDataTable($data))
            ->addIndexColumn()
            ->addColumn('', function ($row) {
                $btn = ' <table style="margin: 0 auto;border-collapse: collapse;"><tr>';
                if ($row->estado_solicitud == 'PENDIENTE' || $row->estado_solicitud == '' || $row->estado_solicitud == null)
                    $btn .= '<td style="text-align: center;padding:1px"><button class="btn btn-danger btn-xs" onclick="app.borrarDispositivo(\'' . $row->id . '\')"><i class="far fa-trash-alt"></i></button></td>';
                else
                    $btn .= 'Solicitud enviada';
                $btn .= ' </tr></table>';
                return $btn;
            })
            ->rawColumns([''])
            ->toJson();
    }
    //GUARDA ACEPTACION DE POLITICA
    public function guardarPolitica(request $request)
    {
        $objSelectRepositorio = new RP();

        if ($request->id == null || $request->id == 0) {
            $cqlSolicitudByod = new Solicitud_byod();
            $cqlSolicitudByod->identificacion_jefe = Auth::user()->identificacion;
            $cqlSolicitudByod->estado_solicitud = 'PENDIENTE';
            $cqlSolicitudByod->fecha_aprueba_jefe = date("Y-m-d");
            $cqlSolicitudByod->estado = 'ACT';
            $cqlSolicitudByod->usuario_ingresa = Auth::user()->name;
            $cqlSolicitudByod->fecha_ingresa = date("Y-m-d H:i:s");
            $cqlSolicitudByod->save();
            $cqlSolicitudByod->fill($request->except('estado_solicitud'))->save();
            $cqlSolicitudByod = Solicitud_byod::select(
                'solicitudes_byod.id as id',
                'persona.apellidos_nombres as apellidos_nombres',
            )
                ->leftjoin('sc_distributivo_.personas as persona', 'persona.identificacion', 'solicitudes_byod.identificacion_jefe')
                ->where('solicitudes_byod.id', $cqlSolicitudByod->id)
                ->first();
            $array_response['datos'] = $cqlSolicitudByod;
            
            $descripcion = 'Se creo la solicitud para la red BYOD' . $cqlSolicitudByod->id . ' por el funcionario :' . Auth::user()->nombres;
            $objSelectRepositorio->guardarEstado($cqlSolicitudByod->id, 1, $descripcion,'BYOD');

        }
        $array_response['status'] = 200;
        $array_response['message'] = 'POLITICA ACEPTADA';
        return response()->json($array_response, 200);
    }
    //AGREGA FUNCIONARIOS CON DISPOSITIVO
    public function agregarDispositivo(request $request)
    {
        $cqlSolicitud = Solicitud_byod::find($request->solicitud_byod_id);
        if ($cqlSolicitud->identificacion_solicitante == null && $cqlSolicitud->justificacion_solicitante == null) {
            $cqlSolicitud->identificacion_solicitante = $request->identificacion_solicitante;
            $cqlSolicitud->justificacion_solicitante = $request->justificacion_solicitante;
            $cqlSolicitud->usuario_modifica = Auth::user()->name;
            $cqlSolicitud->fecha_modifica = date("Y-m-d H:i:s");
            $cqlSolicitud->save();
        }
        if ($request->id == null || $request->id == 0) {
            $cqlDatosTecnicos = new Red_byod();
            $cqlDatosTecnicos->estado = 'ACT';
            $cqlDatosTecnicos->usuario_ingresa = Auth::user()->name;
            $cqlDatosTecnicos->fecha_ingresa = date("Y-m-d H:i:s");
            $cqlDatosTecnicos->save();
            $cqlDatosTecnicos->fill($request->except('identificacion_solicitante', 'justificacion_solicitante'))->save();
        }
        $array_response['status'] = 200;
        $array_response['message'] = 'REGISTRO AGREGADO';
        return response()->json($array_response, 200);
    }
    //BORRA FUNCIONARIOS CON DISPOSITIVO
    public function borrarDispositivo(request $request)
    {
        if ($request->id != null || $request->id != 0) {
            $cqlFuncionario = Red_byod::find($request->id);
            $cqlFuncionario->eliminado = true;
            $cqlFuncionario->usuario_modifica = Auth::user()->name;
            $cqlFuncionario->fecha_modifica = date("Y-m-d H:i:s");
            $cqlFuncionario->save();
        }
        $array_response['status'] = 200;
        $array_response['message'] = 'REGISTRO ELIMINADO';
        return response()->json($array_response, 200);
    }
    //JEFE INMEDIATO APRUEBA TODA LA SOLICITUD
    public function aprobarSolicitud(request $request)
    {
        //dd($request);
        $cqlFuncionarioCount = Red_byod::select('id')->where('solicitud_byod_id', $request->id)->where('eliminado', false)->where('estado', 'ACT')->count('id');
        if ($cqlFuncionarioCount >= 1) {
            
            $objSelectRepositorio = new RP();
            $cqlFuncionario = Solicitud_byod::find($request->id);
            $cqlFuncionario->usuario_modifica = Auth::user()->name;
            $cqlFuncionario->fecha_modifica = date("Y-m-d H:i:s");
            $cqlFuncionario->identificacion_solicitante = $request->identificacion_solicitante;
            $cqlFuncionario->justificacion_solicitante = $request->justificacion_solicitante;
            $cqlFuncionario->estado_solicitud = 'APROBADO';
            $cqlFuncionario->fecha_aprueba_jefe = date("Y-m-d");
            $cqlFuncionario->save();
            $objSelect = new SelectController();
            $arregloUserRol = $objSelect->consultaUsuariosRol('AUTORIZADOR DE ACCESO A INTERNET');
            foreach ($arregloUserRol as $analistaSegInformacion) {
                $usuario = User::select('email')->where('id', $analistaSegInformacion)->first();
                $notificarAnalista = $objSelect->NotificarSinRegistro(' tiene una solicitud pendiente sobre acceso a la red Byod para su revisión por parte del funcionario, el Sr. ' . strtoupper(Auth::user()->nombres) . ' ', $usuario->email);
            }
            $mail_seg_informatica = config('app_acceso_internet.MAIL_GESTION');
            $notificarSI = $objSelect->NotificarSinRegistro(' se comunica que una solicitud de acceso a la red byod ha sido aprobada por, el Sr. ' . strtoupper(Auth::user()->nombres) . ' ', $mail_seg_informatica);
           
            $objSelectRepositorio->guardarEstado($cqlFuncionario->id, 2,'','BYOD');

            $array_response['status'] = 200;
            $array_response['message'] = 'REGISTRO APROBADO';
            return response()->json($array_response, 200);
        } else {
            $array_response['status'] = 300;
            $array_response['message'] = 'REGISTRE AL MENOS UN DISPOSITIVO';
            return response()->json($array_response, 200);
        }
    }
    //JEFE INMEDIATO VIZUALIZA LA SOLICIUTD
    public function verSolicitudByod(request $request)
    {
        $cql = Solicitud_byod::select(
            'solicitudes_byod.id',
            'solicitudes_byod.estado_solicitud',
            'solicitudes_byod.politica_seguridad',
            'solicitudes_byod.identificacion_solicitante',
            'solicitudes_byod.justificacion_solicitante',
            'solicitudes_byod.fecha_aprueba_jefe as fecha_atencion',
            'persona.apellidos_nombres as apellidos_nombres',
        )
            ->leftjoin('sc_distributivo_.personas as persona', 'persona.identificacion', 'solicitudes_byod.identificacion_jefe')
            ->where('solicitudes_byod.id', $request->id)
            ->where('solicitudes_byod.eliminado', false)
            ->where('solicitudes_byod.estado', 'ACT')
            ->first();
        $objSelect = new SelectController();
        $dia = $objSelect->saber_dia($cql->fecha_atencion);
        $mes = $objSelect->saber_mes($cql->fecha_atencion);
        $fecha_atencion = $dia . ", " . strftime("%d de " . $mes . " de %Y", strtotime($cql->fecha_atencion));
        $array_response['status'] = 200;
        $array_response['datos'] = $cql;
        $array_response['fecha_atencion'] = strtoupper($fecha_atencion);
        return response()->json($array_response, 200);
    }
    //BORRAR SOLICITUD BYOD
    public function borrarSolicitudByod(request $request)
    {
        $objSelectRepositorio = new RP();

        if ($request->id != null || $request->id != 0) {
            $cqlSolicitud = Solicitud_byod::find($request->id);
            $cqlSolicitud->eliminado = true;
            $cqlSolicitud->usuario_modifica = Auth::user()->name;
            $cqlSolicitud->fecha_modifica = date("Y-m-d H:i:s");
            $cqlSolicitud->save();
        }
        Red_byod::where('solicitud_byod_id', $cqlSolicitud->id)->update([
            'eliminado' => true,
            'usuario_modifica' => Auth::user()->name,
            'fecha_modifica' => date("Y-m-d H:i:s"),
        ]);
        $objSelectRepositorio->guardarEstado($cqlSolicitud->id, 8,'','BYOD');

        $array_response['status'] = 200;
        $array_response['message'] = 'SOLICITUD ELIMINADA';
        return response()->json($array_response, 200);
    }
  
}
