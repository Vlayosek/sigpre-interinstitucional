<?php

namespace App\Http\Controllers\AccesoInternet;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Ajax\SelectController;
use App\Http\Controllers\AccesoInternet\RepositorioController as RP;
use App\Core\Entities\AccesoInternet\Solicitud;
use App\Core\Entities\AccesoInternet\Dato_tecnico;
use DB;

use Yajra\DataTables\CollectionDataTable;

use Auth;

class InfraestructuraController extends Controller
{
    protected function actualizacionDatos()
    {
        $objSelect = new RP();
        $objSelect->updateSolicitudBorrador();
    }
    public function infraestructura()
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
        $mensajeria = $objTipo->getParametro('SECCION-MENSAJERIA', 'http');

        ksort($videos_en_linea);
        ksort($redes_sociales);
        ksort($mensajeria);
        $perfiles = [];

        $perfiles = array_merge($videos_en_linea, $redes_sociales, $mensajeria, $descargas);

        return view('modules.acceso_internet.infraestructura.index', compact('perfiles', 'mensajeria', 'area_mision', 'funcionario', 'jefe_inmediato', 'area', 'cargo', 'historial', 'sistema_operativo', 'descargas', 'videos_en_linea', 'redes_sociales'));
    }
    public function getDatatableInfraestructuraServerSide($fecha_inicio, $fecha_fin)
    {
        $data = Solicitud::select(
            'solicitudes.id as id',
            'solicitudes.codigo_solicitud as codigo_solicitud',
            'solicitudes.fecha_solicitud as fecha_solicitud',
            'solicitudes.estado as estado',
            'parametro.descripcion as descripcion',
            'funcionario.apellidos_nombres as apellidos_nombres',
        )
            ->leftjoin('sc_distributivo_.personas as funcionario', 'funcionario.identificacion', 'solicitudes.identificacion')
            ->leftjoin('core.tb_parametro as parametro', 'parametro.id', 'solicitudes.sistema_operativo_id')
            ->whereDate('solicitudes.fecha_solicitud', '>=', $fecha_inicio)
            ->whereDate('solicitudes.fecha_solicitud', '<=', $fecha_fin)
            ->where('solicitudes.estado', 'APROBADO-SI')
            ->where('solicitudes.eliminado', false)
            ->where('parametro.descripcion', 'MACOS')
            ->get();
        return (new CollectionDataTable($data))
            ->addIndexColumn()
            ->addColumn('', function ($row) {
                $btn = ' <table style="margin: 0 auto;border-collapse: collapse; width:100%"><tr>';
                $btn .= '<td style="padding:1px"><button class="btn btn-default btn-xs btn-block"  onclick="app.verSolicitudInfraestructura(\'' . $row->id . '\')" data-toggle="modal" data-target="#modal-formulario"><i class="fa fa-cog"></i>&nbsp;SOLICITUD</button></td>';
                $btn .= ' </tr></table>';
                return $btn;
            })
            ->addColumn('estado_solicitud', function ($row) {
                if ($row->estado == 'APROBADO-SI') $estado = 'AUTORIZADO PARA EJECUCIÓN';
                return $estado;
            })
            ->rawColumns([''])
            ->toJson();
    }
    public function verSolicitudInfraestructura(request $request)
    {

        $array_response['status'] = 200;
        $array_response['datos'] = Solicitud::with([
            'funcionarios', 'tipo_',
            'historial_completo' => function ($q) {
                $q->with(['area', 'cargo']);
            }
        ])
            ->where('id', $request->id)->get()->first();

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
        $array_response['datos_tecnicos'] = Dato_tecnico::select('id', 'perfiles', 'observacion', 'ip_wifi', 'ip_ethernet')
            ->where('solicitud_id', $request->id)
            ->where('estado', 'ACT')
            ->where('eliminado', false)
            ->first();

        $objTipo = new SelectController();
        $jefe = $objTipo->buscarDatosUATH(Auth::user()->identificacion);
        $jefe_inmediato = $jefe['apellidos_nombres_jefe'];

        $array_response['area_mision'] = $area_mision;
        $array_response['funcionario'] = $funcionario;
        $array_response['jefe_inmediato'] = $jefe_inmediato;
        $array_response['area'] = $area;
        $array_response['cargo'] = $cargo;
        return response()->json($array_response, 200);
    }
    public function agregarIp(request $request)
    {
        $cqlAgregarIp = Dato_tecnico::find($request->id_datos_tecnicos);
        $cqlAgregarIp->usuario_modifica = Auth::user()->name;
        $cqlAgregarIp->fecha_modifica = date("Y-m-d H:i:s");
        if ($request->ip_wifi != null) $cqlAgregarIp->ip_wifi = $request->ip_wifi;
        if ($request->ip_ethernet != null) $cqlAgregarIp->ip_ethernet = $request->ip_ethernet;
        $cqlAgregarIp->save();
        //datos del correo
        $objTipo = new SelectController();
        $correo_envia = config('app_acceso_internet.MAIL_INFRAESTRUCTURA');
        //notificar
        $notificar = $objTipo->NotificarSinRegistro(' que el Administrador del firewall tiene un proceso pendiente de permisos de acceso a internet de la solicitud ' . $request->codigo_solicitud . ' ', $correo_envia);
        $array_response['status'] = 200;
        $array_response['message'] = 'REGISTRO AGREGADO, CON EXTIO';
        return response()->json($array_response, 200);
    }
}
