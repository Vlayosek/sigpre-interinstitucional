<?php

namespace App\Http\Controllers\AccesoInternet;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Ajax\SelectController;
use App\Http\Controllers\AccesoInternet\RepositorioController as RP;
use App\Core\Entities\AccesoInternet\Dato_tecnico;
use App\Core\Entities\AccesoInternet\Solicitud;
use App\Core\Entities\TalentoHumano\Distributivo\Persona;
use App\Core\Entities\TalentoHumano\Distributivo\Area;
use Yajra\DataTables\CollectionDataTable;

use Auth;
use DB;

class SoporteTecnicoController extends Controller
{
    protected function actualizacionDatos()
    {
        $objSelect = new RP();
        $objSelect->updateSolicitudBorrador();
    }
    public function index()
    {
        $cql = Persona::select(
            'apellidos_nombres',
            'id'
        )
            ->with(['historial_completo' => function ($q) {
                $q->with(['area', 'cargo']);
            }])
            ->where('identificacion', Auth::user()->identificacion)
            ->limit(1)
            ->first();

        $funcionario = '--';
        $jefe_inmediato = '--';
        $area = '--';
        $cargo = '--';
        $area_mision = '--';
        $historial = null;

        if ($cql != null) {
            $funcionario = $cql->apellidos_nombres;
            $historial = $cql->historial_completo->count() > 0 ? $cql->historial_completo[0] : null;
            if ($historial != null) {
                $area = $historial->area != null ? $historial->area->nombre : '--';
                $area_id = $historial->area != null ? $historial->area->id : '--';
                $area_mision = $historial->area->mision != null ? $historial->area->mision : 'MISIÓN NO DISPONIBLE';
                $cargo = $historial->cargo != null ? $historial->cargo->nombre : '--';
            }
        }
        $objTipo = new SelectController();
        $jefe = $objTipo->buscarDatosUATH(Auth::user()->identificacion);
        $jefe_inmediato = $jefe['apellidos_nombres_jefe'];
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
        //FUNCIONARIO QUE SE ENCUENTREN EN LA SECRETARIA GENERAL DE COMUNICACION
        $area_sc = config("app_acceso_internet.area_streaming");

        //  dd($area_sc);
        $letras_tildes = 'áéíóúÁÉÍÓÚäëïöüÄËÏÖÜ';
        $letras_sin_tildes = 'aeiouAEIOUaeiouAEIOU';
        $identificacion = $this->eliminar_acentos($area_sc);
        $cqlAreaId = Area::select('id')
            ->whereRaw('translate(UPPER(nombre),\'' . $letras_tildes . '\',\'' . $letras_sin_tildes . '\') ILIKE ? ', [strtoupper($identificacion)])
            ->first();
        if ($area_id == $cqlAreaId->id) {
            $videos_en_linea = $objTipo->getParametro('SECCION-VIDEOS', 'http');
        }
        //AGREGAR LA MISION DE UNA AREA
        $cqlArea = Area::select('id', 'nombre')->where('estado', 'ACT')->where('eliminado', false)->whereNull('mision')->pluck('nombre', 'id');
        //VALIDA SI LA MISION DEL AREA ESTA EN NULL
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
        return view('modules.acceso_internet.soporte.index', compact(
            'mensajeria',
            'area_mision',
            'cqlArea',
            'funcionario',
            'area',
            'cargo',
            'jefe_inmediato',
            'sistema_operativo',
            'descargas',
            'videos_en_linea',
            'redes_sociales',
            'cqlAreaFuncionario',
            'cqlFuncionarioReporte'
        ));
    }

    public function getDatatableSoporteRevisionServerSide($fecha_inicio, $fecha_fin, $tipoActual)
    {

        $data = Solicitud::select(
            'solicitudes.id as id',
            'solicitudes.fecha_solicitud as fecha_solicitud',
            'solicitudes.codigo_solicitud as codigo_solicitud',
            'solicitudes.estado as estado',
            'dato_tecnico.perfiles as perfiles',
            'persona.apellidos_nombres as funcionario',
            'parametro.descripcion as computador',
            'transaccion.descripcion as ultimo_estado',
            'solicitudes.revisado_soporte'
        )
            ->leftjoin('sc_acceso_internet.datos_tecnicos as dato_tecnico', 'dato_tecnico.solicitud_id', 'solicitudes.id')
            ->leftjoin('sc_distributivo_.personas as persona', 'persona.identificacion', 'solicitudes.identificacion')
            ->leftjoin('core.tb_parametro as parametro', 'parametro.id', 'solicitudes.sistema_operativo_id')
            ->leftjoin('sc_acceso_internet.transacciones as transaccion', function ($join) {
                $join->on('transaccion.solicitud_id', 'solicitudes.id')
                    ->where('transaccion.estado', 'ACT');
            });
       
        if ($tipoActual != 'APROBADO')
            $data = $data->where('solicitudes.revisado_soporte', false) ->where('solicitudes.estado', 'APROBADOJI');
        else {
            $data = $data->where('solicitudes.revisado_soporte', true)->whereDate('fecha_solicitud', '>=', $fecha_inicio)
                ->whereDate('fecha_solicitud', '<=', $fecha_fin);
        }

        $data = $data->where('solicitudes.eliminado', false)->distinct()
            ->get();
        return (new CollectionDataTable($data))
            ->addIndexColumn()
            ->addColumn('', function ($row) {
                $estado = 'SOPORTE';
                if ($row->revisado_soporte)
                    $estado = $row->estado;

                $btn = ' <table style="margin: 0 auto;border-collapse: collapse; width:100%"><tr>';
                $btn .= '<td style="padding:1px"><button class="btn btn-default btn-xs btn-block"  onclick="app.editarRegistro(\'' . $row->id . '\',\'' . $estado . '\')" data-toggle="modal" data-target="#modal-solicitud"><i class="fa fa-cog"></i>&nbsp;SOLICITUD</button></td>';
                $btn .= ' </tr></table>';
                return $btn;
            })
            ->addColumn('estado_', function ($row) {
                if ($row->estado == 'ENVIADO') $estado_ = 'PENDIENTE PARA SU APROBACIÓN';
                if ($row->estado == 'APROBADOJI' && $row->revisado_soporte) $estado_ = 'PENDIENTE DE AUTORIZACIÓN';
                if ($row->estado == 'APROBADOJI' && !$row->revisado_soporte) $estado_ = 'PENDIENTE DE REVISIÓN POR SOPORTE';
                if ($row->estado == 'RECHAZADOJI') $estado_ = 'RECHAZADO POR JEFE INMEDIATO';
                if ($row->estado == 'APROBADO-SI') $estado_ = 'AUTORIZADO PARA EJECUCIÓN';
                if ($row->estado == 'RECHAZADO-SI') $estado_ = 'NO AUTORIZADO PARA EJECUCIÓN';
                if ($row->estado == 'ASIGNADO') $estado_ = 'SOLICITUD ASIGNADA';
                if ($row->estado == 'ATENDIDO') $estado_ = 'SOLICITUD ATENDIDA';

                return is_null($row->ultimo_estado) || $row->ultimo_estado == '' ? $estado_ : $row->ultimo_estado;
            })
            ->addColumn('fecha_atendido_', function ($row) {
                $fecha_atendido_ = $row->fecha_atendido == null ? 'N/A' : $row->fecha_atendido;
                return $fecha_atendido_;
            })
            ->addColumn('perfiles_', function ($row) {
                $perfiles_ = $row->perfiles != null ? $row->perfiles : 'PENDIENTE DE AUTORIZACIÓN';
                if(!$row->revisado_soporte)
                return 'PENDIENTE DE REVISIÓN A SOPORTE';
                else
                return $perfiles_;

            })

            ->rawColumns([''])
            ->toJson();
    }
    protected function menuOpcion($row)
    {
        $html = ' <div class="btn-group dropdown">';
        $html .= '    <button class="btn btn-default btn-sm dropdown-toggle" aria-haspopup="true" aria-expanded="false" onclick="transaccionToogle(this)">Acciones <span class="caret"></span></button>';
        $html .= '        <ul class="dropdown-menu">';
        if ($row->estado == 'PENDIENTE') {
            $html .= '         <li>';
            $html .= '         <a href="#" class="dropdown-item" data-toggle="modal" data-target="#modal-solicitud" onclick="app.editarRegistro(\'' . $row->id . '\');transaccionToogle(this,true)" data-backdrop="static" data-keyboard="false"><i class="fa fa-cog"></i>&nbsp;Editar</a>';
            $html .= '         </li>';
            $html .= '        <li>';
            $html .= '         <a href="#" class="dropdown-item"  onclick="app.enviarRegistro(\'' . $row->id . '\');transaccionToogle(this,true)" data-backdrop="static" data-keyboard="false"><i class="fa fa-paper-plane"></i>Enviar</a>';
            $html .= '        </li>';
            $html .= '         <li>';
            $html .= '         <a href="#" class="dropdown-item"  onclick="app.eliminarRegistro(\'' . $row->id . '\');transaccionToogle(this,true)" data-backdrop="static" data-keyboard="false">Eliminar</a>';
            $html .= '         </li>';
        } else {
            $html .= '<li>';
            $html .= '         <a href="#" class="dropdown-item" data-toggle="modal" data-target="#modal-solicitud" onclick="app.editarRegistro(\'' . $row->id . '\');transaccionToogle(this,true); app.consulta=true;" data-backdrop="static" data-keyboard="false"><i class="fa fa-cog"></i>&nbsp;Ver Solicitud</a>';
            $html .= '</li>';
        }
        $html .= '         </ul>';
        $html .= ' </div>';
        return $html;
    }
    public function consultaEstados(request $request)
    {
        $identificacion = Auth::user()->identificacion;

        $array_response['status'] = 200;
        $array_response['pendientes'] = Solicitud::select('id')
            ->where('identificacion', $identificacion)
            ->where('fecha_solicitud', '>=', $request->fecha_inicio)
            ->where('fecha_solicitud', '<=', $request->fecha_fin)
            ->whereIn('estado', ['PENDIENTE', 'ENVIADO'])->where('eliminado', false)->get()->count();
        $array_response['aprobados'] = Solicitud::select('id')
            ->where('identificacion', $identificacion)
            ->where('fecha_solicitud', '>=', $request->fecha_inicio)
            ->where('fecha_solicitud', '<=', $request->fecha_fin)
            ->whereIn('estado', ['APROBADOJI', 'APROBADO-SI'])->where('eliminado', false)->get()->count();
        $array_response['rechazados'] = Solicitud::select('id')
            ->where('identificacion', $identificacion)
            ->where('fecha_solicitud', '>=', $request->fecha_inicio)
            ->where('fecha_solicitud', '<=', $request->fecha_fin)
            ->whereIn('estado', ['RECHAZADOJI', 'RECHAZADO-SI'])->where('eliminado', false)->get()->count();
        $array_response['eliminados'] = Solicitud::select('id')
            ->where('identificacion', $identificacion)
            ->where('fecha_solicitud', '>=', $request->fecha_inicio)
            ->where('fecha_solicitud', '<=', $request->fecha_fin)
            ->where('estado', 'ELIMINADO')->where('eliminado', true)->get()->count();
        return response()->json($array_response, 200);
    }

    // CRUD DE SOLICITUD - FUNCIONARIO
    public function guardarFormularioSoporte(request $request)
    {
        //$lider_area=Auth::user()->evaluarole(['JEFE DE AREA']);
        $objSelectRepositorio = new RP();
        $cqlFormulario = Solicitud::where('id', $request->id)
            ->first();
        $cambios=$objSelectRepositorio->verificarCambios($request,$cqlFormulario);
        $cqlFormulario->ip_address_wifi = $request->ip_address_wifi;
        $cqlFormulario->ip_address_ethernet = $request->ip_address_ethernet;
        $cqlFormulario->mac_address_wifi = $request->mac_address_wifi;
        $cqlFormulario->mac_address_ethernet = $request->mac_address_ethernet;
        $cqlFormulario->usuario_modifica = Auth::user()->name;
        $cqlFormulario->fecha_modifica = date('Y-m-d H:i:s');
        $cqlFormulario->revisado_soporte = true;

        $cqlFormulario->save();  //guardo los datos de auditoria

            $descripcion = 'Se actualizo los registros de red por el funcionario :' . Auth::user()->nombres;
            $solicitud_id = $cqlFormulario->id;
            $tabla='solicitudes';
            if($cambios!=[]){
                $objSelectRepositorio->crearTransaccion($descripcion, $cqlFormulario->id);
                $objSelectRepositorio->guardarLogs($cambios, $solicitud_id,$tabla);
                $objSelectRepositorio->guardarEstado($cqlFormulario->id,[6,7],[$descripcion,'']);
            }
        $array_response['status'] = 200;
        return response()->json($array_response, 200);
    }

    public function editarRegistro(request $request)
    {
        $array_response['status'] = 200;
        $array_response['datos'] = Solicitud::with([
            'funcionarios', 'tipo_',
            'historial_completo' => function ($q) {
                $q->with(['area', 'cargo']);
            }
        ])
            ->where('id', $request->id)->get()->first();

        $cql = $array_response['datos'];
        $funcionario = '--';
        $jefe_inmediato = '--';
        $area = '--';
        $area_mision = '--';
        $cargo = '--';
        $historial = null;

        if ($cql->funcionarios != null) {
            $funcionario = $cql->funcionarios->apellidos_nombres;
        }
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

        $array_response['area_mision'] = $area_mision;
        $array_response['funcionario'] = $funcionario;
        $array_response['jefe_inmediato'] = $jefe_inmediato;
        $array_response['area'] = $area;
        $array_response['cargo'] = $cargo;

        return response()->json($array_response, 200);
    }

    public function consultaEstadosRevisadosSoporte(request $request)
    {
        $array_response['status'] = 200;
        $array_response['pendientes'] = Solicitud::select('id')
            ->where('revisado_soporte', false)
            ->where('estado', 'APROBADOJI')
            ->where('eliminado', false)->get()->count();

        $array_response['aprobados'] = Solicitud::select('id')
            ->whereDate('fecha_solicitud', '>=', $request->fecha_inicio)
            ->whereDate('fecha_solicitud', '<=', $request->fecha_fin)
            ->where('revisado_soporte', true)
            ->where('eliminado', false)->get()->count();

        return response()->json($array_response, 200);
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
}
