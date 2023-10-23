<?php

namespace App\Http\Controllers\AccesoInternet;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Ajax\SelectController;
use App\Http\Controllers\AccesoInternet\RepositorioController as RP;
use App\Core\Entities\AccesoInternet\Solicitud;
use App\Core\Entities\TalentoHumano\Distributivo\Persona;
use App\Core\Entities\TalentoHumano\Distributivo\Area;
use Yajra\DataTables\CollectionDataTable;

use Auth;
use DB;

class AprobarSolicitudController extends Controller
{
    protected function actualizacionDatos()
    {
        $objSelect = new RP();
        $objSelect->updateSolicitudBorrador();
    }

    protected function eliminar_acentos($cadena)
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

        //Reemplazamos la N, n, C y c
        /*  $cadena = str_replace(
        array('Ñ', 'ñ', 'Ç', 'ç'),
        array('N', 'n', 'C', 'c'),
        $cadena
        );*/
        $cadena = str_replace(
            array('Ç', 'ç'),
            array('C', 'c'),
            $cadena
        );
        return $cadena;
    }

    // APROBACION JEFE INMEDIATO  
    public function aprobacionJI()
    {
        $this->actualizacionDatos();
        $perfiles_filtro = '';
        $funcionario_filtro = '--';
        $funcionario = '--';
        $jefe_inmediato = '--';
        $area = '--';
        $area_mision = '--';
        $cargo = '--';
        $area_mision = '--';
        $historial = null;
        $objTipo = new SelectController();
        $sistema_operativo = $objTipo->getParametro('SISTEMA_OPERATIVO_REVISA', 'http');
        $descargas = $objTipo->getParametro('SECCION-DESCARGAS', 'http');
        $redes_sociales = $objTipo->getParametro('SECCION-REDES-SOCIALES', 'http');
        //En el caso de funcionarios con cargo de Coordinadores, Subsecretarios y Secretarios
        $encontrar = ['COORDINADOR', 'SUBSECRETARIO', 'SECRETARIO'];
        $pos = false;
        foreach ($encontrar as $valor) {
            $pos_ = strpos($cargo, $valor);
            if ($pos_ !== false)
                $pos = true;
        }

        if ($pos == false) {
            $parametro_id = DB::table('tb_parametro AS C')
                ->select('C.id as id')->where('C.estado', 'A')
                ->where('C.descripcion', 'SECCION-VIDEOS')
                ->pluck('id');

            $videos_en_linea = DB::table('tb_parametro AS C')
                ->groupBy('C.descripcion', 'C.id')
                ->orderBy('C.descripcion', 'desc')
                ->select('C.id as id', 'C.descripcion as descripcion')
                ->where('C.estado', 'A')
                ->where('C.parametro_id', $parametro_id)
                ->whereNotIn('C.descripcion', ['STREAMING'])
                ->pluck('descripcion', 'id')->toArray();
        } else {
            $videos_en_linea = $objTipo->getParametro('SECCION-VIDEOS', 'http');
        }
        //obtener cedula del jefe logueado
        $objSelect = new SelectController();
        $jefe = $objSelect->buscarJefeSIGPREIdentificacion(Auth::user()->identificacion);
        $identificaciones = $jefe['identificaciones'];
        //obtener los funcionarios del area
        $funcionario_filtro = Persona::select('apellidos_nombres', 'identificacion')
            ->whereIn('identificacion', $identificaciones)->pluck('apellidos_nombres', 'identificacion');
        $mensajeria = $objTipo->getParametro('SECCION-MENSAJERIA', 'http');

        ksort($videos_en_linea);
        ksort($redes_sociales);
        ksort($mensajeria);
        return view('modules.acceso_internet.aprobacionJI', compact('mensajeria', 'funcionario', 'jefe_inmediato', 'area', 'area_mision', 'cargo', 'historial', 'sistema_operativo', 'descargas', 'videos_en_linea', 'redes_sociales', 'funcionario_filtro', 'perfiles_filtro'));
    }
    public function getDatatableAprobacionServerSide($tipoActual, $fecha_inicio, $fecha_fin, $funcionario_filtro, $perfiles_filtro)
    {
        $identificacion = Auth::user()->identificacion;
        $objSelect = new SelectController();
        $array_identificaciones_ = $objSelect->buscarJefeSIGPREIdentificacion($identificacion);
        $array_identificaciones_ = $array_identificaciones_['identificaciones'];
        
            $objRepo = new Rp();

            switch ($tipoActual) {
                case 'PENDIENTE':
                    $estado = ['APROBADO POR JEFE INMEDIATO', 'RECHAZADO POR JEFE INMEDIATO'];
                    $data = $objRepo->selectDatatableAprobar(null, null, $estado)->where('solicitudes.eliminado', false);
                    break;
                default:
                    switch ($tipoActual) {
                        case 'APROBADO':
                            $estado = 'APROBADO POR JEFE INMEDIATO';
                            $data = $objRepo->selectDatatableAprobar($fecha_inicio, $fecha_fin, $estado)->where('solicitudes.eliminado', false);
                            break;
    
                        case 'RECHAZADO':
                            $estado = 'RECHAZADO POR JEFE INMEDIATO';
                            $data = $objRepo->selectDatatableAprobar($fecha_inicio, $fecha_fin, $estado)->where('solicitudes.eliminado', false);
    
                            break;
                    }
                    break;
            }
     
        if ($funcionario_filtro == '' || $funcionario_filtro == 'null')   $data = $data->whereIn('solicitudes.identificacion', $array_identificaciones_);
        else $data = $data->where('solicitudes.identificacion', $funcionario_filtro);
        if ($perfiles_filtro != 'TODOS')  $data = $data->where('dato_tecnico.perfiles', 'like', '%' . $perfiles_filtro . '%');
       
        $data = $data->distinct()->get();
        return (new CollectionDataTable($data))
            ->addIndexColumn()
            ->addColumn('perfiles_', function ($row) {
                return $row->perfiles != null ? $row->perfiles : '';
           
            })
            ->addColumn('estado_', function ($row) {
                return $row->ultimo_estado;
            })
            ->addColumn('', function ($row)use($tipoActual) {
                $estado=$tipoActual;
                $btn = '';
                $btn = ' <table ><tr>';
                if ($estado == 'ENVIADO') {
                    $btn .= '<td style="padding:2px"><button class="btn btn-primary  btn-xs"  onclick="app.editarRegistro(\'' . $row->id . '\');" data-toggle="modal" data-target="#modal-solicitud"><i class="fa fa-eye"></i>&nbsp;Ver Solicitud</button></td>';
                } else {
                    $btn .= ' <td style="padding: 2px">';
                    $btn .= '<button title="Ver solicitud" class="btn btn-default  btn-xs"  onclick="app.editarRegistro(\'' . $row->id . '\');" data-toggle="modal" data-target="#modal-solicitud"><i class="fa fa-eye"></i>&nbsp;Ver Solicitud</button></td>';
                }
                if ($estado == 'RECHAZADO') {
                    $btn = '<td style="padding:2px">' . $row->observacion_jefe . '</td>';
                }
                $btn .= ' </tr></table>';

                return $btn;
            })
            ->rawColumns([''])
            ->toJson();
    }
    public function aprobarSolicitudJI(request $request)
    {
        $notificar = new SelectController();
        $objSelectRepositorio = new RP();

        $cqlFormulario = Solicitud::find($request->id); //buscar en bd el id para pasarlo a la variable
        $cambios = $objSelectRepositorio->verificarCambios($request, $cqlFormulario);

        $cqlFormulario->usuario_modifica = Auth::user()->name; //Obtengo el login para agregarlo al campo usuario modifica
        $cqlFormulario->fecha_modifica = date("Y-m-d H:i:s"); //Obtengo el date time para agregarlo al campo fecha modifica
        $cqlFormulario->estado = 'APROBADOJI';
        $cqlFormulario->observacion_jefe = $request->observacion_jefe;
        $cqlFormulario->fecha_aprobacionji = date("Y-m-d H:i:s");
        $cqlFormulario->identificacion_jefe_aprueba = Auth::user()->identificacion;
        if ($request->valida_redes != null)
            $cqlFormulario->valida_redes = $request->valida_redes;
        else
            $cqlFormulario->valida_redes = 'false';
        if ($request->valida_descargas != null)
            $cqlFormulario->valida_descargas = $request->valida_descargas;
        else
            $cqlFormulario->valida_descargas = 'false';
        if ($request->valida_videos != null)
            $cqlFormulario->valida_videos = $request->valida_videos;
        else
            $cqlFormulario->valida_videos = 'false';
        if ($request->valida_mensajeria != null)
            $cqlFormulario->valida_mensajeria = $request->valida_mensajeria;
        else
            $cqlFormulario->valida_mensajeria = 'false';
        $cqlFormulario->save(); //guarda los datos 
        //obtener el correo del funcionario que creo la solicitud
        $cql = Persona::select(
            'correo_institucional'
        )
            ->where('identificacion', $cqlFormulario->identificacion)
            ->limit(1)
            ->first();
        $correo_institucional = $cql->correo_institucional;

        $notificar->NotificarSinRegistro('su solicitud No. ' . $cqlFormulario->codigo_solicitud . ' ha sido Aprobada por su jefe inmediato', $correo_institucional);
        $descripcion = 'Se aprobo la solicitud por jefe inmediato :' . Auth::user()->nombres;
        $solicitud_id = $cqlFormulario->id;

        $tecnicos = $notificar->consultaUsuariosRolCorreo('LIDER DE SOPORTE');
        foreach ($tecnicos as $tecnico) {
            $notificar->NotificarSinRegistro('La solicitud No. ' . $cqlFormulario->codigo_solicitud . ' esta pendiente de analisis de soporte técnico', $tecnico);
        }

        $objSelectRepositorio->crearTransaccion($descripcion, $solicitud_id);
        $objSelectRepositorio->guardarLogs($cambios, $cqlFormulario->id);
        $objSelectRepositorio->guardarEstado($cqlFormulario->id,[3,5],[$descripcion,'']);

        $array_response['status'] = 200;
        $array_response['message'] = 'APROBADO';
        return response()->json($array_response, 200);
    }
    public function rechazarSolicitudJI(request $request)
    {
        $objSelectRepositorio = new RP();

        $cqlFormulario = Solicitud::find($request->id); //buscar en bd el id para pasarlo a la variable
        $cambios = $objSelectRepositorio->verificarCambios($request, $cqlFormulario);
        $cqlFormulario->usuario_modifica = Auth::user()->name; //Obtengo el login para agregarlo al campo usuario modifica
        $cqlFormulario->fecha_modifica = date("Y-m-d H:i:s"); //Obtengo el date time para agregarlo al campo fecha modifica
        $cqlFormulario->estado = 'RECHAZADOJI';
        $cqlFormulario->observacion_jefe = $request->observacion_jefe;
        $cqlFormulario->valida_descargas = false;
        $cqlFormulario->valida_redes = false;
        $cqlFormulario->valida_videos = false;
        $cqlFormulario->fecha_aprobacionji = date("Y-m-d H:i:s");
        $cqlFormulario->identificacion_jefe_aprueba = Auth::user()->identificacion;
        $cqlFormulario->save(); //guarda los datos del cqlAprobacion

        $descripcion = 'Se rechazo la solicitud por el jefe inmediato :' . Auth::user()->nombres;
        $solicitud_id = $cqlFormulario->id;
        $objSelectRepositorio->crearTransaccion($descripcion, $solicitud_id);
        $objSelectRepositorio->guardarLogs($cambios, $cqlFormulario->id);
        $objSelectRepositorio->guardarEstado($cqlFormulario->id,4,$descripcion);

        $array_response['status'] = 200;
        $array_response['message'] = 'Rechazado exitósamente';

        return response()->json($array_response, 200);
    }
    public function consultaEstadosJI(request $request)
    {
        $identificacion = Auth::user()->identificacion;
        $objSelect = new SelectController();
        $historial = $objSelect->buscarJefeSIGPREIdentificacion($identificacion);
        $array_identificaciones_ = $historial['identificaciones'];
        $array_response['status'] = 200;
        $objRepo = new Rp();
        $fecha_inicio = $request->fecha_inicio;
        $fecha_fin = $request->fecha_fin;
        $estado = ['APROBADO POR JEFE INMEDIATO', 'RECHAZADO POR JEFE INMEDIATO'];
        $array_response['pendientes'] = $objRepo->selectDatatableAprobar(null, null, $estado)->where('solicitudes.eliminado', false)
        ->whereIn('solicitudes.identificacion', $array_identificaciones_)
        ->get()->count();
        $estado = 'APROBADO POR JEFE INMEDIATO';
        $array_response['aprobados']  = $objRepo->selectDatatableAprobar($fecha_inicio, $fecha_fin, $estado)->where('solicitudes.eliminado', false)
        ->whereIn('solicitudes.identificacion', $array_identificaciones_)
        ->get()->count();
        $estado = 'RECHAZADO POR JEFE INMEDIATO';
        $array_response['rechazados'] = $objRepo->selectDatatableAprobar($fecha_inicio, $fecha_fin, $estado)->where('solicitudes.eliminado', false)
        ->whereIn('solicitudes.identificacion', $array_identificaciones_)
        ->get()->count();
     
        $array_response['status'] = 200;
        return response()->json($array_response, 200);
    }
    //PARA AGREAGAR LA MISION DEL AREA
    public function guardarMision(request $request)
    {
        $cqlPasaje = Area::find($request->id);
        $cqlPasaje->mision = $request->mision;
        $cqlPasaje->save();
        $array_response['status'] = 200;
        $array_response['message'] = 'Guardado con exito';
        return response()->json($array_response, 200);
    }
    public function editarMision(request $request)
    {
        $array_response['status'] = 200;
        $array_response['datos'] = Area::select(
            'area.id',
            'areas.mision',
        )
            ->where('areas.id', $request->id)
            ->get()->first();
        return response()->json($array_response, 200);
    }
    public function consultarSeguimiento(request $request)
    {
        $objSelectRepositorio = new RP();
        $array_response=$objSelectRepositorio->consultarSeguimiento($request->id);
        return response()->json($array_response, 200);
    }
    
}
