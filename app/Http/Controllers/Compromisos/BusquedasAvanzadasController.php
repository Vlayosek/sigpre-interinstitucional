<?php

namespace App\Http\Controllers\Compromisos;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Core\Entities\Compromisos\Institucion;
use Yajra\Datatables\Datatables;
use App\Core\Entities\Compromisos\Compromiso;
use App\Core\Entities\Compromisos\Monitor;
use DB;

class BusquedasAvanzadasController extends Controller
{
  public function busquedaAvanzadaCompromisos(request $request)
  {
    $obj = new \StdClass();
    $obj->institucion_id_busqueda = $request->institucion_id_busqueda;
    $obj->gabinete_id_busqueda = $request->gabinete_id_busqueda;
    $obj->monitor_busqueda = $request->monitor_busqueda;

    $array_response = $this->consultaEstadosBusqueda($obj);
    return response()->json($array_response, 200);
  }
  protected function consultaDatosContados(
    $respuesta_data,
    $tipo,
    $data,
    $id,
    $arregloCompromisosIntervinientes
  ) {
    $array_response[$respuesta_data] = $this->restriccionesPorConsulta(
      $tipo,
      $data,
      $id
    );

    $arregloCompromisosIntervinientes = array_merge(
      $arregloCompromisosIntervinientes,
      $array_response[$respuesta_data]->pluck('compromisos.id')->toArray()
    );

    $array_response[$respuesta_data] = $array_response[$respuesta_data]->get()->count();
    $array_response['arregloCompromisosIntervinientes'] = $arregloCompromisosIntervinientes;
    return $array_response;
  }
  public function consultaEstadosBusqueda($obj, $filtro_datos = null)
  {
    $arregloCompromisosIntervinientes = [];
    /* ---MENSAJES--- */
    $tipo = 'MENSAJES';
    $respuesta_data = 'mensajes_busqueda';
    $data = DB::table('sc_compromisos.mensajes as mensajes')
      ->join('sc_compromisos.compromisos as compromisos', 'mensajes.compromiso_id', 'compromisos.id');

    $id = $this->selectBusquedas($obj, $filtro_datos)
      ->leftjoin('sc_compromisos.mensajes as mensajes', 'mensajes.compromiso_id', 'compromisos.id')
      ->pluck('compromisos.id')->toArray();

    $respuesta_datos_contados = $this->consultaDatosContados(
      $respuesta_data,
      $tipo,
      $data,
      $id,
      $arregloCompromisosIntervinientes
    );
    $array_response[$respuesta_data] = $respuesta_datos_contados[$respuesta_data];
    $arregloCompromisosIntervinientes = $respuesta_datos_contados['arregloCompromisosIntervinientes'];
    /* ---ARCHIVOS--- */

    $tipo = 'ARCHIVOS';
    $respuesta_data = 'archivos_busqueda';

    $data = DB::table('sc_compromisos.archivos as archivos')
      ->join('sc_compromisos.compromisos as compromisos', 'archivos.compromiso_id', 'compromisos.id');

    $id = $this->selectBusquedas($obj, $filtro_datos)
      ->join('sc_compromisos.archivos as archivos', 'archivos.compromiso_id', 'compromisos.id')
      ->pluck('compromisos.id')->toArray();
    $respuesta_datos_contados = $this->consultaDatosContados(
      $respuesta_data,
      $tipo,
      $data,
      $id,
      $arregloCompromisosIntervinientes
    );
    $array_response[$respuesta_data] = $respuesta_datos_contados[$respuesta_data];
    $arregloCompromisosIntervinientes = $respuesta_datos_contados['arregloCompromisosIntervinientes'];
    /* ---AVANCES--- */

    $tipo = 'AVANCES';
    $respuesta_data = 'avances_busqueda';

    $data = DB::table('sc_compromisos.avances as avances')
      ->join('sc_compromisos.compromisos as compromisos', 'avances.compromiso_id', 'compromisos.id');

    $id = $this->selectBusquedas($obj, $filtro_datos)
      ->join('sc_compromisos.avances as avances', 'avances.compromiso_id', 'compromisos.id')
      ->pluck('compromisos.id')->toArray();
    $respuesta_datos_contados = $this->consultaDatosContados(
      $respuesta_data,
      $tipo,
      $data,
      $id,
      $arregloCompromisosIntervinientes
    );
    $array_response[$respuesta_data] = $respuesta_datos_contados[$respuesta_data];
    $arregloCompromisosIntervinientes = $respuesta_datos_contados['arregloCompromisosIntervinientes'];
    /* ---OBJETIVOS--- */

    $tipo = 'OBJETIVOS';
    $respuesta_data = 'objetivos_busqueda';

    $data = DB::table('sc_compromisos.objetivos as objetivos')
      ->join('sc_compromisos.compromisos as compromisos', 'objetivos.compromiso_id', 'compromisos.id');

    $id = $this->selectBusquedas($obj, $filtro_datos)
      ->join('sc_compromisos.objetivos as objetivos', 'objetivos.compromiso_id', 'compromisos.id')
      ->pluck('compromisos.id')->toArray();
    $respuesta_datos_contados = $this->consultaDatosContados(
      $respuesta_data,
      $tipo,
      $data,
      $id,
      $arregloCompromisosIntervinientes
    );
    $array_response[$respuesta_data] = $respuesta_datos_contados[$respuesta_data];
    $arregloCompromisosIntervinientes = $respuesta_datos_contados['arregloCompromisosIntervinientes'];

    $array_response['total'] = array_sum($array_response);
    $array_response['compromisos_intervinientes'] = array_unique($arregloCompromisosIntervinientes);
    $array_response['status'] = 200;
    return $array_response;
  }
  /* public function busquedaAvanzadaCompromisos(request $request)
  {
    $tipo = 'MENSAJES';
    $array_response['mensajes_busqueda'] = $this->restriccionesPorConsulta(
      $tipo,
      $this->selectBusquedas($request)
        ->leftjoin('sc_compromisos.mensajes as mensajes', 'mensajes.compromiso_id', 'compromisos.id')
    )->get()->count();

    $tipo = 'ARCHIVOS';
    $array_response['archivos_busqueda'] = $this->restriccionesPorConsulta(
      $tipo,
      $this->selectBusquedas($request)
        ->leftjoin('sc_compromisos.archivos as archivos', 'archivos.compromiso_id', 'compromisos.id')
    )->get()->count();

    $tipo = 'AVANCES';
    $array_response['avances_busqueda'] = $this->restriccionesPorConsulta(
      $tipo,
      $this->selectBusquedas($request)
        ->leftjoin('sc_compromisos.avances as avances', 'avances.compromiso_id', 'compromisos.id')
    )->get()->count();

    $tipo = 'OBJETIVOS';
    $array_response['objetivos_busqueda'] = $this->restriccionesPorConsulta(
      $tipo,
      $this->selectBusquedas($request)
        ->leftjoin('sc_compromisos.objetivos as objetivos', 'objetivos.compromiso_id', 'compromisos.id')
    )->get()->count();

    $array_response['status'] = 200;
    return response()->json($array_response, 200);
  }*/
  public function getDatatableBusquedasServerSide(Request $request)
  {
    $tipo = $request->tipo;
    if ($tipo == 'MENSAJES') {
      $data = $this->restriccionesPorConsulta($tipo, $this->selectBusquedas($request)->leftjoin('sc_compromisos.mensajes as mensajes', 'mensajes.compromiso_id', 'compromisos.id'));
    }
    if ($tipo == 'ARCHIVOS') {
      $data = $this->restriccionesPorConsulta($tipo, $this->selectBusquedas($request)->leftjoin('sc_compromisos.archivos as archivos', 'archivos.compromiso_id', 'compromisos.id'));
    }
    if ($tipo == 'AVANCES') {
      $data = $this->restriccionesPorConsulta($tipo, $this->selectBusquedas($request)->leftjoin('sc_compromisos.avances as avances', 'avances.compromiso_id', 'compromisos.id'));
    }
    if ($tipo == 'OBJETIVOS') {
      $data = $this->restriccionesPorConsulta($tipo, $this->selectBusquedas($request)->leftjoin('sc_compromisos.objetivos as objetivos', 'objetivos.compromiso_id', 'compromisos.id'));
    }

    return Datatables::of($data)
      ->addIndexColumn()
      ->addColumn('', function ($row) use ($tipo) {
        $btn = ' <table style="margin: 0 auto;border-collapse: collapse; width:100%"><tr>';
        $btn .= '<td style="padding:1px"><button class="btn btn-default btn-xs btn-block" data-toggle="modal" data-backdrop="static" data-keyboard="false"  onclick="app.verBusqueda(\'' . $row->id . '\',\'' . $tipo . '\')" data-toggle="modal" data-target="#modal-mensajes"><i class="fa fa-list"></i>&nbsp;Ver</button></td>';
        $btn .= ' </tr></table>';
        return $btn;
      })
      ->rawColumns([''])
      ->make(true);
  }

  public function getDatatableBusquedaPorTipo(Request $request)
  {
    $tipo = $request->tipo;
    if ($tipo == 'MENSAJES') {
      $data = $this->restriccionesPorConsulta($tipo, (new RepositorioController())->selectMensajes(), $request->id);

      return (new RepositorioController())->retornaOpcionesDatatableMensajes(Datatables::of($data), true)->make(true);
    }
    if ($tipo == 'ARCHIVOS') {
      $data = $this->restriccionesPorConsulta($tipo, (new RepositorioController())->selectArchivos(), $request->id);

      return (new RepositorioController())->retornaOpcionesDatatableArchivos(Datatables::of($data))->make(true);
    }
    if ($tipo == 'AVANCES') {
      $data = $this->restriccionesPorConsulta($tipo, (new RepositorioController())->selectAvances(), $request->id);

      return (new RepositorioController())->retornaOpcionesDatatableAvances(Datatables::of($data), true)->make(true);
    }
    if ($tipo == 'OBJETIVOS') {
      $data = $this->restriccionesPorConsulta($tipo, (new RepositorioController())->selectObjetivos(), $request->id);

      return (new RepositorioController())->retornaOpcionesDatatableObjetivos(Datatables::of($data), true)->make(true);
    }
  }

  protected function restriccionesPorConsulta($tipo, $data, $id = null)
  {
    $monitor = Monitor::select('usuario_id')
      //->where('eliminado', false) //por eliminacion de monitor y no cuente mensajes de los monitore
      ->distinct()
      ->pluck('usuario_id');
    $tabla = 'mensajes.';
    if ($tipo == 'MENSAJES') {
      $tabla = 'mensajes.';

      $data = $data
        ->where('mensajes.leido', 'NO')
        ->where('mensajes.estado', 'ACT')
        ->whereNotIn('mensajes.usuario_ingresa', $monitor);
    }
    if ($tipo == 'ARCHIVOS') {
      $tabla = 'archivos.';

      $data = $data
        ->where('archivos.leido', 'NO')
        ->where('archivos.estado', 'ACT')
        ->whereNotIn('archivos.usuario_ingresa', $monitor);
    }
    if ($tipo == 'AVANCES') {
      $tabla = 'avances.';

      $data = $data
        ->whereNull('avances.fecha_revisa')
        ->where('avances.estado', 'ACT')
        ->whereNotIn('avances.usuario_ingresa', $monitor);
    }

    if ($tipo == 'OBJETIVOS') {
      $tabla = 'objetivos.';

      $data = $data
        ->whereNull('objetivos.aprobado')
        ->where('objetivos.estado', 'ACT')
        ->whereNotIn('objetivos.usuario_ingresa', $monitor);
    }
    if (!is_null($id)) {
      if (!is_array($id)) $id = [$id];
      $data = $data->whereIn($tabla . 'compromiso_id', $id);
    }
    return $data;
  }
  public function filtroGabineteBusqueda(request $request)
  {
    if ($request->institucion_id_busqueda == 0 || $request->institucion_id_busqueda == null) {
      $cqlFiltroGabinete = Institucion::select('id', 'descripcion')->where('nivel', '1')->get();
    } else {
      $cqlConsultaInstitucion = Institucion::select('institucion_id')->where('nivel', '2')->where('id', $request->institucion_id_busqueda)->first();
      $cqlFiltroGabinete = Institucion::select('id', 'descripcion')
        ->where('nivel', '1')
        ->where('id', $cqlConsultaInstitucion->institucion_id)
        ->first();
    }
    $array_response['status'] = 200;
    $array_response['datos'] = $cqlFiltroGabinete;
    return response()->json($array_response, 200);
  }
  public function filtroInstitucionBusqueda(request $request)
  {
    if ($request->gabinete_id_busqueda == 0) {
      $cqlFiltroInstituciones = Institucion::select('id', 'descripcion')->where('nivel', '2')->get();
    } else {
      $cqlFiltroInstituciones = Institucion::select('id', 'descripcion')
        ->where('nivel', '2')
        ->where('institucion_id', $request->gabinete_id_busqueda)
        ->get();
    }
    $array_response['status'] = 200;
    $array_response['datos'] = $cqlFiltroInstituciones;
    return response()->json($array_response, 200);
  }
  protected function selectBusquedas($request, $filtro_datos = null)
  {

    $data = Compromiso::select(
      DB::RAW('distinct CASE WHEN compromisos.codigo IS NOT NULL THEN compromisos.codigo ELSE compromisos.id::varchar(15) END as codigo'),
      'compromisos.nombre_compromiso as nombre_compromiso',
      'institucion_.descripcion as institucion_responsable',
      'gabinete.descripcion as gabinete_',
      'compromisos.fecha_inicio as fecha_inicio_',
      'compromisos.fecha_fin as fecha_fin_',
      'estado_gestion.descripcion as estado_porcentaje_',
      'compromisos.id'
    );


    $data = $data->leftjoin('sc_compromisos.responsables as responsable_', 'responsable_.compromiso_id', 'compromisos.id')
      ->join('sc_compromisos.instituciones as institucion_', 'institucion_.id', 'responsable_.institucion_id')
      ->leftjoin('sc_compromisos.estados_porcentaje as estado_gestion', 'estado_gestion.id', 'compromisos.estado_porcentaje_id')
      ->leftjoin('sc_compromisos.instituciones as gabinete', 'gabinete.id', 'institucion_.institucion_id')
      ->where('responsable_.estado', 'ACT')
      ->where('compromisos.estado', 'ACT')
      ->where('cerrado', 'false')
      ->where('estado_gestion.abv', '<>', 'CUM');
    if (!is_null($filtro_datos))  $data = $data->whereIn('compromisos.id', $filtro_datos);
    if ($request->institucion_id_busqueda != "0" && $request->institucion_id_busqueda != null)
      $data = $data->where('institucion_.id', $request->institucion_id_busqueda);
    if ($request->gabinete_id_busqueda != 0 && $request->gabinete_id_busqueda != null) {
      $data = $data->where('gabinete.id', $request->gabinete_id_busqueda);
    }
    if ($request->monitor_busqueda != null && $request->monitor_busqueda != 0)
      $data = $data->where('compromisos.monitor_id', $request->monitor_busqueda);

    return $data;
  }
  //Consultar busqueda de mensajes, archivos, avances sin leer
  private function consultaBusqueda($request)
  {
    $cql =  Compromiso::select('compromisos.id')
      ->leftjoin('sc_compromisos.responsables as responsable_', 'responsable_.compromiso_id', 'compromisos.id')
      ->leftjoin('sc_compromisos.instituciones as institucion_', 'institucion_.id', 'responsable_.institucion_id')
      ->join('sc_compromisos.estados_porcentaje as estado_gestion', 'estado_gestion.id', 'compromisos.estado_porcentaje_id')
      ->where('compromisos.estado', 'ACT')
      ->where('cerrado', 'false')
      ->where('estado_gestion.abv', '<>', 'CUM');
    if ($request->institucion_id_busqueda != 0 || $request->institucion_id_busqueda != NULL)
      $cql = $cql->where('institucion_.id', $request->institucion_id_busqueda);
    if ($request->gabinete_id_busqueda != 0 && $request->gabinete_id_busqueda != NULL) {
      $cql = $cql->leftjoin('sc_compromisos.instituciones as gabinete', 'gabinete.id', 'institucion_.institucion_id')
        ->where('gabinete.id', $request->gabinete_id_busqueda);
    }
    if ($request->monitor_busqueda != 0 && $request->monitor_busqueda != null) {
      $cql = $cql->where('compromisos.monitor_id', $request->monitor_busqueda);
    }

    return $cql;
  }
}
