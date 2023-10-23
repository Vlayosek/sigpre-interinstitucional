<?php

namespace App\Http\Controllers\Admin;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Datatables;
use App\Core\Entities\Admin\Auditoria;
use App\Core\Entities\Admin\Actividades;
use App\Core\Entities\Admin\Logs;
use App\Core\Entities\Admin\AsignacionFuncionario;
use Illuminate\Support\Arr;
use PDF;

use App\Core\Entities\Admision\mdlFiliacion;
use App\Core\Entities\Admision\mdlPersona;
use App\Core\Entities\Admin\Agenda;
use App\Core\Entities\Admin\Auditable;
use App\Core\Entities\Admin\LogsMongo;

class AuditoriaController extends Controller
{
    public function logs()
    {
        return view('admin.logs.core');
    }
    public function logsModulos()
    {
        //  $modelos=Auditable::select()
        return view('admin.logs.modulos');
    }

    public function getDatatableRegistroRolesServerSide()
    {
        $array = AsignacionFuncionario::orderby('fecha_inserta', 'desc');

        $datatable = Datatables::of($array)
            ->make(true);

        return $datatable;
    }
    public function getDatatableLogsModulosServerSide($fecha_inicio, $fecha_fin)
    {
        // dd($fecha_inicio,$fecha_fin);
        $array = Auditable::whereDate('created_at', '>=', $fecha_inicio)
            ->whereDate('created_at', '<=', $fecha_fin)
            ->orderby('created_at', 'desc');

        $datatable = Datatables::of($array)
            ->addIndexColumn()
            ->make(true);

        return $datatable;
    }
    public function getDatatableLogsServerSide($fecha_inicio, $fecha_fin)
    {
        // dd($fecha_inicio,$fecha_fin);
        $array = Logs::whereDate('fecha_inserta', '>=', $fecha_inicio)
            ->whereDate('fecha_inserta', '<=', $fecha_fin)
            ->orderby('fecha_inserta', 'desc');

        $datatable = Datatables::of($array)
            ->addIndexColumn()
            ->addColumn('fecha', function ($row) {
                return $row->fecha_inserta;
            })
            ->addColumn('nombre', function ($row) {
                if ($row->nombreCompleto == ""||is_null($row->nombreCompleto))
                    return "";

                $btn = str_replace("CN=", "", explode(",", $row->nombreCompleto)[0]);
               return $btn == ""||is_null($btn)?$row->nombreCompleto:$btn;
            })
            ->addColumn('cargo', function ($row) {
                if ($row->nombreCompleto == ""||is_null($row->nombreCompleto))
                    return "";

                $btn = str_replace("CARGO=", "", explode(",", $row->nombreCompleto)[1]);
                return $btn == ""||is_null($btn)?$row->nombreCompleto:$btn;
            })
            ->addColumn('cedula', function ($row) {
                if ($row->nombreCompleto == ""||is_null($row->nombreCompleto))
                    return "";

                $btn = str_replace("CEDULA=", "", explode(",", $row->nombreCompleto)[2]);
                return $btn == ""||is_null($btn)?$row->nombreCompleto:$btn;
            })
            ->rawColumns(['nombre', 'cargo', 'cedula', 'fecha'])
            ->make(true);

        return $datatable;
    }
    public function consultarLocalAgenda()
    {
        $cUser = Auth::user();
        $director = $cUser->evaluarole(['DIRECTOR']);
        if ($director == true) {
            $csql = DB::connection('pgsql_presidencia_tthh')
                ->select(
                    "SELECT CONCAT(p.prs_primerapellido,' ',p.prs_segundoapellido,' ',p.prs_primernombre,' ',p.prs_segundonombre) as servidor,
            f.rgf_correoinstitucional,
            c.crg_descripcion as cargo,
            f.rgf_id as id
            FROM rhumanos_persona p, rhumanos_regfiliacion f, catalogo_seccion s, catalogo_cargo c, catalogo_tipoempleado t
            WHERE p.prs_id=f.rgf_persona 
            AND s.scc_id=f.rgf_seccion 
            AND c.crg_id=f.rgf_cargo 
            AND t.tpm_id=f.rgf_tipoempleado 
            AND f.rgf_estado=? 
            AND s.scc_id 
            IN (SELECT rgf_seccion FROM rhumanos_regfiliacion WHERE rgf_correoinstitucional=?)",
                    ['A', Auth::user()->email]
                );
        } else {
            $csql = DB::connection('pgsql_presidencia_tthh')
                ->select(
                    "SELECT 
            CONCAT(p.prs_primerapellido,
            ' ',p.prs_segundoapellido,
            ' ',p.prs_primernombre,
            ' ',p.prs_segundonombre) as servidor,
            f.rgf_correoinstitucional,
            c.crg_descripcion as cargo,
            f.rgf_id as id
            FROM rhumanos_persona p, rhumanos_regfiliacion f, catalogo_seccion s, catalogo_cargo c, catalogo_tipoempleado t
            WHERE p.prs_id=f.rgf_persona 
            AND s.scc_id=f.rgf_seccion 
            AND c.crg_id=f.rgf_cargo 
            AND t.tpm_id=f.rgf_tipoempleado 
            AND f.rgf_estado=? 
            AND f.rgf_correoinstitucional=?
            ",
                    ['A', Auth::user()->email]
                );
        }
        $personal = Arr::pluck($csql, 'rgf_correoinstitucional');

        $csql = User::whereIn('email', $personal)
            ->where('estado', 'A')
            ->pluck('id');

        $consulta = Agenda::with([
            'usuario',
        ]);
        $consulta = $consulta->whereIn('usuario_ingresa', $csql);
        if ($director == false)
            $consulta = $consulta->orwhereIn('dirigido_id', $csql);

        return  $consulta = $consulta->get();
    }
    public function consultaAgenda(request $request)
    {

        if ($request->tipo != 'CONSULTA') {
            $cs = Agenda::find($request->id);
            $cs->duracion = $request->duracion;
            $cs->cumplida = $request->cumplida;
            $cs->dirigido_id = $request->dirigido_id;
            $cs->fecha = $request->fecha;
            $cs->hora = $request->hora;
            $cs->horaf = $request->horaf;
            $cs->descripcion = $request->descripcion;
            if ($request->cumplida == false)
                $cs->color = $request->color;
            $cs->save();
        }
        $consulta = $this->consultarLocalAgenda();


        $array_response['status'] = "200";
        $array_response['message'] = $consulta;
        return response()->json($array_response, 200);
    }
    public function agregarAgenda(request $request)
    {

        $consulta = new Agenda();
        $consulta->descripcion = $request->descripcion;
        $consulta->dirigido_id = $request->dirigido_id;
        $consulta->dirigido_text = $request->dirigido_text;
        $consulta->duracion = $request->duracion;
        $consulta->fecha = $request->fecha;
        $consulta->hora = $request->hora != null ? $request->hora : '08:00:00';
        $consulta->horaf = $request->horaf != null ? $request->horaf : '23:59:00';
        $consulta->usuario_ingresa = Auth::user()->id;
        $consulta->color = $request->color;
        $consulta->correo = Auth::user()->email;
        $consulta->cumplida = $request->cumplida;
        $consulta->save();
        $csql = $this->consultarLocalAgenda();
        $array_response['status'] = $consulta->id;
        $array_response['message'] = $csql;

        return response()->json($array_response, 200);
    }

    public function consultaEmpleado(request $request)
    {
        $cUser = Auth::user();
        $director = $cUser->evaluarole(['DIRECTOR']);
        if ($director == true) {
            $csql = DB::connection('pgsql_presidencia_tthh')
                ->select(
                    "SELECT CONCAT(p.prs_primerapellido,' ',p.prs_segundoapellido,' ',p.prs_primernombre,' ',p.prs_segundonombre) as servidor,
            f.rgf_correoinstitucional,
            c.crg_descripcion as cargo,
            f.rgf_id as id
            FROM rhumanos_persona p, rhumanos_regfiliacion f, catalogo_seccion s, catalogo_cargo c, catalogo_tipoempleado t
            WHERE p.prs_id=f.rgf_persona 
            AND s.scc_id=f.rgf_seccion 
            AND c.crg_id=f.rgf_cargo 
            AND t.tpm_id=f.rgf_tipoempleado 
            AND f.rgf_estado=? 
            AND s.scc_id 
            IN (SELECT rgf_seccion FROM rhumanos_regfiliacion WHERE rgf_correoinstitucional=?)",
                    ['A', Auth::user()->email]
                );
        } else {
            $csql = DB::connection('pgsql_presidencia_tthh')
                ->select(
                    "SELECT 
            CONCAT(p.prs_primerapellido,
            ' ',p.prs_segundoapellido,
            ' ',p.prs_primernombre,
            ' ',p.prs_segundonombre) as servidor,
            f.rgf_correoinstitucional,
            c.crg_descripcion as cargo,
            f.rgf_id as id
            FROM rhumanos_persona p, rhumanos_regfiliacion f, catalogo_seccion s, catalogo_cargo c, catalogo_tipoempleado t
            WHERE p.prs_id=f.rgf_persona 
            AND s.scc_id=f.rgf_seccion 
            AND c.crg_id=f.rgf_cargo 
            AND t.tpm_id=f.rgf_tipoempleado 
            AND f.rgf_estado=? 
            AND f.rgf_correoinstitucional=?
            ",
                    ['A', Auth::user()->email]
                );
        }
        $personal = Arr::pluck($csql, 'rgf_correoinstitucional');
        $csql = User::whereIn('email', $personal)
            ->where('estado', 'A')
            ->get();

        $array_response['status'] = '200';
        $array_response['message'] = $csql;
        return response()->json($array_response, 200);
    }
    public function eliminaAgenda(request $request)
    {

        $consulta = Agenda::find($request->id);
        if ($consulta->usuario_ingresa == Auth::user()->id) {
            $consulta->estado = "I";
            $consulta->save();
            $csql = $this->consultarLocalAgenda();
            $array_response['status'] = '200';
            $array_response['message'] = $csql;
        } else {
            $array_response['status'] = '300';
            $array_response['message'] = 'No es el dueño de la actividad';
        }
        return response()->json($array_response, 200);
    }


    public function actualizarAgenda(request $request)
    {
        DB::beginTransaction();

        try {
            $consulta = Agenda::find($request->id);
            $consulta->fecha = $request->fecha;
            $consulta->hora = $request->hora;
            $consulta->horaf = $request->horaf;
            $consulta->save();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            $array_response['status'] = '300';
            $array_response['message'] = 'Error al grabar';
        }
        $csql = $this->consultarLocalAgenda();

        $array_response['status'] = '200';
        $array_response['message'] = $csql;


        return response()->json($array_response, 200);
    }
    public function indexAgenda()
    {

        $paciente = User::where('id', '!=', Auth::user()->id)
            ->where('estado', 'A')->get();

        return view('modules.Auditoria.agenda', compact('paciente'));
    }
    public function ChatIndex()
    {
        $users = User::where('id', '!=', Auth::user()->id)
            ->where('estado', 'A')->get();
        //     return view('home', compact('users'));
        return view('modules.Auditoria.chat', compact('users'));
    }

    public function getDatatableUsuariosAudita(request $request)
    {

        $cUser = Auth::user();
        $director = $cUser->evaluarole(['DIRECTOR']);
        if ($director == true) {
            $personal = DB::connection('pgsql_presidencia_tthh')

                ->select(
                    "SELECT f.rgf_correoinstitucional
            FROM rhumanos_persona p, 
            rhumanos_regfiliacion f, 
            catalogo_seccion s, 
            catalogo_cargo c, 
            catalogo_tipoempleado t
            WHERE p.prs_id=f.rgf_persona 
            AND s.scc_id=f.rgf_seccion 
            AND c.crg_id=f.rgf_cargo 
            AND t.tpm_id=f.rgf_tipoempleado 
            AND f.rgf_estado=? 
            AND NOT c.crg_descripcion=?
            AND s.scc_id 
            IN (SELECT rgf_seccion FROM rhumanos_regfiliacion WHERE rgf_correoinstitucional=?)",
                    ['A', 'Directores', $cUser->email]
                );
            $personal = Arr::pluck($personal, 'rgf_correoinstitucional');
            $csql = User::with([
                'personaTalento' => function ($q) {
                    $q->with([
                        'tipoEmpleado',
                        'cargo',
                        'seccion',
                        'persona',
                        'usuario',
                    ])->where('rgf_estado', 'A');
                }
            ])
                ->whereIn('email', $personal)
                ->where('estado', 'A')
                ->get();
        } else {
            $csql = User::with([
                'personaTalento' => function ($q) {
                    $q->with([
                        'tipoEmpleado',
                        'cargo',
                        'seccion',
                        'persona',
                        'usuario',
                    ])->where('rgf_estado', 'A');
                }
            ])
                ->where('estado', 'A')
                ->get();
        }

        $array_response['status'] = 200;
        $array_response['message'] = $csql;
        return response()->json($array_response, 200);
    }
    public function index()
    {
        return view('modules.Auditoria.index');
    }
    public function DescargarInforme($id, $inicio, $fin)
    {

        $cUser = User::find($id);
        if ($cUser != null) {
            $consulta1 = mdlFiliacion::with([
                'persona',
                'lugarTrabajo',
                'cargo',
                'seccion' => function ($q) {
                    $q->with('area');
                }
            ])->where('rgf_correoinstitucional', $cUser->email)
                ->where('rgf_estado', 'A')->get()->first();

            $cedula = 'No tiene Filiacion';
            $nc = 'No tiene Filiacion';
            $cargo = 'No tiene Filiacion';
            $area = 'No tiene Filiacion';
            $ciudad = 'No tiene Filiacion';
            $jservidor = '';
            $jcargo = '';
            $jservidorCorreo = '';

            if ($consulta1 != null) {
                $cedula = $consulta1->persona->prs_didentidad;
                $nc = $consulta1->persona->prs_primerapellido . ' ' . $consulta1->persona->prs_segundoapellido . ' ' . $consulta1->persona->prs_primernombre . ' ' . $consulta1->persona->prs_segundonombre;
                $cargo = $consulta1->cargo->crg_descripcion;
                $area = $consulta1->seccion->area->are_descripcion;
                $ciudad = $consulta1->lugarTrabajo->lgr_descripcion;
            }
            $csql = DB::connection('pgsql_presidencia_tthh')
                ->select(
                    "SELECT CONCAT(p.prs_primerapellido,' ',p.prs_segundoapellido,' ',p.prs_primernombre,' ',p.prs_segundonombre) as servidor,f.rgf_correoinstitucional,c.crg_descripcion as cargo
                FROM rhumanos_persona p, rhumanos_regfiliacion f, catalogo_seccion s, catalogo_cargo c, catalogo_tipoempleado t
                WHERE p.prs_id=f.rgf_persona 
                AND s.scc_id=f.rgf_seccion 
                AND c.crg_id=f.rgf_cargo 
                AND t.tpm_id=f.rgf_tipoempleado 
                AND f.rgf_estado=? 
                AND c.crg_descripcion=?
                AND s.scc_id 
                IN (SELECT rgf_seccion FROM rhumanos_regfiliacion WHERE rgf_correoinstitucional=?)",
                    ['A', 'Directores', $cUser->email]
                );

            if (count($csql) > 0) {
                $jservidor = $csql[0]->servidor;
                $jcargo = $csql[0]->cargo;
                $jservidorCorreo = $csql[0]->rgf_correoinstitucional;
            }
            $fecha = date("Y-m-d");
            $url = env('APP_URL');
            $url = $url . '/auditoria/DescargarInforme' . '/' . $id . '/' . $inicio . '/' . $fin;
            $codigoQR = \QrCode::format('png')->size(150)->generate($url);
            $hoy = date("Y-m-d H:i:s");
            $last = null;
            $html = '';
            $start = $inicio;
            $end = $fin;
            $range = array();
            if (is_string($start) === true) $start = strtotime($start);
            if (is_string($end) === true) $end = strtotime($end);

            do {
                $range[] = date('Y-m-d', $start);
                $start = strtotime("+ 1 day", $start);
            } while ($start <= $end);

            $actividades = Agenda::where('dirigido_id', $cUser->id)
                ->whereIn('fecha', $range)
                ->orderby('fecha', 'DESC')
                ->get()->toArray();
            if (count($actividades) > 0) {
                foreach ($actividades as $actividad) {
                    if ($last != $actividad["fecha"]) {
                        if ($last != null) {
                            $html .= '</td>';
                            $html .= '</tr>';
                        }
                        $html .= '<tr>';
                        $last = $actividad["fecha"];
                        $html .= '<td width="25%" class="tablaactividad">';
                        $html .= explode(" ", $actividad["fecha"])[0];
                        $html .= '</td>';
                        $html .= '<td width="75%" class="tablaactividad">';
                    }
                    $html .= '<p><strong>' . (explode(":", $actividad["hora"])[0] . ':' . explode(":", $actividad["hora"])[1]) . '</strong>&nbsp;' . $actividad["descripcion"] . '<strong style="float:right;font-style: italic;' . ($actividad["cumplida"] == "true" ? "color:green" : "color:#b7b7b7") . '">' . ($actividad["cumplida"] == "true" ? "Cumplida" : "No Cumplida") . '</strong></p>';
                }

                $html .= '</td>';
                $html .= '</tr>';
            } else {
                $html .= '<tr>';
                $html .= '<td colspan="2" class="tablaactividad">';
                $html .= 'No se han encontrado Actividades Registradas';
                $html .= '</td>';
                $html .= '</tr>';
            }
        } else {
            $cedula = 'No tiene Filiacion';
            $nc = 'No tiene Filiacion';
            $cargo = 'No tiene Filiacion';
            $area = 'No tiene Filiacion';
            $ciudad = 'No tiene Filiacion';
            $jservidor = '';
            $jcargo = '';
            $jservidorCorreo = '';
            $hoy = date("Y-m-d");
            $url = env('APP_URL');
            $url = $url . '/auditoria/DescargarInforme' . '/' . $id . '/' . $inicio . '/' . $fin;
            $codigoQR = \QrCode::format('png')->size(150)->generate($url);
            $html = '';
            $html .= '<tr>';
            $html .= '<td colspan="2" class="tablaactividad">';
            $html .= 'No se han encontrado Actividades Registradas';
            $html .= '</td>';
            $html .= '</tr>';
        }



        $pdf = PDF::loadView('frontend/solicitudInforme', [
            'fecha' => $hoy,
            'nc' => $nc,
            'inicio' => $inicio,
            'fin' => $fin,
            'cargo' => $cargo,
            'cedula' => $cedula,
            'area' => $area,
            'ciudad' => $ciudad,
            'jservidor' => $jservidor,
            'jcargo' => $jcargo,
            'jservidorCorreo' => $jservidorCorreo,
            'html' => $html,
            'codigoQR' => $codigoQR
        ]);

        return $pdf->stream('INFORME_ACTIVIDADES_' . $id . '.pdf');
    }

    public function auditoria(request $request)
    {

        $correo = Auth::user()->email;
        $fil = mdlFiliacion::where([
            'rgf_correoinstitucional' => $correo,
            'rgf_estado' => 'A'
        ])->get()->first();
        $fil = $fil != null ? $fil->rgf_persona : 0;
        $ced = mdlPersona::where([
            'prs_id' => $fil,
            'prs_estado' => 'A'
        ])->get()->first();

        $ced = $ced != null ? $ced->prs_didentidad : "0";
        $idUser = 0;

        $user = Auth::user()->id;
        $hoy = date("Y-m-d H:i:s");
        $hoyFecha = date("Y-m-d");
        if ($request->tipo != 'CONSULTA' && $request->tipo != 'AGREGAR' && $request->tipo != 'ELIMINAR') {
            $conteoEstadoI = Auditoria::whereIn('tipo', ['INI'])
                ->where('fecha_creada', $hoyFecha)
                ->where('usuario_ingresa', $user)
                ->get()->first();
            $conteoEstadoIPA = Auditoria::whereIn('tipo', ['IPA'])
                ->where('fecha_creada', $hoyFecha)
                ->where('usuario_ingresa', $user)

                ->get()->first();

            $conteoEstado = null;
            $error = "0";
            switch ($request->tipo) {
                case 'INI':
                    $conteoEstado = Auditoria::whereIn('tipo', ['INI', 'IPA', 'FPA', 'FIN'])
                        ->where('fecha_creada', $hoyFecha)
                        ->where('usuario_ingresa', $user)

                        ->get()->first();
                    break;
                case 'IPA':
                    if ($conteoEstadoI != null) {
                        $conteoEstado = Auditoria::whereIn('tipo', ['IPA', 'FPA', 'FIN'])
                            ->where('fecha_creada', $hoyFecha)
                            ->where('usuario_ingresa', $user)

                            ->get()->first();
                    } else {
                        $error = 'Falta el registro de inicio de jornada';
                    }

                    break;
                case 'FPA':
                    if ($conteoEstadoIPA != null) {
                        $conteoEstado = Auditoria::whereIn('tipo', ['FPA', 'FIN'])
                            ->where('fecha_creada', $hoyFecha)
                            ->where('usuario_ingresa', $user)

                            ->get()->first();
                    } else {
                        $error = 'Falta el registro de inicio de Parada';
                    }
                    break;
                case 'FIN':
                    if ($conteoEstadoI != null) {
                        $conteoEstado = Auditoria::whereIn('tipo', ['FIN'])
                            ->where('fecha_creada', $hoyFecha)
                            ->where('usuario_ingresa', $user)
                            ->get()->first();
                    } else {
                        $error = 'Falta el registro de inicio de jornada';
                    }
                    break;
            }

            if ($conteoEstado == null && strlen($error) == 1) {
                $consulta = new Auditoria();
                $consulta->tipo = $request->tipo;
                $consulta->usuario_ingresa = Auth::user()->id;
                $consulta->created_at = $hoy;
                $consulta->RecordTime = $hoy;
                $consulta->CreatedDate = $hoy;
                $consulta->CreatedBy = $idUser;
                $consulta->IdUser = $idUser;
                $consulta->fecha_creada = $hoyFecha;
                $consulta->sistema = 'TELETRABAJO';
                $consulta->save();
            } else {
                $array_response['status'] = 300;
                if ($error != "0")
                    $array_response['message'] = $error;
                else
                    $array_response['message'] = 'Ya existe un registro';

                return response()->json($array_response, 200);
            }
        } else {
            if ($request->tipo == 'AGREGAR') {
                $consulta = new Actividades();
                $consulta->descripcion = $request->descripcion;
                $consulta->usuario_ingresa = Auth::user()->id;
                $consulta->created_at = $request->fecha;
                $consulta->save();
            }
            if ($request->tipo == 'ELIMINAR') {
                $consulta = Actividades::find($request->id);
                $consulta->delete();
            }
        }

        $datetime1 = date_create($request->fecha_inicio); //fecha actual
        $datetime2 = date_create($request->fecha_fin); //fecha de db
        $interval = date_diff($datetime2, $datetime1, false);
        $newDate = date("d/m/Y", strtotime($request->fecha_inicio));
        $newDateD = date("d/m/Y", strtotime($request->fecha_fin));
        $d = 0;
        $dias = intval($interval->format('%R%a'));
        $consulta2 = [];
        if (($dias < 0) || ($dias == 0)) {
            $start = $request->fecha_inicio;
            $end = $request->fecha_fin;
            $range = array();
            if (is_string($start) === true) $start = strtotime($start);
            if (is_string($end) === true) $end = strtotime($end);

            do {
                $range[] = date('Y-m-d', $start);
                $start = strtotime("+ 1 day", $start);
            } while ($start <= $end);
            $consulta2 = User::with([
                'auditoria' => function ($q) use ($range) {
                    $q
                        ->whereIn('fecha_creada', $range)
                        ->orderby('fecha_creada', 'DESC');
                },
                'actividades'
            ]);
            if ($request->tipo == 'ELIMINAR')
                $consulta2 = $consulta2->where('id', Auth::user()->id);
            else
                $consulta2 = $consulta2->where('id', $request->id == 0 ? Auth::user()->id : $request->id);

            $consulta2 = $consulta2->get()->toArray();
        } else if ($dias > 0) {
            $d = 1;
        }
        if ($d == 0) {
            $array_response['status'] = 200;
            $array_response['message'] = 'Grabado Exitosamente';
        } else {
            $array_response['status'] = 404;
            $array_response['message'] = "La Fecha es menor a la fecha Inicial";
        }

        $array_response['data'] = $consulta2;
        return response()->json($array_response, 200);
    }
    public function apiConsultaMarcacion($correo, $start1, $fin1)
    {

        $date = new \DateTime();

        $start = str_replace("_", "/", $start1);
        $end = str_replace("_", "/", $fin1);

        $range = array();
        if (is_string($start) === true) $start = strtotime($start);
        if (is_string($end) === true) $end = strtotime($end);

        do {
            $range[] = date('Y-m-d', $start);
            $start = strtotime("+ 1 day", $start);
        } while ($start <= $end);
        $d = date_create($start1);
        $df = date_create($fin1);
        $fil = mdlFiliacion::where([
            'rgf_correoinstitucional' => $correo,
            'rgf_estado' => 'A'
        ])->get()->first();

        $idFiliacion = $fil != null ? $fil->rgf_id : 0;
        $seccion = $fil != null ? $fil->seccion : "";
        $fil = $fil != null ? $fil->rgf_persona : 0;
        if ($seccion != "") {
            $seccion = $seccion != null ? $seccion->scc_descripcion : "";
        }
        $ced = mdlPersona::where([
            'prs_id' => $fil,
            'prs_estado' => 'A'
        ])->get()->first();

        $ced = $ced != null ? $ced->prs_didentidad : "0";
        $cc = [];
        if ($ced != 0) {
            $cc = DB::connection('sqlsrv_marcaciones')
                ->table('dbo.User as u')
                ->leftjoin('dbo.Record as r', 'u.IdUser', 'r.IdUser')
                ->select(
                    'sistema',
                    DB::raw('CONVERT(date, r.RecordTime, 103) as Fecha'),
                    DB::raw("(CASE DATENAME(dw,r.RecordTime)
                    when 'Monday' then 'LUNES'
                    when 'Tuesday' then 'MARTES'
                    when 'Wednesday' then 'MIERCOLES'
                    when 'Thursday' then 'JUEVES'
                    when 'Friday' then 'VIERNES'
                    when 'Saturday' then 'SABADO'
                    when 'Sunday' then 'DOMINGO'
                  END) as Dia"),
                    'r.RecordTime as marca'
                )
                ->where('u.IdentificationNumber', $ced)
                ->whereIn(DB::raw('CONVERT(date, r.RecordTime, 103)'), $range)
                ->get()->toArray();
        }
        $array_response['data'] = $cc;
        return response()->json($array_response, 200);
    }

    public function apiConsultaMarcacionReporte($area, $start1, $fin1, $filiaRequest)
    {

        $date = new \DateTime();

        $start = str_replace("_", "/", $start1);
        $end = str_replace("_", "/", $fin1);

        $range = array();
        if (is_string($start) === true) $start = strtotime($start);
        if (is_string($end) === true) $end = strtotime($end);

        do {
            $range[] = date('Y-m-d', $start);
            $start = strtotime("+ 1 day", $start);
        } while ($start <= $end);
        $d = date_create($start1);
        $df = date_create($fin1);
        if ($filiaRequest != 0) {
            $fil = mdlFiliacion::select('rgf_persona')->where([
                'rgf_id' => $filiaRequest,
                'rgf_estado' => 'A'
            ])->pluck('rgf_persona')->toArray();
        } else {
            $fil = mdlFiliacion::select('rgf_persona')->where([
                'rgf_seccion' => $area,
                'rgf_estado' => 'A'
            ])->pluck('rgf_persona')->toArray();
        }


        $ced = mdlPersona::select('prs_didentidad')
            ->where([
                'prs_estado' => 'A'
            ])
            ->whereIn('prs_id', $fil)->pluck('prs_didentidad')->toArray();
        $cc = [];
        if ($ced != []) {
            $cc = DB::connection('sqlsrv_marcaciones')
                ->table('dbo.User as u')
                ->leftjoin('dbo.Record as r', 'u.IdUser', 'r.IdUser')
                ->select(
                    'sistema',
                    DB::raw('CONVERT(date, r.RecordTime, 103) as Fecha'),
                    DB::raw("(CASE DATENAME(dw,r.RecordTime)
                        when 'Monday' then 'LUNES'
                        when 'Tuesday' then 'MARTES'
                        when 'Wednesday' then 'MIERCOLES'
                        when 'Thursday' then 'JUEVES'
                        when 'Friday' then 'VIERNES'
                        when 'Saturday' then 'SABADO'
                        when 'Sunday' then 'DOMINGO'
                      END) as Dia"),
                    'r.RecordTime as marca',
                    'u.IdentificationNumber as id'
                )
                ->whereIn('u.IdentificationNumber', $ced)
                ->whereIn(DB::raw('CONVERT(date, r.RecordTime, 103)'), $range)
                ->orderby('r.RecordTime', 'asc')
                ->orderby('u.IdentificationNumber', 'asc')
                ->get()->toArray();
        }


        $array_response['data'] = $cc;
        return response()->json($array_response, 200);
    }
    public function verificarCambios($request, $consulta,$excepciones = [])
    {
     
        $arregloObservacionAnterior = [];
        $arregloObservacionActual = [];
        $arregloObservacion = [];
       // $tabla=$consulta->table;
        if ($request['id'] != 0) {
            $excepcionAuditoria = ['id', 'eliminado', 'fecha_modifica', 'fecha_inserta', 'usuario_inserta', 'usuario_modifica'];
            $request = $request->except(array_merge($excepcionAuditoria, $excepciones));
            foreach ($request as $key => $value) {
                // dd($consulta->attributes,isset($consulta->{$key}));
                if (isset($consulta->{$key}) && !is_array($value)) {
                    if ($consulta[$key] != $value) {
                        $obj = new \stdClass();
                        $obj->campo = $key;
                        $obj->valor = $consulta[$key];
                        array_push($arregloObservacionAnterior, $obj);

                        $obj = new \stdClass();
                        $obj->campo = $key;
                        $obj->valor = $value;
                        array_push($arregloObservacionActual, $obj);
                    }
                }
            }
        }
        $arregloObservacion['anterior'] = $arregloObservacionAnterior;
        $arregloObservacion['actual'] = $arregloObservacionActual;
        return $arregloObservacion;
    }
    public function guardarLogs(
        $actual,
        $anterior,
        $modulo='CORE',
        $tabla='CORE',
        $eliminado = false,
        $excepciones = []
    ) {
        $id=$anterior->id;
        $arregloObservacion = $this->verificarCambios($actual, $anterior, $excepciones);

        $descripcion = $eliminado ? 'ELIMINADO' : 'ACTUALIZACION';
        $anterior = $eliminado ? $descripcion : $arregloObservacion['anterior'];
        $actual = $eliminado ? $descripcion : $arregloObservacion['actual'];
        $fecha_elimina = $eliminado ? date('Y-m-d H:i:s') : '';
        if($anterior!=[]|| $actual !=[]){
            $cqlInserta = new LogsMongo();
            $cqlInserta->descripcion = $descripcion;
            $cqlInserta->usuario_id_inserta = Auth::user()->id;
            $cqlInserta->usuario_inserta = Auth::user()->nombres;
            $cqlInserta->fecha_inserta = date('Y-m-d H:i:s');
            $cqlInserta->fecha_elimina = $fecha_elimina;
            $cqlInserta->eliminado = false;
            $cqlInserta->registro_id = $id;
            $cqlInserta->anterior = $anterior;
            $cqlInserta->actual = $actual;
            $cqlInserta->modulo = $modulo;
            $cqlInserta->tabla = $tabla;
            $cqlInserta->save();
        }
    }
    
}