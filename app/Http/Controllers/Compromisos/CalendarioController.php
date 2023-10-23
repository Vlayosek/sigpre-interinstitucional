<?php

namespace App\Http\Controllers\Compromisos;

use App\Http\Controllers\Controller;
use App\Core\Entities\Compromisos\Compromiso;
use Yajra\Datatables\Datatables;
use Auth;
use DB;

class CalendarioController extends Controller
{
  private $TABLE_COMPROMISOS = 'compromisos';

  public function index()
  {
    return view('modules.compromisos.calendario.calendario_reportes');
  }

  public function calendario_finalizacion()
  {
    return view('modules.compromisos.calendario.calendario_finalizacion');
  }

  private function retornaConsultaCompromisosCalendario()
  {
    try {
      //code...
      // join: compromisos - objetivo - periodo
      $data = Compromiso::where($this->TABLE_COMPROMISOS . '.estado', 'ACT')
        ->select([
          $this->TABLE_COMPROMISOS . '.id',
          Compromiso::raw('row_number() OVER () AS "calendarId"'),
          Compromiso::raw('\'time\' AS category'),
          Compromiso::raw('\'#2ECC71\' AS "bgColor"'),
          Compromiso::raw('\'#AED6F1\' AS "color"'),
          Compromiso::raw('\'#9e5fff\' AS "dragBgColor"'),
          Compromiso::raw('\'#1B2631\' AS "borderColor"'),
          Compromiso::raw('\'true\' AS "isReadOnly"'),
          Compromiso::raw('(cast(compromisos.fecha_fin as text) || \'T00:00:00\')::timestamp as start'),
          Compromiso::raw('(cast(compromisos.fecha_fin as text) || \'T23:59:00\')::timestamp as end'),
          Compromiso::raw('(' . $this->TABLE_COMPROMISOS . '.codigo  ) as title'),
          Compromiso::raw('(' . $this->TABLE_COMPROMISOS . '.codigo  ) as name'),
          Compromiso::raw($this->TABLE_COMPROMISOS . '.detalle_compromiso as body')
        ])
        ->when(Auth::user()->evaluarole(['MINISTRO']), function ($query) {
          return $query
            ->where('institucion.ministro_usuario_id', Auth::user()->id)
            ->join('sc_compromisos.responsables as responsable', 'responsable.compromiso_id', 'compromisos.id')
            ->where('responsable.estado', 'ACT')
            ->join('sc_compromisos.instituciones as institucion', 'institucion.id', 'responsable.institucion_id');
        })
        ->whereDate('compromisos.fecha_fin', '>=', date('Y-m-d'))
        ->whereNotNull('compromisos.codigo')
        ->orderby('compromisos.fecha_fin')
        ->get();
      return $data;
    } catch (\Throwable $th) {
      //throw $th;
      throw $th;
    }
  }
  private function retornaConsultaCompromisosCalendarioReporte()
  {
    try {
      //code...
      // join: compromisos - objetivo - periodo
      return Compromiso::where($this->TABLE_COMPROMISOS . '.estado', 'ACT')
        ->select([
          $this->TABLE_COMPROMISOS . '.id',
          Compromiso::raw('row_number() OVER () AS "calendarId"'),
          Compromiso::raw('\'time\' AS category'),
          Compromiso::raw('\'#2ECC71\' AS "bgColor"'),
          Compromiso::raw('\'#AED6F1\' AS "color"'),
          Compromiso::raw('\'#9e5fff\' AS "dragBgColor"'),
          Compromiso::raw('\'#1B2631\' AS "borderColor"'),
          Compromiso::raw('\'true\' AS "isReadOnly"'),
          Compromiso::raw('(cast(compromisos.fecha_reporte as text) || \'T00:00:00\')::timestamp as start'),
          Compromiso::raw('(cast(compromisos.fecha_reporte as text) || \'T23:59:00\')::timestamp as end'),
          Compromiso::raw('(' . $this->TABLE_COMPROMISOS . '.codigo  ) as title'),
          Compromiso::raw('(' . $this->TABLE_COMPROMISOS . '.codigo  ) as name'),
          Compromiso::raw($this->TABLE_COMPROMISOS . '.detalle_compromiso as body')
        ])
        ->when(Auth::user()->evaluarole(['MINISTRO']), function ($query) {
          return $query
            ->where('institucion.ministro_usuario_id', Auth::user()->id)
            ->join('sc_compromisos.responsables as responsable', 'responsable.compromiso_id', 'compromisos.id')
            ->where('responsable.estado', 'ACT')
            ->join('sc_compromisos.instituciones as institucion', 'institucion.id', 'responsable.institucion_id');
        })
        ->whereDate('compromisos.fecha_reporte', '>=', date('Y-m-d'))
        ->whereNotNull('compromisos.codigo')
        ->get();
    } catch (\Throwable $th) {
      //throw $th;
      throw $th;
    }
  }
  public function calendario()
  {
    try {
      //code...
      // consulta...
      $sqlCompromisos = $this->retornaConsultaCompromisosCalendario();
      // respuesta...
      $array_response['status'] = 200;
      $array_response['datos'] = $sqlCompromisos;
      // return...
      return response()->json($sqlCompromisos, 200);
    } catch (\Throwable $th) {
      // errorr...
      return response()->json($th, 400);
    }
  }
  public function calendarioReporte()
  {
    try {
      //code...
      // consulta...
      $sqlCompromisos = $this->retornaConsultaCompromisosCalendarioReporte();
      // respuesta...
      $array_response['status'] = 200;
      $array_response['datos'] = $sqlCompromisos;
      // return...
      return response()->json($sqlCompromisos, 200);
    } catch (\Throwable $th) {
      // errorr...
      return response()->json($th, 400);
    }
  }

  public function getDatatableCompromisoDetalleCalendario($id)
  {
    $objSelect = new RepositorioController();
    $data = $objSelect->selectConsultaCompromisos();
    $data = $data->where('compromisos.id', $id)->get();
    //dd("data->",$data);
    $datatable = Datatables::of($data)
      ->addIndexColumn()
      ->addColumn('fecha_creacion', function ($row) {
        return $row->created_at;
      })
      ->addColumn('registro_', function ($row) {
        return $row->reg_;
      })
      ->rawColumns(['fecha_creacion', 'registro_'])
      ->make(true);
    return $datatable;
  }

  public function getDatatableCompromisoCalendarioFinalizacion($id)
  {
    $objSelect = new RepositorioController();
    $data = $objSelect->selectConsultaCompromisos();
    $data = $data->where('compromisos.id', $id)->get();
    // dd($data);
    $datatable = Datatables::of($data)
      ->addIndexColumn()
      ->addColumn('fecha_creacion', function ($row) {
        return $row->created_at;
      })
      ->addColumn('registro_', function ($row) {
        return $row->reg_;
      })
      ->rawColumns(['fecha_creacion', 'registro_'])
      ->make(true);
    return $datatable;
  }
}
