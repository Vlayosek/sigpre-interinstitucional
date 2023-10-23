<?php

namespace App\Http\Controllers\Compromisos;

use App\Core\Entities\Admin\Notificacion;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\Datatables\Datatables;
use App\Core\Entities\Compromisos\Monitor;
use App\Core\Entities\Compromisos\Institucion;
use App\Core\Entities\Compromisos\TipoNotificacion;
use App\Core\Entities\Compromisos\Compromiso;
use Illuminate\Support\Facades\Auth;
use App\User;
use DB;

class CompromisosAdministradorController extends Controller
{
    public function index()
    {
        $cqlInstitucion = Institucion::select('id', 'descripcion')->where('nivel', '2')->pluck('descripcion', 'id');
        $cqlTipo = TipoNotificacion::select('id', 'descripcion')->where('eliminado', false)->pluck('descripcion', 'id');

        return view('modules.compromisos.administrador.index', compact('cqlInstitucion','cqlTipo'));
    }

    public function getDatatableAsignacionMonitorServerSide()
    {
        $data = Monitor::select('usuario_id')
            ->where('eliminado', false)
            ->get()->pluck('usuario_id')->toArray();

        $cql = User::whereIn('id', $data)->orderby('nombres', 'asc');
        $datatable = Datatables::of($cql)
            ->addIndexColumn()
            ->addColumn('instituciones', function ($row) {
                $inst = Monitor::select('institucion_id')
                    ->where('eliminado', false)
                    ->where('usuario_id', $row->id)
                    ->get()->pluck('institucion_id')
                    ->toArray();

                $cql = Institucion::whereIn('id', $inst)
                    ->get()
                    ->pluck('nombre')
                    ->toArray();

                $html = '';
                foreach ($cql as $value) {
                    $html .= $value . ',';
                }

                return $html;
            })
            ->addColumn('', function ($row) {
                $dataArray = User::find($row->id);
                $btn = '';
                $btn = '<button title="Editar" class="btn btn-info btn-xs" data-toggle="modal" data-target="#modal-default"  data-backdrop="static" data-keyboard="false" onclick="app.editar(\'' . $row->id . '\',\'' . $dataArray->id . '\',\'' . $dataArray->nombres . '\')" ><i class="fa fa-edit"></i></button>';
                $btn .= '&nbsp;<button title="Eliminar" class="btn btn-danger btn-xs" onclick="app.eliminar(\'' . $row->id . '\')" ><i class="fa fa-times"></i></button>';

                return $btn;
            })
            ->rawColumns([''])
            ->make(true);
        return $datatable;
    }

    public function editar(request $request)
    {

        $cql = Monitor::select('institucion_id')->where('usuario_id', $request->usuario_id)
            ->where('eliminado',false)->get()
            ->pluck('institucion_id')->toArray();

        $cqlIns = Institucion::select('id', 'nombre')
            ->whereIn('id', $cql)
            ->get()
            ->pluck('id', 'nombre')
            ->toArray();

        $array_response['status'] = 200;
        $array_response['datos'] = $cqlIns;

        return response()->json($array_response, 200);
    }
    public function guardarAsignacion(request $request)
    {
        $arreglo = explode(",", $request->institucion_id);
        $hoy = date("Y-m-d H:i:s");

        $cqlDelete = Monitor::where('usuario_id', $request->usuario_id)
            ->update(['eliminado' => true]);

        $user = User::find($request->usuario_id);
        $est = $user->evaluarole(['MONITOR']);
        if (!$est)
            $user->assignRole(['MONITOR']);

        foreach ($arreglo as $value) {
            $cql = new Monitor();
            $cql->usuario_id = $request->usuario_id;
            $cql->institucion_id = $value;
            $cql->created_at = $hoy;
            $cql->usuario_ingresa = Auth::user()->id;
            $cql->save();
        }


        $array_response['status'] = 200;
        $array_response['datos'] = $cql->id;

        return response()->json($array_response, 200);
    }
    public function eliminar(request $request)
    {

        $cqlDelete = Monitor::where('usuario_id', $request->id)
            ->update(['eliminado' => true]);

        $array_response['status'] = 200;
        $array_response['datos'] = "Eliminado Existosamente";

        return response()->json($array_response, 200);
    }
    /**Consulta de eliminados*/
    public function getDatatableEliminadosServerSide($institucion,$fecha_inicio, $fecha_fin)
    {
        $data = Compromiso::select('codigo',
                    'nombre_compromiso',
                    'institucion.nombre as institucion',
                    'fecha_inicio',
                    'fecha_fin',
                    DB::RAW("CASE WHEN compromisos.estado='INA' THEN 'Eliminado' END as eliminado"),
                    'motivo_eliminado')
                ->where('compromisos.estado', 'INA')
                ->leftjoin('sc_compromisos.responsables as responsable', 'responsable.compromiso_id', 'compromisos.id')
                ->leftjoin('sc_compromisos.instituciones as institucion', 'institucion.id', 'responsable.institucion_id');
        if($institucion !="0")
            $data = $data->where('institucion.id',$institucion);
        if ($fecha_inicio != "0" && $fecha_fin != "0") {
            $data = $data->whereDate('compromisos.updated_at', '>=', $fecha_inicio);
            $data = $data->whereDate('compromisos.updated_at', '<=', $fecha_fin);
        }
        $data = $data->get();
        $datatable = Datatables::of($data)
        ->addIndexColumn()
        ->rawColumns([''])
            ->make(true);
        return $datatable;
    }

    /**Consulta de eliminados*/
    public function getDatatableNotificacionesServerSide($institucion,$tipo_id,$fecha_inicio, $fecha_fin)
    {
        $data = Compromiso::select('compromisos.codigo',
                    'nombre_compromiso',
                    'institucion.nombre as institucion',
                    'fecha_inicio',
                    'fecha_fin',
                    'notificacion.descripcion',
                    'notificacion.tipo as tipo_notificacion',
                    'estado_compromiso.descripcion as estado_gestion')
                ->where('compromisos.estado', 'ACT')
                ->leftjoin('sc_compromisos.responsables as responsable', 'responsable.compromiso_id', 'compromisos.id')
                ->leftjoin('sc_compromisos.instituciones as institucion', 'institucion.id', 'responsable.institucion_id')
                ->leftjoin('sc_compromisos.estados as estado_compromiso', 'estado_compromiso.id', 'compromisos.estado_id')
                ->leftjoin('sc_compromisos.notificaciones as notificacion', 'notificacion.compromiso_id','compromisos.id');
        if($tipo_id !="0"){
            $data = $data->where('notificacion.tipo', TipoNotificacion::where('id', $tipo_id)->get()->first()->descripcion);
        }
        if($institucion !="0")
            $data = $data->where('institucion.id',$institucion);
        if ($fecha_inicio != "0" && $fecha_fin != "0") {
            $data = $data->whereDate('notificacion.fecha_ingresa', '>=', $fecha_inicio);
            $data = $data->whereDate('notificacion.fecha_ingresa', '<=', $fecha_fin);
        }
        $data = $data->get();
        $datatable = Datatables::of($data)
        ->addIndexColumn()
        ->rawColumns([''])
            ->make(true);
        return $datatable;
    }
}
