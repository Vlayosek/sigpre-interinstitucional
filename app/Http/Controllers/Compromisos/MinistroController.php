<?php

namespace App\Http\Controllers\Compromisos;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Core\Entities\Compromisos\Institucion;
use Yajra\DataTables\CollectionDataTable;

use App\Http\Controllers\Ajax\SelectController;

use Illuminate\Support\Facades\Auth;
use App\User;

class MinistroController extends Controller
{
    public function index()
    {
        $cqlInstitucion = Institucion::select('id', 'descripcion')->where('nivel', 1)->pluck('descripcion', 'id');
        $objSelect = new SelectController();
        $usuarios_ministros = $objSelect->consultaUsuariosRolSelect('MINISTRO', null, true);

        return view('modules.compromisos.ministro.index', compact('cqlInstitucion', 'usuarios_ministros'));
    }


    public function getDatatableUsuariosMinistrosServerSide()
    {
        $objSelect = new SelectController();

        $usuarios_ministros = $objSelect->consultaUsuariosRolSelect('MINISTRO');
        $data = User::select(
            'users.id as id',
            'users.identificacion',
            'users.nombres as nombres',
            'users.email as email',
            'users.estado as estado',
            'users.cargo',
            'users.extension'
        )

            ->whereIn('users.id', array_keys($usuarios_ministros))
            ->get();

        return (new CollectionDataTable($data))
            ->addIndexColumn()
            ->addColumn('', function ($row) {
                $btn = ' <table style="margin: 0 auto;border-collapse: collapse; width:100%">';
                if ($row->estado == 'A') {
                    $btn .= '<tr><td style="padding:1px"><button class="btn btn-info btn-xs btn-block" data-toggle="modal" data-target="#modal-Usuario"  onclick="app.editarUsuario(\'' . $row->id . '\')">Editar</button></td>';
                    $btn .= '<td style="padding:1px"><button class="btn btn-primary btn-xs btn-block" onclick="app.cambiarEstadoUsuario(\'' . $row->id . '\')">Activo</button></td></tr>';
                } else
                    $btn .= '<tr><td style="padding:1px"><button class="btn btn-warning btn-xs btn-block" onclick="app.cambiarEstadoUsuario(\'' . $row->id . '\')">Inactivar</button></td></tr>';
                $btn .= ' </table>';
                return $btn;
            })
            ->rawColumns([''])
            ->toJson();
    }
    protected function agregarRolBase($usuario)
    {

        $user = User::find($usuario);
        $objSelect = new SelectController();
        $objSelect->logsCRUDRegistro('MINISTRO', $user, 'ASIGNACION ROL');
        $user->syncRoles(['MINISTRO']);
        return true;
    }

    private function registroDuplicado()
    {
        $array_response['status'] = 300;
        $array_response['message'] = 'Registro ya existe';
        return response()->json($array_response, 400);
    }

    public function guardarUsuario(request $request)
    {
        // consultando registor de usuario...
        $cqlConsultaUsuario = User::where('identificacion', $request->identificacion)->first();
        // verifica nuevo usuario....
        if ($request->id == 0) {
            // verifica si exite el usuario...
            if (!is_null($cqlConsultaUsuario)) return $this->registroDuplicado();
            // no existe... continua
            $cqlConsulta = new User();
            $cqlConsulta->identificacion = $request->identificacion;
            $cqlConsulta->name = $request->identificacion;
            $cqlConsulta->nombres = $request->nombres;
            $cqlConsulta->cargo = $request->cargo;
            $cqlConsulta->extension = $request->extension;
            $cqlConsulta->email = $request->email;
            $cqlConsulta->password = $request->password;
            $cqlConsulta->save();
            $cqlRoleUser = $this->agregarRolBase($cqlConsulta->id);
        } else {
            $cqlConsulta = User::find($request->id);
            $cqlConsulta->identificacion = $request->identificacion;
            $cqlConsulta->name = $request->identificacion;
            $cqlConsulta->nombres = $request->nombres;
            $cqlConsulta->cargo = $request->cargo;
            $cqlConsulta->extension = $request->extension;
            $cqlConsulta->email = $request->email;
            $cqlConsulta->password = $request->password;
            $cqlConsulta->save();
        }

        $array_response['status'] = 200;
        $array_response['message'] = 'Guardado con exito';
        return response()->json($array_response, 200);
    }

    public function editarUsuario(request $request) //ELIMINAR un registro
    {
        $array_response['status'] = 200;
        $array_response['datos'] = User::select(
            'id',
            'identificacion',
            'nombres',
            'email',
            'cargo',
            'extension'
        )->where('id', $request->id)->first();
        return response()->json($array_response, 200);
    }
    public function generacion_clave(request $request) //ELIMINAR un registro
    {
        $objSelect = new SelectController();

        $array_response['status'] = 200;
        $array_response['datos'] = $objSelect->clave_aleatoria();
        return response()->json($array_response, 200);
    }

    public function cambiarEstadoUsuario(request $request) //ELIMINAR un registro
    {
        $cqlDelete = User::find($request->id);
        if ($cqlDelete->estado == 'A')
            $cqlDelete->estado = 'I';
        else
            $cqlDelete->estado = 'A';

        $cqlDelete->usuario_modifica = Auth::user()->id;
        $cqlDelete->fecha_modifica = date("Y-m-d h:i:s");
        $cqlDelete->save();

        $array_response['status'] = 200;
        $array_response['datos'] = "Eliminado Exitosamente";
        return response()->json($array_response, 200);
    }
}
