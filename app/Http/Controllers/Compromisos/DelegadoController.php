<?php

namespace App\Http\Controllers\Compromisos;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Core\Entities\Compromisos\Institucion;
use Yajra\DataTables\CollectionDataTable;
use App\Core\Entities\Compromisos\Delegado;

use Illuminate\Support\Facades\Auth;

class DelegadoController extends Controller
{
    public function delegados()
    {
        $cqlInstitucion = Institucion::select('id', 'descripcion')->pluck('descripcion', 'id');
        return view('modules.compromisos.delegado.delegado', compact('cqlInstitucion'));
    }
    public function getDatatableDelegadoInstitucionServerSide()
    {
        //dd(1);
        $data = Delegado::select(
            'delegados.id as id',
            'delegados.identificacion as identificacion',
            'delegados.nombres as nombres',
            'delegados.institucion_id',
            //  'institucion_.descripcion as institucion',
            'delegados.cargo',
            'delegados.telefono',
            'delegados.celular'
        )
            //   ->join('core.instituciones as institucion_','institucion_.id','delegados.institucion_id')
            ->where('delegados.estado', 'ACT')
            ->get();

        // dd($data->toArray());
        return (new CollectionDataTable($data))
            ->addIndexColumn()
            ->addColumn('', function ($row) {
                $btn = ' <table style="margin: 0 auto;border-collapse: collapse; width:100%">';
                $btn .= '<tr><td style="padding:1px"><button class="btn btn-primary btn-xs btn-block" onclick="app.editarDelegadoInstitucion(\'' . $row->id . '\')" data-toggle="modal" data-target="#modal-ASIGNAR_DELEGADO"><i class="fa fa-pen"></i>&nbsp;Editar</button></td>';
                //   $btn .='<td style="padding:1px"><button class="btn btn-danger btn-xs btn-block" onclick="app.eliminarDelegadoInstitucion(\'' . $row->id. '\')"><i class="fa fa-times"></i>&nbsp;eliminar</button></td></tr>';
                $btn .= ' </table>';
                return $btn;
            })
            ->addColumn('institucion', function ($row) {
                $btn = Institucion::select('descripcion')->where('id', $row->institucion_id)->first();
                $btn = $btn != null ? $btn->descripcion : 'Sin informaciÃ³n';
                return $btn;
            })
            ->rawColumns([''])
            ->toJson();
    }
    public function guardarDelegadoInstitucion(request $request)
    {
        if ($request->id == 0) {
            $cqlUpdateInstitucion = Delegado::where('institucion_id', $request->institucion_id)->update(['estado' => 'INA']);

            $cqlPasaje = new Delegado();
            $cqlPasaje->estado = 'ACT';
            $cqlPasaje->usuario_ingresa = Auth::user()->id;
            $cqlPasaje->created_at = date("Y-m-d h:i:s");
        } else {
            $cqlPasaje = Delegado::find($request->id);
            $cqlPasaje->estado = 'ACT';
            $cqlPasaje->usuario_actualiza = Auth::user()->id;
            $cqlPasaje->updated_at = date("Y-m-d h:i:s");
        }
        $cqlPasaje->save();
        $cqlPasaje->fill($request->all())->save(); //llena los campos guardados y posteriormente los guarda en la tabla
        $array_response['status'] = 200;
        $array_response['message'] = 'Guardado con exito';
        return response()->json($array_response, 200);
    }
    public function editarDelegadoInstitucion(request $request)
    {
        $array_response['status'] = 200;
        $array_response['datos'] = Delegado::select(
            'delegados.id',
            'delegados.identificacion',
            'delegados.nombres',
            'delegados.email',
            'delegados.cargo',
            'delegados.telefono',
            'delegados.celular',
            'institucion_.id as institucion_id',
        )
            ->where('delegados.id', $request->id)
            ->leftjoin('core.instituciones as institucion_', 'institucion_.id', 'delegados.institucion_id')
            ->get()->first();
        return response()->json($array_response, 200);
    }
    public function eliminarDelegadoInstitucion(request $request) //ELIMINAR un registro
    {
        $cqlDelete = Delegado::find($request->id);
        $cqlDelete->estado = 'INA';
        $cqlDelete->usuario_actualiza = Auth::user()->id;
        $cqlDelete->updated_at = date("Y-m-d h:i:s");
        $cqlDelete->save();
        $array_response['status'] = 200;
        $array_response['datos'] = "Eliminado Exitosamente";
        return response()->json($array_response, 200);
    }
}
