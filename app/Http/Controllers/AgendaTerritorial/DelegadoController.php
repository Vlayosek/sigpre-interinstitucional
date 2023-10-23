<?php

namespace App\Http\Controllers\AgendaTerritorial;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Core\Entities\Agenda_territorial\Institucion;
use Yajra\DataTables\CollectionDataTable;
use App\Core\Entities\Agenda_territorial\Delegado;

use Illuminate\Support\Facades\Auth;

class DelegadoController extends Controller
{
    public function delegados()
    {
        $cqlInstitucion = Institucion::select('id', 'descripcion')->where('nivel', 2)->pluck('descripcion', 'id');
        return view('modules.agenda_territorial.delegado.delegado', compact('cqlInstitucion'));
    }

    public function getDatatableAsignacionDelegadoServerSide()
    {
        $data = Delegado::select(
            'delegados.id as id',
            'delegados.identificacion as identificacion',
            'delegados.nombres as nombres',
            'delegados.institucion_id',
            'institucion_.descripcion as institucion',
            'delegados.cargo',
            'delegados.telefono',
            'delegados.celular',
            'delegados.estado'
        )
            ->join('sc_agenda_territorial.instituciones as institucion_', 'institucion_.id', 'delegados.institucion_id')
            ->get();

        return (new CollectionDataTable($data))
            ->addIndexColumn()
            ->addColumn('', function ($row) {
                $btn = ' <table style="width:100%;border:0px">';
                $btn .= '<tr><td style="padding: 2px;border:0px;text-align:center"><button class="btn btn-primary btn-xs btn-block" onclick="app.editarDelegado(\'' . $row->id . '\')" data-toggle="modal" data-target="#modal-ASIGNAR_DELEGADO"><i class="fa fa-pen"></i>&nbsp;Editar</button></td>';
                if ($row->estado == 'ACT') {
                    $btn .= '<tr><td style="padding: 2px;border:0px;text-align:center"><button class="btn btn-warning btn-xs btn-block" onclick="app.cambiarEstadoDelegado(\'' . $row->id . '\',\' inactivará \')">INACTIVAR</button></td></tr>';
                } else
                    $btn .= '<tr><td style="padding: 2px;border:0px;text-align:center"><button class="btn btn-info btn-xs btn-block" onclick="app.cambiarEstadoDelegado(\'' . $row->id . '\',\' activará \')">ACTIVAR</button></td></tr>';
                $btn .= ' </table>';
                return $btn;
            })
            ->addColumn('estado_', function ($row) {
                return $row->estado == 'ACT' ? 'ACTIVO' : 'INACTIVO';
            })
            ->rawColumns([''])
            ->toJson();
    }

    public function guardarDelegado(request $request)
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

    public function editarDelegado(request $request)
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
            ->leftjoin('sc_agenda_territorial.instituciones as institucion_', 'institucion_.id', 'delegados.institucion_id')
            ->get()->first();
        return response()->json($array_response, 200);
    }

    public function eliminarDelegado(request $request) //ELIMINAR un registro
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

    public function cambiarEstadoDelegado(request $request) //ELIMINAR un registro
    {
        $cqlEstado = Delegado::find($request->id);
        if ($cqlEstado->estado == 'ACT')
            $cqlEstado->estado = 'INA';
        else
            $cqlEstado->estado = 'ACT';

        $cqlEstado->usuario_actualiza = Auth::user()->id;
        $cqlEstado->updated_at = date("Y-m-d h:i:s");
        $cqlEstado->save();

        $array_response['status'] = 200;
        $array_response['datos'] = "Actualizado Exitosamente";
        return response()->json($array_response, 200);
    }
}
