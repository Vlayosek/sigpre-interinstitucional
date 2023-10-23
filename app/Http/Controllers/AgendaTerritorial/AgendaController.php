<?php

namespace App\Http\Controllers\AgendaTerritorial;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Core\Entities\Solicitudes\Solicitud;
use App\Core\Entities\Admin\mhr;
use App\Core\Entities\Admin\Role;
use App\Core\Entities\Agenda_territorial\Institucion;
use Yajra\Datatables\Datatables;
use Yajra\DataTables\CollectionDataTable;
use App\Core\Entities\Agenda_territorial\Tipo;
use App\Core\Entities\Agenda_territorial\Estado;
use App\Core\Entities\Agenda_territorial\EstadoPorcentaje;

use App\Core\Entities\Agenda_territorial\AgendaTerritorial;
use App\Core\Entities\Agenda_territorial\Antecedente;
use App\Core\Entities\Agenda_territorial\Archivo;
use App\Core\Entities\Agenda_territorial\Mensaje;
use App\Core\Entities\Agenda_territorial\Origen;
use App\Core\Entities\Agenda_territorial\Temporalidad;
use App\Core\Entities\Agenda_territorial\Objetivo;
use App\Core\Entities\Agenda_territorial\Delegado;
use App\Core\Entities\Agenda_territorial\Transaccion;
use App\Core\Entities\Agenda_territorial\Responsable;
use App\Core\Entities\Agenda_territorial\Corresponsable;
use App\Core\Entities\Agenda_territorial\Ubicacion;
use App\Core\Entities\Agenda_territorial\Codigo;
use App\Core\Entities\Agenda_territorial\Avance;
use App\Core\Entities\Agenda_territorial\Monitor;
use App\Core\Entities\Agenda_territorial\Periodo;
use App\Core\Entities\Agenda_territorial\Tipo_objetivo;
use App\Core\Entities\Agenda_territorial\OrdenDia;
use App\Core\Entities\Agenda_territorial\ObraComplementaria;
use App\Core\Entities\Agenda_territorial\ObraPrincipal;

use App\Core\Entities\Admin\parametro_ciudad;
use App\Http\Controllers\Ajax\SelectController;

use Illuminate\Support\Facades\Auth;
use App\User;
use Illuminate\Support\Facades\DB;

/*EDITAR EXCEL */
use PhpOffice\PhpSpreadsheet\{Spreadsheet, IOFactory, Exception};
/*FIN EDITAR EXCEL*/

class AgendaController extends Controller
{
    public function index()
    {
        $tipos = Tipo::select('id', 'abv', 'descripcion')->where('eliminado', false)->get();
        $gabinete = Institucion::where('nivel', 1)->get()->pluck('descripcion', 'id');
        $estados = Estado::get()->pluck('descripcion', 'id');
        $estados_porcentaje = EstadoPorcentaje::get()->pluck('descripcion', 'id');
        $origenes = Origen::get()->pluck('descripcion', 'id');
        $temporalidades = Temporalidad::orderby('id', 'asc')->pluck('descripcion', 'id');
        $tipos_objetivos = Tipo_objetivo::orderby('id', 'asc')->pluck('descripcion', 'id');
        $delegados = Delegado::get()->pluck('nombres', 'id');

        $model = Monitor::select('usuario_id')
            ->get()
            ->pluck('usuario_id')->toArray();

        $monitores = User::select(["id", "nombres"])
            ->whereIn('id', $model)
            ->get()->pluck('nombres', 'id');

        $instituciones = Institucion::where('nivel', 2)->get()->pluck('descripcion', 'id');

        return view(
            'modules.agenda_territorial.index',
            compact(
                'tipos',
                'estados',
                'gabinete',
                'origenes',
                'temporalidades',
                'monitores',
                'delegados',
                'tipos_objetivos',
                'instituciones',
                'estados_porcentaje'
            )
        );
    }

    public function corresponsableIndex()
    {
        $tipos = Tipo::get()->pluck('descripcion', 'id');
        $gabinete = Institucion::where('nivel', 1)->get()->pluck('descripcion', 'id');
        $estados = Estado::get()->pluck('descripcion', 'id');
        $estados_porcentaje = EstadoPorcentaje::get()->pluck('descripcion', 'id');
        $origenes = Origen::get()->pluck('descripcion', 'id');
        $temporalidades = Temporalidad::get()->pluck('descripcion', 'id');
        $model = Monitor::select('usuario_id')
            ->get()
            ->pluck('usuario_id')->toArray();
        $delegados = Delegado::get()->pluck('nombres', 'id');
        $tipos_objetivos = Tipo_objetivo::orderby('id', 'asc')->pluck('descripcion', 'id');

        $monitores = User::select(["id", "nombres"])
            ->whereIn('id', $model)
            ->get()->pluck('nombres', 'id');

        return view(
            'modules.agenda_territorial.corresponsable',
            compact(
                'tipos',
                'estados',
                'gabinete',
                'origenes',
                'temporalidades',
                'monitores',
                'delegados',
                'tipos_objetivos',
                'estados_porcentaje'
            )
        );
    }

    protected function consultaUsuariosRol($tipo, $institucion_id = null)
    {
        $role = Role::where('name', $tipo)->get()->first();
        $role = $role != null ? $role->id : 0;

        $model = mhr::select('model_id')
            ->where('role_id', $role)
            ->pluck('model_id')
            ->toArray();

        if ($institucion_id != null) {
            $model = User::select('id')
                ->where('institucion_id', $institucion_id)
                ->whereIn('id', $model)
                ->pluck('id')
                ->toArray();
        }
        return $model;
    }

    function buscarMonitor(Request $request)
    {
        $input = $request->all();
        if (!empty($input['query'])) {
            $busqueda = strtoupper($input['query']);
            $data = User::select(["id", "nombres"])
                ->where(DB::raw('upper(nombres)'), "LIKE", "%{$busqueda}%")
                ->orderby('nombres', 'asc')
                ->get();
        } else {
            $data = User::select(["id", "nombres"])
                ->orderby('nombres', 'asc')
                ->get()->take(5);
        }
        $countries = [];
        if (count($data) > 0) {
            foreach ($data as $country) {
                $countries[] = array(
                    "id" => $country->id,
                    "text" => $country->nombres,
                );
            }
        }
        return response()->json($countries);
    }

    function buscarResponsable(Request $request)
    {
        $model = $this->consultaUsuariosRol('MINISTRO');
        $input = $request->all();
        if (!empty($input['query'])) {
            $busqueda = strtoupper($input['query']);
            $data = User::select(["id", "nombres"])
                ->where(DB::raw('upper(nombres)'), "LIKE", "%{$busqueda}%")
                ->whereIn('id', $model)
                ->get();
        } else {
            $data = User::select(["id", "nombres"])
                ->whereIn('id', $model)
                ->get()->take(5);
        }
        $countries = [];
        if (count($data) > 0) {
            foreach ($data as $country) {
                $countries[] = array(
                    "id" => $country->id,
                    "text" => $country->nombres,
                );
            }
        }
        return response()->json($countries);
    }

    function buscarInstitucionCo(Request $request)
    {

        $input = $request->all();
        $model = $this->consultaUsuariosRol('MINISTRO');

        if (!empty($input['query'])) {
            $busqueda = strtoupper($input['query']);
            $data = Institucion::select(["instituciones.id", DB::RAW("CONCAT(instituciones.nombre,'/ ',users.nombres) as nombreData")])
                ->join('core.users', 'users.institucion_id', 'instituciones.id')
                ->where('instituciones.nivel', 2)
                ->whereIn('users.id', $model)
                ->where(DB::raw('upper(nombre)'), "LIKE", "%{$busqueda}%")
                ->get();
        } else {
            $data = Institucion::select(["instituciones.id", DB::RAW("CONCAT(instituciones.nombre,'/ ',users.nombres) as nombreData")])
                ->join('core.users', 'users.institucion_id', 'instituciones.id')
                ->where('instituciones.nivel', 2)
                ->whereIn('users.id', $model)
                ->get()->take(5);
        }
        $countries = [];
        if (count($data) > 0) {
            foreach ($data as $country) {
                $countries[] = array(
                    "id" => $country->id,
                    "text" => $country->nombredata,
                );
            }
        }
        return response()->json($countries);
    }

    public function buscarResponsableMinistroAgenda(request $request)
    {

        $array_response['status'] = 200;
        $array_response['datos'] = Auth::user();

        return response()->json($array_response, 200);
    }

    function buscarInstitucionMonitor(Request $request)
    {

        $model = Monitor::select('institucion_id')->where('eliminado', false)
            ->pluck('institucion_id')
            ->toArray();

        $input = $request->all();

        if (!empty($input['query'])) {
            $busqueda = strtoupper($input['query']);
            $data = Institucion::select(["id", "nombre"])
                ->where(DB::raw('upper(nombre)'), "LIKE", "%{$busqueda}%")
                //  ->whereNotIn('id',$model)
                ->where('nivel', 2)
                ->get();
        } else {
            $data = Institucion::select(["id", "nombre"])
                ->where('nivel', 2)
                //  ->whereNotIn('id',$model)
                ->get()->take(20);
        }
        $countries = [];
        if (count($data) > 0) {
            foreach ($data as $country) {
                $countries[] = array(
                    "id" => $country->id,
                    "text" => $country->nombre,
                );
            }
        }
        return response()->json($countries);
    }

    function buscarInstitucion(Request $request)
    {

        $input = $request->all();

        if (!empty($input['query'])) {
            $busqueda = strtoupper($input['query']);
            $data = Institucion::select(["id", "nombre"])
                ->where(DB::raw('upper(nombre)'), "LIKE", "%{$busqueda}%")
                ->where('nivel', 2)
                ->get()->take(5);
        } else {
            $data = Institucion::select(["id", "nombre"])
                ->where('nivel', 2)
                ->get()->take(5);
        }
        $countries = [];
        if (count($data) > 0) {
            foreach ($data as $country) {
                $countries[] = array(
                    "id" => $country->id,
                    "text" => $country->nombre,
                );
            }
        }
        return response()->json($countries);
    }

    protected function consultarConteoEstado($tabla, $estado_id, $ministro, $agenda_territorials, $asignaciones)
    {

        if (!$ministro)
            $cql = AgendaTerritorial::where('estado_porcentaje_id', $estado_id)->whereNotNull('codigo');
        else
            $cql = AgendaTerritorial::where('estado_porcentaje_id', $estado_id)->whereIn('id', $agenda_territorials);

        if ($asignaciones != "false")
            $cql = $cql->where('monitor_id', Auth::user()->id);
        $cql = $cql->where('estado', 'ACT')->get()->count();

        return $cql;
    }

    public function consultaCorresponsable($asignaciones)
    {

        $cq0 = Estado::where('abv', 'OPT')->get()->first()->id;
        $cq1 = Estado::where('abv', 'BUE')->get()->first()->id;
        $cq2 = Estado::where('abv', 'LEV')->get()->first()->id;
        $cq3 = Estado::where('abv', 'MOD')->get()->first()->id;
        $cq4 = Estado::where('abv', 'GRA')->get()->first()->id;

        $cq_1 = EstadoPorcentaje::where('abv', 'PLA')->get()->first()->id;
        $cq_2 = EstadoPorcentaje::where('abv', 'CUM')->get()->first()->id;
        $cq_3 = EstadoPorcentaje::where('abv', 'CER')->get()->first()->id;
        $cq_4 = EstadoPorcentaje::where('abv', 'STA')->get()->first()->id;
        $cq_5 = EstadoPorcentaje::where('abv', 'EJE')->get()->first()->id;

        $cqlAgendasResponsables = [];
        $ministro = Auth::user()->evaluarole(['MINISTRO']);
        $conteo = AgendaTerritorial::where('estado', 'ACT')->whereNotNull('codigo');
        if ($ministro) {
            $cqlAgendasResponsables = Corresponsable::select('agenda_territorial_id')
                ->where('institucion_corresponsable_id', $this->consultaInstitucionporMinistro(Auth::user()->id))
                ->where('estado', 'ACT')
                ->get()->pluck('agenda_territorial_id')->toArray();
            $conteo = $conteo->whereIn('id', $cqlAgendasResponsables);
        }
        if ($asignaciones != "false")
            $conteo = $conteo->where('monitor_id', Auth::user()->id);
        $conteo = $conteo->get()->count();
        $consulta["optimo"] = $this->consultarConteoEstado(1, $cq0, $ministro, $cqlAgendasResponsables, $asignaciones);
        $consulta["bueno"] = $this->consultarConteoEstado(1, $cq1, $ministro, $cqlAgendasResponsables, $asignaciones);
        $consulta["leve"] = $this->consultarConteoEstado(1, $cq2, $ministro, $cqlAgendasResponsables, $asignaciones);
        $consulta["moderado"] = $this->consultarConteoEstado(1, $cq3, $ministro, $cqlAgendasResponsables, $asignaciones);
        $consulta["grave"] = $this->consultarConteoEstado(1, $cq4, $ministro, $cqlAgendasResponsables, $asignaciones);
        $consulta["registrados"] = $conteo;

        $consulta["planificacion"] = $this->consultarConteoEstado(2, $cq_1, $ministro, $cqlAgendasResponsables, $asignaciones);
        $consulta["cumplido"] = $this->consultarConteoEstado(2, $cq_2, $ministro, $cqlAgendasResponsables, $asignaciones);
        $consulta["cerrado"] = $this->consultarConteoEstado(2, $cq_3, $ministro, $cqlAgendasResponsables, $asignaciones);
        $consulta["standby"] = $this->consultarConteoEstado(2, $cq_4, $ministro, $cqlAgendasResponsables, $asignaciones);
        $consulta["ejecucion"] = $this->consultarConteoEstado(2, $cq_5, $ministro, $cqlAgendasResponsables, $asignaciones);

        $asigna = AgendaTerritorial::where('estado', 'ACT')->whereNotNull('codigo')
            ->where('monitor_id', Auth::user()->id)
            ->get()->count();

        $pendientes = AgendaTerritorial::where('estado', 'ACT')->whereNotNull('codigo');
        if ($asignaciones != "false")
            $pendientes = $pendientes->where('monitor_id', Auth::user()->id);
        $pendientes = $pendientes->where('pendientes', '>', 0)
            ->get()->count();

        $temporales = AgendaTerritorial::where('estado', 'ACT');
        if ($asignaciones != "false")
            $temporales = $temporales->where('monitor_id', Auth::user()->id);
        $temporales = $temporales->whereNull('codigo');
        $temporales = $temporales->get()->count();

        $consulta["asignaciones_"] = $asigna;
        $consulta["pendientes_"] = $pendientes;
        $consulta["temporales_"] = $temporales;

        return $consulta;
    }

    public function consulta($asignaciones)
    {

        $cq_1 = EstadoPorcentaje::where('abv', 'PLA')->get()->first()->id;
        $cq_2 = EstadoPorcentaje::where('abv', 'CUM')->get()->first()->id;
        $cq_3 = EstadoPorcentaje::where('abv', 'AGE')->get()->first()->id;
        $cq_4 = EstadoPorcentaje::where('abv', 'DES')->get()->first()->id;

        $cqlAgendasResponsables = [];
        $ministro = Auth::user()->evaluarole(['MINISTRO']);
        if ($ministro) {
            $conteo = AgendaTerritorial::where('estado', 'ACT');
        } else {
            $conteo = AgendaTerritorial::where('estado', 'ACT')
                ->whereNotNull('codigo');
        }
        if ($ministro) {
            $cqlAgendasResponsables = Responsable::select('agenda_territorial_id')
                ->where('institucion_id', $this->consultaInstitucionporMinistro(Auth::user()->id))
                ->where('estado', 'ACT')
                ->get()->pluck('agenda_territorial_id')->toArray();
            $conteo = $conteo->whereIn('id', $cqlAgendasResponsables);
        }
        if ($asignaciones != "false")
            $conteo = $conteo->where('monitor_id', Auth::user()->id);
        $conteo = $conteo->get()->count();

        $consulta["registrados"] = $conteo;

        $consulta["planificacion"] = $this->consultarConteoEstado(2, $cq_1, $ministro, $cqlAgendasResponsables, $asignaciones);
        $consulta["cumplido"] = $this->consultarConteoEstado(2, $cq_2, $ministro, $cqlAgendasResponsables, $asignaciones);
        $consulta["agendado"] = $this->consultarConteoEstado(2, $cq_3, $ministro, $cqlAgendasResponsables, $asignaciones);
        $consulta["descartado"] = $this->consultarConteoEstado(2, $cq_4, $ministro, $cqlAgendasResponsables, $asignaciones);

        $asigna = AgendaTerritorial::where('estado', 'ACT')
            ->whereNotNull('codigo')
            ->where('monitor_id', Auth::user()->id)
            ->get()->count();


        $consulta["asignaciones_"] = $asigna;
        $consulta["pendientes_"] = 0;

        return $consulta;
    }

    public function getCargaDatosInstitucionCorresponsables(request $request)
    {
        $model = Corresponsable::select('institucion_corresponsable_id')
            ->where('agenda_territorial_id', $request->id)
            ->where('estado', 'ACT')
            ->pluck('institucion_corresponsable_id')
            ->toArray();

        $modelRol = $this->consultaUsuariosRol('MINISTRO');

        $consulta = Institucion::select([
            "instituciones.id",
            DB::RAW(
                "CONCAT(instituciones.nombre,'/ ',users.nombres)
                     as nombreData"
            )
        ])
            ->join('core.users', 'users.institucion_id', 'instituciones.id')
            ->whereIn('instituciones.id', $model)
            ->whereIn('users.id', $modelRol)
            ->get()->toArray();
        $array_response['status'] = 200;
        $array_response['datos'] = $consulta;

        return response()->json($array_response, 200);
    }

    public function getCargaDatosInstitucion(request $request)
    {

        $ministro = $this->consultaUsuariosRol('MINISTRO');
        $conteoMonitor = Monitor::with('usuario')->where('eliminado', false)->distinct('usuario_id')->get()->toArray();
        $consulta = Institucion::with([
            'gabinete',
            'delegado_agenda' => function ($q) {
                $q->where('estado', 'ACT');
            },
            'usuarios_monitor_agenda' => function ($q) {
                $q->with(['usuario']);
            },
            'usuarios_ministro'
        ]);
        $cqlIns = $request->id;
        if ($request->tipo == "responsable") {
            $cqlUser = User::find($request->id);
            $cqlIns = $cqlUser != null ? ($this->consultaInstitucionporMinistro($cqlUser->id)) : 0;
        }
        $consulta = $consulta->where('id', $cqlIns)
            ->get()->first();

        $array_response['status'] = 200;
        $array_response['datos'] = $consulta;
        $array_response['conteo_monitor'] = count($conteoMonitor);
        $array_response['datos_monitor'] = $conteoMonitor;

        //  $array_response['usuario_ministro'] =$this->consultaMinistroporUsuario($consulta->ministro_usuario_id);


        return response()->json($array_response, 200);
    }

    public function guardarTipo(request $request)
    {
        if ($request->id != 0) {
            $cql = Tipo::find($request->id);
            $cql->usuario_ingresa = Auth::user()->id;
        } else {
            $cql = new Tipo();
            $cql->usuario_modifica = Auth::user()->id;
        }
        $cql->descripcion = $request->tipo;
        $cql->role_id_ingresa = $request->role_id_ingresa;
        $cql->role_id_dirige = $request->role_id_dirige;
        $cql->save();
        return;
    }

    public function editarAgenda($id)
    {
        return AgendaTerritorial::with([
            'obra_principal',
            'estado',
            'estado_porcentaje',
            'tipo',
            'objetivos' => function ($q) {
                $q->where('estado', 'ACT')->orderby('id', 'asc');
            },
            'responsables' => function ($q) {
                $q->where('estado', 'ACT');
            },
            'corresponsables' => function ($q) {
                $q->where('estado', 'ACT');
            },
        ])->where('id', $id)->get();
    }

    public function eliminarObjetivo(request $request)
    {
        $hoy = date("Y-m-d H:i:s");

        //$cql=Objetivo::where('id',$request->id)->update(['estado'=>'INA']);
        $cql = Objetivo::find($request->id);
        $cql->estado = 'INA';
        $cql->updated_at = $hoy;
        $cql->save();

        $cqlRecorrido = Objetivo::select('id')
            ->where('agenda_territorial_id', $cql->agenda_territorial_id)
            ->where('estado', 'ACT')
            ->pluck('id')
            ->toArray();

        foreach ($cqlRecorrido as $key => $item) {
            $cql = Objetivo::find($item);
            $cql->numero = $key + 1;
            $cql->save();
        }

        $descripcion = 'Elimino el objetivo ' . $cql->numero . ', el usuario ' . Auth::user()->nombres;
        $this->nuevaTransaccion($descripcion, $cql->agenda_territorial_id);

        $array_response['status'] = 200;
        $array_response['objetivos'] = Objetivo::where('agenda_territorial_id', $cql->agenda_territorial_id)
            ->where('estado', 'ACT')->orderby('id', 'asc')->get()->toArray();

        return response()->json($array_response, 200);
    }

    public function eliminarObraComplementaria(request $request)
    {
        $hoy = date("Y-m-d H:i:s");

        //$cql=Objetivo::where('id',$request->id)->update(['estado'=>'INA']);
        $cql = ObraComplementaria::find($request->id);
        $cql->estado = 'INA';
        $cql->fecha_modifica = $hoy;
        $cql->usuario_modifica = Auth::user()->name;
        $cql->save();


        $descripcion = 'Elimino la obra complementaria ' . $cql->id . ', el usuario ' . Auth::user()->nombres;
        $this->nuevaTransaccion($descripcion, $cql->agenda_territorial_id);

        $array_response['status'] = 200;
        $array_response['message'] = 'Eliminado Exitosamente';

        return response()->json($array_response, 200);
    }

    public function eliminarOrdenDia(request $request)
    {
        $hoy = date("Y-m-d H:i:s");

        //$cql=Objetivo::where('id',$request->id)->update(['estado'=>'INA']);
        $cql = OrdenDia::find($request->id);
        $cql->estado = 'INA';
        $cql->fecha_modifica = $hoy;
        $cql->usuario_modifica = Auth::user()->name;
        $cql->save();


        $descripcion = 'Elimino el registro de la orden del dia ' . $cql->id . ', el usuario ' . Auth::user()->nombres;
        $this->nuevaTransaccion($descripcion, $cql->agenda_territorial_id);

        $array_response['status'] = 200;
        $array_response['message'] = 'Eliminado Exitosamente';

        return response()->json($array_response, 200);
    }

    public function negarAvance(request $request)
    {
        $hoy = date("Y-m-d H:i:s");

        $cql = Avance::find($request->id);
        $cql->usuario_actualiza = Auth::user()->id;
        $cql->fecha_revisa = $hoy;
        $cql->usuario_revisa = Auth::user()->id;
        $cql->aprobado = 'NO';
        $cql->motivo = $request->motivo;
        $cql->save();

        $descripcion = 'Se nego el avance ' . $cql->numero . ',el usuario ' . Auth::user()->nombres;
        $this->nuevaTransaccion($descripcion, $cql->agenda_territorial_id);
        if (Auth::user()->evaluarole(['MONITOR AGENDA TERRITORIAL'])) {
            $cqlAgenda = AgendaTerritorial::find($cql->agenda_territorial_id);
            $cqlAgenda->pendientes = $cqlAgenda->pendientes - 1;
            $cqlAgenda->save();
        }
        $array_response['status'] = 200;
        $array_response['datos'] = $cql->id;

        return response()->json($array_response, 200);
    }

    public function aprobarAvance(request $request)
    {
        $hoy = date("Y-m-d H:i:s");

        $cql = Avance::find($request->id);
        $cql->usuario_actualiza = Auth::user()->id;
        $cql->fecha_revisa = $hoy;
        $cql->usuario_revisa = Auth::user()->id;
        $cql->aprobado = 'SI';
        $cql->save();

        $descripcion = 'Se aprobo el avance ' . $cql->numero . ',el usuario ' . Auth::user()->nombres;
        $this->nuevaTransaccion($descripcion, $cql->agenda_territorial_id);

        $cqlAgenda = AgendaTerritorial::find($cql->agenda_territorial_id);
        $cqlAgenda->avance_compromiso = $cql->descripcion;
        $cqlAgenda->avance_id = $cql->id;
        $cqlAgenda->pendientes = $cqlAgenda->pendientes - 1;
        $cqlAgenda->save();

        $array_response['status'] = 200;
        $array_response['datos'] = $cql->id;

        return response()->json($array_response, 200);
    }

    public function eliminarAvance(request $request)
    {
        $hoy = date("Y-m-d H:i:s");

        $cql = Avance::find($request->id);
        $cql->estado = 'INA';
        $cql->usuario_actualiza = Auth::user()->id;
        $cql->updated_at = $hoy;
        $cql->save();

        $cqlRecorrido = Avance::select('id')
            ->where('agenda_territorial_id', $cql->agenda_territorial_id)
            ->where('estado', 'ACT')
            ->pluck('id')
            ->toArray();

        foreach ($cqlRecorrido as $key => $item) {
            $cql = Avance::find($item);
            $cql->numero = $key + 1;
            $cql->updated_at = $hoy;
            $cql->save();
        }

        $descripcion = 'Elimino el avance ' . $cql->numero . ',el usuario ' . Auth::user()->nombres;
        $this->nuevaTransaccion($descripcion, $cql->agenda_territorial_id);

        $array_response['status'] = 200;
        $array_response['datos'] = $cql->id;

        return response()->json($array_response, 200);
    }

    public function eliminarAntecedente(request $request)
    {
        $hoy = date("Y-m-d H:i:s");

        $cql = Antecedente::find($request->id);
        $cql->estado = 'INA';
        $cql->save();

        $cqlRecorrido = Antecedente::select('id')
            ->where('agenda_territorial_id', $cql->agenda_territorial_id)
            ->where('estado', 'ACT')
            ->pluck('id')
            ->toArray();

        foreach ($cqlRecorrido as $key => $item) {
            $cql = Antecedente::find($item);
            $cql->numero = $key + 1;
            $cql->updated_at = $hoy;
            $cql->save();
        }

        $descripcion = 'Elimino el antecedente ' . $cql->numero . ', el usuario ' . Auth::user()->nombres;
        $this->nuevaTransaccion($descripcion, $cql->agenda_territorial_id);

        $array_response['status'] = 200;
        $array_response['datos'] = $cql->id;

        return response()->json($array_response, 200);
    }

    public function eliminarCompromiso(request $request)
    {
        $hoy = date("Y-m-d H:i:s");

        $cql = AgendaTerritorial::where('id', $request->id)->update([
            'estado' => 'INA',
            'updated_at' => $hoy
        ]);
        return;
    }

    public function eliminarTipo(request $request)
    {
        $cqlDelete = Tipo::where('id', $request->id)->delete();
        return;
        /*$array_response['status'] = 200;
        $array_response['datos'] = 'Eliminado Exitosamente';

         return response()->json($array_response, 200);*/
    }

    protected function nuevaTransaccion($descripcion, $agenda_territorial_id, $query = true, $visible = true)
    {
        $agenda_territorialAfectado = $agenda_territorial_id;
        if ($query) {
            $agenda_territorialAfectado = AgendaTerritorial::find($agenda_territorial_id);
            $agenda_territorialAfectado = $agenda_territorialAfectado->codigo != null && $agenda_territorialAfectado->codigo != '' ? $agenda_territorialAfectado->codigo : ('Temporal ' . $agenda_territorialAfectado->id);
        }

        $hoy = date("Y-m-d H:i:s");

        $cql1 = new Transaccion();
        $cql1->descripcion = $descripcion . ', a la agenda ' . $agenda_territorialAfectado;
        $cql1->agenda_territorial_id = $agenda_territorial_id;
        $cql1->created_at = $hoy;
        $cql1->visible = $visible == true || $visible == 1 ? "true" : "false";
        $cql1->estado = 'ACT';
        $cql1->usuario_ingresa = Auth::user()->id;
        $cql1->save();
        return $cql1->id;
    }

    protected function comparaCampos($campo1, $campo2, $id, $tipo, $campoCompleto = false, $visible = true, $notificar = false)
    {
        if ($campo1 != $campo2) {
            $descripcion = "Se cambiaron los datos del campo ," . $tipo;
            if (!$campoCompleto)
                $descripcion .= " del campo ," . $campo2 . " al " . $campo1;

            $this->nuevaTransaccion($descripcion, $id, true, $visible);

            if ($notificar == true) {
                $cqlResposanble = Responsable::where('agenda_territorial_id', $id)
                    ->where('estado', 'ACT')
                    ->get()->first();

                $cqlAgenda = AgendaTerritorial::find($id);
                $codigo = $cqlAgenda->codigo;

                $institucion_model = Institucion::where('id', $cqlResposanble->institucion_id)->first();
                if (!is_null($institucion_model)) {
                    if (!is_null($institucion_model->ministro_usuario_id)) {
                        $model = [$institucion_model->ministro_usuario_id];
                        $msj = $descripcion . ' en la agenda: ' . $codigo;
                        if (env('NOTIFICAR'))
                            $not = $this->notificarUsuario($model, $msj);
                    }
                }

                $arregloUsuarios[0] = $cqlAgenda->monitor_id;
                $model = $arregloUsuarios;
                $msj = $descripcion . ' en la agenda: ' . $codigo;
                if (env('NOTIFICAR'))
                    $not = $this->notificarUsuario($model, $msj);
            }
        }
        return $campo1;
    }

    protected function validarDatos($agenda_territorial, $cambioResponsable = 0, $cambioCorresponsable = 0)
    {
        $hoy = date("Y-m-d H:i:s");

        if ($agenda_territorial->id == 0) {
            $cqlAgenda = new AgendaTerritorial();
            $cqlAgenda->usuario_ingresa = Auth::user()->id;
            $cqlAgenda->created_at = $hoy;

            $cqlAgenda->fill($agenda_territorial->except(['estado', 'estado_porcentaje', 'tipo']))->save();
        } else {
            $cqlAgenda = AgendaTerritorial::find($agenda_territorial->id);

            $cq_2 = EstadoPorcentaje::where('abv', 'CUM')->get()->first()->id;

            if ($agenda_territorial->estado_porcentaje_id == $cq_2) {
                $cqlAgenda->cerrado = "true";
            }

            $cqlAgenda->usuario_actualiza = Auth::user()->id;
            $cqlAgenda->updated_at = $hoy;

            $cqlAgenda->tipo_id =
                $this->comparaCampos(
                    $agenda_territorial->tipo_id,
                    $cqlAgenda->tipo_id,
                    $cqlAgenda->id,
                    ('Tipo de: ' . Tipo::find($cqlAgenda->tipo_id)->descripcion . ' al, ' . Tipo::find($agenda_territorial->tipo_id)->descripcion),
                    true
                );

            $cqlAgenda->fecha_inicio = $this->comparaCampos(
                $agenda_territorial->fecha_inicio,
                $cqlAgenda->fecha_inicio,
                $cqlAgenda->id,
                'Fecha de Inicio ',
                false
            );

            $cqlAgenda->tema = $this->comparaCampos(
                $agenda_territorial->tema,
                $cqlAgenda->tema,
                $cqlAgenda->id,
                'Tema',
                false
            );
            $cqlAgenda->objetivo = $this->comparaCampos(
                $agenda_territorial->objetivo,
                $cqlAgenda->objetivo,
                $cqlAgenda->id,
                'Objetivo ',
                false
            );
            $cqlAgenda->justificacion = $this->comparaCampos(
                $agenda_territorial->justificacion,
                $cqlAgenda->justificacion,
                $cqlAgenda->id,
                'Justificacion ',
                false
            );

            $cqlAgenda->descripcion = $this->comparaCampos(
                $agenda_territorial->descripcion,
                $cqlAgenda->descripcion,
                $cqlAgenda->id,
                'Descripcion ',
                false,
                false
            );
            $cqlAgenda->descripcion = $this->comparaCampos(
                $agenda_territorial->observacion,
                $cqlAgenda->observacion,
                $cqlAgenda->id,
                'Observacion ',
                false,
                false
            );
            if ($cqlAgenda->monitor_id != 0 && $cqlAgenda->monitor_id != null) {
                $cqMonitor1 = Monitor::with(['usuario'])
                    ->where('usuario_id', $cqlAgenda->monitor_id)
                    ->get()->first();
                $cqMonitor2 = Monitor::with(['usuario'])
                    ->where('usuario_id', $agenda_territorial->monitor_id)
                    ->get()->first();
                $cqlAgenda->monitor_id = $this->comparaCampos(
                    $agenda_territorial->monitor_id,
                    $cqlAgenda->monitor_id,
                    $cqlAgenda->id,
                    ('Monitor de la agenda de: ' .
                        ($cqMonitor1 != null ? ($cqMonitor1->usuario = null ? $cqMonitor1->usuario->nombres : '') : '') .
                        ' al, ' .
                        ($cqMonitor2 != null ? ($cqMonitor2->usuario = null ? $cqMonitor2->usuario->nombres : '') : '')),
                    true
                );
            }

            $cqlAgenda->estado_porcentaje_id =
                $this->comparaCampos(
                    EstadoPorcentaje::find($agenda_territorial->estado_porcentaje_id)->descripcion,
                    EstadoPorcentaje::find($cqlAgenda->estado_porcentaje_id)->descripcion,
                    $cqlAgenda->id,
                    ('Estado de ' .
                        EstadoPorcentaje::find($cqlAgenda->estado_porcentaje_id)->descripcion . ' al ' .
                        EstadoPorcentaje::find($agenda_territorial->estado_porcentaje_id)->descripcion . ''),
                    true,
                    true,
                    true
                );
            $cqlAgenda->estado_id =
                $this->comparaCampos(
                    Estado::find($agenda_territorial->estado_id)->descripcion,
                    Estado::find($cqlAgenda->estado_id)->descripcion,
                    $cqlAgenda->id,
                    ('Prioridad de ' .
                        Estado::find($cqlAgenda->estado_id)->descripcion .
                        ' al ' . Estado::find($agenda_territorial->estado_id)->descripcion),
                    true,
                    true,
                    true
                );
            if ($agenda_territorial->avance_id == "null" || $agenda_territorial->avance_id == "" || $agenda_territorial->avance_id == null)
                $cqlAgenda->fill($agenda_territorial->except(['estado', 'estado_porcentaje', 'tipo']))->save();
            else
                $cqlAgenda->fill($agenda_territorial->except(['estado', 'estado_porcentaje', 'tipo']))->save();
        }


        if ($agenda_territorial->id == 0) {
            $descripcion = 'Se creo una agenda ';
            $this->nuevaTransaccion($descripcion, $cqlAgenda->id);
        }

        //if(($cambioResponsable!=0||$agenda_territorial->id==0)&&$agenda_territorial->responsable_id!=null){
        $cqlUpdate = Responsable::where('institucion_id', $agenda_territorial->institucion_id)
            ->where('agenda_territorial_id', $cqlAgenda->id)
            //  ->where('usuario_ingresa',Auth::user()->id)
            ->where('estado', 'ACT')
            ->first();

        if (is_null($cqlUpdate)) {
            $cqlBuscarResposanble = Responsable::where('agenda_territorial_id', $agenda_territorial->id)
                ->update([
                    'estado' => 'INA',
                    'usuario_actualiza' => Auth::user()->id
                ]);

            $descripcion = 'Se Agrego Nuevo Responsable ' . Institucion::find($agenda_territorial->institucion_id)->descripcion;
            $this->nuevaTransaccion($descripcion, $cqlAgenda->id);

            $cqlResposanble = new Responsable();
            $cqlResposanble->institucion_id = $agenda_territorial->institucion_id;
            $cqlResposanble->agenda_territorial_id = $cqlAgenda->id;
            $cqlResposanble->usuario_ingresa = Auth::user()->id;
            $cqlResposanble->estado = 'ACT';
            $cqlResposanble->save();
        }

        $cqlAgenda->estado = 'ACT';
        $cqlAgenda->pendientes = 0;
        $cqlAgenda->save();


        return $cqlAgenda->id;
    }

    protected function notificarUsuario($usuario, $descripcion)
    {
        $cqlArregloUsuarios = User::select('email')
            ->whereIn('id', $usuario)
            ->get()
            ->pluck('email')
            ->toArray();

        foreach ($cqlArregloUsuarios as $for) {
            try {
                $objSelect = new SelectController();
                if (env('NOTIFICAR'))
                    $notificacion = $objSelect->NotificarSinRegistro($descripcion, $for);
            } catch (\Exception $ex) {
                echo $ex->getMessage();
            }
        }

        return true;
    }

    public function crearCodigo(request $request)
    {
        $hoy = date("Y-m-d H:i:s");

        $codigo = 'S/C';
        $cqlCodigo = Codigo::where('institucion_id', $request->institucion_id)
            ->get()->first();
        if ($cqlCodigo != null) {
            $cqlCodigo->numero = $cqlCodigo->numero + 1;
            $cqlCodigo->updated_at = $hoy;
            $cqlCodigo->usuario_actualiza = Auth::user()->id;
            $cqlCodigo->save();
            $codigo = trim($cqlCodigo->abv . '-' . $cqlCodigo->numero);
        } else {
            $cqlCodigo_new = new Codigo();
            $cqlCodigo_new->numero = 1;
            $cqlCodigo_new->institucion_id = $request->institucion_id;
            $cqlCodigo_new->abv = Institucion::find($request->institucion_id)->siglas;
            $cqlCodigo_new->usuario_ingresa = Auth::user()->id;
            $cqlCodigo_new->created_at = $hoy;
            $cqlCodigo_new->save();

            $codigo = trim($cqlCodigo_new->abv . '-' . $cqlCodigo_new->numero);
        }
        $cqlAgenda = AgendaTerritorial::find($request->id);
        $cqlAgenda->codigo = $codigo;
        $cqlAgenda->save();

        if ($request->id != 0) {
            $msj = 'tiene una actividad por Revisar, por favor revisar la información.';
            $model = [$cqlAgenda->monitor_id];
            if (env('NOTIFICAR'))
                $not = $this->notificarUsuario($model, $msj);
        }

        $descripcion = 'Se creo el codigo ' . $codigo;
        $this->nuevaTransaccion($descripcion, $request->id, false);

        return $codigo;
    }

    public function guardarAvance(request $request)
    {
        $hoy = date("Y-m-d H:i:s");

        $agenda_territorial_id = $request->id;
        if ($request->idAvance != 0) {
            $cql = Avance::find($request->idAvance);
            $cql->usuario_actualiza = Auth::user()->id;
            $cql->updated_at = $hoy;

            $descripcion = 'Se actualizo el avance ' . $cql->numero;
            $this->nuevaTransaccion($descripcion, $agenda_territorial_id);
        } else {
            $numero = Avance::where('agenda_territorial_id', $request->id)->where('estado', 'ACT')->get()->count();

            $cql = new Avance();
            $cql->usuario_ingresa = Auth::user()->id;
            $cql->numero = $numero + 1;
            $cql->created_at = $hoy;


            $descripcion = 'Se creo el avance ' . ($numero + 1);
            $this->nuevaTransaccion($descripcion, $agenda_territorial_id);
        }
        $cql->aprobado = 'NO';
        $cql->descripcion = $request->descripcion;
        $cql->agenda_territorial_id = $agenda_territorial_id;
        $cql->save();

        $cqlAgenda = AgendaTerritorial::find($agenda_territorial_id);
        $cqlAgenda->pendientes = $cqlAgenda->pendientes + 1;
        $cqlAgenda->save();

        $array_response['status'] = 200;
        $array_response['datos'] = $agenda_territorial_id;

        return response()->json($array_response, 200);
    }

    public function guardarAntecedente(request $request)
    {
        $hoy = date("Y-m-d H:i:s");

        $agenda_territorial_id = $request->id;
        if ($request->idAntecedente != 0) {
            $cql = Antecedente::find($request->idAntecedente);
            $cql->usuario_actualiza = Auth::user()->id;
            $cql->updated_at = $hoy;


            $descripcion = 'Se actualizo el antecedente ' . $cql->numero;
            $this->nuevaTransaccion($descripcion, $agenda_territorial_id);
        } else {
            $numero = Antecedente::where('agenda_territorial_id', $request->id)->where('estado', 'ACT')->get()->count();

            $cql = new Antecedente();
            $cql->usuario_ingresa = Auth::user()->id;
            $cql->numero = $numero + 1;
            $cql->created_at = $hoy;


            $descripcion = 'Se creo el antecedente ' . ($numero + 1);
            $this->nuevaTransaccion($descripcion, $agenda_territorial_id);
        }
        $cql->descripcion = $request->antecedente;
        $cql->fecha_antecedente = $request->fecha_antecedente;
        $cql->agenda_territorial_id = $agenda_territorial_id;
        $cql->save();

        $array_response['status'] = 200;
        $array_response['datos'] = $agenda_territorial_id;

        return response()->json($array_response, 200);
    }

    public function guardarUbicacion(request $request)
    {
        $agenda_territorial_id = $request->id;
        $cqlDelete = Ubicacion::where('agenda_territorial_id', $agenda_territorial_id)->delete();
        if ($request->ubicacion != null) {
            $arregloUbicaciones = explode(",", $request->ubicacion);
            foreach ($arregloUbicaciones as $ubicacion) {
                $cql = new Ubicacion();
                $cql->agenda_territorial_id = $agenda_territorial_id;
                $cql->parametro_id = $ubicacion;
                $cql->usuario_ingresa = Auth::user()->id;
                $cql->save();
            }
        }

        $descripcion = 'Se agrego cambios en las ubicaciones de la agenda ';
        $this->nuevaTransaccion($descripcion, $agenda_territorial_id);

        $array_response['status'] = 200;
        $array_response['datos'] = $agenda_territorial_id;

        return response()->json($array_response, 200);
    }

    public function grabarArchivos(request $request)
    {
        $hoy = date("Y-m-d H:i:s");

        $agenda_territorial_id = $request->id;
        foreach ($request->archivo as $archivo) {
            $file      = $archivo;
            $extension = $file->getClientOriginalExtension();
            $nombre = $file->getClientOriginalName();

            $nameFile  = uniqid() . '.' . $extension;
            \Storage::disk('storage')->put("AGENDA_TERRITORIAL/$nameFile",  \File::get($file));

            $grabarImg = new Archivo();
            $grabarImg->agenda_territorial_id = $agenda_territorial_id;
            $grabarImg->descripcion = $nameFile;
            $grabarImg->nombre = $nombre;
            $grabarImg->estado = 'ACT';
            $grabarImg->usuario_ingresa = Auth::user()->id;
            $grabarImg->leido = 'NO';
            $grabarImg->created_at = $hoy;
            $grabarImg->save();

            $descripcion = 'Se agrego el archivo ' . $grabarImg->nombre;
            $this->nuevaTransaccion($descripcion, $grabarImg->agenda_territorial_id);
        }
        if (Auth::user()->evaluarole(['MINISTRO'])) {
            $cqlAgenda = AgendaTerritorial::find($agenda_territorial_id);
            $cqlAgenda->pendientes = $cqlAgenda->pendientes + 1;
            $cqlAgenda->save();
        }


        $array_response['status'] = 200;
        $array_response['datos'] = $agenda_territorial_id;

        return response()->json($array_response, 200);
    }

    public function eliminarArchivo(request $request)
    {
        $hoy = date("Y-m-d H:i:s");

        $cql = Archivo::find($request->id);
        $cql->estado = 'INA';
        $cql->usuario_actualiza = Auth::user()->id;
        $cql->updated_at = $hoy;

        $cql->save();

        $descripcion = 'Se elimino archivo ' . $cql->nombre;
        $this->nuevaTransaccion($descripcion, $cql->agenda_territorial_id);

        $array_response['status'] = 200;
        $array_response['datos'] = $cql->id;

        return response()->json($array_response, 200);
    }

    public function grabarMensaje(request $request)
    {
        $hoy = date("Y-m-d H:i:s");

        $agenda_territorial_id = $request->id;

        $cql = new Mensaje();
        $cql->agenda_territorial_id = $agenda_territorial_id;
        $cql->descripcion = $request->descripcion;
        $cql->estado = 'ACT';
        $cql->leido = 'NO';
        $cql->usuario_ingresa = Auth::user()->id;
        $cql->created_at = $hoy;
        $cql->save();

        $descripcion = 'Agrego un nuevo mensaje el usuario ' . Auth::user()->nombres;
        $this->nuevaTransaccion($descripcion, $agenda_territorial_id);

        if (Auth::user()->evaluarole(['MINISTRO'])) {
            $cqlAgenda = AgendaTerritorial::find($agenda_territorial_id);
            $cqlAgenda->pendientes = $cqlAgenda->pendientes + 1;
            $cqlAgenda->save();

            $model = [$cqlAgenda->monitor_id];
            $msj = 'Tiene un mensaje en el Sistema de Agenda Territorial.';
            if (env('NOTIFICAR'))
                $not = $this->notificarUsuario($model, $msj);
        } else {

            $cqlAgenda = AgendaTerritorial::find($agenda_territorial_id);

            $cqlAgendasResponsables = Responsable::select('institucion_id')
                ->where('agenda_territorial_id', $agenda_territorial_id)
                ->where('estado', 'ACT')
                ->pluck('institucion_id')->toArray();

            $model = User::select('id')
                ->whereIn('institucion_id', $cqlAgendasResponsables)
                ->pluck('id')
                ->toArray();

            $institucion_model = Institucion::whereIn('id', $cqlAgendasResponsables)->first();
            if (!is_null($institucion_model)) {
                if (!is_null($institucion_model->ministro_usuario_id)) {
                    $model = [$institucion_model->ministro_usuario_id];
                }
            }
            // dd($model);
            $msj = 'Tiene un mensaje en el Sistema de Agenda Territorial.';
            if (env('NOTIFICAR'))
                $not = $this->notificarUsuario($model, $msj);
        }
        $array_response['status'] = 200;
        $array_response['datos'] = $agenda_territorial_id;

        return response()->json($array_response, 200);
    }

    public function descargarArchivo(request $request)
    {
        $hoy = date("Y-m-d H:i:s");


        $cql = Archivo::find($request->id);
        $cql->fecha_revisa = $hoy;
        $cql->usuario_revisa = Auth::user()->id;
        $cql->leido = 'SI';
        $cql->save();

        if (Auth::user()->evaluarole(['MONITOR AGENDA TERRITORIAL'])) {
            $cqlAgenda = AgendaTerritorial::find($cql->agenda_territorial_id);
            $cqlAgenda->pendientes = $cqlAgenda->pendientes - 1;
            $cqlAgenda->save();
        }


        $descripcion = 'Ha descargado el archivo ' . $cql->nombre . ', el usuario ' . Auth::user()->nombres;
        $this->nuevaTransaccion($descripcion, $cql->agenda_territorial_id);

        $array_response['status'] = 200;
        $array_response['datos'] = $cql->id;

        return response()->json($array_response, 200);
    }

    public function leerMensaje(request $request)
    {
        $hoy = date("Y-m-d H:i:s");


        $cql = Mensaje::find($request->id);
        $cql->fecha_revisa = $hoy;
        $cql->usuario_revisa = Auth::user()->id;
        $cql->leido = 'SI';
        $cql->save();

        if (Auth::user()->evaluarole(['MONITOR AGENDA TERRITORIAL'])) {
            $cqlAgenda = AgendaTerritorial::find($cql->agenda_territorial_id);
            $cqlAgenda->pendientes = $cqlAgenda->pendientes - 1;
            $cqlAgenda->save();
        }

        $descripcion = 'Ha leido un mensaje el usuario ' . Auth::user()->nombres;
        $this->nuevaTransaccion($descripcion, $cql->agenda_territorial_id);

        $array_response['status'] = 200;
        $array_response['datos'] = $cql->id;

        return response()->json($array_response, 200);
    }

    public function eliminarMensaje(request $request)
    {
        $hoy = date("Y-m-d H:i:s");

        $cql = Mensaje::find($request->id);
        $cql->estado = 'INA';
        $cql->usuario_actualiza = Auth::user()->id;
        $cql->updated_at = $hoy;
        $cql->save();

        $descripcion = 'Elimino un mensaje el usuario ' . Auth::user()->nombres;
        $this->nuevaTransaccion($descripcion, $cql->agenda_territorial_id);

        $array_response['status'] = 200;
        $array_response['datos'] = $cql->id;

        return response()->json($array_response, 200);
    }

    public function guardarAgenda(request $request)
    {

        $agenda_territorial_id = $this->validarDatos($request);


        $conteo = 0;

        $cqlAgenda = AgendaTerritorial::with('tipo')->where('id', $agenda_territorial_id)->first();
        $cerrado = $cqlAgenda->cerrado;

        $array_response['status'] = 200;
        $array_response['dato_completo'] = $cqlAgenda;
        $array_response['datos'] = $agenda_territorial_id;
        $array_response['conteo'] = $conteo;
        $array_response['cerrado'] = $cerrado;


        return response()->json($array_response, 200);
    }

    protected function saber_dia($nombredia)
    {
        //  if(date('N', strtotime($nombredia))==7)
        // dd(date('N', strtotime($nombredia)),$nombredia);
        $dias = array('DOMINGO', 'LUNES', 'MARTES', 'MIERCOLES', 'JUEVES', 'VIERNES', 'SABADO', 'DOMINGO');

        $fecha = $dias[date('N', strtotime($nombredia))];

        return  $fecha;
    }

    protected function getMondaysInRange($dateFromString, $dateToString)
    {
        $dateFrom = new \DateTime($dateFromString);
        $dateTo = new \DateTime($dateToString);
        $dates = [];

        if ($dateFrom > $dateTo) {
            return $dates;
        }

        if (1 != $dateFrom->format('N')) {
            $dateFrom->modify('next monday');
        }

        while ($dateFrom <= $dateTo) {
            $dates[] = $dateFrom->format('Y-m-d');
            $dateFrom->modify('+1 week');
        }

        return $dates;
    }

    protected function nuevoPeriodo($obj_id, $numero, $value, $tiempo, $meta_acumulada, $cumplimiento_acumulado, $fin_periodo)
    {
        $cql = new Periodo();
        $cql->numero = $numero;
        $cql->temporalidad = $tiempo->descripcion;
        $cql->fecha_inicio_periodo = $value;
        $cql->fecha_fin_periodo = $fin_periodo;
        $cql->objetivo_id = $obj_id;
        $cql->usuario_ingresa = Auth::user()->id;
        $cql->created_at = date("Y-m-d H:i:s");

        $cql->cumplimiento_periodo = 0;
        $cql->meta_periodo = 0;
        $cql->cumplimiento_periodo_porcentaje = 0;
        $cql->pendiente_periodo = 0;

        $cql->cumplimiento_acumulado = 0;
        $cql->pendiente_acumulado = 0;
        $cql->meta_acumulada = 0;

        $cql->valor_anterior_meta_acumulada = $meta_acumulada;
        $cql->valor_anterior_cumplimiento_acumulado = $cumplimiento_acumulado;
        $cql->estado = 'INA';

        $cql->save();
    }

    protected function rangosFechas($inicio, $fin)
    {
        $start = $inicio;
        $end = $fin;
        $range = array();
        if (is_string($start) === true) $start = strtotime($start);
        if (is_string($end) === true) $end = strtotime($end);

        do {
            $range[] = date('Y-m-d', $start);
            $start = strtotime("+ 30 day", $start);
        } while ($start <= $end);
        return $range;
    }

    public function getDatatableCargarObraComplementaria($campo)
    {
        $data = ObraComplementaria::where('agenda_territorial_id', $campo)->where('estado', 'ACT')
            ->orderby('id', 'desc');
        $datatable = Datatables::of($data)
            ->addIndexColumn()

            ->addColumn('', function ($row) {
                $btn = '<table ><tr>';
                if (Auth::user()->evaluarole(['MINISTRO']))
                    $btn .= '<td  style="padding:2px"><button  onclick="app.eliminarObraComplementaria(\'' . $row->id . '\');" class="btn btn-danger btn-xs">Eliminar</button></td>';
                return $btn . '</tr></table>';
            })

            ->rawColumns([''])
            ->make(true);
        return $datatable;
    }

    public function getDatatableCargarOrdenDia($campo)
    {
        $increment = 0;
        $data = OrdenDia::where('agenda_territorial_id', $campo)->where('estado', 'ACT')
            ->orderby('id', 'asc');
        $datatable = Datatables::of($data)
            ->addIndexColumn()

            ->addColumn('', function ($row) {
                $btn = '<table><tr>';
                if (Auth::user()->evaluarole(['MINISTRO'])) {
                    $btn .= '<td  style="padding:2px"><button  onclick="app.editarOrdenDia(\'' . $row->id . '\');" class="btn btn-info btn-xs btn">Editar</button></td>';
                    $btn .= '<td  style="padding:2px"><button  onclick="app.eliminarOrdenDia(\'' . $row->id . '\');" class="btn btn-danger btn-xs btn">Eliminar</button></td>';
                }

                return $btn . '</tr></table>';
            })
            ->addColumn('increment', function ($row) use ($increment) {
                return '';
            })

            ->rawColumns([''])
            ->make(true);
        return $datatable;
    }

    public function getDatatableAvancesServerSide($campo)
    {
        $data = Avance::with([
            'usuario', 'usuario_leido'
        ])
            ->where('agenda_territorial_id', $campo)
            ->orderby('id', 'desc');
        $datatable = Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('institucion', function ($row) {
                $cql = Institucion::where('ministro_usuario_id', $row->usuario->id)->get()->first();
                return $cql != null ? $cql->descripcion : '--';
            })
            ->addColumn('institucion_leida', function ($row) {
                if ($row->usuario_leido != null) {
                    $cql = Institucion::where('ministro_usuario_id', $row->usuario_leido->id)->get()->first();
                    return $cql != null ? $cql->descripcion : '--';
                }

                return '';
            })
            ->addColumn('', function ($row) {
                $btn = '';
                $btn = '<table><tr>';
                if (($row->fecha_revisa == null || $row->fecha_revisa == '') && Auth::user()->evaluarole(['MONITOR AGENDA TERRITORIAL'])) {
                    $btn .= '<td style="padding:2px"><button class="btn btn-primary  btn-xs"  onclick="app.aprobarAvances(\'' . $row->id . '\',\'' . $row->descripcion . '\')" title="Aprobar"><i class="fa fa-check"></i></button></td>';
                    $btn .= ' <td style="padding:2px"><button class="btn btn-danger  btn-xs" data-toggle="modal" data-target="#modal-negar" onclick="app.agregarNegarAvances(\'' . $row->id . '\')" title="Rechazar"><i class="fa fa-times"></i></button></td>';
                }

                $btn .= '</tr></table>';
                return $btn;
            })

            ->rawColumns(['', 'institucion', 'institucion_leida'])
            ->make(true);
        return $datatable;
    }

    public function getDatatableUbicacionesServerSide(request $request)
    {
        $dataFiltro = Ubicacion::select('parametro_id')
            ->where('agenda_territorial_id', $request->id)
            ->pluck('parametro_id')->toArray();

        $dataFiltroGeneral = [];
        $data = parametro_ciudad::with(['fatherpara' => function ($q) {
            $q->with('fatherpara');
        }])
            ->where('verificacion', 'PARROQUIA')
            ->whereIn('id', $dataFiltro)
            ->orderby('id', 'asc')
            ->get()
            ->toArray();
        if (count($data) == 0) {
            $dataFiltroGeneral = parametro_ciudad::whereIn('id', $dataFiltro)
                ->orderby('id', 'desc')
                ->get()
                ->toArray();
        }
        $array_response['status'] = 200;
        $array_response['message'] = $data;
        $array_response['datos'] = $dataFiltro;
        $array_response['datos_generales'] = $dataFiltroGeneral;

        return response()->json($array_response, 200);
    }

    public function getDatatablePeriodosServerSide($campo)
    {
        $data = Periodo::where('objetivo_id', $campo)->orderby('id', 'asc');
        $datatable = Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('', function ($row) {
                $btn = '';
                if (Auth::user()->evaluarole(['MONITOR AGENDA TERRITORIAL'])) {
                    $edicion = false;
                    $cqlVerificaPeriodoAnterior = Periodo::where('objetivo_id', $row->objetivo_id)->where('numero', $row->numero - 1)->get()->first();
                    if ($cqlVerificaPeriodoAnterior != null) {
                        if ($cqlVerificaPeriodoAnterior->estado == 'ACT')
                            $edicion = true;
                    }
                    if ($cqlVerificaPeriodoAnterior == null)
                        $edicion = true;

                    $btn = ' <table ><tr>';
                    $btn .= ' <td style="padding: 2px"><button title="Editar" class="btn btn-primary  btn-xs"  onclick="app.editaPeriodo(\'' . $row->id . '\',\'' . $edicion . '\')"><i class="fa fa-cog"></i></button></td>';
                    $btn .= ' </tr></table>';
                }
                return $btn;
            })

            ->rawColumns([''])
            ->make(true);
        return $datatable;
    }

    public function getDatatableObjetivosServerSide($campo)
    {
        $data = Objetivo::with(['temporalidad'])
            ->where('agenda_territorial_id', $campo)
            ->where('estado', 'ACT')
            ->orderby('id', 'asc');

        $datatable = Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('', function ($row) {
                $btn = '<table><tr>';
                if (Auth::user()->evaluarole(['MONITOR AGENDA TERRITORIAL'])) {
                    $btn .= ' <td style="padding:1px"><button title="Editar" class="btn btn-primary  btn-xs"  onclick="app.editarObjetivo(\'' . $row->id . '\',\'' . $row->objetivo . '\',\'' . $row->descripcion . '\',\'' . $row->temporalidad_id . '\',\'' . $row->fecha_inicio . '\',\'' . $row->fecha_fin . '\',\'' . $row->meta . '\',\'' . $row->tipo_objetivo_id . '\')"><i class="fa fa-cog"></i></button></td>';
                    $btn .= '<td style="padding:1px"><button title="Eliminar" class="btn btn-danger  btn-xs"  onclick="app.eliminarObjetivo(\'' . $row->id . '\')" ><i class="fa fa-times"></i></button></td>';
                    if (is_null($row->aprobado)) {
                        $btn .= '<td style="padding:1px"><button title="Aprobar" class="btn btn-primary  btn-xs"  onclick="app.aprobarObjetivo(\'' . $row->id . '\')" ><i class="fa fa-thumbs-up"></i></button></td>';
                        $btn .= '<td style="padding:1px"><button title="Rechazar" class="btn btn-danger  btn-xs"  onclick="app.rechazarObjetivo(\'' . $row->id . '\')" ><i class="fa fa-thumbs-down"></i></button></td>';
                    } else {
                        if ($row->aprobado)
                            $btn .= '<td style="padding:1px">&nbsp;<label title="Aprobar" class="label label-default  label-xs"  >APROBADO</label></td>';
                        else
                            $btn .= '<td style="padding:1px">&nbsp;<label title="Rechazado" class="label label-default  label-xs"  >RECHAZADO</label></td>';
                    }
                }

                return $btn . '</tr></table>';
            })

            ->rawColumns([''])
            ->make(true);
        return $datatable;
    }

    public function editarOrdenDia(request $request)
    {

        $cqlOrdenDia = OrdenDia::where('id', $request->id)->first();

        $array_response['status'] = 200;
        $array_response['datos'] = $cqlOrdenDia;

        return response()->json($array_response, 200);
    }

    public function aprobarObjetivo(request $request)
    {

        $cqlObjetivo = Objetivo::find($request->id);
        $cqlObjetivo->aprobado = true;
        $cqlObjetivo->save();

        $descripcion = 'Se aprobo el objetivo: ' . $request->objetivo;
        $this->nuevaTransaccion($descripcion, $cqlObjetivo->agenda_territorial_id);

        $array_response['status'] = 200;
        $array_response['datos'] = $request->id;

        return response()->json($array_response, 200);
    }

    public function rechazarObjetivo(request $request)
    {

        $cqlObjetivo = Objetivo::find($request->id);
        $cqlObjetivo->motivo_negado = $request->observacion;
        $cqlObjetivo->aprobado = false;
        $cqlObjetivo->save();

        $descripcion = 'Se rechazo el objetivo: ' . $request->objetivo . ', por motivo: ' . $request->observacion;
        $this->nuevaTransaccion($descripcion, $cqlObjetivo->agenda_territorial_id);

        $array_response['status'] = 200;
        $array_response['datos'] = $request->id;

        return response()->json($array_response, 200);
    }

    public function guardarObraComplementaria(request $request)
    {
        if ($request->id != 0) {
            $cql = ObraComplementaria::find($request->id);
            $cql->usuario_modifica = Auth::user()->name;
            $cql->fecha_modifica = date('Y-m-d h:i:s');
        } else {
            $cql = new ObraComplementaria();
            $cql->usuario_inserta = Auth::user()->name;
            $cql->fecha_inserta = date('Y-m-d h:i:s');
        }
        $cql->save();
        $cql->fill($request->all())->save();

        $descripcion = 'Se creo una nueva obra complementaria #: ' . $cql->id . '';
        $this->nuevaTransaccion($descripcion, $cql->agenda_territorial_id);

        $array_response['status'] = 200;
        $array_response['datos'] = 'Grabado Exitosamente';

        return response()->json($array_response, 200);
    }

    public function guardarArchivoParticipantes(request $request)
    {

        $cqlAgenda = AgendaTerritorial::find($request->agenda_territorial_id);
        $objSelect = new SelectController();
        if ($request->archivos != null && $request->archivos != [] && $request->archivos != "null") {
            $archivos = $objSelect->grabarArchivosStorage($request->archivos, 'AGENDA_TERRITORIAL/ORDEN_DIA/PARTICIPANTES');
            if ($archivos != []) {
                $cqlAgenda->descripcion_participantes_archivo = $archivos[0]["descripcion"];
                $cqlAgenda->nombre_participantes_archivo = $archivos[0]["nombre"];
                $cqlAgenda->save();
            }
        }
        $descripcion = 'Se agrego un archivo de participantes al registro : ' . $cqlAgenda->id . '';
        $this->nuevaTransaccion($descripcion, $cqlAgenda->id);

        $array_response['status'] = 200;
        $array_response['datos'] = 'Grabado Exitosamente';
        $array_response['descripcion_participantes_archivo'] = $cqlAgenda->descripcion_participantes_archivo;
        $array_response['nombre_participantes_archivo'] = $cqlAgenda->nombre_participantes_archivo;

        return response()->json($array_response, 200);
    }

    public function guardarOrdenDia(request $request)
    {

        if ($request->id != 0) {
            $cql = OrdenDia::find($request->id);
            $cql->usuario_modifica = Auth::user()->name;
            $cql->fecha_modifica = date('Y-m-d h:i:s');
        } else {
            $cql = new OrdenDia();
            $cql->usuario_inserta = Auth::user()->name;
            $cql->fecha_inserta = date('Y-m-d h:i:s');
        }

        $cql->save();
        $cql->fill($request->all())->save();

        $descripcion = 'Se creo una nueva orden del día #: ' . $cql->id . '';
        $this->nuevaTransaccion($descripcion, $cql->agenda_territorial_id);



        $array_response['status'] = 200;
        $array_response['datos'] = 'Grabado Exitosamente';

        return response()->json($array_response, 200);
    }

    public function guardarObraPrincipal(request $request)
    {


        if ($request->id != 0) {
            $cql = ObraPrincipal::find($request->id);
            $cql->usuario_modifica = Auth::user()->name;
            $cql->fecha_modifica = date('Y-m-d h:i:s');
        } else {
            $cqlConsulta = ObraPrincipal::where('agenda_territorial_id', $request->agenda_territorial_id)->first();
            if (is_null($cqlConsulta))
                $cql = new ObraPrincipal();
            else
                $cql = ObraPrincipal::find($cqlConsulta->id);

            $cql->usuario_inserta = Auth::user()->name;
            $cql->fecha_inserta = date('Y-m-d h:i:s');
        }
        $cql->save();
        $cql->fill($request->all())->save();

        $descripcion = 'Se creo una nueva obra principal #: ' . $cql->id . '';
        $this->nuevaTransaccion($descripcion, $cql->agenda_territorial_id);

        $array_response['status'] = 200;
        $array_response['datos'] = 'Grabado Exitosamente';

        return response()->json($array_response, 200);
    }

    public function getDatatableMensajeServerSide($campo)
    {
        $data = Mensaje::with('usuario', 'usuario_leido')
            ->where('agenda_territorial_id', $campo)
            ->where('estado', 'ACT')
            ->orderby('id', 'desc');
        $datatable = Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('institucion', function ($row) {
                $cql = Institucion::where('ministro_usuario_id', $row->usuario->id)->get()->first();
                return $cql != null ? $cql->descripcion : '--';
            })
            ->addColumn('institucion_leida', function ($row) {
                if ($row->usuario_leido != null) {
                    $cql = Institucion::where('ministro_usuario_id', $row->usuario_leido->id)->get()->first();
                    return $cql != null ? $cql->descripcion : '--';
                }
                return '';
            })
            ->addColumn('', function ($row) {
                $btn = '';
                if ($row->usuario_ingresa != Auth::user()->id) {
                    if ($row->leido == 'NO')
                        $btn = '<button title="Leer" class="btn btn-info  btn-xs"  onclick="app.leerMensaje(\'' . $row->id . '\')" ><i class="fa fa-envelope"></i></button>';
                    else
                        $btn = '<button title="Leido" class="btn btn-info  btn-xs" disabled><i class="fa fa-envelope-open"></i></button>';
                }
                if ($row->usuario_ingresa == Auth::user()->id)
                    $btn .= '&nbsp;<button title="Eliminar" class="btn btn-danger  btn-xs"  onclick="app.eliminarMensaje(\'' . $row->id . '\')" ><i class="fa fa-times"></i></button>';

                return $btn;
            })

            ->rawColumns(['', 'institucion', 'institucion_leida'])
            ->make(true);
        return $datatable;
    }

    public function getDatatableHistoricoServerSide($campo)
    {
        $data =
            Transaccion::select(
                'sc_agenda_territorial.transacciones.id',
                'sc_agenda_territorial.transacciones.created_at as fecha',
                'sc_agenda_territorial.transacciones.descripcion as descripcion',
                'inst.descripcion as institucion',
                'sc_agenda_territorial.transacciones.agenda_territorial_id',
                'sc_agenda_territorial.transacciones.visible',
                'sc_agenda_territorial.transacciones.usuario_ingresa'
            )
            //  ->leftjoin('core.users as user','user.id','sc_agenda_territorial.transacciones.usuario_ingresa')
            ->leftjoin('sc_agenda_territorial.instituciones as inst', 'sc_agenda_territorial.transacciones.usuario_ingresa', 'inst.ministro_usuario_id')
            ->where('sc_agenda_territorial.transacciones.agenda_territorial_id', $campo);
        if (!Auth::user()->evaluarole(['MONITOR AGENDA TERRITORIAL']))
            $data = $data->where('sc_agenda_territorial.transacciones.visible', 'true');
        $data = $data->orderby('sc_agenda_territorial.transacciones.id', 'desc');
        $datatable = Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('usuario', function ($row) {
                $cql = User::where('id', $row->usuario_ingresa)->first();
                return $cql != null ? $cql->nombres : '--';
            })
            ->make(true);
        return $datatable;
    }

    public function getDatatableArchivosServerSide($campo)
    {
        $data = Archivo::with([
            'usuario',
            'usuario_leido'
        ])
            ->where('agenda_territorial_id', $campo)
            ->where('estado', 'ACT')
            ->orderby('id', 'desc');
        $datatable = Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('', function ($row) {
                $btn = '';
                // if($row->usuario_ingresa!=Auth::user()->id)
                $btn = '<button title="Descargar" class="btn btn-info btn-xs" onclick="app.descargarArchivo(\'' . $row->id . '\',\'' . $row->descripcion . '\',\'' . $row->nombre . '\',\'' . $row->leido . '\')" >Descargar</button>';
                if (Auth::user()->evaluarole(['MONITOR AGENDA TERRITORIAL']))
                    $btn .= '&nbsp;<button title="Eliminar" class="btn btn-danger  btn-xs"  onclick="app.eliminarArchivo(\'' . $row->id . '\')" >Eliminar</button>';
                return $btn;
            })
            ->addColumn('institucion', function ($row) {
                $cql = Institucion::where('ministro_usuario_id', $row->usuario->id)->get()->first();
                return $cql != null ? $cql->descripcion : '--';
            })
            ->addColumn('institucion_leida', function ($row) {
                if ($row->usuario_leido != null) {
                    $cql = Institucion::where('ministro_usuario_id', $row->usuario_leido->id)->get()->first();
                    return $cql != null ? $cql->descripcion : '--';
                }
                return '';
            })
            ->rawColumns(['', 'institucion', 'institucion_leida'])
            ->make(true);
        return $datatable;
    }

    public function getDatatableAntecedentesServerSide($campo)
    {

        $data = Antecedente::where('agenda_territorial_id', $campo)
            ->where('estado', 'ACT')
            ->orderby('id', 'asc');
        $datatable = Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('', function ($row) {
                $btn = '';
                if (!Auth::user()->evaluarole(['MONITOR AGENDA TERRITORIAL'])) {
                    $btn = ' <button title="Editar" class="btn btn-primary  btn-xs" onclick="app.editarAntecedente(\'' . $row->id . '\',\'' . $row->descripcion . '\',\'' . $row->fecha_antecedente . '\')">Editar</button>';
                    $btn .= '&nbsp;<button title="Eliminar" class="btn btn-danger  btn-xs"  onclick="app.eliminarAntecedente(\'' . $row->id . '\')" ><i class="fa fa-times"></i></button>';
                }
                return $btn;
            })
            ->rawColumns([''])
            ->make(true);
        return $datatable;
    }

    public function getDatatableCorresponsableServerSide(
        $estado,
        $tabla,
        $asignaciones,
        $temporales,
        $pendientes
    ) {
        $data = AgendaTerritorial::with([
            'tipo',
            'estado',
            'estado_porcentaje',
            'latest_responsable' => function ($q) {
                $q->with(['institucion' => function ($q1) {
                    $q1->with('gabinete');
                }])->where('estado', 'ACT');
            },
            'corresponsables' => function ($q) {
                $q->with(['institucion'])->where('estado', 'ACT');
            }
        ]);
        $data = $data->where('estado', 'ACT');

        if ($estado != "data") {
            if ($tabla == "1")
                $data = $data->where('estado_id', Estado::where('abv', $estado)->get()->first()->id);
            else
                $data = $data->where('estado_porcentaje_id', EstadoPorcentaje::where('abv', $estado)->get()->first()->id);
        }
        if ($asignaciones == "true")
            $data = $data->where('monitor_id', Auth::user()->id);

        if (Auth::user()->evaluarole(['MINISTRO'])) {
            $cqlMinistro = $this->consultaInstitucionporMinistro(Auth::user()->id);
            $cqlAgendasResponsables = Corresponsable::select('agenda_territorial_id')
                ->where('institucion_corresponsable_id', $cqlMinistro)
                ->where('estado', 'ACT')
                ->pluck('agenda_territorial_id')->toArray();

            $data = $data->whereNotNull('codigo');
            $data = $data->whereIn('id', $cqlAgendasResponsables);
        } else {
            if ($temporales == "true")
                $data = $data->whereNull('codigo');
            else
                $data = $data->whereNotNull('codigo');

            if ($pendientes == "true")
                $data = $data->where('pendientes', '>', 0);
        }
        $data = $data->orderby('id', 'desc')->get();
        $url = $_SERVER["REQUEST_URI"];

        return (new CollectionDataTable($data))
            ->addIndexColumn()
            ->addColumn('', function ($row) {
                $btn = ' <button title="Editar" class="btn btn-primary  btn-xs" data-toggle="modal" data-target="#modal-default" onclick="app.editar(\'' . $row->id . '\',\'aprobado\')" data-backdrop="static" data-keyboard="false">Editar</button>';
                return $btn;
            })
            ->rawColumns([''])
            ->toJson();
    }

    public function getDatatableAgendaServerSidePOST(Request $request)
    {
        $estado = $request->estado;
        $asignaciones = $request->asignaciones;
        $filtro = $request->filtro;
        $institucion_id = $request->institucion_id_exportar;
        $fecha_inicio = $request->fecha_inicio;
        $fecha_fin = $request->fecha_fin;
        $provincia_id = $request->provincia_id_exportar;
        $ciudad_id = $request->ciudad_id_exportar;
        $canton_id = $request->canton_id_exportar;
        $estado_id_exportar = $request->estado_id_exportar;
        $buscar = $request->buscar;
        //dd($estado_id_exportar);
        $data = AgendaTerritorial::select(
            DB::RAW('CASE WHEN agenda_territorial.codigo IS NOT NULL THEN agenda_territorial.codigo ELSE agenda_territorial.id::varchar(15) END as reg_'),
            'agenda_territorial.id',
            'agenda_territorial.fecha_inicio as fecha_inicio_',
            'agenda_territorial.fecha_fin as fecha_fin_',
            'agenda_territorial.tema as nombre_',
            'agenda_territorial.descripcion as descripcion_',
            'institucion.descripcion as institucion_',
            'gabinete.descripcion as gabinete_',
            'tipo.descripcion as tipo_',
            'estado.descripcion as estado_',
            'estado_porcentaje.descripcion as estado_porcentaje_',
            'obp.numero_beneficiarios_directos as beneficiario_',
            'obp.costo_proyecto as costo_',
            'agenda_territorial.observacion as observacion_',
            'agenda_territorial.coyuntura as coyuntura_',
            'agenda_territorial.impacto as impacto_'
        )
            ->addSelect(
                [
                    'provincias' =>
                    Ubicacion::select(
                        DB::RAW("array_to_string(ARRAY_AGG(DISTINCT parametro__.descripcion),',\r\n')")
                    )
                        ->leftjoin('core.parametro_ciudad as parametro', 'parametro.id', 'ubicaciones.parametro_id')
                        ->leftjoin('core.parametro_ciudad as parametro_', 'parametro_.id', 'parametro.parametro_id')
                        ->leftjoin('core.parametro_ciudad as parametro__', 'parametro__.id', 'parametro_.parametro_id')
                        ->whereColumn('ubicaciones.agenda_territorial_id', 'agenda_territorial.id')
                        ->where('parametro.verificacion', 'PARROQUIA')
                ]
            )
            ->addSelect(
                [
                    'ciudades' =>
                    Ubicacion::select(
                        DB::RAW("array_to_string(ARRAY_AGG(DISTINCT parametro_.descripcion),',\r\n')")
                    )
                        ->leftjoin('core.parametro_ciudad as parametro', 'parametro.id', 'ubicaciones.parametro_id')
                        ->leftjoin('core.parametro_ciudad as parametro_', 'parametro_.id', 'parametro.parametro_id')
                        ->whereColumn('ubicaciones.agenda_territorial_id', 'agenda_territorial.id')
                        ->where('parametro.verificacion', 'PARROQUIA')

                ]
            )
            ->addSelect(
                [
                    'parroquias' =>
                    Ubicacion::select(
                        DB::RAW("array_to_string(ARRAY_AGG(DISTINCT parametro.descripcion),',\r\n')")
                    )
                        ->leftjoin('core.parametro_ciudad as parametro', 'parametro.id', 'ubicaciones.parametro_id')
                        ->whereColumn('ubicaciones.agenda_territorial_id', 'agenda_territorial.id')
                        ->where('parametro.verificacion', 'PARROQUIA')

                ]
            )
            ->join('sc_agenda_territorial.responsables as r', function ($join) {
                $join->on('r.agenda_territorial_id', '=', 'agenda_territorial.id')
                    ->where('r.estado', 'ACT');
            })
            ->join('sc_agenda_territorial.instituciones as institucion', 'institucion.id', 'r.institucion_id')
            ->join('sc_agenda_territorial.instituciones as gabinete', 'gabinete.id', 'institucion.institucion_id')
            ->join('sc_agenda_territorial.tipos as tipo', 'tipo.id', 'agenda_territorial.tipo_id')
            ->join('sc_agenda_territorial.estados as estado', 'estado.id', 'agenda_territorial.estado_id')
            ->join('sc_agenda_territorial.estados_porcentaje as estado_porcentaje', 'estado_porcentaje.id', 'agenda_territorial.estado_porcentaje_id')
            ->leftjoin('sc_agenda_territorial.obras_principales as obp', 'obp.agenda_territorial_id', 'agenda_territorial.id')
            ->where('agenda_territorial.estado', 'ACT');

        if (Auth::user()->evaluarole(['MINISTRO'])) {
            $cqlAgendasResponsables = Responsable::select('agenda_territorial_id')
                ->where('institucion_id', $this->consultaInstitucionporMinistro(Auth::user()->id))
                ->where('estado', 'ACT')
                ->pluck('agenda_territorial_id')->toArray();
            //  $data=$data->whereNotNull('agenda_territorial.codigo');
            $data = $data->whereIn('agenda_territorial.id', $cqlAgendasResponsables);
        } else {
            $data = $data->whereNotNull('agenda_territorial.codigo');
        }

        if ($filtro != "false") {
            if ($provincia_id != "null" && !is_null($provincia_id)) {
                if ($ciudad_id != "null" && !is_null($ciudad_id)) {
                    if ($canton_id != "null" && !is_null($canton_id)) {
                        $data = $data->where(function ($query) use ($canton_id) {
                            $query->select(DB::RAW('COUNT(u.id)'))
                                ->from('sc_agenda_territorial.ubicaciones as u')
                                ->whereColumn('agenda_territorial.id', 'agenda_territorial_id')
                                ->where('u.parametro_id', $canton_id);
                        }, '>', 0);
                    } else {

                        $dataParroquia = parametro_ciudad::select('id', 'parametro_id')
                            ->where('parametro_id', $ciudad_id)
                            ->orderby('id', 'asc')
                            ->pluck('id')
                            ->toArray();

                        $data = $data->where(function ($query) use ($dataParroquia) {
                            $query->select(DB::RAW('COUNT(u.id)'))
                                ->from('sc_agenda_territorial.ubicaciones as u')
                                ->whereColumn('agenda_territorial.id', 'u.agenda_territorial_id')
                                ->whereIn('u.parametro_id', $dataParroquia);
                        }, '>', 0);
                    }
                } else {

                    $dataNacional = parametro_ciudad::select('id', 'descripcion', 'parametro_id')
                        ->where('id', $provincia_id)->first();
                    if ($dataNacional->descripcion == 'NACIONAL' || $dataNacional->descripcion == 'EXTERIOR') {
                        $data = $data->where(function ($query) use ($dataNacional) {
                            $query->select(DB::RAW('COUNT(u.id)'))
                                ->from('sc_agenda_territorial.ubicaciones as u')
                                ->whereColumn('agenda_territorial.id', 'u.agenda_territorial_id')
                                ->where('u.parametro_id', $dataNacional->id);
                        }, '>', 0);
                    } else {
                        $dataCanton = parametro_ciudad::select('id', 'parametro_id')
                            ->where('parametro_id', $provincia_id)
                            ->orderby('id', 'asc')
                            ->pluck('id')
                            ->toArray();

                        $dataParroquia = parametro_ciudad::select('id', 'parametro_id')
                            ->whereIn('parametro_id', $dataCanton)
                            ->orderby('id', 'asc')
                            ->pluck('id')
                            ->toArray();

                        $data = $data->where(function ($query) use ($dataParroquia) {
                            $query->select(DB::RAW('COUNT(u.id)'))
                                ->from('sc_agenda_territorial.ubicaciones as u')
                                ->whereColumn('agenda_territorial.id', 'u.agenda_territorial_id')
                                ->whereIn('u.parametro_id', $dataParroquia);
                        }, '>', 0);
                    }
                }
            }
            if ($estado_id_exportar != "null" && !is_null($estado_id_exportar)) {
                //   dd($estado_id_exportar,$estado_id_exportar!="null"&&!is_null($estado_id_exportar));
                $cqlEstado = EstadoPorcentaje::where('id', $estado_id_exportar)->get()->first();
                if (!is_null($cqlEstado))
                    $data = $data->where('agenda_territorial.estado_porcentaje_id', $cqlEstado->id);
            }
            if (!Auth::user()->evaluarole(['MINISTRO'])) {
                if ($institucion_id != "null" && !is_null($institucion_id)) {
                    $arreglo = [];
                    foreach ($institucion_id as $value) {
                        if (!is_null($value))
                            array_push($arreglo, $value);
                    }
                    $cqlAgendasResponsables = Responsable::select('agenda_territorial_id')
                        ->whereIn('institucion_id', $arreglo)
                        ->where('estado', 'ACT')
                        ->pluck('agenda_territorial_id')->toArray();

                    $data = $data->whereIn('agenda_territorial.id', $cqlAgendasResponsables);
                }
            }
            $data = $data
                ->whereDate('agenda_territorial.fecha_inicio', '>=', $fecha_inicio)
                ->whereDate('agenda_territorial.fecha_inicio', '<=', $fecha_fin);
        } else {

            if ($estado != "data") {
                $cqlEstado = EstadoPorcentaje::where('abv', $estado)->get()->first();
                if (!is_null($cqlEstado))
                    $data = $data->where('agenda_territorial.estado_porcentaje_id', $cqlEstado->id);
            }
            if ($asignaciones == "true") {
                $data = $data->where('agenda_territorial.monitor_id', Auth::user()->id)
                    ->whereNotNull('agenda_territorial.codigo');
            }
        }

        if ($buscar != "null") {
            $data = $data->where(function ($query) use ($buscar) {
                $query->orwhere(DB::RAW('CASE WHEN agenda_territorial.codigo IS NOT NULL THEN agenda_territorial.codigo ELSE agenda_territorial.id::varchar(15) END'), 'LIKE', '%' . $buscar . '%')
                    ->orwhere('agenda_territorial.tema', 'LIKE', '%' . $buscar . '%')
                    //    ->orwhereDate('agenda_territorial.fecha_inicio', $buscar)
                    ->orwhere('estado.descripcion', 'LIKE', '%' . $buscar . '%');
                //    ->orwhere('estado_porcentaje.descripcion','LIKE','%'.$buscar.'%');
            });
        }
        $data = $data->orderby('agenda_territorial.id', 'desc')->distinct();

        $datatable = Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('', function ($row) {
                $html = '';
                $html .= '<table width="100%" border="0"><tr>';
                $html .= '<td style="padding:2px">';
                $html .= '<button title="Editar" class="btn btn-primary btn-block btn-xs" data-toggle="modal"
                            data-target="#modal-default" onclick="app.editar(\'' . $row->id . '\',\'aprobado\')"
                            data-backdrop="static" data-keyboard="false">Editar</button>';
                $html .= '</td>';
                $html .= '<td style="padding:2px">';
                $html .= '<button title="Imprimir" class="btn btn-info btn-block btn-xs" onclick="app.imprimirFicha(\'' . $row->id . '\')"
                           >Ficha</button>';
                $html .= '</td>';
                $html .= '</tr></table>';
                return $html;
            })
            ->rawColumns([''])
            ->make(true);
        return $datatable;
    }

    public function getDatatableAgendaServerSide(Request $request)
    {
        $estado = $request->estado;
        $tabla = $request->tabla;
        $asignaciones = $request->asignaciones;
        $temporales = $request->temporales;
        $pendientes = $request->pendientes;
        $filtro = $request->filtro;
        $institucion_id = $request->institucion_id_exportar;
        $gabinete_id = $request->gabinete_id_exportar;
        $fecha_inicio = $request->fecha_inicio;
        $fecha_fin = $request->fecha_fin;
        $buscar = $request->buscar;
        $data = AgendaTerritorial::select(
            DB::RAW('CASE WHEN agenda_territorial.codigo IS NOT NULL THEN agenda_territorial.codigo ELSE agenda_territorial.id::varchar(15) END as reg_'),
            'agenda_territorial.id as id_',
            'agenda_territorial.fecha_inicio as fecha_inicio_',
            'agenda_territorial.fecha_fin as fecha_fin_',
            'agenda_territorial.tema as nombre_',
            'institucion.descripcion as institucion_',
            'gabinete.descripcion as gabinete_',
            'tipo.descripcion as tipo_',
            'estado.descripcion as estado_',
            'estado_porcentaje.descripcion as estado_porcentaje_'
        )
            ->leftJoin('sc_agenda_territorial.responsables as r', function ($join) {
                $join->on('r.agenda_territorial_id', '=', 'agenda_territorial.id')
                    ->where('r.estado', 'ACT');
            })
            ->leftjoin('sc_agenda_territorial.instituciones as institucion', 'institucion.id', 'r.institucion_id')
            ->leftjoin('sc_agenda_territorial.instituciones as gabinete', 'gabinete.id', 'institucion.institucion_id')
            ->leftjoin('sc_agenda_territorial.tipos as tipo', 'tipo.id', 'agenda_territorial.tipo_id')
            ->leftjoin('sc_agenda_territorial.estados as estado', 'estado.id', 'agenda_territorial.estado_id')
            ->leftjoin('sc_agenda_territorial.estados_porcentaje as estado_porcentaje', 'estado_porcentaje.id', 'agenda_territorial.estado_porcentaje_id')

            ->where('agenda_territorial.estado', 'ACT');
        //   dd($data->get()->toArray());
        if ($estado != "data") {
            $data = $data->where('agenda_territorial.estado_porcentaje_id', EstadoPorcentaje::where('abv', $estado)->get()->first()->id);
        }
        if ($asignaciones == "true") {
            $data = $data->where('agenda_territorial.monitor_id', Auth::user()->id)
                ->whereNotNull('agenda_territorial.codigo');
        }

        if (Auth::user()->evaluarole(['MINISTRO'])) {
            $cqlAgendasResponsables = Responsable::select('agenda_territorial_id')
                ->where('institucion_id', $this->consultaInstitucionporMinistro(Auth::user()->id))
                ->where('estado', 'ACT')
                ->pluck('agenda_territorial_id')->toArray();

            $data = $data->whereNotNull('agenda_territorial.codigo');
            $data = $data->whereIn('agenda_territorial.id', $cqlAgendasResponsables);
        }

        if ($filtro != "false") {
            if ($institucion_id != null && $institucion_id != "null") {
                $cqlAgendasResponsables = Responsable::select('agenda_territorial_id')
                    ->where('institucion_id', $institucion_id)
                    ->where('estado', 'ACT')
                    ->pluck('agenda_territorial_id')->toArray();

                $data = $data->whereIn('agenda_territorial.id', $cqlAgendasResponsables);
            }
            if ($gabinete_id != null && $gabinete_id != "null") {
                $cqlInsituciones = Institucion::select('id')->where('institucion_id', $gabinete_id)->pluck('id')->toArray();

                $cqlAgendasResponsables = Responsable::select('agenda_territorial_id')
                    ->whereIn('institucion_id', $cqlInsituciones)
                    ->where('estado', 'ACT')
                    ->pluck('agenda_territorial_id')->toArray();

                $data = $data->whereIn('agenda_territorial.id', $cqlAgendasResponsables);
            }
            $data = $data->whereDate('agenda_territorial.fecha_inicio', '>=', $fecha_inicio)->whereDate('agenda_territorial.fecha_inicio', '<=', $fecha_fin);
        }



        $data = $data->orderby('agenda_territorial.id', 'desc')->paginate(8);

        return [
            'pagination' => [
                'total'         => $data->total(),
                'current_page'  => $data->currentPage(),
                'per_page'      => $data->perPage(),
                'last_page'     => $data->lastPage(),
                'from'          => $data->firstItem(),
                'to'            => $data->lastItem(),
            ],
            'tasks' => $data
        ];
    }

    public function getDatatableCompromisosServerSide(
        $estado,
        $tabla,
        $asignaciones,
        $temporales,
        $pendientes

    ) {

        $data = AgendaTerritorial::with([
            'tipo',
            'estado',
            'estado_porcentaje',
            'latest_responsable' => function ($q) {
                $q->with(['institucion' => function ($q1) {
                    $q1->with('gabinete');
                }])->where('estado', 'ACT');
            },
            'corresponsables' => function ($q) {
                $q->with(['institucion'])->where('estado', 'ACT');
            }
        ]);
        $data = $data->where('estado', 'ACT');

        if ($estado != "data") {
            if ($tabla == "1")
                $data = $data->where('estado_id', Estado::where('abv', $estado)->get()->first()->id);
            else
                $data = $data->where('estado_porcentaje_id', EstadoPorcentaje::where('abv', $estado)->get()->first()->id);
        }
        if ($asignaciones == "true")
            $data = $data->where('monitor_id', Auth::user()->id);

        if (Auth::user()->evaluarole(['MINISTRO'])) {
            $cqlAgendasResponsables = Responsable::select('agenda_territorial_id')
                ->where('institucion_id', $this->consultaInstitucionporMinistro(Auth::user()->id))
                ->where('estado', 'ACT')
                ->get()->pluck('agenda_territorial_id')->toArray();

            $data = $data->whereNotNull('codigo');
            $data = $data->whereIn('id', $cqlAgendasResponsables);
        } else {
            if ($temporales == "true")
                $data = $data->whereNull('codigo');
            else
                $data = $data->whereNotNull('codigo');

            if ($pendientes == "true")
                $data = $data->where('pendientes', '>', 0);
        }
        $data = $data->orderby('id', 'desc')->get();
        $url = $_SERVER["REQUEST_URI"];

        return (new CollectionDataTable($data))
            ->addIndexColumn()
            ->addColumn('', function ($row) {
                $btn = ' <button title="Editar" class="btn btn-primary  btn-xs" data-toggle="modal" data-target="#modal-default" onclick="app.editar(\'' . $row->id . '\',\'aprobado\')" data-backdrop="static" data-keyboard="false"><i class="fa fa-edit"></i></button>';
                return $btn;
            })
            ->addColumn('latest_responsable_institucion_', function ($row) {
                $btn = $row->latest_responsable != null ? $row->latest_responsable->institucion->descripcion : '--';
                return $btn;
            })
            ->addColumn('latest_responsable_gabienete_', function ($row) {
                $btn = $row->latest_responsable != null ? $row->latest_responsable->institucion->gabinete->descripcion : '--';
                return $btn;
            })
            ->rawColumns([
                '',
                'latest_responsable_institucion_',
                'latest_responsable_gabienete_'
            ])
            ->toJson();
    }

    public function getDatatableSolicitudServerSide($campo)
    {
        $roles = mhr::select('role_id')
            ->where('model_id', Auth::user()->id)
            ->pluck('role_id')
            ->toArray();

        $tipo = Tipo::select('id')
            ->whereIn('role_id_dirige', $roles)
            ->pluck('id')
            ->toArray();

        $data = Solicitud::with(['usuario', 'estado', 'tipo'])
            ->whereIn('tipo_id', $tipo);

        if ($campo != "data") {
            $estado = Estado::where('abv', $campo)->get()->first()->id;
            $data = $data->where('estado_id', $estado);
        }
        $data = $data->orderby('id', 'desc');
        $datatable = Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('', function ($row) {
                $btn = ' <label>' . $row->motivo == null ? '--' : ($row->motivo == '' ? '--' : '<label>MOTIVO:</label>' . $row->motivo . ' </label>');
                if ($row->estado->abv == 'PEN') {
                    $btn = ' <button title="Aprobar" class="btn btn-primary" onclick="app.confirmar(\'' . $row->id . '\',\'aprobado\')">Aprobado</button>';
                    $btn .= '<button title="Rechazar" class="btn btn-danger" data-toggle="modal" data-target="#modal-default" onclick="app.confirmar(\'' . $row->id . '\')" >Negado</button>';
                }
                return $btn;
            })
            ->rawColumns([''])
            ->make(true);
        return $datatable;
    }

    public function aprobar(Request $request)
    {
        $objSelect = new SelectController();

        $estado = Estado::where('abv', 'APR')->get()->first();
        $consulta = Solicitud::find($request->id);
        $consulta->estado_id = $estado->id;
        $consulta->usuario_modifica = Auth::user()->id;
        $consulta->save();
        try {
            $for = User::find($consulta->usuario_ingresa)->email;
            $msj = 'Se le notifica que se ha <strong>Aprobado</strong> su Solicitud numero: ' . $consulta->codigo . '';
            $base = '';
            if (env('NOTIFICAR'))
                $notificacion = $objSelect->NotificarSinRegistro($msj, $for);
        } catch (\Exception $ex) {
            echo $ex->getMessage();
        }
        return;
    }

    public function negar(Request $request)
    {
        $objSelect = new SelectController();

        $estado = Estado::where('abv', 'NEG')->get()->first();
        $consulta = Solicitud::find($request->id);
        $consulta->estado_id = $estado->id;
        $consulta->motivo = $request->motivo;
        $consulta->save();

        try {
            $for = User::find($consulta->usuario_ingresa)->email;
            $msj = 'Se le notifica que se ha <strong>Negado</strong> su Solicitud numero: ' . $consulta->codigo . '';
            $base = $request->motivo;
            if (env('NOTIFICAR'))
                $notificacion = $objSelect->NotificarSinRegistro($msj, $for);
        } catch (\Exception $ex) {
            echo $ex->getMessage();
        }

        return;
    }

    public function cargarInstituciones(Request $request)
    {
        $consulta = Institucion::select('id', 'descripcion')->where('institucion_id', $request->id)->pluck('descripcion', 'id');

        $array_response['status'] = 200;
        $array_response['datos'] = $consulta;

        return response()->json($array_response, 200);
    }

    public function editaPeriodo(Request $request)
    {
        $consulta = Periodo::where('id', $request->id)->get()->first();

        $array_response['status'] = 200;
        $array_response['datos'] = $consulta;

        return response()->json($array_response, 200);
    }

    public function guardarPeriodo(Request $request)
    {
        $consulta = Periodo::find($request->id);
        $consulta->fill($request->all())->save();
        $consulta->estado = 'ACT';
        $consulta->save();

        $cqlx1 = Periodo::where('numero', $consulta->numero + 1)
            ->where('objetivo_id', $consulta->objetivo_id)
            ->get()->first();

        if ($cqlx1 != null) {
            $cqlx1->valor_anterior_meta_acumulada = $consulta->meta_acumulada;
            $cqlx1->valor_anterior_cumplimiento_acumulado = $consulta->cumplimiento_acumulado;
            $cqlx1->save();

            $cql1 = Periodo::where('numero', '>', $consulta->numero)
                ->where('objetivo_id', $consulta->objetivo_id)
                ->where('estado', 'ACT')
                ->orderby('numero', 'asc')
                ->get()->toArray();

            $meta_acumulado = $consulta->meta_acumulada;
            $cumplimiento_acumulado = $consulta->cumplimiento_acumulado;

            foreach ($cql1 as $cql) {
                $cql = Periodo::find($cql["id"]);
                $cql->valor_anterior_meta_acumulada = $meta_acumulado;
                $cql->valor_anterior_cumplimiento_acumulado = $cumplimiento_acumulado;

                $cql->meta_acumulada = $meta_acumulado + $cql->meta_periodo;
                $cql->cumplimiento_acumulado = $cumplimiento_acumulado + $cql->cumplimiento_periodo;

                $cql->pendiente_periodo = $cql->meta_periodo - $cql->cumplimiento_periodo;
                $cql->pendiente_acumulado = $cql->meta_acumulada - $cql->cumplimiento_acumulado;
                $cql->save();

                $meta_acumulado = $cql->meta_acumulada;
                $cumplimiento_acumulado = $cql->cumplimiento_acumulado;
            }
        }


        $array_response['status'] = 200;
        $array_response['datos'] = $request->objetivo_id;

        return response()->json($array_response, 200);
    }

    protected function imprimirFicha(Request $request)
    {
        $cqlConsulta = AgendaTerritorial::select(
            'est.descripcion as prioridad',
            'agenda_territorial.tema as tema',
            'agenda_territorial.objetivo as objetivo',
            'agenda_territorial.antecedente as antecedente',
            'agenda_territorial.justificacion as justificacion',
            'agenda_territorial.descripcion as descripcion',
            'agenda_territorial.fecha_inicio as fecha_sugerida',
            'agenda_territorial.lugar as lugar',
            'agenda_territorial.duracion as duracion',

        )
            ->join('sc_agenda_territorial.estados as est', 'est.id', 'agenda_territorial.estado_id')
            ->where('agenda_territorial.id', $request->id)->first();

        $array_response['status'] = "200";
        $base_url = 'storage/FORMATOS/';
        $base_url_entrega = 'storage/AGENDA_TERRITORIAL_GENERADAS/';
        $url_ = env('APP_URL');
        $url = $base_url . 'FICHA_AUDIENCIA_REUNION_AGENDA_TERRITORIAL.xlsx';
        $reader = IOFactory::createReader("Xlsx");
        $spread = $reader->load($url);
        $sheet = $spread->getActiveSheet();
        $writer = IOFactory::createWriter($spread, 'Xlsx');
        try {
            $sheet = $spread->getActiveSheet();
            $writer = IOFactory::createWriter($spread, 'Xlsx');
            $sheet->setCellValue("C3", $cqlConsulta->tema);
            $sheet->setCellValue("C4", $cqlConsulta->objetivo);
            $sheet->setCellValue("C5", $cqlConsulta->antecedente);
            $sheet->setCellValue("C6", $cqlConsulta->justificacion);
            $sheet->setCellValue("C7", $cqlConsulta->prioridad);
            $sheet->setCellValue("C8", $cqlConsulta->descripcion);
            $sheet->setCellValue("C17", $cqlConsulta->fecha_sugerida);
            $sheet->setCellValue("C18", $cqlConsulta->duracion);
            $sheet->setCellValue("C19", $cqlConsulta->lugar);
            $nombre_entrega = "FICHA_AUDIENCIA_REUNION_GENERADA.xlsx";
            $url_entrega = $base_url_entrega . $nombre_entrega;
            $writer->save($url_entrega);
        } catch (\Exception $e) {
            $array_response['status'] = 300;
            $array_response['message'] = "Error al intentar crear el documento";
            $array_response['url_base'] = $url_;
            $array_response['nombre'] = '';
        }
        $array_response['status'] = 200;
        $array_response['message'] = $url_entrega;
        $array_response['url_base'] = $url_;
        $array_response['nombre'] = $nombre_entrega;
        return response()->json($array_response, 200);
    }

    protected function consultaInstitucionporMinistro($usuario_id)
    {
        $cqlMinistro = Institucion::where('ministro_usuario_id', $usuario_id)->first();
        $cqlMinistro = $cqlMinistro != null ? $cqlMinistro->id : 0;
        return $cqlMinistro;
    }
    /* AGREGADO POR ADMINISTRACION DE USUARIOS*/
    protected function consultaMinistroporUsuario($ministro_usuario_id)
    {
        $cqlMinistro = User::select('id', 'nombres')->where('id', $ministro_usuario_id)->first();
        return $cqlMinistro;
    }
    /* FIN AGREGADO POR ADMINISTRACION DE USUARIOS*/
}
