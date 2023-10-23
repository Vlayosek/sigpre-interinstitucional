<?php

namespace App\Http\Controllers\Compromisos;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Core\Entities\Compromisos\Institucion;
use Yajra\Datatables\Datatables;
use Yajra\DataTables\CollectionDataTable;
use App\Core\Entities\Compromisos\Tipo;
use App\Core\Entities\Compromisos\Estado;
use App\Core\Entities\Compromisos\EstadoPorcentaje;

use App\Core\Entities\Compromisos\Compromiso;
use App\Core\Entities\Compromisos\Antecedente;
use App\Core\Entities\Compromisos\Archivo;
use App\Core\Entities\Compromisos\Mensaje;
use App\Core\Entities\Compromisos\Objetivo;
use App\Core\Entities\Compromisos\Responsable;
use App\Core\Entities\Compromisos\Corresponsable;
use App\Core\Entities\Compromisos\Ubicacion;
use App\Core\Entities\Compromisos\Avance;
use App\Core\Entities\Compromisos\Monitor;
use App\Core\Entities\Compromisos\Periodo;
use App\Core\Entities\Admin\parametro_ciudad;
use App\Core\Entities\Compromisos\CodigoMigrado;
use App\Http\Controllers\Ajax\SelectController;
use Auth;
use App\User;
use DB;

class CompromisosController extends Controller
{
  /*  CUM, CER, STA,   PLA, EJE */

  protected $ESTADOS_NO_INICIALES = ['CUM', 'STA', 'CER'];

  public function index()
  {
    $data = (new RepositorioController())->variablesGlobales(false);
    return view('modules.compromisos.index', $data);
  }
  public function corresponsableIndex()
  {

    return view(
      'modules.compromisos.index',
      (new RepositorioController())->variablesGlobales(true)
    );
  }
  public function getDatatableAvancesServerSide($campo)
  {
    $data = (new RepositorioController)->selectAvances()
      ->where('avances.compromiso_id', $campo)
      ->orderby('avances.id', 'desc');
    return (new RepositorioController())->retornaOpcionesDatatableAvances(Datatables::of($data))->make(true);
  }

  public function getDatatableArchivosServerSide($campo)
  {
    $data =  (new RepositorioController)->selectArchivos()->where('archivos.compromiso_id', $campo)
      ->orderby('archivos.id', 'desc');
    return (new RepositorioController())->retornaOpcionesDatatableArchivos(Datatables::of($data))->make(true);
  }

  public function getDatatableMensajeServerSide($campo)
  {
    $data = (new RepositorioController)->selectMensajes()
      ->where('mensajes.compromiso_id', $campo)
      ->orderby('mensajes.id', 'desc');

    return (new RepositorioController())->retornaOpcionesDatatableMensajes(Datatables::of($data))->make(true);
  }
  public function getDatatableHistoricoServerSide($campo)
  {
    $data =
      (new RepositorioController())->selectHistorico()->where('sc_compromisos.transacciones.compromiso_id', $campo);
    if (!Auth::user()->evaluarole(['MONITOR']))
      $data = $data->where('sc_compromisos.transacciones.visible', 'true');
    $data = $data->orderby('sc_compromisos.transacciones.id', 'desc');

    $datatable = Datatables::of($data)
      ->addIndexColumn()
      ->addColumn('institucion', function ($row) {
        return is_null($row->emite_ministro) ? (is_null($row->emite) ? '--' : $row->emite) : $row->emite_ministro;
      })
      ->make(true);
    return $datatable;
  }

  public function getDatatableAntecedentesServerSide($campo)
  {

    $data = (new RepositorioController())->selectAntecedente()
      ->where('compromiso_id', $campo)
      ->orderby('id', 'asc');
    $datatable = Datatables::of($data)
      ->addIndexColumn()
      ->addColumn('', function ($row) {
        $btn = 'Sin Acciones';
        if (Auth::user()->evaluarole(['MONITOR'])) {
          $btn = ' <button title="Editar" class="btn btn-primary  btn-xs" onclick="app.editarAntecedente(\'' . $row->id . '\',\'' . $row->descripcion . '\',\'' . $row->fecha_antecedente . '\')"><i class="fa fa-edit"></i></button>';
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
    // dd($estado,$tabla,$asignaciones,$temporales,$pendientes);
    $data = (new RepositorioController())->selectConsultaCompromisosCorresponsable()
      ->where('estado', 'ACT')
      ->whereNotNull('codigo')
      ->whereIn('id', Corresponsable::select('compromiso_id')
        ->where('institucion_corresponsable_id', (new RepositorioController())->consultaInstitucionporMinistro(Auth::user()->id))
        ->where('estado', 'ACT')
        ->pluck('compromiso_id'));

    if ($estado != "data") {
      if ($tabla == "1")  $data = $data->where('estado_id', Estado::where('abv', $estado)->get()->first()->id);
      else $data = $data->where('estado_porcentaje_id', EstadoPorcentaje::where('abv', $estado)->get()->first()->id);
    }

    $data = $data->orderby('id', 'desc')->get();
    $url = $_SERVER["REQUEST_URI"];

    return (new CollectionDataTable($data))
      ->addIndexColumn()
      ->addColumn('', function ($row) {
        $btn = ' <button title="Editar" class="btn btn-primary  btn-xs" data-toggle="modal" data-target="#modal-default" onclick="app.editar(\'' . $row->id . '\',\'aprobado\')" data-backdrop="static" data-keyboard="false"><i class="fa fa-edit"></i></button>';
        return $btn;
      })
      ->rawColumns([''])
      ->toJson();
  }
  public function getDatatableUbicacionesServerSide(request $request)
  {
    $dataFiltro = Ubicacion::select('parametro_id')
      ->where('compromiso_id', $request->id)
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
    $data = (new RepositorioController())->selectPeriodos()->where('eliminado', false)
      ->where('objetivo_id', $campo)
      ->orderby('id', 'asc');

    $datatable = Datatables::of($data)
      ->addIndexColumn()
      ->addColumn('conteo_periodos', function ($row) {
        return Periodo::where('objetivo_id', $row->objetivo_id)->where('eliminado', false)->count();
      })
      ->addColumn('suma_meta_acumulada', function ($row) {
        $cql = Periodo::select('meta_acumulada')
          ->where('objetivo_id', $row->objetivo_id)
          ->where('eliminado', false)
          ->where('meta_acumulada', '<>', 0)
          ->whereNotNull('meta_acumulada')
          ->orderby('id', 'desc')
          ->first();

        return is_null($cql) ? 0 : $cql->meta_acumulada;
      })
      ->addColumn('', function ($row) {
        $btn = '';
        // if(Auth::user()->evaluarole(['MONITOR'])){
        $edicion = false;
        $cqlVerificaPeriodoAnterior = Periodo::where('objetivo_id', $row->objetivo_id)->where('eliminado', false)->where('numero', $row->numero - 1)->get()->first();
        if ($cqlVerificaPeriodoAnterior != null) {
          if ($cqlVerificaPeriodoAnterior->estado == 'ACT')
            $edicion = true;
        }
        if ($cqlVerificaPeriodoAnterior == null)
          $edicion = true;

        $btn = ' <table ><tr>';
        $btn .= ' <td style="padding: 2px"><button title="Editar" class="btn btn-primary  btn-xs"  onclick="app.editaPeriodo(\'' . $row->id . '\',\'' . $edicion . '\')"><i class="fa fa-cog"></i></button></td>';
        $btn .= ' </tr></table>';
        // }
        return $btn;
      })

      ->rawColumns([''])
      ->make(true);
    return $datatable;
  }
  public function getDatatableObjetivosServerSide($campo)
  {
    $data = (new RepositorioController)->selectObjetivos()
      ->where('objetivos.compromiso_id', $campo)
      ->where('objetivos.estado', 'ACT')
      ->orderby('objetivos.id', 'asc');

    $datatable = (new RepositorioController())->retornaOpcionesDatatableObjetivos(Datatables::of($data))
      ->make(true);
    return $datatable;
  }
  public function getDatatableCompromisosGETServerSide(Request $request)
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

    $filtros_monitor['pendientes'] = $pendientes;
    $filtros_monitor['temporales'] = $temporales;
    $filtros_monitor['asignaciones'] = $asignaciones;
    $data = (new RepositorioController())->selectConsultaCompromisos();
    $data = (new RepositorioController())->restriccionesPorEstado($data, $estado, $tabla, $filtros_monitor);
    //FC cuando los temporales no tienen responsable
    $data = (new RepositorioController())->restriccionesPorRol($data);

    if ($filtro != "false") {
      if ($institucion_id != null && $institucion_id != "null") {

        $data = $data->whereIn('compromisos.id', Responsable::select('compromiso_id')
          ->where('institucion_id', $institucion_id)
          ->where('estado', 'ACT')
          ->pluck('compromiso_id'));
      }
      if ($gabinete_id != null && $gabinete_id != "null") {
        $cqlCompromisosResponsables = Responsable::select('compromiso_id')
          ->whereIn('institucion_id', Institucion::select('id')->where('institucion_id', $gabinete_id)->pluck('id'))
          ->where('estado', 'ACT')
          ->pluck('compromiso_id');

        $data = $data->whereIn('compromisos.id', $cqlCompromisosResponsables);
      }
      $data->whereDate('compromisos.fecha_inicio', '>=', $fecha_inicio)->whereDate('compromisos.fecha_inicio', '<=', $fecha_fin);
    }


    $data = $data->orderby('compromisos.id', 'desc')->paginate(8);

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

  public function filtroInstitucional($data, $request, $corresponsable = true)
  {
    $objRepositorio = new RepositorioController();

    $institucion_id = $objRepositorio->verifcarDatoArray($request->institucion_id_exportar_monitor);
    $gabinete_id = $objRepositorio->verifcarDatoArray($request->gabinete_id_exportar_monitor);

    //////////////REPONSABLE
    $filtro_gabinete = false;
    $filtro_institucion = false;
    $filtro_gabinete_corresponsable = false;
    $filtro_institucion_corresponsable = false;

    if ($objRepositorio->validarArregloVacio($institucion_id)) $filtro_institucion = true;
    if ($objRepositorio->validarArregloVacio($gabinete_id)) $filtro_gabinete = true;

    if ($filtro_gabinete && $filtro_institucion) {
      $data = $data->whereIn('compromisos.id', Responsable::select('compromiso_id')
        ->whereIn('institucion_id', $institucion_id)
        ->where('estado', 'ACT')
        ->pluck('compromiso_id'));


      $cqlCompromisosResponsables = Responsable::select('compromiso_id')
        ->whereIn('institucion_id', Institucion::select('id')->whereIn('institucion_id', $gabinete_id)->pluck('id'))
        ->where('estado', 'ACT')
        ->pluck('compromiso_id');

      $data = $data->whereIn('compromisos.id', $cqlCompromisosResponsables);
    } else if ($filtro_gabinete && !$filtro_institucion) {
      $cqlCompromisosResponsables = Responsable::select('compromiso_id')
        ->whereIn('institucion_id', Institucion::select('id')->whereIn('institucion_id', $gabinete_id)->pluck('id'))
        ->where('estado', 'ACT')
        ->pluck('compromiso_id');

      $data = $data->whereIn('compromisos.id', $cqlCompromisosResponsables);
    } else if (!$filtro_gabinete && $filtro_institucion) {
      $data = $data->whereIn('compromisos.id', Responsable::select('compromiso_id')
        ->whereIn('institucion_id', $institucion_id)
        ->where('estado', 'ACT')
        ->pluck('compromiso_id'));
    }
    if ($corresponsable) {
      $institucion_id_corresponsable_exportar_monitor = $objRepositorio->verifcarDatoArray($request->institucion_id_corresponsable_exportar_monitor);
      $gabinete_id_corresponsable_exportar_monitor = $objRepositorio->verifcarDatoArray($request->gabinete_id_corresponsable_exportar_monitor);
      if ($objRepositorio->validarArregloVacio($institucion_id_corresponsable_exportar_monitor)) $filtro_institucion_corresponsable = true;
      if ($objRepositorio->validarArregloVacio($gabinete_id_corresponsable_exportar_monitor)) $filtro_gabinete_corresponsable = true;

      ///////////////CORRESPONSABLE

      if ($filtro_institucion_corresponsable && $filtro_gabinete_corresponsable) {
        $cqlCompromisosResponsables = Corresponsable::select('compromiso_id')
          ->whereIn('institucion_corresponsable_id', Institucion::select('id')->whereIn('institucion_id', $gabinete_id_corresponsable_exportar_monitor)->pluck('id'))
          ->where('estado', 'ACT')
          ->pluck('compromiso_id');

        $data = $data->whereIn('compromisos.id', $cqlCompromisosResponsables);

        $data = $data->whereIn('compromisos.id', Corresponsable::select('compromiso_id')
          ->whereIn('institucion_corresponsable_id', $institucion_id_corresponsable_exportar_monitor)
          ->where('estado', 'ACT')
          ->pluck('compromiso_id'));
      } else if (!$filtro_institucion_corresponsable && $filtro_gabinete_corresponsable) {
        $cqlCompromisosResponsables = Corresponsable::select('compromiso_id')
          ->whereIn('institucion_corresponsable_id', Institucion::select('id')->whereIn('institucion_id', $gabinete_id_corresponsable_exportar_monitor)->pluck('id'))
          ->where('estado', 'ACT')
          ->pluck('compromiso_id');

        $data = $data->whereIn('compromisos.id', $cqlCompromisosResponsables);
      } else if ($filtro_institucion_corresponsable && !$filtro_gabinete_corresponsable) {

        $data = $data->whereIn('compromisos.id', Corresponsable::select('compromiso_id')
          ->whereIn('institucion_corresponsable_id', $institucion_id_corresponsable_exportar_monitor)
          ->where('estado', 'ACT')
          ->pluck('compromiso_id'));
      }
    }



    return $data;
  }
  public function filtrosProvinciasCantones($data, $request)
  {
    $objRepositorio = new RepositorioController();

    $provincia_id_exportar_monitor = $objRepositorio->verifcarDatoArray($request->provincia_id_exportar_monitor);
    $canton_id_exportar_monitor = $objRepositorio->verifcarDatoArray($request->canton_id_exportar_monitor);
    $parroquia_id_exportar_monitor = $objRepositorio->verifcarDatoArray($request->parroquia_id_exportar_monitor);
    $filtro_provincia = false;
    $filtro_canton = false;
    $filtro_parroquia = false;
    if ($objRepositorio->validarArregloVacio($provincia_id_exportar_monitor)) $filtro_provincia = true;
    if ($objRepositorio->validarArregloVacio($canton_id_exportar_monitor)) $filtro_canton = true;
    if ($objRepositorio->validarArregloVacio($parroquia_id_exportar_monitor)) $filtro_parroquia = true;

    if ($filtro_provincia && $filtro_canton) {

      $cqlUbicacionProvincia = Ubicacion::select('compromiso_id')
        ->whereIn('parametro_id', $objRepositorio->selectParametroCiudad(
          'PROVINCA',
          $provincia_id_exportar_monitor,
        ))
        ->pluck('compromiso_id');

      $data = $data->whereIn('compromisos.id', $cqlUbicacionProvincia);

      $cqlUbicacionCanton = Ubicacion::select('compromiso_id')
        ->whereIn('parametro_id', $objRepositorio->selectParametroCiudad(
          'CANTON',
          $canton_id_exportar_monitor,
        ))
        ->pluck('compromiso_id');

      $data = $data->whereIn('compromisos.id', $cqlUbicacionCanton);
    }
    if ($filtro_provincia && !$filtro_canton) {
      $totalRegistrosUbicacion = $objRepositorio->selectParametroCiudad(
        'PROVINCIA',
        $provincia_id_exportar_monitor
      );
      $cqlUbicacionProvincia = Ubicacion::select('compromiso_id')
        ->whereIn('parametro_id', $totalRegistrosUbicacion)
        ->distinct('compromiso_id')
        ->pluck('compromiso_id');

      $data = $data->whereIn('compromisos.id', $cqlUbicacionProvincia);
    }
    if (!$filtro_provincia && $filtro_canton) {

      $cqlUbicacionCanton = Ubicacion::select('compromiso_id')
        ->whereIn('parametro_id', $objRepositorio->selectParametroCiudad(
          'CANTON',
          $canton_id_exportar_monitor,
        ))
        ->pluck('compromiso_id');

      $data = $data->whereIn('compromisos.id', $cqlUbicacionCanton);
    }
    return $data;
  }
  public function filtroEstadosCompromiso($data, $request)
  {
    $objRepositorio = new RepositorioController();
    $estado_id_exportar_monitor = $objRepositorio->verifcarDatoArray($request->estado_id_exportar_monitor);
    $estado_porcentaje_id_exportar_monitor = $objRepositorio->verifcarDatoArray($request->estado_porcentaje_id_exportar_monitor);
    if ($objRepositorio->validarArregloVacio($estado_id_exportar_monitor))  $data = $data->whereIn('compromisos.estado_id', $estado_id_exportar_monitor);
    if ($objRepositorio->validarArregloVacio($estado_porcentaje_id_exportar_monitor)) $data = $data->whereIn('compromisos.estado_porcentaje_id', $estado_porcentaje_id_exportar_monitor);
    return $data;
  }
  public function filtrosConsultaExcel($data, $request)
  {
    $objRepositorio = new RepositorioController();
    if (Auth::user()->evaluarole(['MONITOR'])) {
      $fecha_inicio = $request->fecha_inicio_exportar_monitor;
      $fecha_fin = $request->fecha_fin_exportar_monitor;

      $habilitarFechaInicio = $request->habilitarFechaInicio;
      $habilitarFechaFin = $request->habilitarFechaFin;
      $fecha_inicio_fin_exportar_monitor = $request->fecha_inicio_fin_exportar_monitor;
      $fecha_fin_fin_exportar_monitor = $request->fecha_fin_fin_exportar_monitor;

      $habilitarFechaAntecedente = $request->habilitarFechaAntecedente;
      $fecha_inicio_antecedente_exportar_monitor = $request->fecha_inicio_antecedente_exportar_monitor;
      $fecha_fin_antecedente_exportar_monitor = $request->fecha_fin_antecedente_exportar_monitor;

      $habilitarFechaUltimoAvance = $request->habilitarFechaUltimoAvance;
      $fecha_inicio_avance_compromiso_exportar_monitor = $request->fecha_inicio_exportar_monitor;
      $fecha_fin_avance_compromiso_exportar_monitor = $request->fecha_fin_avance_exportar_monitor;
      $nombre_compromiso_exportar_monitor = $request->nombre_compromiso_exportar_monitor;
      $codigo_compromiso_exportar_monitor = $request->codigo_compromiso_exportar_monitor;

      $tipo_id_exportar_monitor = $objRepositorio->verifcarDatoArray($request->tipo_id_exportar_monitor);
      $descripcion_avance_exportar_monitor = $request->descripcion_avance_exportar_monitor;
      $descripcion_antecedente_exportar_monitor = $request->descripcion_antecedente_exportar_monitor;
      $habilitarFechaCumplido = $request->habilitarFechaCumplido;
      $fecha_inicio_cuumplido_exportar_monitor = $request->fecha_inicio_cuumplido_exportar_monitor;
      $fecha_fin_cumplido_exportar_monitor = $request->fecha_fin_cumplido_exportar_monitor;

      $data = $this->filtrosProvinciasCantones($data, $request);
      $data = $this->filtroInstitucional($data, $request);
      $data = $this->filtroEstadosCompromiso($data, $request);

      if ($habilitarFechaInicio == 'true') $data->whereDate('compromisos.fecha_inicio', '>=', $fecha_inicio)->whereDate('compromisos.fecha_inicio', '<=', $fecha_fin);
      if ($habilitarFechaFin == 'true') $data->whereDate('compromisos.fecha_fin', '>=', $fecha_inicio_fin_exportar_monitor)->whereDate('compromisos.fecha_fin', '<=', $fecha_fin_fin_exportar_monitor);

      /*    if ($nombre_compromiso_exportar_monitor != "null" && $nombre_compromiso_exportar_monitor != null)
                $data = $data->where('compromisos.nombre_compromiso', 'like', '%' . $nombre_compromiso_exportar_monitor . '%');
            if ($codigo_compromiso_exportar_monitor != "null" && $codigo_compromiso_exportar_monitor != null)
                $data = $data->where('compromisos.codigo', 'like', '%' . $codigo_compromiso_exportar_monitor . '%');*/

      if ($objRepositorio->validarArregloVacio($tipo_id_exportar_monitor)) $data = $data->whereIn('compromisos.tipo_compromiso_id', $tipo_id_exportar_monitor);

      if ($habilitarFechaCumplido == 'true') {
        $consultaDatos = EstadoPorcentaje::where('descripcion', ['CUMPLIDO', 'CERRADO'])->select('id')->pluck('id');
        $data = $data->whereIn('compromisos.estado_porcentaje_id', $consultaDatos)
          ->whereDate('compromisos.updated_at', '>=', $fecha_inicio_cuumplido_exportar_monitor)
          ->whereDate('compromisos.updated_at', '<=', $fecha_fin_cumplido_exportar_monitor);
      }
      if ($habilitarFechaAntecedente == 'true') {
        $data->join('sc_compromisos.antecedentes as ant_expo', function ($join) use ($fecha_inicio_antecedente_exportar_monitor, $fecha_fin_antecedente_exportar_monitor) {
          $join->on('ant_expo.compromiso_id', 'compromisos.id')
            ->whereDate('ant_expo.fecha_antecedente', '>=', $fecha_inicio_antecedente_exportar_monitor)
            ->whereDate('ant_expo.fecha_antecedente', '<=', $fecha_fin_antecedente_exportar_monitor);
        });
      }
      if ($habilitarFechaUltimoAvance == 'true') {

        $data->join('sc_compromisos.avances as ava_expo', function ($join) use (
          $fecha_inicio_avance_compromiso_exportar_monitor,
          $fecha_fin_avance_compromiso_exportar_monitor
        ) {
          $join->on('ava_expo.compromiso_id', 'compromisos.id')
            ->whereDate('ava_expo.fecha_revisa', '>=', $fecha_inicio_avance_compromiso_exportar_monitor)
            ->whereDate('ava_expo.fecha_revisa', '<=', $fecha_fin_avance_compromiso_exportar_monitor);
        });
      }
      $letras_tildes = 'áéíóúÁÉÍÓÚäëïöüÄËÏÖÜ';
      $letras_sin_tildes = 'aeiouAEIOUaeiouAEIOU';
      
      if ($descripcion_avance_exportar_monitor != "null" && $descripcion_avance_exportar_monitor != null) {
        $cqlAprobado = Avance::select('compromiso_id')
        ->whereRaw('translate(UPPER(descripcion),\'' . $letras_tildes . '\',\'' . $letras_sin_tildes . '\') ILIKE ? ', ['%' .
        strtoupper((new SelectController())->eliminar_acentos($descripcion_avance_exportar_monitor)) . '%'])
        ->where('estado', 'ACT')
        ->where('aprobado', 'SI')
        ->pluck('compromiso_id');
        
        dd($data->toSql()); ///AQUI FALLA OJO
        $data = $data->whereIn('compromisos.id', $cqlAprobado);
      }
      if ($descripcion_antecedente_exportar_monitor != "null" && $descripcion_antecedente_exportar_monitor != null) {
        $cqlAntecedente = Antecedente::select('compromiso_id')
          ->whereRaw('translate(UPPER(descripcion),\'' . $letras_tildes . '\',\'' . $letras_sin_tildes . '\') ILIKE ? ', ['%' .
          strtoupper((new SelectController())->eliminar_acentos($descripcion_antecedente_exportar_monitor)) . '%'])
          ->where('estado', 'ACT')
          ->pluck('compromiso_id');

        $data = $data->whereIn('compromisos.id', $cqlAntecedente);
      }
    } else {
      $fecha_inicio = $request->fecha_inicio;
      $fecha_fin = $request->fecha_fin;
      $data->whereDate('compromisos.fecha_inicio', '>=', $fecha_inicio)->whereDate('compromisos.fecha_inicio', '<=', $fecha_fin);
    }


    return $data;
  }
  public function filtrarDatosConsultaCompromisos($request, $exportar = false, $conteo = false, $estado = null)
  { 
    $tabla = $request->tabla;
    $estado = is_null($estado) ? $request->estado : 'ACT';
    $asignaciones = $request->asignaciones;
    $temporales = $request->temporales;
    $pendientes = $request->pendientes;
    $filtro = $request->filtro;

    $filtros_monitor['pendientes'] = $pendientes;
    $filtros_monitor['temporales'] = $temporales;
    $filtros_monitor['asignaciones'] = $asignaciones;
    $corresponsable = false;
    $data = (new RepositorioController())->selectConsultaCompromisos($conteo, $exportar, $temporales);
    if (!is_null($request->corresponsable)) {
      if ($request->corresponsable == "true") {
        $corresponsable = true;

        $consultaCorresponsable = Corresponsable::select('compromiso_id')
          ->where('institucion_corresponsable_id', (new RepositorioController())->consultaInstitucionporMinistro(Auth::user()->id))
          ->where('estado', 'ACT')
          ->pluck('compromiso_id');

        $data = $data->whereIn('compromisos.id', $consultaCorresponsable);
      }
    }
    if (!$corresponsable) {
      if (!$exportar) {
        $data = (new RepositorioController())->restriccionesPorEstado($data, $estado, $tabla, $filtros_monitor, 1);
      }
      $data = (new RepositorioController())->restriccionesPorRol($data);
      $objRepositorio = new RepositorioController();
      $nombre_compromiso_exportar_monitor = $request->nombre_compromiso_exportar_monitor;
      $codigo_compromiso_exportar_monitor = $request->codigo_compromiso_exportar_monitor;
      $monitor_id_exportar_monitor = $request->monitor_id_exportar_monitor;
      $verificacion_filtrada_previa = false;

      if ($objRepositorio->validarArregloVacio($objRepositorio->verifcarDatoArray($nombre_compromiso_exportar_monitor))) {
        $data = $data->whereIn('compromisos.id', $objRepositorio->verifcarDatoArray($nombre_compromiso_exportar_monitor));
        $verificacion_filtrada_previa = true;
      }
      if ($objRepositorio->validarArregloVacio($objRepositorio->verifcarDatoArray($codigo_compromiso_exportar_monitor))) {
        $data = $data->whereIn('compromisos.id', $objRepositorio->verifcarDatoArray($codigo_compromiso_exportar_monitor));
        $verificacion_filtrada_previa = true;
      }
      //Se agrega el filtro monitor FC ago23
      if ($objRepositorio->validarArregloVacio($objRepositorio->verifcarDatoArray($monitor_id_exportar_monitor))) {
        $data = $data->whereIn('compromisos.monitor_id', $objRepositorio->verifcarDatoArray($monitor_id_exportar_monitor));
        $verificacion_filtrada_previa = true;
      }
      if (($filtro != "false" || $exportar) && !$verificacion_filtrada_previa) $data = $this->filtrosConsultaExcel($data, $request);
      /*    if (($filtro != "false" || $exportar) && !$verificacion_filtrada_previa) $data = $this->filtrosConsultaExcel($data, $request);
      else */
    }
    //dd($data->toSql()); //FCago23
    if (!$conteo) {
      /*if (!$exportar)   $data = $data->orderby('compromisos.codigo', 'desc')->orderby('compromisos.fecha_inicio', 'desc');
      else $data->orderby('compromisos.fecha_inicio', 'desc');*/
      if (!$exportar) {
        $data = $data->orderBy('compromisos.created_at', 'desc');
      }
    }


    return $data->distinct();
  }

  public function consultaEstados(request $request)
  {
    $tabla = $request->tabla;
    $asignaciones = $request->asignaciones;
    $temporales = $request->temporales;
    $pendientes = $request->pendientes;

    $filtros_monitor['pendientes'] = $pendientes;
    $filtros_monitor['temporales'] = $temporales;
    $filtros_monitor['asignaciones'] = $asignaciones;
    $data = $this->filtrarDatosConsultaCompromisos($request, false, true, 'ACT');
    if ($asignaciones != "false" && Auth::user()->evaluarole(['MONITOR']))  $data = $data->where('compromisos.monitor_id', Auth::user()->id);
    /*, OPT,BUE,LEV,MOD,GRA,*/
    $tabla = "2";
    $consulta["optimo"] = (new RepositorioController())->restriccionesPorEstado(clone $data, 'OPT', $tabla, $filtros_monitor)->get()->count();
    $consulta["bueno"] = (new RepositorioController())->restriccionesPorEstado(clone $data, 'BUE', $tabla, $filtros_monitor)->get()->count();
    $consulta["leve"] = (new RepositorioController())->restriccionesPorEstado(clone $data, 'LEV', $tabla, $filtros_monitor)->get()->count();
    $consulta["moderado"] = (new RepositorioController())->restriccionesPorEstado(clone $data, 'MOD', $tabla, $filtros_monitor)->get()->count();
    $consulta["grave"] = (new RepositorioController())->restriccionesPorEstado(clone $data, 'GRA', $tabla, $filtros_monitor)->get()->count();
    
    /*, PLA,CUM,CER,STA,EJE,*/
    $tabla = "1";
    $consulta["cerrado"] = (new RepositorioController())->restriccionesPorEstado(clone $data, 'CER',  $tabla, $filtros_monitor)->get()->count();
    $consulta["planificacion"] = (new RepositorioController())->restriccionesPorEstado(clone $data, 'PLA',  $tabla, $filtros_monitor)->get()->count();
    $consulta["cumplido"] = (new RepositorioController())->restriccionesPorEstado(clone $data, 'CUM', $tabla, $filtros_monitor)->get()->count();
    $consulta["standby"] = (new RepositorioController())->restriccionesPorEstado(clone $data, 'STA',  $tabla, $filtros_monitor)->get()->count();
    $consulta["ejecucion"] = (new RepositorioController())->restriccionesPorEstado(clone $data, 'EJE',  $tabla, $filtros_monitor)->get()->count();
    //  $consulta["registrados"]=0;
    $consultaEstadosCombinados = $this->consultaEstadosCombinados($asignaciones, clone $data->pluck('id'));
    $consulta["asignaciones_"] = $consultaEstadosCombinados['asignaciones_'];
    $consulta["pendientes_"] = $consultaEstadosCombinados['pendientes_'];
    $consulta["temporales_"] = $consultaEstadosCombinados['temporales_'];
    $consulta["registrados"] = $data->get()->count();

    $array_response['datos'] = $consulta;
    return response()->json($array_response, 200);
  }
  public function getDatatableCompromisosPOSTServerSide(Request $request)
  {
    $data = $this->filtrarDatosConsultaCompromisos($request);

    return Datatables::of($data)
      ->addIndexColumn()
      ->addColumn('', function ($row) {
        //  $tipo = 'aprobado';
        $btn = '';
        $btn .= '<table>';
        $btn .= '    <tr>';
        $btn .= '        <td style="padding:2px">';
        $btn .= '            <button title="Editar" class="btn btn-primary  btn-xs btn-block"';
        $btn .= '                data-toggle="modal" data-target="#modal-default"';
        $btn .= '                onclick="app.editar(\'' . $row->id . '\')"';
        $btn .= '                data-backdrop="static" data-keyboard="false"><i';
        $btn .= '                    class="fa fa-edit"></i>&nbsp;Detalle</button>';
        $btn .= '        </td>';
        $btn .= '    </tr>';
        $btn .= '    <tr>';
        if (!Auth::user()->evaluarole(['MINISTRO'])) {
          $btn .= '            <td style="padding:2px"> <button title="Eliminar" ';
          $btn .= '                    class="btn btn-danger  btn-xs btn-block"';
          $btn .= '                    onclick="app.eliminarCompromiso(\'' . $row->id . '\')"><i';
          $btn .= '                        class="fa fa-times"></i>&nbsp;Eliminar</button></td>';
        }
        $btn .= '    </tr>';
        $btn .= '</table>';
        return $btn;
      })
      ->addColumn('fecha_creacion', function ($row) {
          return $row->created_at;
      })
      ->addColumn('registro_', function ($row) {
        return $row->reg_;
    })
      ->rawColumns(['','fecha_creacion','registro_'])
      ->toJson();
  }
  public function getDatatableCompromisosServerSide(
    $estado,
    $tabla,
    $asignaciones,
    $temporales,
    $pendientes

  ) {

    $data = Compromiso::with([
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
      if ($tabla == "1") $data = $data->where('estado_id', Estado::where('abv', $estado)->get()->first()->id);
      else $data = $data->where('estado_porcentaje_id', EstadoPorcentaje::where('abv', $estado)->get()->first()->id);
    }
    if ($asignaciones == "true")  $data = $data->where('monitor_id', Auth::user()->id);

    if (Auth::user()->evaluarole(['MINISTRO'])) {

      $data = $data->whereNotNull('codigo');
      $data = $data->whereIn('id', Responsable::select('compromiso_id')
        ->where('institucion_id', (new RepositorioController())->consultaInstitucionporMinistro(Auth::user()->id))
        ->where('estado', 'ACT')
        ->pluck('compromiso_id'));
    } else {
      if ($temporales == "true") $data = $data->whereNull('codigo');
      else  $data = $data->whereNotNull('codigo');
      if ($pendientes == "true")  $data = $data->where('pendientes', '>', 0);
    }
    $data = $data->orderby('id', 'desc')->get();

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

  public function editarCompromiso($id)
  {
    return Compromiso::with([
      'estado',
      'estado_porcentaje',
      'tipo',
      'objetivos' => function ($q) {
        $q->where('estado', 'ACT')->where('aprobado', true)->orderby('id', 'asc');
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
    $array_response['message'] = 'Eliminado exitoso';

    try {
      DB::connection('pgsql_presidencia')->beginTransaction();
      $cql = Objetivo::find($request->id);

      $cql->estado = 'INA';
      $cql->updated_at = $hoy;
      $cql->save();

      $cqlRecorrido = Objetivo::select('id')
        ->where('compromiso_id', $cql->compromiso_id)
        ->where('estado', 'ACT')
        ->pluck('id')
        ->toArray();

      foreach ($cqlRecorrido as $key => $item) {
        $cql = Objetivo::find($item);
        $cql->numero = $key + 1;
        $cql->save();
      }

      $descripcion = 'Elimino el objetivo ' . $cql->numero . ', el usuario ' . Auth::user()->nombres;
      (new RepositorioController())->nuevaTransaccion($descripcion, $cql->compromiso_id);

      $array_response['status'] = 200;
      $array_response['objetivos'] = Objetivo::where('compromiso_id', $cql->compromiso_id)
        ->where('estado', 'ACT')->where('aprobado', true)->orderby('id', 'asc')->get()->toArray();

      DB::connection('pgsql_presidencia')->commit();
    } catch (\Exception $e) {
      DB::connection('pgsql_presidencia')->rollBack();

      $array_response['status'] = 300;
      $array_response['objetivos'] = $e->getMessage();
      $array_response['message'] = 'Error al eliminar el registro';
    }
    return response()->json($array_response, 200);
  }
  public function negarAvance(request $request)
  {
    $hoy = date("Y-m-d H:i:s");
    try {
      DB::connection('pgsql_presidencia')->beginTransaction();
      $cql = Avance::find($request->id);
      $cql->usuario_actualiza = Auth::user()->id;
      $cql->fecha_revisa = $hoy;
      $cql->usuario_revisa = Auth::user()->id;
      $cql->aprobado = 'NO';
      $cql->motivo = $request->motivo;
      $cql->save();

      $descripcion = 'Se nego el avance ' . $cql->numero . ',el usuario ' . Auth::user()->nombres;
      (new RepositorioController())->nuevaTransaccion($descripcion, $cql->compromiso_id);
      if (Auth::user()->evaluarole(['MONITOR'])) {
        $cqlCompromiso = Compromiso::find($cql->compromiso_id);
        $cqlCompromiso->pendientes = $cqlCompromiso->pendientes - 1;
        $cqlCompromiso->save();
      }
      $array_response['status'] = 200;
      $array_response['datos'] = $cql->id;

      $descripcion = 'Se rechazó el avance ' . $cql->descripcion . ', por motivo ' . $cql->motivo . ', en el compromiso ' . $cqlCompromiso->codigo;
      $compromiso_id = $cqlCompromiso->id;
      (new RepositorioController())->agregarNotificaciones($descripcion, $compromiso_id, 'AVANCE');

      DB::connection('pgsql_presidencia')->commit();
    } catch (\Exception $e) {
      DB::connection('pgsql_presidencia')->rollBack();

      $array_response['status'] = 300;
      $array_response['datos'] = 0;
    }

    return response()->json($array_response, 200);
  }
  public function aprobarAvance(request $request)
  {
    $hoy = date("Y-m-d H:i:s");

    try {
      DB::connection('pgsql_presidencia')->beginTransaction();
      $cql = Avance::find($request->id);
      $cql->usuario_actualiza = Auth::user()->id;
      $cql->fecha_revisa = $hoy;
      $cql->usuario_revisa = Auth::user()->id;
      $cql->aprobado = 'SI';
      $cql->save();

      $descripcion = 'Se aprobo el avance ' . $cql->numero . ',el usuario ' . Auth::user()->nombres;
      (new RepositorioController())->nuevaTransaccion($descripcion, $cql->compromiso_id);

      $cqlCompromiso = Compromiso::find($cql->compromiso_id);
      $cqlCompromiso->avance_compromiso = $cql->descripcion;
      $cqlCompromiso->avance_id = $cql->id;
      $cqlCompromiso->pendientes = $cqlCompromiso->pendientes - 1;
      $cqlCompromiso->save();

      $array_response['status'] = 200;
      $array_response['datos'] = $cql->id;
      $array_response['descripcion'] = $cql->descripcion;


      $descripcion = 'Se aprobó el avance ' . $cql->descripcion . ', en el compromiso ' . $cqlCompromiso->codigo;
      $compromiso_id = $cqlCompromiso->id;
      (new RepositorioController())->agregarNotificaciones($descripcion, $compromiso_id,  'AVANCE');

      DB::connection('pgsql_presidencia')->commit();
    } catch (\Exception $e) {
      DB::connection('pgsql_presidencia')->rollBack();

      $array_response['status'] = 300;
      $array_response['datos'] = 0;
      $array_response['descripcion'] = "";
    }



    return response()->json($array_response, 200);
  }
  public function eliminarAvance(request $request)
  {
    $hoy = date("Y-m-d H:i:s");
    try {
      DB::connection('pgsql_presidencia')->beginTransaction();
      $cql = Avance::find($request->id);
      $cql->estado = 'INA';
      $cql->usuario_actualiza = Auth::user()->id;
      $cql->updated_at = $hoy;
      $cql->save();

      $cqlRecorrido = Avance::select('id')
        ->where('compromiso_id', $cql->compromiso_id)
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
      (new RepositorioController())->nuevaTransaccion($descripcion, $cql->compromiso_id);

      $array_response['status'] = 200;
      $array_response['datos'] = $cql->id;

      DB::connection('pgsql_presidencia')->commit();
    } catch (\Exception $e) {
      DB::connection('pgsql_presidencia')->rollBack();

      $array_response['status'] = 300;
      $array_response['datos'] = 0;
    }

    return response()->json($array_response, 200);
  }
  public function eliminarAntecedente(request $request)
  {

    $compromiso_id = 0;
    $hoy = date("Y-m-d H:i:s");

    try {
      DB::connection('pgsql_presidencia')->beginTransaction();
      $cql = Antecedente::find($request->id);
      $cql->estado = 'INA';
      $cql->save();
      $compromiso_id = $cql->compromiso_id;

      $cqlRecorrido = Antecedente::select('id')
        ->where('compromiso_id', $cql->compromiso_id)
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
      (new RepositorioController())->nuevaTransaccion($descripcion, $cql->compromiso_id);

      $array_response['status'] = 200;
      $array_response['datos'] = $compromiso_id;
      DB::connection('pgsql_presidencia')->commit();
    } catch (\Exception $e) {
      DB::connection('pgsql_presidencia')->rollBack();

      $array_response['status'] = 300;
      $array_response['datos'] = $compromiso_id;
    }

    return response()->json($array_response, 200);
  }
  public function eliminarCompromiso(request $request)
  {
    $hoy = date("Y-m-d H:i:s");

    try {
      DB::connection('pgsql_presidencia')->beginTransaction();

      Compromiso::where('id', $request->id)->update([
        'estado' => 'INA',
        'motivo_eliminado' => $request->observacion,
        'updated_at' => $hoy
      ]);
      $array_response['status'] = 200;
      $array_response['datos'] = "Grabado Exitosamente";

      $descripcion = 'El usuario ' . Auth::user()->nombres . ', eliminó el compromiso ' . ($request->id) . ',por motivo de ' . $request->observacion;
      (new RepositorioController())->nuevaTransaccion($descripcion, $request->id);

      DB::connection('pgsql_presidencia')->commit();
    } catch (\Exception $ex) {
      DB::connection('pgsql_presidencia')->rollBack();
      $array_response['status'] = 200;
      $array_response['datos'] = "Error al grabar";
    }

    return response()->json($array_response, 200);
  }

  public function eliminarTipo(request $request)
  {
    $cqlDelete = Tipo::where('id', $request->id)->delete();
    return;
    /*$array_response['status'] = 200;
        $array_response['datos'] = 'Eliminado Exitosamente';

         return response()->json($array_response, 200);*/
  }

  public function crearCodigo(request $request)
  {
    try {
      DB::connection('pgsql_presidencia')->beginTransaction();
      $conteo_antecedentes = Antecedente::where('compromiso_id', $request->id)->where('estado', 'ACT')->count();
      if ($conteo_antecedentes == 0)   throw new \Exception("No puede notificar a la institución , deben existir antecedentes registrados");

      $conteo_ubicaciones = Ubicacion::where('compromiso_id', $request->id)->count();
      if ($conteo_ubicaciones == 0)   throw new \Exception("No puede notificar a la institución , deben existir ubicaciones registradas");

      $codigo = (new RepositorioController())->verificacionCodigo($request->institucion_id, $request->id);
      if (is_null($codigo))   throw new \Exception("Error al crear código");

      if (!is_null($codigo)) {
        $descripcion = 'Se creo el codigo ' . $codigo;
        (new RepositorioController())->nuevaTransaccion($descripcion, $request->id, false);
        DB::connection('pgsql_presidencia')->commit();
        $array_response['status'] = 200;
        $array_response['datos'] = $codigo;
      }
    } catch (\Exception $e) {
      DB::connection('pgsql_presidencia')->rollBack();

      $array_response['status'] = 300;
      $array_response['datos'] = $e->getMessage();
    }

    return response()->json($array_response, 200);
  }

  public function guardarAvance(request $request)
  {

    $hoy = date("Y-m-d H:i:s");
    $compromiso_id = $request->id;
    try {
      DB::connection('pgsql_presidencia')->beginTransaction();

      if ($request->idAvance != 0) {
        $cql = Avance::find($request->idAvance);
        $cql->usuario_actualiza = Auth::user()->id;
        $cql->updated_at = $hoy;

        $descripcion = 'Se actualizo el avance ' . $cql->numero;
        (new RepositorioController())->nuevaTransaccion($descripcion, $compromiso_id);
      } else {
        $numero = Avance::where('compromiso_id', $request->id)->where('estado', 'ACT')->get()->count();

        $cql = new Avance();
        $cql->usuario_ingresa = Auth::user()->id;
        $cql->numero = $numero + 1;
        $cql->created_at = $hoy;

        $descripcion = 'Se creo el avance ' . ($numero + 1);
        (new RepositorioController())->nuevaTransaccion($descripcion, $compromiso_id);

        $cqlCompromiso = Compromiso::find($compromiso_id);

        $descripcion = 'Tiene un Avance pendiente de revisar ' . $request->descripcion . ', del compromiso ' . $cqlCompromiso->codigo;
        (new RepositorioController())->agregarNotificaciones($descripcion, $compromiso_id, 'AVANCE');
      }
      $cql->aprobado = 'NO';
      $cql->descripcion = $request->descripcion;
      $cql->compromiso_id = $compromiso_id;
      $cql->save();

      (new RepositorioController())->agregarPendientes($compromiso_id);

      $array_response['status'] = 200;
      $array_response['datos'] = $compromiso_id;
      DB::connection('pgsql_presidencia')->commit();
    } catch (\Exception $e) {
      DB::connection('pgsql_presidencia')->rollBack();

      $array_response['status'] = 300;
      $array_response['datos'] = $e->getMessage();
    }

    return response()->json($array_response, 200);
  }
  public function guardarAntecedente(request $request)
  {
    $hoy = date("Y-m-d H:i:s");
    $hoy = date("Y-m-d H:i:s");
    $compromiso_id = $request->id;

    try {
      DB::connection('pgsql_presidencia')->beginTransaction();
      if ($request->idAntecedente != 0) {
        $cql = Antecedente::find($request->idAntecedente);
        $cql->usuario_actualiza = Auth::user()->id;
        $cql->updated_at = $hoy;


        $descripcion = 'Se actualizo el antecedente ' . $cql->numero;
        (new RepositorioController())->nuevaTransaccion($descripcion, $compromiso_id);
      } else {
        $numero = Antecedente::where('compromiso_id', $request->id)->where('estado', 'ACT')->get()->count();

        $cql = new Antecedente();
        $cql->usuario_ingresa = Auth::user()->id;
        $cql->numero = $numero + 1;
        $cql->created_at = $hoy;


        $descripcion = 'Se creo el antecedente ' . ($numero + 1);
        (new RepositorioController())->nuevaTransaccion($descripcion, $compromiso_id);
      }
      $cql->descripcion = $request->antecedente;
      $cql->fecha_antecedente = $request->fecha_antecedente;
      $cql->compromiso_id = $compromiso_id;
      $cql->save();

      $array_response['status'] = 200;
      $array_response['datos'] = $compromiso_id;

      $array_response['status'] = 200;
      $array_response['datos'] = $compromiso_id;
      DB::connection('pgsql_presidencia')->commit();
    } catch (\Exception $e) {
      DB::connection('pgsql_presidencia')->rollBack();

      $array_response['status'] = 300;
      $array_response['datos'] = $compromiso_id;
    }


    return response()->json($array_response, 200);
  }

  public function guardarUbicacion(request $request)
  {
    $compromiso_id = $request->id;

    try {
      DB::connection('pgsql_presidencia')->beginTransaction();

      $cqlDelete = Ubicacion::where('compromiso_id', $compromiso_id)->delete();
      if ($request->ubicacion != null) {
        $arregloUbicaciones = explode(",", $request->ubicacion);
        foreach ($arregloUbicaciones as $ubicacion) {
          $cql = new Ubicacion();
          $cql->compromiso_id = $compromiso_id;
          $cql->parametro_id = $ubicacion;
          $cql->usuario_ingresa = Auth::user()->id;
          $cql->save();
        }
      }

      $descripcion = 'Se agrego cambios en las ubicaciones del compromiso ';
      (new RepositorioController())->nuevaTransaccion($descripcion, $compromiso_id);

      $array_response['status'] = 200;
      $array_response['datos'] = $compromiso_id;
      DB::connection('pgsql_presidencia')->commit();
    } catch (\Exception $e) {
      DB::connection('pgsql_presidencia')->rollBack();

      $array_response['status'] = 300;
      $array_response['datos'] = 0;
    }
    return response()->json($array_response, 200);
  }
  public function grabarArchivos(request $request)
  {
    $hoy = date("Y-m-d H:i:s");


    try {
      DB::connection('pgsql_presidencia')->beginTransaction();

      $compromiso_id = $request->id;
      foreach ($request->archivo as $archivo) {
        $file      = $archivo;
        $extension = $file->getClientOriginalExtension();
        $nombre = $file->getClientOriginalName();

        $nameFile  = uniqid() . '.' . $extension;
        \Storage::disk('local')->put("COMPROMISOS/$nameFile",  \File::get($file));

        $grabarImg = new Archivo();
        $grabarImg->compromiso_id = $compromiso_id;
        $grabarImg->descripcion = $nameFile;
        $grabarImg->nombre = $nombre;
        $grabarImg->estado = 'ACT';
        $grabarImg->usuario_ingresa = Auth::user()->id;
        $grabarImg->leido = 'NO';
        $grabarImg->created_at = $hoy;
        $grabarImg->save();

        $descripcion = 'Se agrego el archivo ' . $grabarImg->nombre;
        (new RepositorioController())->nuevaTransaccion($descripcion, $grabarImg->compromiso_id);
      }
      $cqlCompromiso = Compromiso::find($compromiso_id);
      if (Auth::user()->evaluarole(['MINISTRO'])) (new RepositorioController())->agregarPendientes($compromiso_id);

      $descripcion = 'Tiene un Archivo Pendiente de descargar con nombre' . $grabarImg->nombre . ' del compromiso ' . $cqlCompromiso->codigo;
      (new RepositorioController())->agregarNotificaciones($descripcion, $compromiso_id, 'ARCHIVO');

      $array_response['status'] = 200;
      $array_response['datos'] = $compromiso_id;
      DB::connection('pgsql_presidencia')->commit();
    } catch (\Exception $e) {
      DB::connection('pgsql_presidencia')->rollBack();

      $array_response['status'] = 300;
      $array_response['datos'] = $e->getMessage();
    }
    return response()->json($array_response, 200);
  }
  public function eliminarArchivo(request $request)
  {
    $hoy = date("Y-m-d H:i:s");

    try {
      DB::connection('pgsql_presidencia')->beginTransaction();

      $cql = Archivo::find($request->id);
      $cql->estado = 'INA';
      $cql->usuario_actualiza = Auth::user()->id;
      $cql->updated_at = $hoy;

      $cql->save();

      $descripcion = 'Se elimino archivo ' . $cql->nombre;
      (new RepositorioController())->nuevaTransaccion($descripcion, $cql->compromiso_id);

      $array_response['status'] = 200;
      $array_response['datos'] = $cql->id;
      DB::connection('pgsql_presidencia')->commit();
    } catch (\Exception $e) {
      DB::connection('pgsql_presidencia')->rollBack();

      $array_response['status'] = 300;
      $array_response['datos'] = 0;
    }
    return response()->json($array_response, 200);
  }

  public function editarMensaje(request $request)
  {

    $array_response['status'] = 200;
    $array_response['datos'] = Mensaje::find($request->id);

    return response()->json($array_response, 200);
  }
  public function grabarMensaje(request $request)
  {
    $corresponsable = false;
    if ($request->chk_corresponsable == "true") $corresponsable = true;
    $hoy = date("Y-m-d H:i:s");

    try {
      DB::connection('pgsql_presidencia')->beginTransaction();
      $anterior_descripcion = '';
      $compromiso_id = $request->compromiso_id;
      if ($request->id != '0') {
        $cql =  Mensaje::find($request->id);
        $cql->usuario_actualiza = Auth::user()->id;
        $cql->updated_at = $hoy;
        $anterior_descripcion = $cql->descripcion;
      } else {
        $cql = new Mensaje();
        $cql->usuario_ingresa = Auth::user()->id;
        $cql->estado = 'ACT';
        $cql->leido = 'NO';
        $cql->created_at = $hoy;
        $cql->compromiso_id = $compromiso_id;
        $cql->monitor = Auth::user()->evaluarole(['MONITOR']) ? true : false;
        $cql->corresponsable = !Auth::user()->evaluarole(['MONITOR']) ? false : $corresponsable;
        $cql->visualizacion_envio = true;
      }

      $cql->descripcion = $request->descripcion;
      $cql->save();
      $descripcion = 'Agrego un nuevo mensaje el usuario ' . Auth::user()->nombres;
      if ($request->id != '0')   $descripcion = 'Se ha editado el mensaje ' . $anterior_descripcion . ' por ' . $request->descripcion . ', el usuario ' . Auth::user()->nombres;

      (new RepositorioController())->nuevaTransaccion($descripcion, $compromiso_id);

      $cqlCompromiso = Compromiso::find($compromiso_id);
      if (Auth::user()->evaluarole(['MINISTRO'])) (new RepositorioController())->agregarPendientes($compromiso_id);

      $descripcion = 'Tiene un Mensaje Pendiente con concepto ' . $cql->descripcion . ', en el compromiso ' . $cqlCompromiso->codigo;
      $compromiso_id = $cqlCompromiso->id;
      (new RepositorioController())->agregarNotificaciones($descripcion,  $compromiso_id, 'MENSAJE');
      //Para envio de correo electrónico al corresponsable
      if (Auth::user()->evaluarole(['MONITOR'])) {
        if ($corresponsable == true) {
          $asignacion_responsable_corresponsable = (new RepositorioController())->consultaResponsableCorresponsable($compromiso_id);

          $msj = 'Se le notifica que tiene un mensaje en el compromiso ' . $cqlCompromiso->codigo . ', por favor revisar la información';
          (new RepositorioController())->notificarUsuario($asignacion_responsable_corresponsable['corresponsable'], $msj);
        }
      }
      $array_response['status'] = 200;
      $array_response['datos'] = $compromiso_id;
      DB::connection('pgsql_presidencia')->commit();
    } catch (\Exception $e) {
      DB::connection('pgsql_presidencia')->rollBack();

      $array_response['status'] = 300;
      $array_response['datos'] = $e->getMessage();
    }
    return response()->json($array_response, 200);
  }



  public function descargarArchivo(request $request)
  {
    $hoy = date("Y-m-d H:i:s");
    try {
      DB::connection('pgsql_presidencia')->beginTransaction();
      $cql = Archivo::find($request->id);
      if ($cql->usuario_ingresa != Auth::user()->id) {
        $cql->fecha_revisa = $hoy;
        $cql->usuario_revisa = Auth::user()->id;
        $cql->leido = 'SI';
        $cql->save();

        if (Auth::user()->evaluarole(['MONITOR'])) {
          $cqlCompromiso = Compromiso::find($cql->compromiso_id);
          $cqlCompromiso->pendientes = $cqlCompromiso->pendientes - 1;
          $cqlCompromiso->save();
        }
      }

      $descripcion = 'Ha descargado el archivo ' . $cql->nombre . ', el usuario ' . Auth::user()->nombres;
      (new RepositorioController())->nuevaTransaccion($descripcion, $cql->compromiso_id);

      $array_response['status'] = 200;
      $array_response['datos'] = $cql->id;
      DB::connection('pgsql_presidencia')->commit();
    } catch (\Exception $e) {
      DB::connection('pgsql_presidencia')->rollBack();

      $array_response['status'] = 300;
      $array_response['datos'] = 0;
    }
    return response()->json($array_response, 200);
  }
  public function leerMensaje(request $request)
  {
    $hoy = date("Y-m-d H:i:s");

    try {
      DB::connection('pgsql_presidencia')->beginTransaction();

      $cql = Mensaje::find($request->id);
      $cql->fecha_revisa = $hoy;
      $cql->usuario_revisa = Auth::user()->id;
      $cql->leido = 'SI';
      $cql->save();

      if (Auth::user()->evaluarole(['MONITOR'])) {
        $cqlCompromiso = Compromiso::find($cql->compromiso_id);
        $cqlCompromiso->pendientes = $cqlCompromiso->pendientes - 1;
        $cqlCompromiso->save();
      }

      $descripcion = 'Ha leido un mensaje el usuario ' . Auth::user()->nombres;
      (new RepositorioController())->nuevaTransaccion($descripcion, $cql->compromiso_id);

      $array_response['status'] = 200;
      $array_response['datos'] = $cql->id;
      DB::connection('pgsql_presidencia')->commit();
    } catch (\Exception $e) {
      DB::connection('pgsql_presidencia')->rollBack();

      $array_response['status'] = 300;
      $array_response['datos'] = $e->getMessage();
    }

    return response()->json($array_response, 200);
  }
  public function eliminarMensaje(request $request)
  {
    $hoy = date("Y-m-d H:i:s");

    try {
      DB::connection('pgsql_presidencia')->beginTransaction();

      $cql = Mensaje::find($request->id);
      $cql->estado = 'INA';
      $cql->usuario_actualiza = Auth::user()->id;
      $cql->updated_at = $hoy;
      $cql->save();

      $descripcion = 'Elimino un mensaje el usuario ' . Auth::user()->nombres;
      (new RepositorioController())->nuevaTransaccion($descripcion, $cql->compromiso_id);

      $array_response['status'] = 200;
      $array_response['datos'] = $cql->id;
      DB::connection('pgsql_presidencia')->commit();
    } catch (\Exception $e) {
      DB::connection('pgsql_presidencia')->rollBack();

      $array_response['status'] = 300;
      $array_response['datos'] = 0;
    }


    return response()->json($array_response, 200);
  }
  public function guardarCompromiso(request $request)
  {
    $conteo = $this->consulta($request->asignaciones);
    $compromiso_id = (new RepositorioController())->validarCompromiso($request);
    if ($compromiso_id['status'] != 200) {
      $array_response['status'] = 300;
      $array_response['datos'] = $compromiso_id['message'];
      $array_response['cerrado'] = "false";
    } else {
      $cqlCompromiso = Compromiso::find($compromiso_id['message']);
      $cerrado = $cqlCompromiso->cerrado;
      $array_response['status'] = 200;
      $array_response['datos'] = $compromiso_id['message'];
      $array_response['cerrado'] = $cerrado;
    }

    $array_response['conteo'] = $conteo;


    return response()->json($array_response, 200);
  }

  public function guardarObjetivos(request $request)
  {
    $compromiso_id = $request->id;
    $hoy = date("Y-m-d H:i:s");
    try {
      DB::connection('pgsql_presidencia')->beginTransaction();

      if ($request->idObjetivo != 0) {
        $cql = Objetivo::find($request->idObjetivo);
        $cql->usuario_actualiza = Auth::user()->id;
        $cql->updated_at = $hoy;

        $descripcion = 'Se actualizo el objetivo ' . $cql->numero;
        (new RepositorioController())->nuevaTransaccion($descripcion, $compromiso_id);
        (new RepositorioController())($cql, $request);
      } else {
        $numero = Objetivo::where('compromiso_id', $request->id)->where('estado', 'ACT')->get()->count();
        $cql = new Objetivo();
        $cql->usuario_ingresa = Auth::user()->id;
        $cql->numero = $numero + 1;
        $cql->created_at = $hoy;

        $descripcion = 'Se Creo el Objetivo ' . ($numero + 1);
        (new RepositorioController())->nuevaTransaccion($descripcion, $compromiso_id);

        $cql->fecha_inicio = $request->fecha_inicio_objetivo;
        $cql->fecha_fin = $request->fecha_fin_objetivo;
        $cql->temporalidad_id = $request->temporalidad_id;
        $cql->compromiso_id = $compromiso_id;

        //     $model = (new RepositorioController())->consultaUsuariosRol('MONITOR');
        $compromisoAfectado = Compromiso::find($compromiso_id);
        $compromisoAfectado = $compromisoAfectado->codigo != null && $compromisoAfectado->codigo != '' ? $compromisoAfectado->codigo : ('Temporal ' . $compromisoAfectado->id);

        /* $msj = 'Se le notifica que tiene un cronograma POR APROBAR en el compromiso ' . $compromisoAfectado . ', por favor revisar la informaciÃ³n';
                (new RepositorioController())->notificarUsuario($model, $msj);*/

        $cqlCompromiso = Compromiso::select('codigo')->where('id', $compromiso_id)->first();
        $descripcion = 'Tiene un Objetivo Pendiente  ' . $request->objetivo . ', en el compromiso ' . $cqlCompromiso->codigo;
        $compromiso_id = $cqlCompromiso->id;
        (new RepositorioController())->agregarNotificaciones($descripcion,  $cql->compromiso_id, 'OBJETIVO');
      }
      $cql->tipo_objetivo_id = $request->tipo_objetivo_id;
      $cql->objetivo = $request->objetivo;
      $cql->descripcion = $request->descripcion_meta;
      $cql->meta = $request->meta;
      $cql->save();
      (new RepositorioController())->agregarPendientes($cql->compromiso_id);
      /* if ($request->idObjetivo == 0) {
                $tmp = Temporalidad::find($request->temporalidad_id);
                (new RepositorioController)->crearPeriodosCalendarios(
                    $request->fecha_inicio_objetivo,
                    $request->fecha_fin_objetivo,
                    $tmp,
                    $cql->id,
                    false
                );
            }
*/

      $array_response['status'] = 200;
      $array_response['datos'] = $compromiso_id;
      $array_response['objetivos'] = Objetivo::where('compromiso_id', $compromiso_id)
        ->where('estado', 'ACT')->where('aprobado', true)->orderby('id', 'asc')->get()->toArray();
      DB::connection('pgsql_presidencia')->commit();
    } catch (\Exception $e) {
      DB::connection('pgsql_presidencia')->rollBack();

      $array_response['status'] = 300;
      $array_response['datos'] = $e->getMessage();
      $array_response['objetivos'] = Objetivo::where('compromiso_id', $compromiso_id)
        ->where('estado', 'ACT')->where('aprobado', true)->orderby('id', 'asc')->get()->toArray();
    }

    return response()->json($array_response, 200);
  }

  public function aprobarObjetivo(request $request)
  {

    try {
      DB::connection('pgsql_presidencia')->beginTransaction();

      $cqlObjetivo = Objetivo::find($request->id);
      $cqlObjetivo->aprobado = true;
      $cqlObjetivo->desbloquear = false;
      $cqlObjetivo->save();

      $descripcion = 'Se aprobo el objetivo: ' . $request->objetivo;
      (new RepositorioController())->nuevaTransaccion($descripcion, $cqlObjetivo->compromiso_id);

      $cqlCompromiso = Compromiso::find($cqlObjetivo->compromiso_id);
      $cqlCompromiso->pendientes = $cqlCompromiso->pendientes - 1;
      $cqlCompromiso->save();

      $array_response['status'] = 200;
      $array_response['datos'] = $request->id;

      $descripcion = 'El objetivo ' . $request->objetivo . ' Ha sido aprobado, del compromiso ' . $cqlCompromiso->codigo;
      $compromiso_id =  $cqlCompromiso->id;
      (new RepositorioController())->agregarNotificaciones($descripcion, $compromiso_id, 'OBJETIVO');

      DB::connection('pgsql_presidencia')->commit();
    } catch (\Exception $e) {
      DB::connection('pgsql_presidencia')->rollBack();

      $array_response['status'] = 300;
      $array_response['datos'] = $request->id;
    }


    return response()->json($array_response, 200);
  }
  public function rechazarObjetivo(request $request)
  {

    try {
      DB::connection('pgsql_presidencia')->beginTransaction();

      $cqlObjetivo = Objetivo::find($request->id);
      $cqlObjetivo->motivo_negado = $request->observacion;
      $cqlObjetivo->aprobado = false;
      $cqlObjetivo->save();

      $descripcion = 'Se rechazo el objetivo: ' . $request->objetivo . ', por motivo: ' . $request->observacion;
      (new RepositorioController())->nuevaTransaccion($descripcion, $cqlObjetivo->compromiso_id);

      $cqlCompromiso = Compromiso::find($cqlObjetivo->compromiso_id);
      $cqlCompromiso->pendientes = $cqlCompromiso->pendientes - 1;
      $cqlCompromiso->save();
      DB::connection('pgsql_presidencia')->commit();

      $array_response['status'] = 200;
      $array_response['datos'] = $request->id;

      $descripcion = 'El objetivo ' . $request->objetivo . ' Ha sido rechazado, del compromiso ' . $cqlCompromiso->codigo;
      $compromiso_id =  $cqlCompromiso->id;
      (new RepositorioController())->agregarNotificaciones($descripcion, $compromiso_id, 'OBJETIVO');
    } catch (\Exception $e) {
      DB::connection('pgsql_presidencia')->rollBack();

      $array_response['status'] = 300;
      $array_response['datos'] = $e->getMessage();
    }



    return response()->json($array_response, 200);
  }
  //Desbloquear un objetivo aprobado por parte del monitor
  public function desbloquearObjetivo(request $request)
  {

    $cqlObjetivo = Objetivo::find($request->id);
    $cqlObjetivo->aprobado = null;
    $cqlObjetivo->desbloquear = true;
    $cqlObjetivo->save();

    $descripcion = 'Se desbloqueÃ³ el objetivo: ' . $request->objetivo;
    (new RepositorioController())->nuevaTransaccion($descripcion, $cqlObjetivo->compromiso_id);

    $array_response['status'] = 200;
    $array_response['datos'] = $request->id;

    return response()->json($array_response, 200);
  }
  protected function institucionEmisora($institucion_id)
  {
    $cql = Institucion::where('ministro_usuario_id', $institucion_id)->get()->first();
    return $cql != null ? $cql->descripcion : null;
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

    $cqlCompromisosResponsables = [];
    $ministro = Auth::user()->evaluarole(['MINISTRO']);
    $conteo = (new RepositorioController())->selectConsultaCompromisos(true);
    $conteo = (new RepositorioController())->restriccionesPorRol($conteo, true);
    $conteo = (new RepositorioController())->restriccionesPorEstado($conteo);

    if ($asignaciones != "false") $conteo = $conteo->where('monitor_id', Auth::user()->id);
    $consulta["optimo"] = $this->consultarConteoEstado(1, $cq0, $ministro, $cqlCompromisosResponsables, $asignaciones);
    $consulta["bueno"] = $this->consultarConteoEstado(1, $cq1, $ministro, $cqlCompromisosResponsables, $asignaciones);
    $consulta["leve"] = $this->consultarConteoEstado(1, $cq2, $ministro, $cqlCompromisosResponsables, $asignaciones);
    $consulta["moderado"] = $this->consultarConteoEstado(1, $cq3, $ministro, $cqlCompromisosResponsables, $asignaciones);
    $consulta["grave"] = $this->consultarConteoEstado(1, $cq4, $ministro, $cqlCompromisosResponsables, $asignaciones);
    $consulta["registrados"] = $conteo->get()->count();

    $consulta["planificacion"] = $this->consultarConteoEstado(2, $cq_1, $ministro, $cqlCompromisosResponsables, $asignaciones);
    $consulta["cumplido"] = $this->consultarConteoEstado(2, $cq_2, $ministro, $cqlCompromisosResponsables, $asignaciones);
    $consulta["cerrado"] = $this->consultarConteoEstado(2, $cq_3, $ministro, $cqlCompromisosResponsables, $asignaciones);
    $consulta["standby"] = $this->consultarConteoEstado(2, $cq_4, $ministro, $cqlCompromisosResponsables, $asignaciones);
    $consulta["ejecucion"] = $this->consultarConteoEstado(2, $cq_5, $ministro, $cqlCompromisosResponsables, $asignaciones);

    $asigna = Compromiso::where('estado', 'ACT')->whereNotNull('codigo')
      ->where('monitor_id', Auth::user()->id)
      ->get()->count();

    $pendientes = Compromiso::where('estado', 'ACT')->whereNotNull('codigo');
    if ($asignaciones != "false")
      $pendientes = $pendientes->where('monitor_id', Auth::user()->id);
    $pendientes = $pendientes->where('pendientes', '>', 0)
      ->get()->count();

    $temporales = Compromiso::where('estado', 'ACT');
    if ($asignaciones != "false")
      $temporales = $temporales->where('monitor_id', Auth::user()->id);
    $temporales = $temporales->whereNull('codigo');
    $temporales = $temporales->get()->count();

    $consulta["asignaciones_"] = $asigna;
    $consulta["pendientes_"] = $pendientes;
    $consulta["temporales_"] = $temporales;

    return $consulta;
  }
  //Consulta de contadores por estados
  protected function consultaEstadosCombinados($asignaciones, $data)
  {
    $asigna = Compromiso::select('compromisos.id')
      ->join('sc_compromisos.responsables as r', 'r.compromiso_id', 'compromisos.id')
      ->where('compromisos.estado', 'ACT')->whereNotNull('compromisos.codigo')
      ->where('compromisos.monitor_id', Auth::user()->id)
      ->where('compromisos.estado', 'ACT')
      ->whereIn('compromisos.id', $data)
      ->where('r.estado', 'ACT')
      ->get()->count();

    /*  $pendientes = Compromiso::where('estado', 'ACT')->whereNotNull('codigo');
    if ($asignaciones != "false")  $pendientes = $pendientes->where('monitor_id', Auth::user()->id);
    $pendientes = $pendientes->where('pendientes', '>', 0)->whereIn('compromisos.id',$data)->count();
*/
    $temporales = Compromiso::where('estado', 'ACT');
    if ($asignaciones != "false") $temporales = $temporales->where('monitor_id', Auth::user()->id);

    $temporales = $temporales->whereNull('codigo')->count();
    $obj = new \StdClass();
    $obj->institucion_id_busqueda = null;
    $obj->gabinete_id_busqueda = null;
    $obj->monitor_busqueda = $asignaciones != "false" ? Auth::user()->id : null;

    $array_response = (new BusquedasAvanzadasController())->consultaEstadosBusqueda($obj, $data);
    $consulta["asignaciones_"] = $asigna;
    $consulta["pendientes_"] = count(array_unique($array_response['compromisos_intervinientes']));
    $consulta["temporales_"] = $temporales;
    return $consulta;
  }

  public function consulta($asignaciones)
  {

    $conteo = (new RepositorioController())->selectConsultaCompromisos(true);
    $conteo = (new RepositorioController())->restriccionesPorRol($conteo);
    $conteo = (new RepositorioController())->restriccionesPorEstado($conteo);
    /**
     * * Si la asignacion es != false consulta por el monitor...
     * ! if(condicion) -> filtro; sin sangria...
     */
    if ($asignaciones != "false" && Auth::user()->evaluarole(['MONITOR'])) $conteo = $conteo->where('monitor_id', Auth::user()->id);

    /**
     * * continua con el sql...
     */
    $data = (new RepositorioController())->restriccionesPorRol((new RepositorioController())->selectConsultaCompromisos());

    $filtros_monitor['pendientes'] = "false";
    $filtros_monitor['asignaciones'] = $asignaciones;
    $filtros_monitor['temporales'] = "false";
    $consulta["planificacion"] = (new RepositorioController())->restriccionesPorEstado($data, 'PLA',  "2", $filtros_monitor)->get()->count();

    /*, OPT,BUE,LEV,MOD,GRA, */
    $consulta["optimo"] = (new RepositorioController())->restriccionesPorEstado($data, 'OPT', "1", $filtros_monitor)->get()->count();
    $consulta["bueno"] = (new RepositorioController())->restriccionesPorEstado($data, 'BUE', "1", $filtros_monitor)->get()->count();
    $consulta["leve"] = (new RepositorioController())->restriccionesPorEstado($data, 'LEV', "1", $filtros_monitor)->get()->count();
    $consulta["moderado"] = (new RepositorioController())->restriccionesPorEstado($data, 'MOD', "1", $filtros_monitor)->get()->count();
    $consulta["grave"] = (new RepositorioController())->restriccionesPorEstado($data, 'GRA', "1", $filtros_monitor)->get()->count();
    /*, PLA,CUM,CER,STA,EJE, */
    $consulta["cumplido"] = (new RepositorioController())->restriccionesPorEstado($data, 'CUM', "2", $filtros_monitor)->get()->count();
    $consulta["cerrado"] = (new RepositorioController())->restriccionesPorEstado($data, 'CER',  "2", $filtros_monitor)->get()->count();
    $consulta["standby"] = (new RepositorioController())->restriccionesPorEstado($data, 'STA',  "2", $filtros_monitor)->get()->count();
    $consulta["ejecucion"] = (new RepositorioController())->restriccionesPorEstado($data, 'EJE',  "2", $filtros_monitor)->get()->count();

    $consulta["registrados"] = $conteo->get()->count();

    $asigna = Compromiso::where('estado', 'ACT')->whereNotNull('codigo')
      ->where('monitor_id', Auth::user()->id)
      ->get()->count();

    $pendientes = Compromiso::where('estado', 'ACT')->whereNotNull('codigo');
    if ($asignaciones != "false")
      $pendientes = $pendientes->where('monitor_id', Auth::user()->id);
    $pendientes = $pendientes->where('pendientes', '>', 0)
      ->get()->count();

    $temporales = Compromiso::where('estado', 'ACT');
    if ($asignaciones != "false")
      $temporales = $temporales->where('monitor_id', Auth::user()->id);
    $temporales = $temporales->whereNull('codigo');
    $temporales = $temporales->get()->count();

    $consulta["asignaciones_"] = $asigna;
    $consulta["pendientes_"] = $pendientes;
    $consulta["temporales_"] = $temporales;

    return $consulta;
  }
  protected function consultarConteoEstado($tabla, $estado_id, $ministro, $compromisos, $asignaciones)
  {
    $cq_5 = EstadoPorcentaje::where('abv', 'EJE')->get()->first()->id;
    if ($tabla == 1) {
      if (!$ministro)
        $cql = Compromiso::where('estado_porcentaje_id', $cq_5)->where('estado_id', $estado_id)->whereNotNull('codigo');
      else
        $cql = Compromiso::where('estado_porcentaje_id', $cq_5)->where('estado_id', $estado_id)->whereNotNull('codigo')->whereIn('id', $compromisos);
    }
    if ($tabla == 2) {
      if (!$ministro)
        $cql = Compromiso::where('estado_porcentaje_id', $estado_id)->whereNotNull('codigo');
      else
        $cql = Compromiso::where('estado_porcentaje_id', $estado_id)->whereNotNull('codigo')->whereIn('id', $compromisos);
    }
    if ($asignaciones != "false" && Auth::user()->evaluarole(['MONITOR']))
      $cql = $cql->where('monitor_id', Auth::user()->id);
    $cql = $cql->where('estado', 'ACT')->get()->count();

    return $cql;
  }


  public function cargarInstituciones(Request $request)
  {
    $objRepositorio = new RepositorioController();
    if (!is_array($request->id)) {
      $consulta = Institucion::select('id', 'descripcion')->where('institucion_id', $request->id)->where('nivel', 2)->pluck('descripcion', 'id');
    } else {
      if ($objRepositorio->validarArregloVacio($request->id)) $consulta = Institucion::select('id', 'descripcion')->where('nivel', 2)->whereIn('institucion_id', $request->id)->pluck('descripcion', 'id');
      else  $consulta = Institucion::select('id', 'descripcion')->where('nivel', 2)->pluck('descripcion', 'id');
    }

    $array_response['status'] = 200;
    $array_response['datos'] = $consulta;

    return response()->json($array_response, 200);
  }
  public function cargarCantones(Request $request)
  {
    if (!is_array($request->id)) {
      $consulta = parametro_ciudad::select('id', 'descripcion')->where('parametro_id', $request->id)->pluck('descripcion', 'id');
    } else {
      if (
        !in_array("", $request->id) &&
        !in_array("null", $request->id) &&
        !in_array(null, $request->id)
      )
        $consulta = parametro_ciudad::select('id', 'descripcion')->whereIn('parametro_id', $request->id)->pluck('descripcion', 'id');
      else
        $consulta = parametro_ciudad::select('id', 'descripcion')->where('verificacion', 'CANTON')->pluck('descripcion', 'id');
    }

    $array_response['status'] = 200;
    $array_response['datos'] = $consulta;

    return response()->json($array_response, 200);
  }
//CargarParroquias FCago23
  public function cargarParroquias(Request $request)
  {
    if (!is_array($request->id)) {
      $consulta = parametro_ciudad::select('id', 'descripcion')->where('parametro_id', $request->id)->pluck('descripcion', 'id');
    } else {
      if (
        !in_array("", $request->id) &&
        !in_array("null", $request->id) &&
        !in_array(null, $request->id)
      )
        $consulta = parametro_ciudad::select('id', 'descripcion')->whereIn('parametro_id', $request->id)->pluck('descripcion', 'id');
      else
        $consulta = parametro_ciudad::select('id', 'descripcion')->where('verificacion', 'PARROQUIA')->pluck('descripcion', 'id');
    }

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
      ->where('eliminado', false)
      ->get()->first();

    if ($cqlx1 != null) {
      $cqlx1->valor_anterior_meta_acumulada = $consulta->meta_acumulada;
      $cqlx1->valor_anterior_cumplimiento_acumulado = $consulta->cumplimiento_acumulado;
      $cqlx1->save();

      $cql1 = Periodo::where('numero', '>', $consulta->numero)
        ->where('objetivo_id', $consulta->objetivo_id)
        ->where('estado', 'ACT')
        ->orderby('numero', 'asc')
        ->where('eliminado', false)
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
    $cqlUpdateaObjetivo = Objetivo::find($consulta->objetivo_id);
    $cqlUpdateaObjetivo->registro_cumplimiento = true;
    $cqlUpdateaObjetivo->save();

    $cqlUpdateCumplimiento = (new RepositorioController())->actualizarCumplimiento($consulta->objetivo_id);

    $array_response['status'] = 200;
    $array_response['datos'] = $request->objetivo_id;
    $array_response['cumplimiento'] = $cqlUpdateCumplimiento;

    return response()->json($array_response, 200);
  }

  /* AGREGADO POR ADMINISTRACION DE USUARIOS*/
  protected function consultaMinistroporUsuario($ministro_usuario_id)
  {
    $cqlMinistro = User::select('id', 'nombres')->where('id', $ministro_usuario_id)->first();
    return $cqlMinistro;
  }
  function buscarMonitor(Request $request)
  {
    $input = $request->all();
    if (!empty($input['query'])) {
      $busqueda = strtoupper($input['query']);
      $data = User::select(["id", "nombres"])
        ->where(DB::raw('upper(nombres)'), "LIKE", "%{$busqueda}%")
        // ->whereIn('id',$model)
        ->orderby('nombres', 'asc')
        ->get()->take(5);
    } else {
      $data = User::select(["id", "nombres"])
        //   ->whereIn('id',$model)
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
    $model = (new RepositorioController())->consultaUsuariosRol('MINISTRO');
    $input = $request->all();
    if (!empty($input['query'])) {
      $busqueda = strtoupper($input['query']);
      $data = User::select(["id", "nombres"])
        ->where(DB::raw('upper(nombres)'), "LIKE", "%{$busqueda}%")
        ->whereIn('id', $model)
        ->get()->take(5);
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
    $model = (new RepositorioController())->consultaUsuariosRol('MINISTRO');

    if (!empty($input['query'])) {
      $busqueda = strtoupper($input['query']);
      $data = Institucion::select(["instituciones.id", DB::RAW("CONCAT(instituciones.nombre,'/ ',users.nombres) as nombreData")])
        ->join('core.users', 'users.id', 'instituciones.ministro_usuario_id')
        ->where('instituciones.nivel', 2)
        ->whereIn('users.id', $model)
        ->where(DB::raw('upper(nombre)'), "LIKE", "%{$busqueda}%")
        ->get()->take(5);
    } else {
      $data = Institucion::select(["instituciones.id", DB::RAW("CONCAT(instituciones.nombre,'/ ',users.nombres) as nombreData")])
        ->join('core.users', 'users.id', 'instituciones.ministro_usuario_id')
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

  function buscarInstitucionMonitor(Request $request)
  {

    $model = Monitor::select('institucion_id')
    ->where('eliminado',false)
      ->pluck('institucion_id')
      ->toArray();

    $input = $request->all();

    if (!empty($input['query'])) {
      $busqueda = strtoupper($input['query']);
      $data = Institucion::select(["id", "nombre"])
        ->where(DB::raw('upper(nombre)'), "LIKE", "%{$busqueda}%")
        ->whereNotIn('id', $model)
        ->where('nivel', 2)
        ->get()->take(5);
    } else {
      $data = Institucion::select(["id", "nombre"])
        ->where('nivel', 2)
        ->whereNotIn('id', $model)
        ->get();
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

  public function getCargaDatosInstitucionCorresponsables(request $request)
  {
    $model = Corresponsable::select('institucion_corresponsable_id')
      ->where('compromiso_id', $request->id)
      ->where('estado', 'ACT')
      ->pluck('institucion_corresponsable_id');

    $modelRol = (new RepositorioController())->consultaUsuariosRol('MINISTRO');

    $consulta = Institucion::select([
      "instituciones.id",
      DB::RAW(
        "CONCAT(instituciones.nombre,'/ ',users.nombres)
                     as nombreData"
      )
    ])
      ->join('core.users', 'users.id', 'instituciones.ministro_usuario_id')
      ->whereIn('instituciones.id', $model)
      ->whereIn('users.id', $modelRol)
      ->get()->toArray();
    $array_response['status'] = 200;
    $array_response['datos'] = $consulta;

    return response()->json($array_response, 200);
  }

  public function getCargaDatosInstitucion(request $request)
  {

    $ministro = (new RepositorioController())->consultaUsuariosRol('MINISTRO');

    $consulta = Institucion::with([
      'gabinete',
      'delegado' => function ($q) {
        $q->where('estado', 'ACT');
      },
      'usuarios_monitor' => function ($q) {
        $q->with(['usuario']);
      },
      'usuarios_ministro' => function ($q) use ($ministro) {
        $q->where('estado', 'A')->whereIn('id', $ministro);
      }
    ]);
    $cqlIns = $request->id;
    if ($request->tipo == "responsable") {
      $cqlUser = User::find($request->id);
      $cqlIns = $cqlUser != null ? ($cqlUser->institucion_id != null ? $cqlUser->institucion_id : 0) : 0;
    }
    $consulta = $consulta->where('id', $cqlIns)->first();

    $array_response['status'] = 200;
    $array_response['datos'] = $consulta;

    return response()->json($array_response, 200);
  }
}
