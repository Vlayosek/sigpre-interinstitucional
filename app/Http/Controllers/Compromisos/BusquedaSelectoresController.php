<?php

namespace App\Http\Controllers\Compromisos;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Core\Entities\Compromisos\Compromiso;
use Illuminate\Support\Facades\DB;


/*EDITAR EXCEL */
use PhpOffice\PhpSpreadsheet\{Spreadsheet, IOFactory, Exception};
use PhpOffice\PhpSpreadsheet\Style\{Border, Color, Alignment, Fill, NumberFormat};
use PhpOffice\PhpSpreadsheet\Chart\{Chart, Layout};
use Auth;
/*FIN EDITAR EXCEL*/

class BusquedaSelectoresController extends Controller
{
  public function busquedaCompromisos(Request $request)
  {
    $input = $request->all();

    if (!empty($input['query'])) {
      $busqueda = strtoupper($input['query']);
      $data = Compromiso::select(["compromisos.id",  DB::RAW("CONCAT(compromisos.codigo,'/ ',compromisos.nombre_compromiso) as describe")])
        ->where(function ($q) use ($busqueda) {
          $q->where(DB::raw("upper(REPLACE(compromisos.codigo,' ',''))"), "LIKE", "%{$busqueda}%")
            ->orwhere(DB::raw(DB::raw("upper(REPLACE(compromisos.codigo,' ',''))"), "LIKE", "%{$busqueda}%"));
        })
        ->join('sc_compromisos.responsables as responsable', 'responsable.compromiso_id', 'compromisos.id')
        ->whereBetween('compromisos.fecha_inicio', [$request->fecha_inicio, $request->fecha_fin])
        ->whereNotNull('compromisos.codigo')
        ->where('responsable.estado', 'ACT')
        ->orderby('compromisos.id', 'desc')
        ->get()->take(5);
    } else {
      $data = Compromiso::select(["compromisos.id", DB::RAW("CONCAT(REPLACE(compromisos.codigo,' ',''),'/ ',compromisos.nombre_compromiso) as describe")])
        ->join('sc_compromisos.responsables as responsable', 'responsable.compromiso_id', 'compromisos.id')
        ->whereNotNull('compromisos.codigo')
        ->whereBetween('compromisos.fecha_inicio', [$request->fecha_inicio, $request->fecha_fin])
        ->where('responsable.estado', 'ACT')
        ->orderby('compromisos.id', 'desc')
        ->get()->take(5);
    }


    $countries = [];
    if (count($data) > 0) {
      foreach ($data as $country) {
        $countries[] = array(
          "id" => $country->id,
          "text" => $country->describe,
        );
      }
    }
    return response()->json($countries);
  }
  public function consultaNombreCodigoCompromisos(Request $request)
  {
    $data = $this->consultaCompromisosFiltros($request);

    $nombresCodigos  = clone $data->pluck('nombre_','id');
    $codigos  = clone $data->pluck('codigo_','id');
    $array_response['status'] = 200;
    $array_response['nombresCodigos'] = $nombresCodigos;
    $array_response['codigos'] = $codigos;
    return response()->json($array_response, 200);
  }
  protected function consultaCompromisosFiltros($request){


    $data = (new RepositorioController())->selectConsultaCompromisos(false,false,"false");
    $data = (new RepositorioController())->restriccionesPorRol($data);
    $data =  $this->filtrosConsulta($data, $request);
    $data = $data
    ->whereNotNull('compromisos.codigo')
    ->orderby('compromisos.codigo', 'desc')->orderby('compromisos.fecha_inicio', 'desc')
    ->distinct();
    return $data;

  }
  public function filtrosConsulta($data, $request)
  {
      $objRepositorio = new RepositorioController();
      if (Auth::user()->evaluarole(['MONITOR'])) {
          $fecha_inicio = $request->fecha_inicio_exportar_monitor;
          $fecha_fin = $request->fecha_fin_exportar_monitor;
          $habilitarFechaInicio = $request->habilitarFechaInicio;
          $habilitarFechaFin = $request->habilitarFechaFin;
          $fecha_inicio_fin_exportar_monitor = $request->fecha_inicio_fin_exportar_monitor;
          $fecha_fin_fin_exportar_monitor = $request->fecha_fin_fin_exportar_monitor;
          $tipo_id_exportar_monitor = $objRepositorio->verifcarDatoArray($request->tipo_id_exportar_monitor);
          $data = (new CompromisosController())->filtrosProvinciasCantones($data, $request);
          $data = (new CompromisosController())->filtroInstitucional($data, $request);
          $data = (new CompromisosController())->filtroEstadosCompromiso($data, $request);

          if ($habilitarFechaInicio == 'true') $data->whereDate('compromisos.fecha_inicio', '>=', $fecha_inicio)->whereDate('compromisos.fecha_inicio', '<=', $fecha_fin);
          if ($habilitarFechaFin == 'true') $data->whereDate('compromisos.fecha_fin', '>=', $fecha_inicio_fin_exportar_monitor)->whereDate('compromisos.fecha_fin', '<=', $fecha_fin_fin_exportar_monitor);

          if ($objRepositorio->validarArregloVacio($tipo_id_exportar_monitor)) $data = $data->whereIn('compromisos.tipo_compromiso_id', $tipo_id_exportar_monitor);

      }
      return $data;
  }
}
