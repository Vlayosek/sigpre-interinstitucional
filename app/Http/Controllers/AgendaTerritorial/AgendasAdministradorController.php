<?php

namespace App\Http\Controllers\AgendaTerritorial;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Core\Entities\Agenda_territorial\Institucion;
use Yajra\Datatables\Datatables;
use App\Core\Entities\Agenda_territorial\Monitor;
use Illuminate\Support\Facades\Auth;
use App\User;


class AgendasAdministradorController extends Controller
{
    public function index()
    {
        return view('modules.agenda_territorial.administrador.index');
    }

    public function getDatatableAsignacionMonitorServerSide()
    {
        $data = Monitor::select('usuario_id')->where('eliminado', false)
            ->get()->pluck('usuario_id')->toArray();
        $cql = User::whereIn('id', $data)->orderby('nombres', 'asc');

        $datatable = Datatables::of($cql)
            ->addIndexColumn()
            ->addColumn('instituciones', function ($row) {
                $inst = Monitor::select('institucion_id')
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
                $btn = '<button title="Editar" class="btn btn-info btn-xs" data-toggle="modal" data-target="#modal-default"  data-backdrop="static" data-keyboard="false" onclick="app.editar(\'' . $row->id . '\',\'' . $dataArray->id . '\',\'' . $dataArray->nombres . '\')" >Editar</button>';
                $btn .= '&nbsp;<button title="Eliminar" class="btn btn-danger btn-xs" onclick="app.eliminar(\'' . $row->id . '\')" >Eliminar</button>';

                return $btn;
            })
            ->rawColumns([''])
            ->make(true);
        return $datatable;
    }

    public function editar(request $request)
    {

        $cql = Monitor::select('institucion_id')->where('usuario_id', $request->usuario_id)->get()
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
}
