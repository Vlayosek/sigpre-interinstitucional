<?php

namespace App\Http\Controllers\Compromisos;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Core\Entities\Compromisos\Institucion;
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
        // vista...
        return view('modules.compromisos.instituciones.index', compact('cqlInstitucion', 'usuarios_ministros'));
    }

    public function getDatatableInstitucionServerSide()
    {
        $data = Institucion::select(
            'instituciones.id as id',
            'instituciones.nombre as nombres',
            'instituciones.siglas as siglas',
            'institucion_.nombre as gabinete',
            'instituciones.estado',
            'instituciones.ministro_usuario_id as ministro_usuario_id',
        )
            ->join('sc_compromisos.instituciones as institucion_', 'institucion_.id', 'instituciones.institucion_id')
            ->orderby('instituciones.estado', 'desc')
            ->orderby('instituciones.id', 'desc')
            ->where('instituciones.nivel', '2')
            ->get();

        return (new CollectionDataTable($data))
            ->addIndexColumn()
            ->addColumn('ministro', function ($row) {
                $btn = User::select('nombres')->where('id', $row->ministro_usuario_id)->first();
                $btn = $btn != null ? $btn->nombres : 'Sin informaciÃ³n';
                return $btn;
            })
            ->addColumn('', function ($row) {
                $btn = ' <table style="margin: 0 auto;border-collapse: collapse; width:100%"><tr>';

                if ($row->estado == 'ACT') {
                    $btn .= '<td style="padding:1px"><button class="btn btn-primary btn-xs btn-block" data-toggle="modal" data-target="#modal-EDITAR_INSTITUCIONES" onclick="app.editaInstitucion(\'' . $row->id . '\')">Editar</button></td>';
                    $btn .= '<td style="padding:1px"><button class="btn btn-info btn-xs btn-block" data-toggle="modal" data-target="#modal-INSTITUCIONES"  onclick="app.consultaInstitucion(\'' . $row->id . '\')">Responsable</button></td>';
                    $btn .= '<td style="padding:1px"><button class="btn btn-danger btn-xs btn-block" onclick="app.eliminarInstitucion(\'' . $row->id . '\')">eliminar</button></td>';
                    $btn .= '</tr>';
                } else
                    $btn .= '<tr><td style="padding:1px"><button class="btn btn-warning btn-xs btn-block" onclick="app.eliminarInstitucion(\'' . $row->id . '\')"><i class="fa fa-sync"></i>&nbsp;Activar</button></td></tr>';

                $btn .= ' </table>';
                return $btn;
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
            ->join('sc_compromisos.instituciones as institucion_', 'institucion_.id', 'instituciones.institucion_id')
            //  ->where('instituciones.estado','ACT')
            ->orderby('instituciones.estado', 'desc')
            ->orderby('instituciones.id', 'desc')
            ->where('instituciones.nivel', '1')
            ->get();

        return (new CollectionDataTable($data))
            ->addIndexColumn()
            ->addColumn('', function ($row) {
                $btn = ' <table style="margin: 0 auto;border-collapse: collapse; width:50%"><tr>';
                if ($row->estado == 'ACT') {
                    $btn .= '<td style="padding:1px"><button class="btn btn-primary btn-xs btn-block" data-toggle="modal" data-target="#modal-EDITA_GABINETE" onclick="app.editaGabinete(\'' . $row->id . '\',\'' . true . '\')"><i class="fa fa-pen"></i>&nbsp;editar</button></td>';
                    $btn .= '<td style="padding:1px"><button class="btn btn-danger btn-xs btn-block" onclick="app.eliminarGabinete(\'' . $row->id . '\')"><i class="fa fa-times"></i>&nbsp;eliminar</button></td>';
                    $btn .= '</tr>';
                } else
                    $btn .= '<tr><td style="padding:1px"><button class="btn btn-warning btn-xs btn-block" onclick="app.eliminarGabinete(\'' . $row->id . '\')"><i class="fa fa-sync"></i>&nbsp;Activar</button></td></tr>';
                $btn .= ' </table>';
                return $btn;
            })
            ->rawColumns([''])
            ->toJson();
    }

    private function ingresaInstitucion(request $request, $institucion_id = 0, $nivel = 0)
    {
        try {
            //code...
            $cqlInserta = new Institucion();
            $cqlInserta->estado = 'ACT';
            $cqlInserta->usuario_inserta = Auth::user()->id;
            $cqlInserta->created_at = date("Y-m-d h:i:s");
            $cqlInserta->nombre = strtoupper($request->nombre);
            $cqlInserta->descripcion = strtoupper($request->nombre);
            $cqlInserta->siglas = strtoupper($request->siglas);
            $cqlInserta->institucion_id = $institucion_id;
            $cqlInserta->nivel = $nivel;
            $cqlInserta->save();
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    private function actualizaInstitucion(request $request, $institucion_id = 0)
    {
        try {
            //code...
            $cqlUpdate = Institucion::find($request->id);
            $cqlUpdate->estado = 'ACT';
            $cqlUpdate->usuario_modifica = Auth::user()->id;
            $cqlUpdate->updated_at = date("Y-m-d h:i:s");
            $cqlUpdate->nombre = strtoupper($request->nombre);
            $cqlUpdate->descripcion = strtoupper($request->nombre);
            $cqlUpdate->siglas = strtoupper($request->siglas);
            $cqlUpdate->institucion_id = $institucion_id;
            $cqlUpdate->nivel = 1;
            $cqlUpdate->save();
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function guardarGabinete(request $request)
    {
        //Verifica nivel 0 de registros...
        $cqlPresidencia = Institucion::where('institucion_id', 0)->first();
        // si es null... inserta un registor superior padre...
        if ($cqlPresidencia == null) {
            // nivel 0...
            $this->ingresaInstitucion($request);
        }
        if ($request->id == 0) {
            // nivel 1
            $this->ingresaInstitucion($request, $cqlPresidencia->id, 1);
        } else {
            // actulaizacion...
            $this->actualizaInstitucion($request, $cqlPresidencia->id);
        }
        // respondiendo datos...
        $array_response['status'] = 200;
        $array_response['message'] = 'Guardado con exito';
        // retorna,,,
        return response()->json($array_response, 200);
    }

    public function guardarInstitucion(request $request)
    {
        $cqlUpdate = Institucion::where('ministro_usuario_id', $request->ministro_usuario_id)->update(['ministro_usuario_id' => null]);
        if ($request->id == 0) {
            $cqlInserta = new Institucion();
            $cqlInserta->estado = 'ACT';
            //$cqlInserta->usuario_inserta=Auth::user()->id;
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
            //$cqlUpdate->usuario_modifica=Auth::user()->id;
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

        $cqlDelete->usuario_modifica = Auth::user()->name;
        $cqlDelete->fecha_modifica = date("Y-m-d h:i:s");
        $cqlDelete->save();

        $array_response['status'] = 200;
        $array_response['datos'] = "Eliminado Exitosamente";
        return response()->json($array_response, 200);
    }
    //EDITAR INSTITUCIONES
    public function editaInstitucion(request $request)
    {
        $cqlInstitucion = Institucion::select('id', 'siglas', 'nombre', 'institucion_id', 'ministro_usuario_id')->where('nivel', 2)->where('id', $request->id)->first();
        $array_response['status'] = 200;
        $array_response['datos'] = $cqlInstitucion;
        return response()->json($array_response, 200);
    }
    //EDITAR INSTITUCIONES
    public function editaGabinete(request $request)
    {
        $cqlGabinete = Institucion::select('id', 'siglas', 'nombre')->where('nivel', 1)->where('id', $request->id)->first();
        $array_response['status'] = 200;
        $array_response['datos'] = $cqlGabinete;
        return response()->json($array_response, 200);
    }
    public function guardaEditaGabinete(request $request)
    {
        $cqlUpdate = Institucion::find($request->id);
        $cqlUpdate->fecha_modifica = date('Y-m-d');
        $cqlUpdate->usuario_modifica = Auth::user()->id;
        $cqlUpdate->updated_at = date("Y-m-d h:i:s");
        $cqlUpdate->nombre = strtoupper($request->nombre);
        $cqlUpdate->descripcion = strtoupper($request->nombre);
        $cqlUpdate->siglas = strtoupper($request->siglas);
        $cqlUpdate->save();

        $array_response['status'] = 200;
        $array_response['message'] = 'Guardado con exito';
        return response()->json($array_response, 200);
    }
}
