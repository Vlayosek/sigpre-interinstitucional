<?php

namespace App\Http\Controllers\AccesoInternet;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Ajax\SelectController;
use App\Http\Controllers\AccesoInternet\RepositorioController as RP;
use App\Core\Entities\AccesoInternet\Dato_tecnico;
use App\Core\Entities\AccesoInternet\Solicitud;
use App\Core\Entities\TalentoHumano\Distributivo\Persona;
use App\Core\Entities\TalentoHumano\Distributivo\Area;
use Yajra\DataTables\CollectionDataTable;

use Auth;
use DB;

class AccesoInternetController extends Controller
{
    protected function actualizacionDatos()
    {
        $objSelect = new RP();
        $objSelect->updateSolicitudBorrador();
    }
    public function solicitud()
    {
        $this->actualizacionDatos();
        $cql = Persona::select(
            'apellidos_nombres',
            'id'
        )
            ->with(['historial_completo' => function ($q) {
                $q->with(['area', 'cargo']);
            }])
            ->where('identificacion', Auth::user()->identificacion)
            ->limit(1)
            ->first();

        $funcionario = '--';
        $jefe_inmediato = '--';
        $area = '--';
        $cargo = '--';
        $area_mision = '--';
        $area_id = 0;
        $historial = null;

        if ($cql != null) {
            $funcionario = $cql->apellidos_nombres;
            $historial = $cql->historial_completo->count() > 0 ? $cql->historial_completo[0] : null;
            if ($historial != null) {
                $area = $historial->area != null ? $historial->area->nombre : '--';
                $area_id = $historial->area != null ? $historial->area->id : '--';
                $area_mision = $historial->area->mision != null ? $historial->area->mision : 'MISIÓN NO DISPONIBLE';
                $cargo = $historial->cargo != null ? $historial->cargo->nombre : '--';
            }
        }
        $objTipo = new SelectController();
        $jefe = $objTipo->buscarDatosUATH(Auth::user()->identificacion);
        $jefe_inmediato = $jefe['apellidos_nombres_jefe'];
        $sistema_operativo = $objTipo->getParametro('SISTEMA_OPERATIVO_REVISA', 'http');
        $descargas = $objTipo->getParametro('SECCION-DESCARGAS', 'http');
        $redes_sociales = $objTipo->getParametro('SECCION-REDES-SOCIALES', 'http');
        //En el caso de funcionarios con cargo de Coordinadores, Subsecretarios y Secretarios
        $encontrar = ['COORDINADOR', 'SUBSECRETARIO', 'SECRETARIO'];
        $pos = false;
        foreach ($encontrar as $valor) {
            $pos_ = strpos($cargo, $valor);
            if ($pos_ !== false)
                $pos = true;
        }

        if ($pos == false) {
            $parametro_id = DB::table('tb_parametro AS C')
                ->select('C.id as id')->where('C.estado', 'A')
                ->where('C.descripcion', 'SECCION-VIDEOS')
                ->pluck('id');

            $videos_en_linea = DB::table('tb_parametro AS C')
                ->groupBy('C.descripcion', 'C.id')
                ->orderBy('C.descripcion', 'desc')
                ->select('C.id as id', 'C.descripcion as descripcion')
                ->where('C.estado', 'A')
                ->where('C.parametro_id', $parametro_id)
                ->whereNotIn('C.descripcion', ['STREAMING'])
                ->pluck('descripcion', 'id')->toArray();
        } else {
            $videos_en_linea = $objTipo->getParametro('SECCION-VIDEOS', 'http');
        }
        //FUNCIONARIO QUE SE ENCUENTREN EN LA SECRETARIA GENERAL DE COMUNICACION
        $area_sc = config("app_acceso_internet.area_streaming");

        //  dd($area_sc);
        $letras_tildes = 'áéíóúÁÉÍÓÚäëïöüÄËÏÖÜ';
        $letras_sin_tildes = 'aeiouAEIOUaeiouAEIOU';
        $identificacion = $this->eliminar_acentos($area_sc);
        $cqlAreaId = Area::select('id')
            ->whereRaw('translate(UPPER(nombre),\'' . $letras_tildes . '\',\'' . $letras_sin_tildes . '\') ILIKE ? ', [strtoupper($identificacion)])
            ->first();
        if ($area_id == $cqlAreaId->id) {
            $videos_en_linea = $objTipo->getParametro('SECCION-VIDEOS', 'http');
        }
        $mensajeria = $objTipo->getParametro('SECCION-MENSAJERIA', 'http');

        ksort($videos_en_linea);
        ksort($redes_sociales);
        ksort($mensajeria);
        //dd($videos_en_linea);
        //AGREGAR LA MISION DE UNA AREA
        $cqlArea = Area::select('id', 'nombre')->where('estado', 'ACT')->where('eliminado', false)->whereNull('mision')->pluck('nombre', 'id');
        //VALIDA SI LA MISION DEL AREA ESTA EN NULL

        return view('modules.acceso_internet.index', compact('mensajeria', 'area_mision', 'cqlArea', 'funcionario', 'area', 'cargo', 'jefe_inmediato', 'sistema_operativo', 'descargas', 'videos_en_linea', 'redes_sociales'));
    }
    protected function eliminar_acentos($cadena)
    {

        //Reemplazamos la A y a
        $cadena = str_replace(
            array('Á', 'À', 'Â', 'Ä', 'á', 'à', 'ä', 'â', 'ª'),
            array('A', 'A', 'A', 'A', 'a', 'a', 'a', 'a', 'a'),
            $cadena
        );

        //Reemplazamos la E y e
        $cadena = str_replace(
            array('É', 'È', 'Ê', 'Ë', 'é', 'è', 'ë', 'ê'),
            array('E', 'E', 'E', 'E', 'e', 'e', 'e', 'e'),
            $cadena
        );

        //Reemplazamos la I y i
        $cadena = str_replace(
            array('Í', 'Ì', 'Ï', 'Î', 'í', 'ì', 'ï', 'î'),
            array('I', 'I', 'I', 'I', 'i', 'i', 'i', 'i'),
            $cadena
        );

        //Reemplazamos la O y o
        $cadena = str_replace(
            array('Ó', 'Ò', 'Ö', 'Ô', 'ó', 'ò', 'ö', 'ô'),
            array('O', 'O', 'O', 'O', 'o', 'o', 'o', 'o'),
            $cadena
        );

        //Reemplazamos la U y u
        $cadena = str_replace(
            array('Ú', 'Ù', 'Û', 'Ü', 'ú', 'ù', 'ü', 'û'),
            array('U', 'U', 'U', 'U', 'u', 'u', 'u', 'u'),
            $cadena
        );

        //Reemplazamos la N, n, C y c
        /*  $cadena = str_replace(
        array('Ñ', 'ñ', 'Ç', 'ç'),
        array('N', 'n', 'C', 'c'),
        $cadena
        );*/
        $cadena = str_replace(
            array('Ç', 'ç'),
            array('C', 'c'),
            $cadena
        );
        return $cadena;
    }
    public function getDatatableSolicitudServerSide($fecha_inicio, $fecha_fin, $tipoActual)
    {
        $objRepo = new Rp();

        switch ($tipoActual) {
            case 'PENDIENTE':
                $estado = ['APROBADO POR JEFE INMEDIATO', 'RECHAZADO POR JEFE INMEDIATO'];
                $data = $objRepo->selectDatatable(null, null, $estado)->where('solicitudes.eliminado', false);
                break;
            default:
                //  $data = $data->whereDate('solicitudes.fecha_inserta', '>=', $fecha_inicio)->whereDate('solicitudes.fecha_inserta', '<=', $fecha_fin);
                switch ($tipoActual) {
                    case 'APROBADO':
                        $estado = 'APROBADO POR JEFE INMEDIATO';
                        $data = $objRepo->selectDatatable($fecha_inicio, $fecha_fin, $estado)->where('solicitudes.eliminado', false);
                        break;

                    case 'RECHAZADO':
                        $estado = 'RECHAZADO POR JEFE INMEDIATO';
                        $data = $objRepo->selectDatatable($fecha_inicio, $fecha_fin, $estado)->where('solicitudes.eliminado', false);

                        break;
                    case 'ELIMINADO':
                        $estado = 'ELIMINADO';
                        $data = $objRepo->selectDatatable($fecha_inicio, $fecha_fin, $estado);
                        break;
                }
                break;
        }

        $data = $data->where('solicitudes.identificacion', Auth::user()->identificacion)->get();
        return (new CollectionDataTable($data))
            ->addIndexColumn()
            ->addColumn('', function ($row) {
                $btn = $this->menuOpcion($row);
                $tipo='NAVEGACION';
                $btn .= '<button class="btn btn-success btn-sm" onclick="app_seguimiento.consultarSeguimiento(\'' . $row->id . '\',\'' . $tipo . '\')"  data-toggle="modal" data-target="#modal-seguimiento">Seguimiento</button>';
                return $btn;
            })
            ->rawColumns([''])
            ->toJson();
    }
    protected function menuOpcion($row)
    {
        $html = ' <div class="btn-group dropdown">';
        $html .= '    <button class="btn btn-default btn-sm dropdown-toggle" aria-haspopup="true" aria-expanded="false" onclick="transaccionToogle(this)">Acciones <span class="caret"></span></button>';
        $html .= '        <ul class="dropdown-menu">';
        if ($row->estado == 'PENDIENTE') {
            $html .= '         <li>';
            $html .= '         <a href="#" class="dropdown-item" data-toggle="modal" data-target="#modal-solicitud" onclick="app.editarRegistro(\'' . $row->id . '\');transaccionToogle(this,true)" data-backdrop="static" data-keyboard="false"><i class="fa fa-cog"></i>&nbsp;Editar</a>';
            $html .= '         </li>';
            $html .= '        <li>';
            $html .= '         <a href="#" class="dropdown-item"  onclick="app.enviarRegistro(\'' . $row->id . '\');transaccionToogle(this,true)" data-backdrop="static" data-keyboard="false"><i class="fa fa-paper-plane"></i>Enviar</a>';
            $html .= '        </li>';
            $html .= '         <li>';
            $html .= '         <a href="#" class="dropdown-item"  onclick="app.eliminarRegistro(\'' . $row->id . '\');transaccionToogle(this,true)" data-backdrop="static" data-keyboard="false">Eliminar</a>';
            $html .= '         </li>';
        } else {
            $html .= '<li>';
            $html .= '         <a href="#" class="dropdown-item" data-toggle="modal" data-target="#modal-solicitud" onclick="app.editarRegistro(\'' . $row->id . '\');transaccionToogle(this,true); app.consulta=true;" data-backdrop="static" data-keyboard="false"><i class="fa fa-cog"></i>&nbsp;Ver Solicitud</a>';
            $html .= '</li>';
        }
        $html .= '         </ul>';
        $html .= ' </div>';
        return $html;
    }
    public function consultaEstados(request $request)
    {
        $objRepo = new Rp();
        $fecha_inicio=$request->fecha_inicio;
        $fecha_fin=$request->fecha_fin;
        $array_response['status'] = 200;
        $estado = ['APROBADO POR JEFE INMEDIATO', 'RECHAZADO POR JEFE INMEDIATO'];
        $array_response['pendientes']= $objRepo->selectDatatable(null, null, $estado)->where('solicitudes.eliminado', false)->where('solicitudes.identificacion', Auth::user()->identificacion)->get()->count();
        $estado = 'APROBADO POR JEFE INMEDIATO';
        $array_response['aprobados']  = $objRepo->selectDatatable($fecha_inicio, $fecha_fin, $estado)->where('solicitudes.eliminado', false)->where('solicitudes.identificacion', Auth::user()->identificacion)->get()->count();
        $estado = 'RECHAZADO POR JEFE INMEDIATO';
        $array_response['rechazados'] = $objRepo->selectDatatable($fecha_inicio, $fecha_fin, $estado)->where('solicitudes.eliminado', false)->where('solicitudes.identificacion', Auth::user()->identificacion)->get()->count();
        $estado = 'ELIMINADO';
        $array_response['eliminados'] = $objRepo->selectDatatable($fecha_inicio, $fecha_fin, $estado)->where('solicitudes.identificacion', Auth::user()->identificacion)->get()->count();

        return response()->json($array_response, 200);
    }
    // CRUD DE SOLICITUD - FUNCIONARIO
    public function guardarFormulario(request $request)
    {
        try {
            DB::connection('pgsql_presidencia')->beginTransaction();
            $objSelectRepositorio = new RP();

            $length = 5;
            if ($request->id == 0) {
                $cqlFormulario = new Solicitud();
                $cqlFormulario->usuario_ingresa = Auth::user()->name;
                $cqlFormulario->fecha_ingresa = date('Y-m-d H:i:s');
                $cqlFormulario->identificacion = Auth::user()->identificacion;
                $cqlFormulario->estado = 'PENDIENTE';
                $cqlFormulario->fecha_borrador = date("Y-m-d", strtotime(date("Y-m-d") . "+ 5 days"));
                $cqlFormulario->eliminado = false;

                $cql = Persona::select('id')
                    ->with(['historial_completo' => function ($q) {
                        $q->with(['cargo']);
                    }])
                    ->where('identificacion', Auth::user()->identificacion)
                    ->limit(1)
                    ->first();

                $cargo = '--';
                $historial = null;

                if ($cql != null) {
                    $historial = $cql->historial_completo->count() > 0 ? $cql->historial_completo[0] : null;
                    if ($historial != null) {
                        $cargo = $historial->cargo != null ? $historial->cargo->nombre : '--';
                        $area = $historial->area != null ? $historial->area->nombre : '--';
                        $historia_laboral_id = $historial['id'];
                    }
                }

                $cqlFormulario->cargo_funcionario = $cargo;
                $cqlFormulario->area_funcionario = $area;
                $cqlFormulario->historia_laboral_id = $historia_laboral_id;
                $cqlCuenta = Solicitud::select('id')
                    ->whereYear('fecha_ingresa', date('Y'))->count();
                $cqlCuenta += 1;
                $anio = date('Y');
                $cuentaSolicitud = substr(str_repeat(0, $length) . $cqlCuenta, -$length);
                $cqlFormulario->codigo_solicitud = "DTIC-I-" . $anio . "-" . $cuentaSolicitud;
            } else {
                $cqlFormulario = Solicitud::find($request->id);
                $cambios = $objSelectRepositorio->verificarCambios($request, $cqlFormulario);

                $cqlFormulario->usuario_modifica = Auth::user()->name;
                $cqlFormulario->fecha_modifica = date('Y-m-d H:i:s');
            }

            $cqlFormulario->save();  //guardo los datos de auditoria
            $cqlFormulario->fill($request->all())->save();

            $solicitud_id = $cqlFormulario->id;
            if ($request->id == 0) {
                $descripcion = 'Se creo la solicitud ' . $solicitud_id . ' por el funcionario :' . Auth::user()->nombres;
                $objSelectRepositorio->crearTransaccion($descripcion, $cqlFormulario->id);
                $objSelectRepositorio->guardarEstado($cqlFormulario->id, 1, $descripcion);
            } else {
                if ($cambios != []) {
                    $descripcion = 'Se actualizo la solicitud ' . $solicitud_id . ' por el funcionario :' . Auth::user()->nombres;
                    $objSelectRepositorio->crearTransaccion($descripcion, $solicitud_id);
                    $objSelectRepositorio->guardarLogs($cambios, $cqlFormulario->id);
                }
            }
            if ($request->enviado == "true") {
                $datos_enviados = $this->enviarSolicitudJefe($cqlFormulario->id);
                if ($datos_enviados['status'] != 200)   throw new \Exception("Error al enviar solicitud");
            }

            $array_response['status'] = 200;
            $array_response['message'] = 'Grabado exitosamente';
            DB::connection('pgsql_presidencia')->commit();
        } catch (\Exception $e) {
            DB::connection('pgsql_presidencia')->rollBack();
            $array_response['status'] = 300;
            $array_response['datos'] = $e->getMessage();
        }
        return response()->json($array_response, 200);
    }
    protected function enviarSolicitudJefe($id)
    {
        try {
            DB::connection('pgsql_presidencia')->beginTransaction();
            $objSelect = new SelectController();
            $objSelectRepositorio = new RP();

            $datosUsuario = $objSelect->buscarDatosUATH(Auth::user()->identificacion);
            $correo_jefe = $datosUsuario['correo_institucional_jefe'];

            $cqlFormulario = Solicitud::find($id);
            $cqlFormulario->estado = 'ENVIADO';
            $cqlFormulario->usuario_modifica = Auth::user()->name;
            $cqlFormulario->fecha_modifica = date('Y-m-d H:i:s');
            $cqlFormulario->save();

            //NOTIFICANDO A MAIL a 
            $objSelect->NotificarSinRegistro(' que tiene una solicitud de permiso de acceso a internet por aprobar por parte del Funcionario ' . Auth::user()->name, $correo_jefe);
            $descripcion = 'Solicitud Pendiente por aprobar , enviada por el funcionario :' . Auth::user()->nombres;
            $solicitud_id = $cqlFormulario->id;
            $objSelectRepositorio->crearTransaccion($descripcion, $solicitud_id);
            $objSelectRepositorio->guardarEstado($cqlFormulario->id, 2, $descripcion);

            $array_response['status'] = 200;
            $array_response['message'] = 'Grabado exitosamente';
            DB::connection('pgsql_presidencia')->commit();
        } catch (\Exception $e) {
            DB::connection('pgsql_presidencia')->rollBack();
            $array_response['status'] = 300;
            $array_response['datos'] = $e->getMessage();
        }
        return $array_response;
    }
    public function enviarRegistro(request $request)
    {

        $this->enviarSolicitudJefe($request->id);
        $array_response['status'] = 200;
        $array_response['datos'] = "Enviado Exitosamente";
        return response()->json($array_response, 200);
    }
    public function editarRegistro(request $request)
    {
        $array_response['status'] = 200;
        $array_response['datos'] = Solicitud::with([
            'funcionarios', 'tipo_',
            'historial_completo' => function ($q) {
                $q->with(['area', 'cargo']);
            }
        ])
            ->where('id', $request->id)->get()->first();

        $cql = $array_response['datos'];
        $funcionario = '--';
        $jefe_inmediato = '--';
        $area = '--';
        $area_mision = '--';
        $cargo = '--';
        $historial = null;

        if ($cql->funcionarios != null) {
            $funcionario = $cql->funcionarios->apellidos_nombres;
        }
        $historial = $cql->historial_completo->count() > 0 ? $cql->historial_completo[0] : null;
        if ($historial != null) {
            $area = $historial->area != null ? $historial->area->nombre : '--';
            $cargo = $historial->cargo != null ? $historial->cargo->nombre : '--';
            $area_mision = $historial->area->mision != null ? $historial->area->mision : 'MISIÓN NO DISPONIBLE';
        }
        $array_response['datos_tecnicos'] = Dato_tecnico::select('id', 'perfiles', 'observacion')
            ->where('solicitud_id', $request->id)
            ->where('estado', 'ACT')
            ->where('eliminado', false)
            ->first();

        $objTipo = new SelectController();
        $jefe = $objTipo->buscarDatosUATH($cql->identificacion);
        $jefe_inmediato = $jefe['apellidos_nombres_jefe'];

        $array_response['area_mision'] = $area_mision;
        $array_response['funcionario'] = $funcionario;
        $array_response['jefe_inmediato'] = $jefe_inmediato;
        $array_response['area'] = $area;
        $array_response['cargo'] = $cargo;

        return response()->json($array_response, 200);
    }

    public function eliminarRegistro(request $request)
    {
        $objSelectRepositorio = new RP();

        $cqlDelete = Solicitud::find($request->id);
        $cqlDelete->eliminado = true;
        $cqlDelete->estado = 'ELIMINADO';
        $cqlDelete->usuario_modifica = Auth::user()->name; //Obtengo el login para agregarlo al campo usuario modifica
        $cqlDelete->fecha_modifica = date("Y-m-d H:i:s"); //Obtengo el date time para agregarlo al campo fecha modifica
        $cqlDelete->save();
        
        $descripcion='Solicitud #'.$cqlDelete->id.',eliminado por el funcionario: '.Auth::user()->nombres;
        $objSelectRepositorio->guardarEstado($cqlDelete->id,13,$descripcion);

        $array_response['status'] = 200;
        $array_response['datos'] = "Eliminado Exitosamente";
        return response()->json($array_response, 200);
    }
    // APROBACION JEFE INMEDIATO  

    public function guardarMision(request $request)
    {
        $cqlPasaje = Area::find($request->id);
        $cqlPasaje->mision = $request->mision;
        $cqlPasaje->save();
        $array_response['status'] = 200;
        $array_response['message'] = 'Guardado con exito';
        return response()->json($array_response, 200);
    }
    public function editarMision(request $request)
    {
        $array_response['status'] = 200;
        $array_response['datos'] = Area::select(
            'area.id',
            'areas.mision',
        )
            ->where('areas.id', $request->id)
            ->get()->first();
        return response()->json($array_response, 200);
    }
    public function consultarSeguimiento(request $request)
    {
        $objSelectRepositorio = new RP();
        $array_response = $objSelectRepositorio->consultarSeguimiento($request->id,$request->tipo);
        return response()->json($array_response, 200);
    }
}
