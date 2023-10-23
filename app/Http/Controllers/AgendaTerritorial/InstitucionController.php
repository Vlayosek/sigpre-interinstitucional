<?php

namespace App\Http\Controllers\AgendaTerritorial;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Core\Entities\Agenda_territorial\Institucion;
use Yajra\DataTables\CollectionDataTable;
use App\Http\Controllers\Ajax\SelectController;

use Illuminate\Support\Facades\Auth;
use App\User;

class InstitucionController extends Controller
{
    public function delegados()
    {
        $cqlInstitucion = Institucion::select('id', 'descripcion')->where('nivel', 1)->pluck('descripcion', 'id');
        $objSelect = new SelectController();
        $usuarios_ministros = $objSelect->consultaUsuariosRolSelect('MINISTRO', null, true);

        return view('modules.agenda_territorial.instituciones.index', compact('cqlInstitucion', 'usuarios_ministros'));
    }

    public function getDatatableInstitucionServerSide()
    {

        $data = Institucion::select(
            'instituciones.id as id',
            'instituciones.nombre as nombres',
            'instituciones.siglas as siglas',
            'institucion_.nombre as gabinete',
            'instituciones.estado',
            'instituciones.ministro_usuario_id'
        )

            ->join('sc_agenda_territorial.instituciones as institucion_', 'institucion_.id', 'instituciones.institucion_id')
            ->orderby('instituciones.estado', 'desc')
            ->orderby('instituciones.id', 'desc')
            ->where('instituciones.nivel', '2')
            ->get();

        return (new CollectionDataTable($data))
            ->addIndexColumn()
            ->addColumn('', function ($row) {
                $btn = ' <table style="margin: 0 auto;border-collapse: collapse; width:100%">';

                if ($row->estado == 'ACT') {
                    $btn .= '<tr><td style="padding:1px"><button class="btn btn-info btn-xs btn-block" data-toggle="modal" data-target="#modal-INSTITUCIONES"  onclick="app.consultaInstitucion(\'' . $row->id . '\')">Ministro</button></td>';
                    $btn .= '<td style="padding:1px"><button class="btn btn-danger btn-xs btn-block" onclick="app.eliminarInstitucion(\'' . $row->id . '\')">Inactivar</button></td></tr>';
                } else
                    $btn .= '<tr><td style="padding:1px"><button class="btn btn-warning btn-xs btn-block" onclick="app.eliminarInstitucion(\'' . $row->id . '\')"><i class="fa fa-sync"></i>&nbsp;Activar</button></td></tr>';

                $btn .= ' </table>';
                return $btn;
            })
            ->addColumn('ministro', function ($row) {
                $cqlConsulta = User::select('nombres')->where('id', $row->ministro_usuario_id)->first();
                return strtoupper($cqlConsulta != null ? $cqlConsulta->nombres : '--');
            })
            ->rawColumns([''])
            ->toJson();
    }

    public function getDatatableGabineteServerSide()
    {
        $data = Institucion::select(
            'instituciones.id as id',
            'instituciones.nombre as nombres',
            'instituciones.siglas as siglas',
            'institucion_.nombre as gabinete',
            'instituciones.estado'
        )
            ->join('sc_agenda_territorial.instituciones as institucion_', 'institucion_.id', 'instituciones.institucion_id')
            ->orderby('instituciones.estado', 'desc')
            ->orderby('instituciones.id', 'desc')
            ->where('instituciones.nivel', '1')
            ->get();

        return (new CollectionDataTable($data))
            ->addIndexColumn()
            ->addColumn('', function ($row) {
                $btn = ' <table style="margin: 0 auto;border-collapse: collapse; width:100%">';

                if ($row->estado == 'ACT')
                    $btn .= '<tr><td style="padding:1px"><button class="btn btn-danger btn-xs btn-block" onclick="app.eliminarGabinete(\'' . $row->id . '\')"><i class="fa fa-times"></i>&nbsp;Inactivar</button></td></tr>';
                else
                    $btn .= '<tr><td style="padding:1px"><button class="btn btn-warning btn-xs btn-block" onclick="app.eliminarGabinete(\'' . $row->id . '\')"><i class="fa fa-sync"></i>&nbsp;Activar</button></td></tr>';

                $btn .= ' </table>';
                return $btn;
            })
            ->rawColumns([''])
            ->toJson();
    }

    public function guardarGabinete(request $request)
    {
        $cqlPresidencia = Institucion::where('descripcion', 'PRESIDENCIA DE LA REPÚBLICA')->first();

        if ($request->id == 0) {
            $cqlInserta = new Institucion();
            $cqlInserta->estado = 'ACT';
            $cqlInserta->usuario_inserta = Auth::user()->id;
            $cqlInserta->created_at = date("Y-m-d h:i:s");
            $cqlInserta->nombre = strtoupper($request->nombre);
            $cqlInserta->descripcion = strtoupper($request->nombre);
            $cqlInserta->siglas = strtoupper($request->siglas);
            $cqlInserta->institucion_id = $cqlPresidencia->id;
            $cqlInserta->nivel = 1;
            $cqlInserta->save();
        } else {
            $cqlUpdate = Institucion::find($request->id);
            $cqlUpdate->estado = 'ACT';
            $cqlUpdate->usuario_modifica = Auth::user()->id;
            $cqlUpdate->updated_at = date("Y-m-d h:i:s");
            $cqlUpdate->nombre = strtoupper($request->nombre);
            $cqlUpdate->descripcion = strtoupper($request->nombre);
            $cqlUpdate->siglas = strtoupper($request->siglas);
            $cqlUpdate->institucion_id = $cqlPresidencia->id;
            $cqlUpdate->nivel = 1;
            $cqlUpdate->save();
        }
        $array_response['status'] = 200;
        $array_response['message'] = 'Guardado con exito';
        return response()->json($array_response, 200);
    }

    public function guardarInstitucion(request $request)
    {
        $cqlUpdate = Institucion::where('ministro_usuario_id', $request->ministro_usuario_id)->update(['ministro_usuario_id' => null]);

        if ($request->id == 0) {
            $cqlInserta = new Institucion();
            $cqlInserta->estado = 'ACT';
            $cqlInserta->usuario_inserta = Auth::user()->id;
            $cqlInserta->created_at = date("Y-m-d h:i:s");
            $cqlInserta->nombre = strtoupper($request->nombre);
            $cqlInserta->descripcion = strtoupper($request->nombre);
            $cqlInserta->siglas = strtoupper($request->siglas);
            $cqlInserta->institucion_id = $request->institucion_id;
            $cqlInserta->ministro_usuario_id = $request->ministro_usuario_id;
            $cqlInserta->nivel = 2;
            $cqlInserta->fecha_inserta = date('Y-m-d');
            $cqlInserta->usuario_inserta = Auth::user()->name;
            $cqlInserta->save();
        } else {
            $cqlUpdate = Institucion::find($request->id);
            $cqlUpdate->estado = 'ACT';
            $cqlUpdate->usuario_modifica = Auth::user()->id;
            $cqlUpdate->updated_at = date("Y-m-d h:i:s");
            $cqlUpdate->nombre = strtoupper($request->nombre);
            $cqlUpdate->descripcion = strtoupper($request->nombre);
            $cqlUpdate->siglas = strtoupper($request->siglas);
            $cqlUpdate->institucion_id = $request->institucion_id;
            $cqlUpdate->nivel = 2;
            $cqlUpdate->ministro_usuario_id = $request->ministro_usuario_id;
            $cqlUpdate->fecha_modifica = date('Y-m-d');
            $cqlUpdate->usuario_modifica = Auth::user()->name;
            $cqlUpdate->save();
        }
        $array_response['status'] = 200;
        $array_response['message'] = 'Guardado con exito';
        return response()->json($array_response, 200);
    }

    public function consultaInstitucion(request $request) //ELIMINAR un registro
    {
        $array_response['status'] = 200;
        $array_response['datos'] = Institucion::select('id', 'nombre', 'siglas', 'institucion_id', 'ministro_usuario_id')->where('id', $request->id)->first();
        return response()->json($array_response, 200);
    }

    public function eliminarInstitucion(request $request) //ELIMINAR un registro
    {
        $cqlDelete = Institucion::find($request->id);
        if ($cqlDelete->estado == 'ACT')
            $cqlDelete->estado = 'INA';
        else
            $cqlDelete->estado = 'ACT';

        $cqlDelete->usuario_modifica = Auth::user()->id;
        $cqlDelete->updated_at = date("Y-m-d h:i:s");
        $cqlDelete->save();

        $array_response['status'] = 200;
        $array_response['datos'] = "Eliminado Exitosamente";
        return response()->json($array_response, 200);
    }
}
