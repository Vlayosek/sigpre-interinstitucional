<?php

namespace App\Http\Controllers\Compromisos;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\Datatables\Datatables;
use Yajra\DataTables\CollectionDataTable;
use App\Core\Entities\Compromisos\EstadoPorcentaje;
use App\Core\Entities\Compromisos\Compromiso;
use App\Core\Entities\Compromisos\Objetivo;
use App\Core\Entities\Compromisos\Corresponsable;
use App\Core\Entities\Compromisos\Periodo;
use App\Core\Entities\Compromisos\Exportacion;
use App\Core\Entities\Compromisos\Institucion;
use App\Core\Entities\Admin\parametro_ciudad;
use App\Core\Entities\Compromisos\Ubicacion;
use App\Core\Entities\Compromisos\FechaPeriodoConsulta;
use App\Core\Entities\Compromisos\VistaCompromisosMinisterios;
use App\Core\Entities\Compromisos\VistaCompromisosGabinetes;
use App\Core\Entities\AdministracionGrafico\Grafico;

use Illuminate\Support\Facades\DB;

use App\Exports\CompromisosExport;
use App\Exports\CompromisosExportAvanzado;
use App\Exports\CompromisosExportAvanzadoFormato;
use App\Http\Controllers\Ajax\SelectController;
use Maatwebsite\Excel\Facades\Excel;

/*EDITAR EXCEL */
use PhpOffice\PhpSpreadsheet\{Spreadsheet, IOFactory, Exception};
use PhpOffice\PhpSpreadsheet\Style\{Border, Color, Alignment, Fill, NumberFormat};
use PhpOffice\PhpSpreadsheet\Chart\{Chart, Layout};
use PhpOffice\PhpSpreadsheet\Chart\DataSeries;
use PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues;
use PhpOffice\PhpSpreadsheet\Chart\Legend;
use PhpOffice\PhpSpreadsheet\Chart\Title;
use PhpOffice\PhpSpreadsheet\Chart\DataLabel;
use PhpOffice\PhpSpreadsheet\Chart\PlotArea;
use PhpOffice\PhpSpreadsheet\Worksheet\MemoryDrawing;
use PhpOffice\PhpSpreadsheet\Style\Style;
use PhpOffice\PhpSpreadsheet\Chart\Axis\Axis;
use PhpOffice\PhpSpreadsheet\Chart\Axis\ChartAxisFactory;
use PhpOffice\PhpSpreadsheet\Chart\Axis\ChartAxis;
use PhpOffice\PhpSpreadsheet\Chart\Axis\DataSource\CellRange;
use PhpOffice\PhpSpreadsheet\Chart\Title\TitleSeparator;
use PhpOffice\PhpSpreadsheet\Chart\Title\TitleSeparatorStyle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Chart\Axis\TickMark;
use PDF;
use Auth;
/*FIN EDITAR EXCEL*/

class ReportesController extends Controller
{
  protected $dimension_style_cabecera_1 = null;
  protected $dimension_style_cabecera_2 = null;
  protected $dimension_style_logo = null;
  protected $imagen_institucional = null;
  protected $inicializadorContenido = 5;
  protected $abecedario = [
    'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L',
    'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X',
    'Y', 'Z'
  ];
  protected $styleTotales = [
    'font' => [
      'bold' => true, 'color' => array('rgb' => '000000'), 'size'  => 12, 'name'  => 'Calibri'
    ],
    'alignment' => [
      'horizontal' => 'center', 'vertical' => 'center'
    ],
    'borders' => [
      'allBorders' => [
        'borderStyle' => Border::BORDER_THIN,
      ],
    ],
    'fill' => [
      'fillType' => Fill::FILL_SOLID,
      'startColor' => [
        'argb' => 'DCE6F1',
      ],
    ],
  ];
  protected $styleCompromisos = [
    'font' => [
      'color' => array('rgb' => '000000'), 'size'  => 12,'name'  => 'Calibri'
    ],
    'alignment' => [
      //   'horizontal' => 'justify', 'vertical' => 'center', 'wrapText' => true
      'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
      'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
      'wrapText' => true,
      'shrinkToFit' => true,
    ],
    'borders' => [
      'allBorders' => [
        'borderStyle' => Border::BORDER_THIN,
      ],
    ],
  ];
  protected  $styleResumen = [
    'font' => [
      'bold' => true, 'color' => array('rgb' => '000000'), 'size'  => 12, 'name'  => 'Calibri'
    ],
    'alignment' => [
      'horizontal' => 'center', 'vertical' => 'center'
    ],
    'borders' => [
      'allBorders' => [
        'borderStyle' => Border::BORDER_THIN,
      ],
    ],
  ];
  protected  $styleCabecera1 = [
    'fill' => [
      'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
      'startColor' => [
        'argb' => '3B608D',
      ],
    ],
    'font' => [
      'bold' => true,
      'color' => ['rgb' => 'FFFFFF'],
      'size'  => 18,
      'name'  => 'Calibri'
    ],

    'alignment' => [
      'horizontal' => 'center', 'vertical' => 'center'
    ],
    'borders' => [
      'allBorders' => [
        'borderStyle' => Border::BORDER_THIN,
      ],
    ],
  ];
  protected  $styleCabecera2 = [
    'fill' => [
      'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
      'startColor' => [
        'argb' => '808080',
      ],
    ],
    'font' => [
      'bold' => true,
      'color' => ['rgb' => 'FFFFFF'],
      'size'  => 12,
      'name'  => 'Calibri',

    ],

    'alignment' => [
      'horizontal' => 'center', 'vertical' => 'center',
      'wrapText' => true,

    ],
    'borders' => [
      'allBorders' => [
        'borderStyle' => Border::BORDER_THIN,
      ],
    ],
  ];
  public function ExportarExcelGET($inicio, $fin, $tipo, $tabla, $asignaciones, $temporales, $pendientes, $filtro, $institucion_id, $gabinete_id)
  {
    ob_end_clean();
    return Excel::download(new CompromisosExport($inicio, $fin, $tipo, $tabla, $asignaciones, $temporales, $pendientes, $filtro, $institucion_id, $gabinete_id), 'compromisos_' . $inicio . '_' . $fin . '_' . 'SIGPRE.xlsx');
  }

  protected function cargarSpreadSheet($url)
  {
    $reader = IOFactory::createReader("Xlsx");
    $spread = $reader->load($url);
    $writer = IOFactory::createWriter($spread, 'Xlsx');
    $array_response['spread'] = $spread;
    $array_response['writer'] = $writer;
    return $array_response;
  }
  protected function creacionObjetoExportacion($request)
  {
    $data = (new RepositorioController())->creacionObjetoExportacion(
      ($request->fecha_inicio_exportar_monitor == null ? 'null' : $request->fecha_inicio_exportar_monitor),
      ($request->fecha_fin_exportar_monitor == null ? 'null' : $request->fecha_fin_exportar_monitor),
      ($request->estado == null ? 'null' : $request->estado),
      ($request->tabla == null ? 'null' : $request->tabla),
      ($request->asignaciones == null ? 'null' : $request->asignaciones),
      ($request->temporales == null ? 'null' : $request->temporales),
      ($request->pendientes == null ? 'null' : $request->pendientes),
      ($request->filtro == null ? 'null' : $request->filtro),
      ($request->institucion_id_exportar_monitor == null ? 'null' : $request->institucion_id_exportar_monitor),
      ($request->gabinete_id_exportar_monitor == null ? 'null' : $request->gabinete_id_exportar_monitor),
      ($request->gabinete_id_corresponsable_exportar_monitor == null ? 'null' : $request->gabinete_id_corresponsable_exportar_monitor),
      ($request->institucion_id_corresponsable_exportar_monitor == null ? 'null' : $request->institucion_id_corresponsable_exportar_monitor),
      ($request->fecha_inicio_fin_exportar_monitor == null ? 'null' : $request->fecha_inicio_fin_exportar_monitor),
      ($request->fecha_fin_fin_exportar_monitor == null ? 'null' : $request->fecha_fin_fin_exportar_monitor),
      ($request->tipo_id_exportar_monitor == null ? 'null' : $request->tipo_id_exportar_monitor),
      ($request->estado_id_exportar_monitor == null ? 'null' : $request->estado_id_exportar_monitor),
      ($request->estado_porcentaje_id_exportar_monitor == null ? 'null' : $request->estado_porcentaje_id_exportar_monitor),
      ($request->descripcion_antecedente_exportar_monitor == null ? 'null' : $request->descripcion_antecedente_exportar_monitor),
      ($request->fecha_inicio_antecedente_exportar_monitor == null ? 'null' : $request->fecha_inicio_antecedente_exportar_monitor),
      ($request->fecha_fin_antecedente_exportar_monitor == null ? 'null' : $request->fecha_fin_antecedente_exportar_monitor),
      ($request->fecha_inicio_cuumplido_exportar_monitor == null ? 'null' : $request->fecha_inicio_cuumplido_exportar_monitor),
      ($request->fecha_fin_cumplido_exportar_monitor == null ? 'null' : $request->fecha_fin_cumplido_exportar_monitor),
      ($request->provincia_id_exportar_monitor == null ? 'null' : $request->provincia_id_exportar_monitor),
      ($request->canton_id_exportar_monitor == null ? 'null' : $request->canton_id_exportar_monitor),
      ($request->parroquia_id_exportar_monitor == null ? 'null' : $request->parroquia_id_exportar_monitor),
      ($request->nombre_compromiso_exportar_monitor == null ? 'null' : $request->nombre_compromiso_exportar_monitor),
      ($request->codigo_compromiso_exportar_monitor == null ? 'null' : $request->codigo_compromiso_exportar_monitor),
      ($request->fecha_inicio_avance_exportar_monitor == null ? 'null' : $request->fecha_inicio_avance_exportar_monitor),
      ($request->fecha_fin_avance_exportar_monitor == null ? 'null' : $request->fecha_fin_avance_exportar_monitor),
      ($request->monitor_id_exportar_monitor == null ? 'null' : $request->monitor_exportar_monitor),
      $request->habilitarFechaInicio,
      $request->habilitarFechaFin,
      $request->habilitarFechaCumplido,
      $request->habilitarFechaAntecedente,
      $request->habilitarFechaUltimoAvance
    );
    $data = (new CompromisosController())
      ->filtrarDatosConsultaCompromisos($data, true)->cursor()->collect();
    //$cabecera = collect((new RepositorioController())->cabeceraExcel());
    //  $datas = $cabecera->concat($data);
    return $data;
  }
  ///CON FORMATO DE EXPORTACIÓN
  /* public function exportarExcelAvanzado(Request $request)
  {
    $hoy = date("Y-m-d H:i:s");
    try {
      $anadir_minutos = 15;
      $now = new \DateTime(date("Y-m-d H:i:s"));
      $now->add(new \DateInterval('PT' . $anadir_minutos . 'M'));
      $expDate = $now->format('H:i:s');
      $nombre_archivo = uniqid() . '.xlsx';

      $cql = new Exportacion();
      $cql->inicio = date('H:i:s');
      $cql->fin = $expDate;
      $cql->fecha_inicio = $request->fecha_inicio;
      $cql->fecha_fin = $request->fecha_fin;
      $cql->created_at = $hoy;
      $cql->archivo = $nombre_archivo;
      $cql->save();

      ob_end_clean();

      $filepath =  '/COMPROMISOS/EXPORTS/' . $nombre_archivo;
      $url = 'storage/FORMATOS_COMPROMISOS/GESTION_COMPROMISOS.xlsx';

      $spreadsheet = $this->cargarSpreadSheet($url);
      $spread = $spreadsheet['spread'];
      $writer = $spreadsheet['writer'];

      $spread->setActiveSheetIndex(0);
      $sheet = $spread->getActiveSheet();
      $this->imagen_institucional = $this->cargarImagenInstitucional();
      $imagen = $this->cargarImagenExcel();
      $imagen->setWorksheet($sheet);
      $sheet->setCellValue("B3", date('Y-m-d'));

      $institucion_id = null;
      $filaFinal = 0;

      $this->dimension_style_logo = $sheet->getRowDimension(1);
      $this->dimension_style_cabecera_1 = $sheet->getRowDimension(2);
      $this->dimension_style_cabecera_2 = $sheet->getRowDimension(4);

      $compromisos = $this->creacionObjetoExportacion($request);
      foreach ($compromisos as $key => $compromiso) {
        if ($filaFinal == 0) $filaFinal = $this->inicializadorContenido + $key;
        else $filaFinal = $filaFinal + 1;
        if ($institucion_id != $compromiso['institucion_id']) {
          $institucion_id = $compromiso['institucion_id'];
          if ($key == 0) {
            $sheet->setCellValue('A2', $compromiso['institucion_']);
            $sheet->getStyle('A2' . ':M2')->applyFromArray($this->styleCabecera1);
            $cabecera_excel = (new RepositorioController())->cabeceraExcel();
            $sheet->fromArray(
              $cabecera_excel,
              NULL,
              'A4'
            );
          } else {
            $filaFinal = $this->insertarCabeceraLogo($sheet, $filaFinal);
            $filaFinal = $this->insertarCabeceraNombre($sheet, $filaFinal, $compromiso['institucion_']);
            $filaFinal = $this->insertarCabeceraFechaCorte($sheet, $filaFinal);
            $filaFinal = $this->insertarCabeceraTabla($sheet, $filaFinal);
          }
        }

        foreach ($compromisos as $value) {
          foreach ($value->toArray() as $compromiso) {
            $letra_inicial='A';
            $letra_final='A';
            foreach ($this->abecedario as $key=> $letra) {
              if($key==0) $letra_inicial=$letra;
              $letra_final=$letra;
              $sheet->setCellValue($letra . $filaFinal, $compromiso);
            }
            $sheet->getStyle($letra_inicial . $filaFinal . ':'.$letra_final . $filaFinal)->applyFromArray($this->styleCompromisos);
            $sheet->getRowDimension($filaFinal)->setRowHeight($this->dimension_style_cabecera_2->getRowHeight());
          }
        }


      }
      $writer->save("storage/" . $filepath);

      $array_response['datos'] = "Grabado Exitoso";
      $array_response['status'] = 200;
    } catch (\Exception $e) {

      $array_response['status'] = 300;
      $array_response['datos'] = $e->getMessage();
    }

    return response()->json($array_response, 200);
  }
*/

  ///SIN FORMATO DE EXPORTACIÓN
  public function exportarExcelAvanzado(Request $request)
  {
    $hoy = date("Y-m-d H:i:s");
    try {
      $anadir_minutos = 15;
      $now = new \DateTime(date("Y-m-d H:i:s"));
      $now->add(new \DateInterval('PT' . $anadir_minutos . 'M'));
      $expDate = $now->format('H:i:s');
      $nombre_archivo = uniqid() . '.xlsx';

      $cql = new Exportacion();
      $cql->inicio = date('H:i:s');
      $cql->fin = $expDate;
      $cql->fecha_inicio = $request->fecha_inicio;
      $cql->fecha_fin = $request->fecha_fin;
      $cql->created_at = $hoy;
      $cql->archivo = $nombre_archivo;
      $cql->save();

      ob_end_clean();
      $export = new CompromisosExportAvanzado(
        ($request->fecha_inicio_exportar_monitor == null ? 'null' : $request->fecha_inicio_exportar_monitor),
        ($request->fecha_fin_exportar_monitor == null ? 'null' : $request->fecha_fin_exportar_monitor),
        ($request->estado == null ? 'null' : $request->estado),
        ($request->tabla == null ? 'null' : $request->tabla),
        ($request->asignaciones == null ? 'null' : $request->asignaciones),
        ($request->temporales == null ? 'null' : $request->temporales),
        ($request->pendientes == null ? 'null' : $request->pendientes),
        ($request->filtro == null ? 'null' : $request->filtro),
        ($request->institucion_id_exportar_monitor == null ? 'null' : $request->institucion_id_exportar_monitor),
        ($request->gabinete_id_exportar_monitor == null ? 'null' : $request->gabinete_id_exportar_monitor),
        ($request->gabinete_id_corresponsable_exportar_monitor == null ? 'null' : $request->gabinete_id_corresponsable_exportar_monitor),
        ($request->institucion_id_corresponsable_exportar_monitor == null ? 'null' : $request->institucion_id_corresponsable_exportar_monitor),
        ($request->fecha_inicio_fin_exportar_monitor == null ? 'null' : $request->fecha_inicio_fin_exportar_monitor),
        ($request->fecha_fin_fin_exportar_monitor == null ? 'null' : $request->fecha_fin_fin_exportar_monitor),
        ($request->tipo_id_exportar_monitor == null ? 'null' : $request->tipo_id_exportar_monitor),
        ($request->estado_id_exportar_monitor == null ? 'null' : $request->estado_id_exportar_monitor),
        ($request->estado_porcentaje_id_exportar_monitor == null ? 'null' : $request->estado_porcentaje_id_exportar_monitor),
        ($request->descripcion_antecedente_exportar_monitor == null ? 'null' : $request->descripcion_antecedente_exportar_monitor),
        ($request->fecha_inicio_antecedente_exportar_monitor == null ? 'null' : $request->fecha_inicio_antecedente_exportar_monitor),
        ($request->fecha_fin_antecedente_exportar_monitor == null ? 'null' : $request->fecha_fin_antecedente_exportar_monitor),
        ($request->fecha_inicio_cuumplido_exportar_monitor == null ? 'null' : $request->fecha_inicio_cuumplido_exportar_monitor),
        ($request->fecha_fin_cumplido_exportar_monitor == null ? 'null' : $request->fecha_fin_cumplido_exportar_monitor),
        ($request->provincia_id_exportar_monitor == null ? 'null' : $request->provincia_id_exportar_monitor),
        ($request->canton_id_exportar_monitor == null ? 'null' : $request->canton_id_exportar_monitor),
        ($request->parroquia_id_exportar_monitor == null ? 'null' : $request->parroquia_id_exportar_monitor),
        ($request->nombre_compromiso_exportar_monitor == null ? 'null' : $request->nombre_compromiso_exportar_monitor),
        ($request->codigo_compromiso_exportar_monitor == null ? 'null' : $request->codigo_compromiso_exportar_monitor),
        ($request->fecha_inicio_avance_exportar_monitor == null ? 'null' : $request->fecha_inicio_avance_exportar_monitor),
        ($request->fecha_fin_avance_exportar_monitor == null ? 'null' : $request->fecha_fin_avance_exportar_monitor),
        ($request->monitor_id_avance_exportar_monitor == null ? 'null' : $request->monitor_id_avance_exportar_monitor),
        $request->habilitarFechaInicio,
        $request->habilitarFechaFin,
        $request->habilitarFechaCumplido,
        $request->habilitarFechaAntecedente,
        $request->habilitarFechaUltimoAvance,
        ($request->descripcion_avance_exportar_monitor == null ? 'null' : $request->descripcion_avance_exportar_monitor),
      );
      $filepath =  '/COMPROMISOS/EXPORTS/' . $nombre_archivo;

      Excel::store($export, $filepath);

      $array_response['datos'] = $filepath;
      $array_response['status'] = 200;
    } catch (\Exception $e) {

      $array_response['status'] = 300;
      $array_response['datos'] = $e->getMessage();
    }

    return response()->json($array_response, 200);
  }
  ///SIN FORMATO DE EXPORTACIÓN

  public function getDatatableExportaciones()
  {
    $data = Exportacion::orderby('id', 'desc');

    $datatable = Datatables::of($data)
      ->addIndexColumn()
      ->addColumn('', function ($row) {
        // if($row->estado=='INA'){
        if (is_null($row->archivo)) return '--';
        $btn = '<button class="btn btn-primary btn-sm" onclick="app.descargarCompromisoGenerado(\'' . $row->archivo . '\')">DESCARGAR</button>';
        //  }else{
        //     $btn='PENDIENTE DE GENERACIÓN';
        //  }
        return $btn;
      })

      ->rawColumns([''])
      ->make(true);
    return $datatable;
  }
  public function reportes()
  {
    //CONSULTA DE GABINETE
    $cqlGabinete = Institucion::select('id', 'descripcion')->where('nivel', '1')->pluck('descripcion', 'id');
    //CONSULTA DE INSTITUCION
    $cqlInstitucion = Institucion::select('id', 'descripcion')->where('nivel', '2')->pluck('descripcion', 'id');
    //CONSULTA ESTADO GESTIÓN
    $gestion_filtro = EstadoPorcentaje::select('id', 'descripcion')
      ->pluck('descripcion', 'id');
    //CONSULTA COMPROMISOS
    $compromiso_filtro = Compromiso::select('id', 'nombre_compromiso')
      ->where('estado', 'ACT')->pluck('nombre_compromiso', 'id');

    //CONSULTA UBICACION
    $ubicacion_filtro = parametro_ciudad::with(['fatherpara' => function ($q) {
      $q->with('fatherpara');
    }])
      ->where('nivel', '3')
      ->orderby('descripcion', 'asc')->pluck('descripcion', 'id');
    $fecha_inicio = FechaPeriodoConsulta::where('estado', 'ACT')->first();
    $fecha_inicio = is_null($fecha_inicio) ? date('Y-m-d') : $fecha_inicio->fecha_inicio;
    return view('modules.compromisos.controlPanel.index', compact('fecha_inicio', 'cqlGabinete', 'cqlInstitucion', 'gestion_filtro', 'ubicacion_filtro', 'compromiso_filtro'));
  }
  //TRAE LAS INSTITUCIONES
  public function filtro_institucion_cc(request $request)
  {
    $objRepositorio = new RepositorioController();
    if (!$objRepositorio->validarArregloVacio($request->gabinete_id)) {
      $cqlFiltroInstituciones = Institucion::select('id', 'descripcion')->where('nivel', '2')->get();
    } else {
      $cqlFiltroInstituciones = Institucion::select('id', 'descripcion')
        ->where('nivel', '2')
        ->whereIn('institucion_id', $request->gabinete_id)
        ->get();
    }
    $array_response['status'] = 200;
    $array_response['datos'] = $cqlFiltroInstituciones;
    return response()->json($array_response, 200);
  }

  //TRAE EL GABINETE
  public function filtro_gabinete_cc(request $request)
  {
    $objRepositorio = new RepositorioController();
    if (!$objRepositorio->validarArregloVacio($request->institucion_id)) {
      $cqlFiltroGabinete = Institucion::select('id', 'descripcion')->where('nivel', '1')->get();
    } else {
      $cqlConsultaInstitucion = Institucion::select('institucion_id')->where('nivel', '2')
        ->whereIn('id', $request->institucion_id)->pluck('institucion_id');
      $cqlFiltroGabinete = Institucion::select('id', 'descripcion')
        ->where('nivel', '1')
        ->whereIn('id', $cqlConsultaInstitucion)
        ->pluck('descripcion', 'id');
    }
    $array_response['status'] = 200;
    $array_response['datos'] = $cqlFiltroGabinete;
    return response()->json($array_response, 200);
  }

  /*REPORTE COMPROMISOS CUMPLIDOS
    Request: fecha_inicio, fecha_fin, filtro_gabinete, filtro_institucion
    Return: Re  torna un archivo Excel
    */
  public function exportarExcelCumplidos(request $request)
  {
    $objSelect = new RepositorioController();
    $objSelectCompromiso = new CompromisosController();
    $estado_porcentaje = EstadoPorcentaje::where('abv', 'CUM')->select('id')->pluck('id')->toArray();
    $fill = new \stdClass();
    $fill->institucion_id_exportar_monitor = $request->filtro_institucion;
    $fill->gabinete_id_exportar_monitor = $request->filtro_gabinete;
    $fill->estado_id_exportar_monitor = [];
    $fill->estado_porcentaje_id_exportar_monitor = $estado_porcentaje;

    $url = 'storage/FORMATOS_COMPROMISOS/CUMPLIDOS_COMPROMISOS.xlsx';
    $reader = IOFactory::createReader("Xlsx");
    $spread = $reader->load($url);
    $spread->setActiveSheetIndex(0);
    $sheet = $spread->getActiveSheet();
    $writer = IOFactory::createWriter($spread, 'Xlsx');
    $this->imagen_institucional = $this->cargarImagenInstitucional();
    $imagen = $this->cargarImagenExcel();
    $imagen->setWorksheet($sheet);
    $compromisos = $objSelect->selectConsultaCompromisos();
    $compromisos = $objSelectCompromiso->filtroInstitucional($compromisos, $fill, false);
    $compromisos = $objSelectCompromiso->filtroEstadosCompromiso($compromisos, $fill);
    $compromisos = $compromisos->whereBetween('compromisos.fecha_inicio', [$request->fecha_inicio, $request->fecha_fin]);
    $compromisos = $compromisos->get()->toArray();
    $sheet->setCellValue("A2", "REPORTE DE COMPROMISOS CUMPLIDOS DEL " . $request->fecha_inicio . " AL " . $request->fecha_fin);
    $sheet->setCellValue("B4", date('Y-m-d'));

    foreach ($compromisos as $key => $compromiso) {
      $fila_ = 6 + $key;
      $sheet->setCellValue('A' . $fila_, $compromiso['reg_']);
      $sheet->setCellValue('B' . $fila_, $compromiso['nombre_']);
      $sheet->setCellValue('C' . $fila_, $compromiso['institucion_']);
      $sheet->setCellValue('D' . $fila_, $compromiso['corresponsables']);
      $sheet->setCellValue('E' . $fila_, $compromiso['gabinete_']);
      $sheet->setCellValue('F' . $fila_, $compromiso['provincias']);
      $sheet->setCellValue('G' . $fila_, $compromiso['cantones']);
      $sheet->setCellValue('H' . $fila_, $compromiso['fecha_inicio_']);
      $sheet->setCellValue('I' . $fila_, $compromiso['fecha_fin_']);
      $sheet->setCellValue('J' . $fila_, $compromiso['avance_compromiso_']);
      $sheet->setCellValue('K' . $fila_, $compromiso['ultimo_avance_aprobado']);
      $sheet->setCellValue('L' . $fila_, $compromiso['fecha_revisa']);
      $sheet->setCellValue('M' . $fila_, $compromiso['fecha_cumplido']);
      $sheet->setCellValue('N' . $fila_, $compromiso['fecha_reporte']);
      $sheet->getStyle('A' . $fila_ . ':N' . $fila_)->applyFromArray($this->styleCompromisos);
    }

    //$writer->save("reporte_compromisos_cumplidos.xlsx");
    $documento_generado = "CUMPLIDOS_COMPROMISOS_" . date('Y-m-d') . ".xlsx";
    $writer->save("storage/COMPROMISOS_GENERADOS/" . $documento_generado);

    $array_response['status'] = 200;
    $array_response['documento_nombre'] = $documento_generado;

    return response()->json($array_response, 200);
  }
  /**FIN */

  //REPORTE EJECUTIVO
  //TRAE LOS COMPROMISOS
  public function consulta_compromiso(request $request)
  {
    $cqlFiltroCompromisos = Compromiso::select(["id", DB::RAW("CONCAT(codigo,'/ ',nombre_compromiso) as nombre_compromiso")])
      ->where('estado', 'ACT')
      ->get();
    $array_response['status'] = 200;
    $array_response['datos'] = $cqlFiltroCompromisos;
    return response()->json($array_response, 200);
  }
  //TRAE PERIODOS ACTUALES
  public function consulta_periodo_actual(request $request)
  {
    $cqlPeriodoCompromiso = Compromiso::select(DB::RAW("CONCAT(compromisos.fecha_inicio,' | ',compromisos.fecha_fin,'    REF: ',compromisos.nombre_compromiso) as periodo_compromiso"), "compromisos.id as id")
      //->leftjoin('sc_compromisos.objetivos as objetivos','objetivos.id','periodos.objetivo_id')
      ->where('compromisos.estado', 'ACT');
    if ($request->hoy != null || $request->hoy != '') {
      $cqlPeriodoCompromiso = $cqlPeriodoCompromiso->whereDate('compromisos.fecha_inicio', '<=', $request->hoy)
        ->whereDate('compromisos.fecha_fin', '>=', $request->hoy);
    }
    $cqlPeriodoCompromiso = $cqlPeriodoCompromiso->get();
    //pluck('periodo_compromiso','id')->toArray();
    //dd($cqlPeriodoCompromiso);
    $array_response['status'] = 200;
    $array_response['datos'] = $cqlPeriodoCompromiso;
    return response()->json($array_response, 200);
  }
  protected function cargarImagenInstitucional()
  {
    $img_encabezado = Grafico::select('imagen')->where('tipo', 'CABECERA DOCUMENTO')->where('eliminado', false)->first()->imagen;
    // $img_pie = Grafico::select('imagen')->where('tipo', 'PIE DOCUMENTO ALTERNO')->where('eliminado', false)->first()->imagen;

    $base64Image = 'data:image/png;base64,' . $img_encabezado;
    $imageData = base64_decode(substr($base64Image, strpos($base64Image, ',') + 1));

    // Crear una imagen a partir de los datos de la imagen
    $image = imagecreatefromstring($imageData);
    return $image;
  }
  protected function cargarImagenExcel($ubicacion = 'A1', $x = 5, $y = 5)
  {
    // Crear un objeto MemoryDrawing a partir de la imagen
    $memoryDrawing = new MemoryDrawing();
    $memoryDrawing->setName('cabecera');
    $memoryDrawing->setImageResource($this->imagen_institucional);
    $memoryDrawing->setMimeType('image/png');
    $memoryDrawing->setCoordinates($ubicacion);
    $memoryDrawing->setOffsetX($x);
    $memoryDrawing->setOffsetY($y);

    return $memoryDrawing;
  }
  public function exportarExcelEjecutivo(request $request)
  {
    $ID = $request->id;
    $array_response['status'] = "200";
    $objSelect = new RepositorioController();
    $compromisos = $objSelect->selectConsultaCompromisos();
    if ($objSelect->validarArregloVacio($ID)) $compromisos = $compromisos->whereIn('compromisos.id', $ID);
    if ($objSelect->validarArregloVacio($request->filtro_institucion_ejecutivo))  $compromisos = $compromisos->whereIn('institucion.id', $request->filtro_institucion_ejecutivo);
    $compromisos = $compromisos->whereBetween('compromisos.fecha_inicio', [$request->fecha_inicio, $request->fecha_fin]);
    $compromisos = $compromisos->orderby('institucion.id', 'asc')
      ->get()->toArray();

    // Obtener la hoja de cálculo y agregar la imagen a la celda
    /*  $spreadsheet = IOFactory::load('path/to/spreadsheet.xlsx');
    $worksheet = $spreadsheet->getActiveSheet();
*/
    $url = 'storage/FORMATOS_COMPROMISOS/EJECUTIVO_COMPROMISOS.xlsx';
    $reader = IOFactory::createReader("Xlsx");
    $spread = $reader->load($url);
    try {
      $sheet = $spread->getActiveSheet();
      //     $sheet->addMemoryDrawing($imagen);
      //  $sheet->addDrawing($imagen);
      //     $sheet->setDrawing($imagen);
      $this->imagen_institucional = $this->cargarImagenInstitucional();
      $imagen = $this->cargarImagenExcel();
      $imagen->setWorksheet($sheet);
      $writer = IOFactory::createWriter($spread, 'Xlsx');
      $sheet->setCellValue("B4", date('Y-m-d'));

      foreach ($compromisos as $key => $compromiso) {
        $fila_ = 6 + $key;
        $sheet->setCellValue('A' . $fila_, $compromiso['reg_']);
        $sheet->setCellValue('B' . $fila_, $compromiso['nombre_']);
        $sheet->setCellValue('C' . $fila_, $compromiso['gabinete_']);
        $sheet->setCellValue('D' . $fila_, $compromiso['institucion_']);
        $sheet->setCellValue('E' . $fila_, $compromiso['corresponsables']);
        $sheet->setCellValue('F' . $fila_, $compromiso['provincias']);
        $sheet->setCellValue('G' . $fila_, $compromiso['cantones']);
        $sheet->setCellValue('H' . $fila_, $compromiso['fecha_inicio_']);
        $sheet->setCellValue('I' . $fila_, $compromiso['fecha_fin_']);
        $sheet->setCellValue('J' . $fila_, $compromiso['estado_porcentaje_']);
        $sheet->setCellValue('K' . $fila_, $compromiso['estado_']);
        $sheet->setCellValue('L' . $fila_, $compromiso['avance_compromiso_']);
        $sheet->setCellValue('M' . $fila_, $compromiso['ultimo_avance_aprobado']);
        $sheet->setCellValue('N' . $fila_, $compromiso['fecha_revisa']);
        $sheet->setCellValue('O' . $fila_, $compromiso['notas_compromiso_']);
        $sheet->setCellValue('P' . $fila_, $compromiso['fecha_reporte']);
        $sheet->getStyle('A' . $fila_ . ':P' . $fila_)->applyFromArray($this->styleCompromisos);
      }

      //FIN CONSTRUYENDO BLOQUE DE OBJETIVOS
    } catch (\Exception $e) {
      $array_response['status'] = 300;
    }
    $writer = IOFactory::createWriter($spread, 'Xlsx');
    $documento_generado = "EJECUTIVO_COMPROMISOS_" . date('Y-m-d') . ".xlsx";
    //$writer->save("reporte_compromiso.xlsx");
    $writer->save("storage/COMPROMISOS_GENERADOS/" . $documento_generado);
    $array_response['status'] = 200;
    $array_response['documento_nombre'] = $documento_generado;

    return response()->json($array_response, 200);
  }
  
  //REPORTES POR MINISTERIO
  public function reportes_ministerio()
  {
    $institucion_filtro = '--';
    //obtener los funcionarios del area
    $institucion_filtro = Institucion::select('id', 'descripcion')
      ->where('nivel', 2)->pluck('descripcion', 'id');
    //obtener los gabinetes
    $gabinete_filtro = Institucion::select('id', 'nombre')
      ->where('nivel', 1)->orderBy('nombre')->pluck('nombre', 'id');
    //CONSULTA DE ESTADO GESTION
    $gestion_filtro = EstadoPorcentaje::select('id', 'descripcion')
      ->pluck('descripcion', 'id');

    return view('modules.compromisos.reportes.reportes_ministerio', compact('institucion_filtro', 'gabinete_filtro', 'gestion_filtro'));
  }

  public function getDatatableReportesVisualizacionServerSide($institucion_filtro)
    {
      dd('getDatatableReportesVisualizacionServerSide',$institucion_filtro);
        $data = Compromiso::select(
            'compromisos.id as id',
            'compromisos.codigo as codigo_compromiso',
            'compromisos.nombre_compromiso as nombre_compromiso',
            'estado_gestion.descripcion as estado_gestion',
            'estado_compromiso.descripcion as estado_compromiso',
            'compromisos.avance as porcentaje_avance',
            'compromisos.avance_compromiso as avance_compromiso',
            'institucion_.descripcion as nombre_institucion'
        )
            ->leftjoin('sc_compromisos.responsables as responsable_', 'responsable_.compromiso_id', 'compromisos.id')
            ->join('sc_compromisos.instituciones as institucion_', 'institucion_.id', 'responsable_.institucion_id')
            ->leftjoin('sc_compromisos.estados as estado_compromiso', 'estado_compromiso.id', 'compromisos.estado_id')
            ->leftjoin('sc_compromisos.estados_porcentaje as estado_gestion', 'estado_gestion.id', 'compromisos.estado_porcentaje_id')
            ->where('compromisos.estado', 'ACT')
            ->orderBy('id', 'asc');
        if ($institucion_filtro != '--')
            $data = $data->where('institucion_.id', $institucion_filtro);
        $data = $data->get();
        return (new CollectionDataTable($data))
            ->addIndexColumn()
            ->rawColumns([''])
            ->toJson();
    }

  protected function cargarGraficoEstadoCompromisos($sheet_3, $grafico)
  {
    //GENERACION DEL GRAFICO
    $colors = [
      '008000', 'FFFF00', 'FFA500', 'FF0000'
    ];
    $formatCode = '0%'; // Establece el formato como porcentaje
    $dataSeriesValues1 = array(
      new DataSeriesValues('Number', $grafico . '!$B$6:$E$6', $formatCode, 4, null, NULL, $colors, NULL),
      // new DataSeriesValues('Number', 'Grafico!$F$3:$F$8', NULL, 5),
    );

    $xAxisTickValues = array(
      new DataSeriesValues('String', $grafico . '!$B$4:$E$4', NULL, 1), //  Cumpli, Cerrado, etc
    );

    //  Construye la serie de datos
    $series1 = new DataSeries(
      DataSeries::TYPE_BARCHART, // plotType
      DataSeries::GROUPING_STANDARD, // plotGrouping STANDARD CLUSTERED
      range(0, count($dataSeriesValues1) - 1), // plotOrder
      [],
      $xAxisTickValues, // plotCategory
      $dataSeriesValues1,
      null                            // nombre de la serie
    );

    $series1->setPlotDirection(DataSeries::DIRECTION_COL);
    $layout1 = new Layout();
    $layout1->setShowVal(true);
    $plotarea = new PlotArea($layout1, array($series1));
    //  Crea el grÃ¡fico
    $chart = new Chart(
      '', // name
      NULL, //title
      NULL, // legend
      $plotarea
    );

    $chart->setTopLeftPosition('A7');
    $chart->setBottomRightPosition('G23');
    // Agregue el gráfico a la hoja de trabajo
    return $chart;
  }


  protected function consultaResumenesMinisterio($request, $estado_gestion = true)
  {
    if ($estado_gestion) {
      $cqlResumen = VistaCompromisosMinisterios::select(
        'estado_gestion',
        DB::RAW('SUM(sin_acciones) as sin_acciones'),
        DB::RAW('SUM(bueno) as bueno'),
        DB::RAW('SUM(atraso_leve)  as atraso_leve'),
        DB::RAW('SUM(atraso_moderado)  as atraso_moderado'),
        DB::RAW('SUM(atraso_grave)  as atraso_grave'),
      );
    } else {
      $cqlResumen = VistaCompromisosMinisterios::select(
        DB::RAW('SUM(sin_acciones) as sin_acciones'),
        DB::RAW('SUM(bueno) as bueno'),
        DB::RAW('SUM(atraso_leve)  as atraso_leve'),
        DB::RAW('SUM(atraso_moderado)  as atraso_moderado'),
        DB::RAW('SUM(atraso_grave)  as atraso_grave'),
      );
    }
    if ($request->institucion != []) $cqlResumen = $cqlResumen->whereIn('id', $request->institucion);
    $cqlResumen = $cqlResumen->whereBetween('fecha_inicio', [$request->fecha_inicio, $request->fecha_fin]);

    return $cqlResumen;
  }

  protected function insertarCabeceraLogo($sheet, $filaFinal)
  {
    if (is_null($this->dimension_style_logo)) return $filaFinal;

    /* CABECERA*/
    $sheet->mergeCells("A" . $filaFinal . ":" . "M" . $filaFinal);
    $imagen = $this->cargarImagenExcel('A' . $filaFinal);
    $imagen->setWorksheet($sheet);
    $sheet->getRowDimension($filaFinal)->setRowHeight($this->dimension_style_logo->getRowHeight());

    $filaFinal = $filaFinal + 1;
    return $filaFinal;
  }
  protected function insertarCabeceraNombre($sheet, $filaFinal, $institucion)
  {
    if (is_null($this->dimension_style_cabecera_1))        return $filaFinal;

    /* CABECERA*/
    $sheet->mergeCells("A" . $filaFinal . ":" . "M" . $filaFinal);
    $sheet->setCellValue('A' . $filaFinal, $institucion);
    $sheet->getStyle('A' . $filaFinal . ':M' . $filaFinal)->applyFromArray($this->styleCabecera1);
    // Establecer la altura de la fila en la que se van a clonar las dimensiones
    $sheet->getRowDimension($filaFinal)->setRowHeight($this->dimension_style_cabecera_1->getRowHeight());

    $filaFinal = $filaFinal + 1;
    return $filaFinal;
  }
  protected function insertarCabeceraFechaCorte($sheet, $filaFinal)
  {
    if (is_null($this->dimension_style_cabecera_1)) return $filaFinal;

    /* FECHA DE CORTE*/
    $sheet->setCellValue('A' . $filaFinal, 'Fecha corte:');
    $sheet->setCellValue('B' . $filaFinal, date('Y-m-d'));
    $sheet->mergeCells("C" . $filaFinal . ":" . "M" . $filaFinal);
    $sheet->getStyle('A' . $filaFinal . ':M' . $filaFinal)
      ->applyFromArray($this->styleCabecera1);
    $sheet->getRowDimension($filaFinal)->setRowHeight($this->dimension_style_cabecera_1->getRowHeight());
    $filaFinal = $filaFinal + 1;
    return $filaFinal;
  }
  protected function insertarCabeceraTabla($sheet, $filaFinal)
  {
    if (is_null($this->dimension_style_cabecera_2)) return $filaFinal;

    $rangoOrigen = "A4:M4";
    $valoresOrigen = $sheet->rangeToArray($rangoOrigen);
    $sheet->fromArray(
      $valoresOrigen,
      NULL,
      'A' . $filaFinal
    );
    $sheet->getStyle('A' . $filaFinal . ':M' . $filaFinal)
      ->applyFromArray($this->styleCabecera2);
    $sheet->getRowDimension($filaFinal)->setRowHeight($this->dimension_style_cabecera_2->getRowHeight());

    $filaFinal = $filaFinal + 1;
    return $filaFinal;
  }
  protected  function consultaDatosCompromisosMinisterio($request, $filtrado = false)
  {
    $objSelect = new RepositorioController();
    $objSelectCompromiso = new CompromisosController();

    $fill = new \stdClass();
    $fill->institucion_id_exportar_monitor = $request->institucion;
    $fill->gabinete_id_exportar_monitor = [];
    $fill->estado_id_exportar_monitor = [];
    $fill->estado_porcentaje_id_exportar_monitor = [];

    $compromisos = $objSelect->selectConsultaCompromisos();
    $compromisos = $objSelectCompromiso
      ->filtroInstitucional($compromisos, $fill, false);
    $compromisos = $compromisos->whereBetween('compromisos.fecha_inicio', [$request->fecha_inicio, $request->fecha_fin])
      ->whereNotNull('compromisos.codigo');
    $compromisos = $compromisos->orderby('institucion.id', 'asc')
      ->get()->toArray();
    return $compromisos;
  }
  protected function cargarGraficoMinisterioGrupal($request, $spread)
  {
    $objConsulta = new \stdClass();
    $objConsulta->institucion = $request->institucion;
    $objConsulta->fecha_inicio = $request->fecha_inicio;
    $objConsulta->fecha_fin = $request->fecha_fin;
    $cqlResumen = $this->consultaResumenesMinisterio($objConsulta, true);
    $data = $cqlResumen
      ->orderby('estado_gestion')
      ->groupBy('estado_gestion');
    $data = $data->get()->toArray();
    $sheet_2 = $spread->getActiveSheet();
    // $string_from_array = implode(',', $request->institucion);
    $sheet_2->fromArray(
      $data,
      NULL,
      'A4'
    );
    //GENERACION DEL GRAFICO
    $colors = [
      '4F6228', '0070C0', 'FFD966', 'F4B183', 'FF0000'
    ];
    $dataseriesLabels1 = array(
      new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'Grafico!$B$2', NULL, 1, [], NULL, "4F6228"), //optimo
      new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'Grafico!$C$2', NULL, 1, [], NULL, "0070C0"), //bueno
      new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'Grafico!$D$2', NULL, 1, [], NULL, 'FFD966'), //atraso leve
      new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'Grafico!$E$2', NULL, 1, [], NULL, "F4B183"), //atraso moderado
      // new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'Grafico!$F$2', NULL, 1, [], NULL, "FF0000"), //atraso grave
    );

    $dataSeriesValues1 = array(
      new DataSeriesValues('Number', 'Grafico!$B$4:$B$8', NULL, 4),
      new DataSeriesValues('Number', 'Grafico!$C$4:$C$8', NULL, 4),
      new DataSeriesValues('Number', 'Grafico!$D$4:$D$8', NULL, 4),
      new DataSeriesValues('Number', 'Grafico!$E$4:$E$8', NULL, 4),
      // new DataSeriesValues('Number', 'Grafico!$F$3:$F$8', NULL, 5),
    );

    $xAxisTickValues = array(
      new DataSeriesValues('String', 'Grafico!$A$4:$A$8', NULL, 4), //  Cumpli, Cerrado, etc
    );
    //  Construye la serie de datos
    $series1 = new DataSeries(
      DataSeries::TYPE_BARCHART, // plotType
      DataSeries::GROUPING_STANDARD, // plotGrouping STANDARD CLUSTERED
      range(0, count($dataSeriesValues1) - 1), // plotOrder
      $dataseriesLabels1, // plotLabel
      $xAxisTickValues, // plotCategory
      $dataSeriesValues1                              // plotValues
    );

    $series1->setPlotDirection(DataSeries::DIRECTION_COL);
    $plotarea = new PlotArea(NULL, array($series1));
    $legend = new Legend(Legend::POSITION_RIGHT, NULL, false);

    //  Crea el grÃ¡fico
    $chart = new Chart(
      'Grafico', // name
      NULL, //title
      $legend, // legend
      $plotarea
    );
    //  Establezca la posiciÃ³n donde debe aparecer el grÃ¡fico en la hoja de trabajo
    $chart->setTopLeftPosition('A12');
    $chart->setBottomRightPosition('I26');
    $sheet_2->addChart($chart);

    //  Agregue el grÃ¡fico a la hoja de trabajo
    return $chart;
  }
  protected function cargarGraficoMinisterioIndividual($request, $spread, $institucion_seleccionada)
  {
    $objConsulta = new \stdClass();
    $objConsulta->institucion = $request->institucion;
    $objConsulta->fecha_inicio = $request->fecha_inicio;
    $objConsulta->fecha_fin = $request->fecha_fin;
    $cqlResumen = $this->consultaResumenesMinisterio($objConsulta, false);
    $cqlResumenCumplidos = $this->consultaResumenesMinisterio($objConsulta, false);
    $data = $cqlResumen
      ->whereIn('estado_gestion', ['EN EJECUCION', 'STAND BY'])->get()->toArray();
    $cumplido = $cqlResumenCumplidos
      ->whereIn('estado_gestion', ['CUMPLIDO'])->get()->toArray();

    $bueno = is_null($data[0]['bueno']) ? 0 : $data[0]['bueno'];
    $atraso_leve = is_null($data[0]['atraso_leve']) ? 0 : $data[0]['atraso_leve'];
    $atraso_moderado = is_null($data[0]['atraso_moderado']) ? 0 : $data[0]['atraso_moderado'];
    $atraso_grave = is_null($data[0]['atraso_grave']) ? 0 : $data[0]['atraso_grave'];

    $bueno_cumplido = is_null($cumplido[0]['bueno']) ? 0 : $cumplido[0]['bueno'];
    $atraso_leve_cumplido = is_null($cumplido[0]['atraso_leve']) ? 0 : $cumplido[0]['atraso_leve'];
    $atraso_moderado_cumplido = is_null($cumplido[0]['atraso_moderado']) ? 0 : $cumplido[0]['atraso_moderado'];
    $atraso_grave_cumplido = is_null($cumplido[0]['atraso_grave']) ? 0 : $cumplido[0]['atraso_grave'];
    $sin_acciones_cumplido = is_null($cumplido[0]['sin_acciones']) ? 0 : $cumplido[0]['sin_acciones'];

    // $spread->setActiveSheetIndex(2);
    $sheet_3 = $spread->getActiveSheet();
    $imagen = $this->cargarImagenExcel('A1', 3, 3);
    $imagen->setWorksheet($sheet_3);
    $sheet_3->setCellValue('A2', $institucion_seleccionada);
    $sheet_3->setCellValue('B3', date('Y-m-d'));

    $sheet_3->setCellValue('B5', $bueno);
    $sheet_3->setCellValue('C5', $atraso_leve);
    $sheet_3->setCellValue('D5', $atraso_moderado);
    $sheet_3->setCellValue('E5', $atraso_grave);
    $total_cumplidos = $bueno_cumplido + $atraso_leve_cumplido + $atraso_moderado_cumplido + $atraso_grave_cumplido + $sin_acciones_cumplido;
    $sheet_3->setCellValue('H5', $total_cumplidos);

    //GENERACION DEL GRAFICO
    $grafico = 'Grafico_Estado_de_Gestion';
    $chart = $this->cargarGraficoEstadoCompromisos($sheet_3, $grafico);
    $sheet_3->addChart($chart);
    return $chart;
  }
  public function exportarExcelMinisterio(request $request)
  {

    $objSelect = new RepositorioController();
    $valida_institucion = $objSelect->validarArregloVacio($request->institucion);
    if ($valida_institucion)  $url = 'storage/FORMATOS_COMPROMISOS/MINISTERIO_EJECUTIVO_COMPROMISOS.xlsx';
    else  $url = 'storage/FORMATOS_COMPROMISOS/MINISTERIO_COMPROMISOS.xlsx';

    $reader = IOFactory::createReader("Xlsx");
    $spread = $reader->load($url);

    $writer = IOFactory::createWriter($spread, 'Xlsx');

    $spread->setActiveSheetIndex(0);
    $sheet = $spread->getActiveSheet();
    $this->imagen_institucional = $this->cargarImagenInstitucional();
    $imagen = $this->cargarImagenExcel();
    $imagen->setWorksheet($sheet);

    $sheet->setCellValue("B3", date('Y-m-d'));
    $institucion_id = null;
    $filaFinal = 0;
    $institucion_seleccionada = '';

    $this->dimension_style_logo = $sheet->getRowDimension(1);
    $this->dimension_style_cabecera_1 = $sheet->getRowDimension(2);
    $this->dimension_style_cabecera_2 = $sheet->getRowDimension(4);

    $compromisos = $this->consultaDatosCompromisosMinisterio($request, false);
    foreach ($compromisos as $key => $compromiso) {
      if ($filaFinal == 0) $filaFinal = $this->inicializadorContenido + $key;
      else $filaFinal = $filaFinal + 1;
      if ($institucion_id != $compromiso['institucion_id']) {
        $institucion_seleccionada = $compromiso['institucion_'];
        $institucion_id = $compromiso['institucion_id'];
        if ($key == 0) {
          $sheet->setCellValue('A2', $compromiso['institucion_']);
          $sheet->getStyle('A2' . ':M2')->applyFromArray($this->styleCabecera1);
        } else {
          $filaFinal = $this->insertarCabeceraLogo($sheet, $filaFinal);
          $filaFinal = $this->insertarCabeceraNombre($sheet, $filaFinal, $compromiso['institucion_']);
          $filaFinal = $this->insertarCabeceraFechaCorte($sheet, $filaFinal);
          $filaFinal = $this->insertarCabeceraTabla($sheet, $filaFinal);
        }
      }

      $sheet->setCellValue('A' . $filaFinal, $compromiso['reg_']);
      $sheet->setCellValue('B' . $filaFinal, $compromiso['nombre_']);
      $sheet->setCellValue('C' . $filaFinal, $compromiso['provincias']);
      $sheet->setCellValue('D' . $filaFinal, $compromiso['cantones']);
      $sheet->setCellValue('E' . $filaFinal, $compromiso['fecha_inicio_']);
      $sheet->setCellValue('F' . $filaFinal, $compromiso['fecha_fin_']);
      $sheet->setCellValue('G' . $filaFinal, $compromiso['estado_porcentaje_']);
      $sheet->setCellValue('H' . $filaFinal, $compromiso['estado_']);
      $sheet->setCellValue('I' . $filaFinal, $compromiso['avance_compromiso_']);
      $sheet->setCellValue('J' . $filaFinal, $compromiso['ultimo_avance_aprobado']);
      $sheet->setCellValue('K' . $filaFinal, $compromiso['fecha_revisa']);
      $sheet->setCellValue('L' . $filaFinal, $compromiso['notas_compromiso_']);
      $sheet->setCellValue('M' . $filaFinal, $compromiso['fecha_reporte']);
      $sheet->getStyle('A' . $filaFinal . ':M' . $filaFinal)->applyFromArray($this->styleCompromisos);
      $sheet->getRowDimension($filaFinal)->setRowHeight($this->dimension_style_cabecera_2->getRowHeight());
    }

    //SEGUNDA HOJA: TABLA RESUMEN Y GRAFICO
    $spread->setActiveSheetIndex(1);

    if ($valida_institucion)   $chart = $this->cargarGraficoMinisterioIndividual($request, $spread, $institucion_seleccionada);
    else $chart = $this->cargarGraficoMinisterioGrupal($request, $spread);

    $spread->setActiveSheetIndex(0);
    $chart->render($spread);
    $writer->setIncludeCharts(TRUE);
    $documento_generado = "MINISTERIO_COMPROMISOS_" . date('Y-m-d') . ".xlsx";
    $writer->save("storage/COMPROMISOS_GENERADOS/" . $documento_generado);
    //$writer->save("reporte_compromisos_ministerio.xlsx");

    $array_response['status'] = 200;
    $array_response['documento_nombre'] = $documento_generado;

    return response()->json($array_response, 200);
  }
  protected function consultaResumenesGabinete($request, $gestion = false)
  {
    if ($gestion) {
      $cqlResumen = VistaCompromisosMinisterios::select(
        'gabinete',
        'estado_gestion',
        DB::RAW('SUM(sin_acciones) as sin_acciones'),
        DB::RAW('SUM(bueno) as bueno'),
        DB::RAW('SUM(atraso_leve)  as atraso_leve'),
        DB::RAW('SUM(atraso_moderado)  as atraso_moderado'),
        DB::RAW('SUM(atraso_grave)  as atraso_grave'),
      );
    } else {
      $cqlResumen = VistaCompromisosMinisterios::select(
        'gabinete',
        DB::RAW('SUM(sin_acciones) as sin_acciones'),
        DB::RAW('SUM(bueno) as bueno'),
        DB::RAW('SUM(atraso_leve)  as atraso_leve'),
        DB::RAW('SUM(atraso_moderado)  as atraso_moderado'),
        DB::RAW('SUM(atraso_grave)  as atraso_grave'),
      );
    }
    if ($request->gabinete != []) $cqlResumen = $cqlResumen->whereIn('institucion_id', $request->gabinete);
    $cqlResumen = $cqlResumen->whereBetween('fecha_inicio', [$request->fecha_inicio, $request->fecha_fin]);

    return $cqlResumen;
  }
  public function exportarExcelResumenGabinete(request $request)
  {
    $objSelect = new RepositorioController();
    if ($request->tipo == 'gestion')
      $url = 'storage/FORMATOS_COMPROMISOS/RESUMEN_GABINETE_GESTION.xlsx';
    else
      $url = 'storage/FORMATOS_COMPROMISOS/RESUMEN_GABINETE_GESTION_COMPROMISOS.xlsx';


    $reader = IOFactory::createReader("Xlsx");
    $spread = $reader->load($url);
    $spread->setActiveSheetIndex(0);
    $sheet = $spread->getActiveSheet();
    $writer = IOFactory::createWriter($spread, 'Xlsx');
    $this->imagen_institucional  = $this->cargarImagenInstitucional();
    $imagen = $this->cargarImagenExcel();
    $imagen->setWorksheet($sheet);


    if ($request->tipo == 'gestion') {
      $compromisos = $this->consultaResumenesGabinete($request)
        ->whereIn('estado_gestion', ['EN EJECUCION', 'STAND BY'])
        ->groupby('gabinete')
        ->orderby('gabinete', 'asc')
        ->get()->toArray();
    } else {
      $compromisos = $this->consultaResumenesGabinete($request, true)
        ->whereIn('estado_gestion', ['CUMPLIDO', 'CERRADO'])
        ->groupby('gabinete', 'estado_gestion')
        ->orderby('gabinete', 'asc')
        ->get()->toArray();
    }

    $sheet->setCellValue("B3", date('Y-m-d'));
    $filaFinal = 0;

    $this->dimension_style_logo = $sheet->getRowDimension(1);
    $this->dimension_style_cabecera_1 = $sheet->getRowDimension(2);
    $this->dimension_style_cabecera_2 = $sheet->getRowDimension(4);
    if ($request->tipo == 'gestion') {

      foreach ($compromisos as $key => $compromiso) {
        if ($filaFinal == 0) $filaFinal = $this->inicializadorContenido + $key;
        else $filaFinal = $filaFinal + 1;

        $sheet->setCellValue('A' . $filaFinal, $compromiso['gabinete']);
        $sheet->setCellValue('B' . $filaFinal, $compromiso['bueno']);
        $sheet->setCellValue('D' . $filaFinal, $compromiso['atraso_leve']);
        $sheet->setCellValue('F' . $filaFinal, $compromiso['atraso_moderado']);
        $sheet->setCellValue('H' . $filaFinal, $compromiso['atraso_grave']);
      }
    } else {
      $gabinete = null;
      $array_cumplidos = [];
      foreach ($compromisos as $key => $compromiso) {
        if ($gabinete != $compromiso['gabinete']) {
          $gabinete = $compromiso['gabinete'];
          if ($filaFinal == 0) $filaFinal = $this->inicializadorContenido + $key;
          else $filaFinal = $filaFinal + 1;
        }

        $bueno_cumplido = is_null($compromiso['bueno']) ? 0 : $compromiso['bueno'];
        $atraso_leve_cumplido = is_null($compromiso['atraso_leve']) ? 0 : $compromiso['atraso_leve'];
        $atraso_moderado_cumplido = is_null($compromiso['atraso_moderado']) ? 0 : $compromiso['atraso_moderado'];
        $atraso_grave_cumplido = is_null($compromiso['atraso_grave']) ? 0 : $compromiso['atraso_grave'];
        $sin_acciones_cumplido = is_null($compromiso['sin_acciones']) ? 0 : $compromiso['sin_acciones'];
        $total_cumplidos = $bueno_cumplido + $atraso_leve_cumplido + $atraso_moderado_cumplido + $atraso_grave_cumplido + $sin_acciones_cumplido;
        //  $array_cumplidos[$compromiso['gabinete']]=$total_cumplidos.'|'.$compromiso['estado_gestion'].'|'.$filaFinal;
        $sheet->setCellValue('A' . $filaFinal, $compromiso['gabinete']);
        if ($compromiso['estado_gestion'] == 'CUMPLIDO') $sheet->setCellValue('B' . $filaFinal, $total_cumplidos);
        else $sheet->setCellValue('D' . $filaFinal, $total_cumplidos);
      }
    }

    //GENERACION DEL GRAFICO
    $spread->setActiveSheetIndex(1);
    $sheet_2 = $spread->getActiveSheet();
    if ($request->tipo == 'gestion') {
      $documento_generado = "RESUMEN_GABINETE_GESTION" . date('Y-m-d') . ".xlsx";

      $chart = $this->graficarDatosResumenEstadosGestionBarras();
    } else {

      $documento_generado = "RESUMEN_GABINETE_ESTADO_COMPROMISO" . date('Y-m-d') . ".xlsx";
      $chart = $this->graficarDatosResumenEstadosCompromisosBarras();

    }
    $sheet_2->addChart($chart);
    $spread->setActiveSheetIndex(0);
    $chart->render($spread);
    $writer->setIncludeCharts(TRUE);
    //$writer->save("reporte_compromisos_gabinete.xlsx");
    $writer->save("storage/COMPROMISOS_GENERADOS/" . $documento_generado);
    //$writer->save("storage/COMPROMISOS_GENERADOS/GABINETE_COMPROMISOS.xlsx");
    $array_response['status'] = 200;
    $array_response['documento_nombre'] = $documento_generado;

    return response()->json($array_response, 200);
  }
  protected function graficarDatosResumenEstadosGestionBarras()
  {

    $fila_ = 13;
    $dataseriesLabels1 = array(
      new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'Resumen!$C$4', NULL, 1, [], NULL, "00B050"), //en ejecucion
      new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'Resumen!$E$4', NULL, 1, [], NULL, "FFFF00"), //cerrado
      new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'Resumen!$G$4', NULL, 1, [], NULL, "FFC000"), //cerrado
      new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'Resumen!$I$4', NULL, 1, [], NULL, "FF0000"), //cerrado
    );

    $dataSeriesValues1 = array(
      new DataSeriesValues('Number', 'Resumen!$C$5:$C$' . $fila_, NULL, 4),
      new DataSeriesValues('Number', 'Resumen!$E$5:$E$' . $fila_, NULL, 4),
      new DataSeriesValues('Number', 'Resumen!$G$5:$G$' . $fila_, NULL, 4),
      new DataSeriesValues('Number', 'Resumen!$I$5:$I$' . $fila_, NULL, 4),
    );

    $xAxisTickValues = array(
      new DataSeriesValues('String', 'Resumen!$A$5:$A' . $fila_, NULL, 4), //  Cumpli, Cerrado, etc
    );
    //  Construye la serie de datos
    $series1 = new DataSeries(
      DataSeries::TYPE_BARCHART, // plotType
      DataSeries::GROUPING_CLUSTERED, // plotGrouping STANDARD CLUSTERED
      range(0, count($dataSeriesValues1) - 1), // plotOrder
      $dataseriesLabels1, // plotLabel
      $xAxisTickValues, // plotCategory
      $dataSeriesValues1                              // plotValues
    );


    $series1->setPlotDirection(DataSeries::DIRECTION_COL);

    $layout1 = new Layout();
    $layout1->setShowVal(true);
    $plotarea = new PlotArea($layout1, array($series1));
    $legend = new Legend(Legend::POSITION_RIGHT, NULL, false);

    //  Crea el grÃ¡fico
    $chart = new Chart(
      'Grafico', // name
      NULL, //title
      $legend, // legend
      $plotarea

    );
    //  Establezca la posiciÃ³n donde debe aparecer el grÃ¡fico en la hoja de trabajo
    $inicia_grafico = 3;
    $termina_grafico = $inicia_grafico + 26;
    $chart->setTopLeftPosition('A' . $inicia_grafico);
    $chart->setBottomRightPosition('W' . $termina_grafico);
    //  Agregue el grÃ¡fico a la hoja de trabajo
    return $chart;
  }
  protected function graficarDatosResumenEstadosCompromisosBarras()
  {

    $fila_ = 13;
    $dataseriesLabels1 = array(
      new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'Resumen!$E$4', NULL, 1, [], NULL, "A5A5A5"), //cerrado
      new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'Resumen!$C$4', NULL, 1, [], NULL, "0070C0"), //en ejecucion
    );
    $dataSeriesValues1 = array(
      new DataSeriesValues('Number', 'Resumen!$E$5:$E$' . $fila_, NULL, 2),
      new DataSeriesValues('Number', 'Resumen!$C$5:$C$' . $fila_, NULL, 2),
    );
    /*  $dataseriesLabels1 = array(
      new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'Resumen!$C$4', NULL, 1, [], NULL, "0070C0"), //en ejecucion
      new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'Resumen!$E$4', NULL, 1, [], NULL, "A5A5A5"), //cerrado
    );

    $dataSeriesValues1 = array(
      new DataSeriesValues('Number', 'Resumen!$C$5:$C$' . $fila_, NULL, 2),
      new DataSeriesValues('Number', 'Resumen!$E$5:$E$' . $fila_, NULL, 2),
    );
*/
    $xAxisTickValues = array(
      new DataSeriesValues('String', 'Resumen!$A$5:$A' . $fila_, NULL, 2), //  Cumpli, Cerrado, etc
    );
    //  Construye la serie de datos
    $series1 = new DataSeries(
      DataSeries::TYPE_BARCHART, // plotType
      DataSeries::GROUPING_CLUSTERED, // plotGrouping STANDARD CLUSTERED
      range(0, count($dataSeriesValues1) - 1), // plotOrder
      $dataseriesLabels1, // plotLabel
      $xAxisTickValues, // plotCategory
      $dataSeriesValues1                              // plotValues
    );

    $series1->setPlotDirection(DataSeries::DIRECTION_COL);
    /* MOSTRAR VALORES O ETIQUETAS EN LOS GRAFICOS */
    $layout1 = new Layout();
    $layout1->setShowVal(true);

    /* MOSTRAR VALORES O ETIQUETAS EN LOS GRAFICOS */
    $plotarea = new PlotArea($layout1, array($series1));
    $legend = new Legend(Legend::POSITION_RIGHT, NULL, false);
    //  Crea el grÃ¡fico
    $chart = new Chart(
      'chart1',
      null,
      $legend,
      $plotarea,
      true,
      0,
      null,
      null,
      null,
      null
    );

    //  Establezca la posiciÃ³n donde debe aparecer el grÃ¡fico en la hoja de trabajo
    $inicia_grafico = 3;
    $termina_grafico = $inicia_grafico + 26;
    $chart->setTopLeftPosition('A' . $inicia_grafico);
    $chart->setBottomRightPosition('W' . $termina_grafico);
    //  Agregue el grÃ¡fico a la hoja de trabajo
    return $chart;
  }
  public function exportarExcelGabinete(request $request)
  {

    $objSelect = new RepositorioController();
    $objSelectCompromiso = new CompromisosController();
    $valida = $objSelect->validarArregloVacio($request->gabinete);
    $url = 'storage/FORMATOS_COMPROMISOS/GABINETE_COMPROMISOS.xlsx';
    if ($valida)  $url = 'storage/FORMATOS_COMPROMISOS/GABINETE_EJECUTIVO_COMPROMISOS.xlsx';

    $reader = IOFactory::createReader("Xlsx");
    $spread = $reader->load($url);
    $spread->setActiveSheetIndex(0);
    $sheet = $spread->getActiveSheet();
    $writer = IOFactory::createWriter($spread, 'Xlsx');
    $this->imagen_institucional  = $this->cargarImagenInstitucional();
    $imagen = $this->cargarImagenExcel();
    $imagen->setWorksheet($sheet);
    $fill = new \stdClass();
    $fill->gabinete_id_exportar_monitor = $request->gabinete;
    $fill->institucion_id_exportar_monitor = [];
    $fill->estado_id_exportar_monitor = [];
    $fill->estado_porcentaje_id_exportar_monitor = [];

    $compromisos = $objSelect->selectConsultaCompromisos();
    $compromisos = $objSelectCompromiso
      ->filtroInstitucional($compromisos, $fill, false);
    $compromisos = $compromisos->whereBetween('compromisos.fecha_inicio', [$request->fecha_inicio, $request->fecha_fin]);
    $compromisos = $compromisos->orderby('institucion.institucion_id', 'asc')
      ->orderby('institucion.id', 'asc')->get()->toArray();

    $sheet->setCellValue("B3", date('Y-m-d'));
    $institucion_id = null;
    $filaFinal = 0;

    $institucion_seleccionada = '';
    $this->dimension_style_logo = $sheet->getRowDimension(1);
    $this->dimension_style_cabecera_1 = $sheet->getRowDimension(2);
    $this->dimension_style_cabecera_2 = $sheet->getRowDimension(4);

    foreach ($compromisos as $key => $compromiso) {
      if ($filaFinal == 0) $filaFinal = $this->inicializadorContenido + $key;
      else $filaFinal = $filaFinal + 1;
      if ($institucion_id != $compromiso['gabinete_id']) {
        $institucion_seleccionada = $compromiso['gabinete_'];
        $institucion_id = $compromiso['gabinete_id'];
        if ($key == 0) {
          $sheet->setCellValue('A2', $compromiso['gabinete_']);
          $sheet->getStyle('A2' . ':M2')->applyFromArray($this->styleCabecera1);
        } else {
          $filaFinal = $this->insertarCabeceraLogo($sheet, $filaFinal);
          $filaFinal = $this->insertarCabeceraNombre($sheet, $filaFinal, $compromiso['gabinete_']);
          $filaFinal = $this->insertarCabeceraFechaCorte($sheet, $filaFinal);
          $filaFinal = $this->insertarCabeceraTabla($sheet, $filaFinal);
        }
      }

      $sheet->setCellValue('A' . $filaFinal, $compromiso['reg_']);
      $sheet->setCellValue('B' . $filaFinal, $compromiso['nombre_']);
      $sheet->setCellValue('C' . $filaFinal, $compromiso['institucion_']);
      $sheet->setCellValue('D' . $filaFinal, $compromiso['provincias']);
      $sheet->setCellValue('E' . $filaFinal, $compromiso['cantones']);
      $sheet->setCellValue('F' . $filaFinal, $compromiso['fecha_inicio_']);
      $sheet->setCellValue('G' . $filaFinal, $compromiso['fecha_fin_']);
      $sheet->setCellValue('H' . $filaFinal, $compromiso['estado_porcentaje_']);
      $sheet->setCellValue('I' . $filaFinal, $compromiso['estado_']);
      $sheet->setCellValue('J' . $filaFinal, $compromiso['avance_compromiso_']);
      $sheet->setCellValue('K' . $filaFinal, $compromiso['ultimo_avance_aprobado']);
      $sheet->setCellValue('L' . $filaFinal, $compromiso['fecha_revisa']);
      $sheet->setCellValue('M' . $filaFinal, $compromiso['fecha_reporte']);
      //  $sheet->setCellValue('L' . $filaFinal, $compromiso['notas_compromiso_']);
      $sheet->getStyle('A' . $filaFinal . ':M' . $filaFinal)->applyFromArray($this->styleCompromisos);
    }
    $spread->setActiveSheetIndex(1);
    $sheet_2 = $spread->getActiveSheet();

    if ($valida) {
      $instituciones = Institucion::where('institucion_id', $request->gabinete)->pluck('id');
      $objConsulta = new \stdClass();
      $objConsulta->institucion = $instituciones;
      $objConsulta->fecha_inicio = $request->fecha_inicio;
      $objConsulta->fecha_fin = $request->fecha_fin;

      $cqlResumen = $this->consultaResumenesMinisterio($objConsulta, false);
      $cqlResumenCumplidos = $this->consultaResumenesMinisterio($objConsulta, false);
      $data = $cqlResumen
        ->whereIn('estado_gestion', ['EN EJECUCION', 'STAND BY'])->get()->toArray();
      $cumplido = $cqlResumenCumplidos
        ->whereIn('estado_gestion', ['CUMPLIDO'])->get()->toArray();
      $bueno = is_null($data[0]['bueno']) ? 0 : $data[0]['bueno'];
      $atraso_leve = is_null($data[0]['atraso_leve']) ? 0 : $data[0]['atraso_leve'];
      $atraso_moderado = is_null($data[0]['atraso_moderado']) ? 0 : $data[0]['atraso_moderado'];
      $atraso_grave = is_null($data[0]['atraso_grave']) ? 0 : $data[0]['atraso_grave'];

      $bueno_cumplido = is_null($cumplido[0]['bueno']) ? 0 : $cumplido[0]['bueno'];
      $atraso_leve_cumplido = is_null($cumplido[0]['atraso_leve']) ? 0 : $cumplido[0]['atraso_leve'];
      $atraso_moderado_cumplido = is_null($cumplido[0]['atraso_moderado']) ? 0 : $cumplido[0]['atraso_moderado'];
      $atraso_grave_cumplido = is_null($cumplido[0]['atraso_grave']) ? 0 : $cumplido[0]['atraso_grave'];
      $sin_acciones_cumplido = is_null($cumplido[0]['sin_acciones']) ? 0 : $cumplido[0]['sin_acciones'];

      // $spread->setActiveSheetIndex(2);

      $imagen = $this->cargarImagenExcel('A1', 3, 3);
      $imagen->setWorksheet($sheet_2);
      $sheet_2->setCellValue('A2', $institucion_seleccionada);
      $sheet_2->setCellValue('B3', date('Y-m-d'));

      $sheet_2->setCellValue('B5', $bueno);
      $sheet_2->setCellValue('C5', $atraso_leve);
      $sheet_2->setCellValue('D5', $atraso_moderado);
      $sheet_2->setCellValue('E5', $atraso_grave);
      $total_cumplidos = $bueno_cumplido + $atraso_leve_cumplido + $atraso_moderado_cumplido + $atraso_grave_cumplido + $sin_acciones_cumplido;
      $sheet_2->setCellValue('H5', $total_cumplidos);

      //GENERACION DEL GRAFICO
      $grafico = 'Grafico_Estado_de_Gestion';
      $chart = $this->cargarGraficoEstadoCompromisos($sheet_2, $grafico);
      $sheet_2->addChart($chart);
    } else {
      $cqlResumen = VistaCompromisosGabinetes::select(
        'siglas',
        DB::RAW('SUM(cumplido) as cumplido'),
        DB::RAW('SUM(en_ejecucion) as en_ejecucion'),
        DB::RAW('SUM(en_planificacion)  as en_planificacion'),
        DB::RAW('SUM(stand_by)  as stand_by'),
        DB::RAW('SUM(cerrado)  as cerrado'),
      );
      if ($request->gabinete != []) $cqlResumen = $cqlResumen->whereIn('institucion_id', $request->gabinete);
      $cqlResumen = $cqlResumen->whereBetween('fecha_inicio', [$request->fecha_inicio, $request->fecha_fin]);

      $data = $cqlResumen
        ->orderby('siglas')
        ->groupBy('siglas')
        ->get()->toArray();

      $sheet_2->fromArray(
        $data,
        NULL,
        'A3'
      );

      $fila_r = 3;
      $fila_ = 0;

      foreach ($data as $key => $resumen) {
        $fila_ = $fila_r + $key;
        $sheet_2->setCellValue('A' . $fila_, $resumen['siglas']);
        $sheet_2->setCellValue('B' . $fila_, $resumen['cumplido']);
        $sheet_2->setCellValue('C' . $fila_, $resumen['en_ejecucion']);
        $sheet_2->setCellValue('D' . $fila_, $resumen['en_planificacion']);
        $sheet_2->setCellValue('E' . $fila_, $resumen['cerrado']);
        $sheet_2->setCellValue('F' . $fila_, $resumen['stand_by']);
      }
      //SUMA DE TOTALES horizontal
      $totales = $fila_ + 1;
      $sheet_2->getStyle('A' . $totales . ':G' . $totales)->applyFromArray($this->styleTotales);
      $sheet_2->setCellValue("A" . $totales, 'TOTAL');
      $sheet_2->setCellValue("B" . $totales, '=SUM(B3:B' . $fila_ . ')');
      $sheet_2->setCellValue("C" . $totales, '=SUM(C3:C' . $fila_ . ')');
      $sheet_2->setCellValue("D" . $totales, '=SUM(D3:D' . $fila_ . ')');
      $sheet_2->setCellValue("E" . $totales, '=SUM(E3:E' . $fila_ . ')');
      $sheet_2->setCellValue("F" . $totales, '=SUM(F3:F' . $fila_ . ')');
      $sheet_2->setCellValue("G" . $totales, '=SUM(G3:G' . $fila_ . ')');

      //SUMA DE TOTALES vertical
      $i = 1;
      while ($i < $fila_) {
        $sheet_2->getStyle('A' . $fila_r . ':G' . $fila_r)->applyFromArray($this->styleResumen);
        $sheet_2->setCellValue("G" . $fila_r, '=SUM(B' . $fila_r . ':F' . $fila_r . ')');
        $fila_r++;
        $i++;
      }

      //GENERACION DEL GRAFICO
      $dataseriesLabels1 = array(
        new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'Grafico!$B$2', NULL, 1, [], NULL, "4472C4"), //cumplido
        new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'Grafico!$C$2', NULL, 1, [], NULL, "548235"), //en ejecucion
        new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'Grafico!$D$2', NULL, 1, [], NULL, 'A6A6A6'), //en planificacion
        new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'Grafico!$E$2', NULL, 1, [], NULL, "FFD966"), //cerrado
        new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'Grafico!$F$2', NULL, 1, [], NULL, "EB84EC"), //stand by
      );

      $dataSeriesValues1 = array(
        new DataSeriesValues('Number', 'Grafico!$B$3:$B$' . $fila_, NULL, 5),
        new DataSeriesValues('Number', 'Grafico!$C$3:$C$' . $fila_, NULL, 5),
        new DataSeriesValues('Number', 'Grafico!$D$3:$D$' . $fila_, NULL, 5),
        new DataSeriesValues('Number', 'Grafico!$E$3:$E$' . $fila_, NULL, 5),
        new DataSeriesValues('Number', 'Grafico!$F$3:$F$' . $fila_, NULL, 5),
      );

      $xAxisTickValues = array(
        new DataSeriesValues('String', 'Grafico!$A$3:$A' . $fila_, NULL, 5), //  Cumpli, Cerrado, etc
      );
      //  Construye la serie de datos
      $series1 = new DataSeries(
        DataSeries::TYPE_BARCHART, // plotType
        DataSeries::GROUPING_STANDARD, // plotGrouping STANDARD CLUSTERED
        range(0, count($dataSeriesValues1) - 1), // plotOrder
        $dataseriesLabels1, // plotLabel
        $xAxisTickValues, // plotCategory
        $dataSeriesValues1                              // plotValues
      );

      $series1->setPlotDirection(DataSeries::DIRECTION_COL);
      $plotarea = new PlotArea(NULL, array($series1));
      $legend = new Legend(Legend::POSITION_RIGHT, NULL, false);

      //  Crea el grÃ¡fico
      $chart = new Chart(
        'Grafico', // name
        NULL, //title
        $legend, // legend
        $plotarea
      );
      //  Establezca la posiciÃ³n donde debe aparecer el grÃ¡fico en la hoja de trabajo
      $inicia_grafico = $fila_ + 3;
      $termina_grafico = $inicia_grafico + 15;
      $chart->setTopLeftPosition('A' . $inicia_grafico);
      $chart->setBottomRightPosition('J' . $termina_grafico);
      //  Agregue el grÃ¡fico a la hoja de trabajo
      $sheet_2->addChart($chart);
    }


    $spread->setActiveSheetIndex(0);
    $chart->render($spread);
    $writer->setIncludeCharts(TRUE);
    $documento_generado = "GABINETE_COMPROMISOS_" . date('Y-m-d') . ".xlsx";
    //$writer->save("reporte_compromisos_gabinete.xlsx");
    $writer->save("storage/COMPROMISOS_GENERADOS/" . $documento_generado);
    //$writer->save("storage/COMPROMISOS_GENERADOS/GABINETE_COMPROMISOS.xlsx");
    $array_response['status'] = 200;
    $array_response['documento_nombre'] = $documento_generado;

    return response()->json($array_response, 200);
  }
  public function reporteDinamico_tc(request $request)
  {
    $array['datatable'] = $this->cargarDatosDashboard($request, "false", false, true);
    $array_response['cantonal'] = $array['datatable']->sum('contador_cantones');
    $array_response['provincia'] = $array['datatable']->sum('contador') - $array['datatable']->sum('contador_nacional');
    $array_response['nacional'] = $array['datatable']->sum('contador_nacional');
    /* $data = $this->consultaDatosContadores($request);
        $array_response['cantonal'] = $data['cantonal'];
        $array_response['provincia'] = $data['provincia'];
        $array_response['nacional'] = $data['nacional'];*/

    $array_response['status'] = 200;

    return response()->json($array_response, 200);
  }
  public function busquedaCompromisosporReporte(Request $request)
  {
    $data = [];
    $array_response['status'] = 300;

    if ($request->valida_fecha) {
      $data = Compromiso::select(["compromisos.id", DB::RAW("CONCAT(REPLACE(compromisos.codigo,' ',''),'/ ',compromisos.nombre_compromiso) as describe")])
        ->join('sc_compromisos.responsables as responsable', 'responsable.compromiso_id', 'compromisos.id')
        ->whereNotNull('compromisos.codigo')
        ->whereBetween('compromisos.fecha_inicio', [$request->fecha_inicio, $request->fecha_fin]);
      if (count($request->filtro_institucion_ejecutivo) > 0)
        $data = $data->whereIn('responsable.institucion_id', $request->filtro_institucion_ejecutivo);
      $data = $data->where('responsable.estado', 'ACT')
        ->orderby('compromisos.id', 'desc')->pluck('describe', 'id');
      $array_response['status'] = 200;
    }
    $array_response['datos'] = $data;
    return response()->json($array_response, 200);
  }
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
  //REPORTES POR GABINETE
  /* public function exportarExcelGabinete(request $request)
    {
        //ESTILO PARA CELDAS DE Institucion
        $styleInstitucion = [
            'font' => [
                'bold' => true, 'color' => array('rgb' => '000000'), 'size'  => 12, 'name'  => 'Calibri'
            ],
            'alignment' => [
                'horizontal' => 'left', 'vertical' => 'center',
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
        ];
        $styleCompromisos = [
            'font' => [
                'bold' => false, 'color' => array('rgb' => '000000'), 'size'  => 12, 'name'  => 'Calibri'
            ],
            'alignment' => [
                'horizontal' => 'left', 'vertical' => 'center', 'wrapText' => true
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
        ];
        $styleTotales = [
            'font' => [
                'bold' => true, 'color' => array('rgb' => '000000'), 'size'  => 12, 'name'  => 'Calibri'
            ],
            'alignment' => [
                'horizontal' => 'center', 'vertical' => 'center'
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => [
                    'argb' => 'DCE6F1',
                ],
            ],
        ];
        $styleResumen = [
            'font' => [
                'bold' => true, 'color' => array('rgb' => '000000'), 'size'  => 12, 'name'  => 'Calibri'
            ],
            'alignment' => [
                'horizontal' => 'center', 'vertical' => 'center'
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
        ];

        $array_response['status'] = "200";
        //$url='storage/compromisos_gabinete.xlsx';
        $url = 'storage/FORMATOS_COMPROMISOS/GABINETE_COMPROMISOS.xlsx';
        $reader = IOFactory::createReader("Xlsx");
        $spread = $reader->load($url);
        $spread->setActiveSheetIndex(0);
        $sheet_1 = $spread->getActiveSheet();
        $sheet_1->getDefaultRowDimension()->setRowHeight(30);
        $writer = IOFactory::createWriter($spread, 'Xlsx');

        $cqlInstituciones = Institucion::select(
            'descripcion as institucion',
            'id as id'
        )
            ->where('institucion_id', $request->gabinete)
            ->orderBy('descripcion', 'asc')
            ->get();

        $sheet_1->setCellValue("A3", $request->nombre_gabinete);
        $sheet_1->setCellValue("C4", date('Y-m-d'));

        $fila = 6;
        if (count($cqlInstituciones) > 0) {
            foreach ($cqlInstituciones as $key => $institucion) {
                $cqlCompromisos = Compromiso::select(
                    'compromisos.codigo as codigo_compromiso',
                    'compromisos.nombre_compromiso as nombre_compromiso',
                    DB::RAW("REPLACE(REGEXP_REPLACE(regexp_replace(ARRAY_TO_STRING(ARRAY_AGG(distinct provincia.descripcion), ','), '[^a-zA-Z0-9]+','')  ,'[[:digit:]]','','g'),'-','') as provincia"),
                    DB::RAW("REPLACE(REGEXP_REPLACE(regexp_replace(ARRAY_TO_STRING(ARRAY_AGG(distinct ciudad.descripcion), ','), '[^a-zA-Z0-9]+','')  ,'[[:digit:]]','','g'),'-','') as canton"),
                    'fecha_inicio',
                    'fecha_fin',
                    'estado_gestion.descripcion as estado_gestion',
                    'estado_compromiso.descripcion as estado_compromiso',
                    'compromisos.avance as porcentaje_avance',
                    'compromisos.avance_compromiso as avance_compromiso',
                    'avance.fecha_revisa as fecha_ultimo_avance',
                )
                    ->leftjoin('sc_compromisos.responsables as responsable_', 'responsable_.compromiso_id', 'compromisos.id')
                    ->join('sc_compromisos.instituciones as institucion_', 'institucion_.id', 'responsable_.institucion_id')
                    ->leftjoin('sc_compromisos.estados as estado_compromiso', 'estado_compromiso.id', 'compromisos.estado_id')
                    ->leftjoin('sc_compromisos.estados_porcentaje as estado_gestion', 'estado_gestion.id', 'compromisos.estado_porcentaje_id')
                    ->leftjoin('sc_compromisos.ubicaciones as ubicacion', 'ubicacion.compromiso_id', 'compromisos.id')
                    ->leftjoin('core.parametro_ciudad as provincia', function ($join) {
                        $join->on('provincia.id', 'ubicacion.parametro_id')
                            ->where('provincia.verificacion', 'PROVINCIA');
                    })
                    ->leftjoin('core.parametro_ciudad as ciudad', function ($join) {
                        $join->on('ciudad.id', 'ubicacion.parametro_id')
                            ->where('ciudad.verificacion', 'CANTON');
                    })
                    ->leftjoin('sc_compromisos.avances as avance', function ($join) {
                        $join->on('avance.compromiso_id', 'compromisos.id')
                            ->where('avance.aprobado', 'SI')
                            ->where('avance.estado', 'ACT');
                    })
                    ->where('compromisos.estado', 'ACT')
                    ->where('responsable_.estado', 'ACT')
                    ->where('institucion_.id', $institucion['id'])
                    ->groupBy('compromisos.id', 'institucion_.descripcion', 'estado_gestion.descripcion', 'estado_compromiso.descripcion', 'avance.fecha_revisa')
                    ->orderBy('institucion_.descripcion', 'asc');
                $contarCompromisos = $cqlCompromisos->get()->count();
                $cqlCompromisos = $cqlCompromisos->get()->toArray();

                if (count($cqlCompromisos) > 0) {
                    $sheet_1->getStyle("A" . $fila . ":K" . $fila)->applyFromArray($styleInstitucion);
                    $sheet_1->mergecells("A" . $fila . ":K" . $fila);
                    $sheet_1->setCellValue("A" . $fila, $institucion['institucion']);

                    $fila++;
                    // construir la seccion de los compromisos por institucion

                    foreach ($cqlCompromisos as $key => $compromiso) {
                        $fila_ = $fila + $key;
                        $sheet_1->setCellValue('A' . $fila_, $compromiso['codigo_compromiso']);
                        $sheet_1->setCellValue('B' . $fila_, $compromiso['nombre_compromiso']);
                        $sheet_1->setCellValue('C' . $fila_, $compromiso['provincia']);
                        $sheet_1->setCellValue('D' . $fila_, $compromiso['canton']);
                        $sheet_1->setCellValue('E' . $fila_, $compromiso['fecha_inicio']);
                        $sheet_1->setCellValue('F' . $fila_, $compromiso['fecha_fin']);
                        $sheet_1->setCellValue('G' . $fila_, $compromiso['estado_gestion']);
                        $sheet_1->setCellValue('H' . $fila_, $compromiso['estado_compromiso']);
                        $sheet_1->setCellValue('I' . $fila_, $compromiso['porcentaje_avance']);
                        $sheet_1->setCellValue('J' . $fila_, $compromiso['avance_compromiso']);
                        $sheet_1->setCellValue('K' . $fila_, $compromiso['fecha_ultimo_avance']);
                        $sheet_1->getStyle('A' . $fila_ . ':K' . $fila_)->applyFromArray($styleCompromisos);
                    }
                    $fila = $fila + $contarCompromisos;
                }
            }
        }

        //$contador = $cql->get()->count();
        //SEGUNDA HOJA: TABLA RESUMEN Y GRAFICO
        $spread->setActiveSheetIndex(1);
        $sheet_2 = $spread->getActiveSheet();
        $cqlResumen = DB::connection('pgsql_presidencia')
            ->select(
                'select *
        from sc_compromisos.fn_compromisos_gabinete(?)',
                [
                    $request->gabinete
                ]
            );

        $data = collect($cqlResumen)->map(function ($x) {
            return (array) $x;
        })->toArray();
        $fila_r = 3;

        $fila_ = 0;

        foreach ($data as $key => $resumen) {
            $fila_ = $fila_r + $key;
            $sheet_2->setCellValue('A' . $fila_, $resumen['siglas']);
            $sheet_2->setCellValue('B' . $fila_, $resumen['CUMPLIDO']);
            $sheet_2->setCellValue('C' . $fila_, $resumen['EN EJECUCIÓN']);
            $sheet_2->setCellValue('D' . $fila_, $resumen['EN PLANIFICACIÓN']);
            $sheet_2->setCellValue('E' . $fila_, $resumen['CERRADO']);
            $sheet_2->setCellValue('F' . $fila_, $resumen['STAND BY']);
        }
        //SUMA DE TOTALES horizontal
        $totales = $fila_ + 1;
        $sheet_2->getStyle('A' . $totales . ':G' . $totales)->applyFromArray($styleTotales);
        $sheet_2->setCellValue("A" . $totales, 'TOTAL');
        $sheet_2->setCellValue("B" . $totales, '=SUM(B3:B' . $fila_ . ')');
        $sheet_2->setCellValue("C" . $totales, '=SUM(C3:C' . $fila_ . ')');
        $sheet_2->setCellValue("D" . $totales, '=SUM(D3:D' . $fila_ . ')');
        $sheet_2->setCellValue("E" . $totales, '=SUM(E3:E' . $fila_ . ')');
        $sheet_2->setCellValue("F" . $totales, '=SUM(F3:F' . $fila_ . ')');
        $sheet_2->setCellValue("G" . $totales, '=SUM(G3:G' . $fila_ . ')');
        //SUMA DE TOTALES vertical
        $i = 1;
        while ($i < $fila_) {
            $sheet_2->getStyle('A' . $fila_r . ':G' . $fila_r)->applyFromArray($styleResumen);
            $sheet_2->setCellValue("G" . $fila_r, '=SUM(B' . $fila_r . ':F' . $fila_r . ')');
            $fila_r++;
            $i++;
        }

        //GENERACION DEL GRAFICO
        $dataseriesLabels1 = array(
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'Grafico!$B$2', NULL, 1, [], NULL, "4472C4"), //cumplido
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'Grafico!$C$2', NULL, 1, [], NULL, "548235"), //en ejecucion
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'Grafico!$D$2', NULL, 1, [], NULL, 'A6A6A6'), //en planificacion
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'Grafico!$E$2', NULL, 1, [], NULL, "FFD966"), //cerrado
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'Grafico!$F$2', NULL, 1, [], NULL, "EB84EC"), //stand by
        );

        $dataSeriesValues1 = array(
            new DataSeriesValues('Number', 'Grafico!$B$3:$B$' . $fila_, NULL, 5),
            new DataSeriesValues('Number', 'Grafico!$C$3:$C$' . $fila_, NULL, 5),
            new DataSeriesValues('Number', 'Grafico!$D$3:$D$' . $fila_, NULL, 5),
            new DataSeriesValues('Number', 'Grafico!$E$3:$E$' . $fila_, NULL, 5),
            new DataSeriesValues('Number', 'Grafico!$F$3:$F$' . $fila_, NULL, 5),
        );

        $xAxisTickValues = array(
            new DataSeriesValues('String', 'Grafico!$A$3:$A' . $fila_, NULL, 5), //  Cumpli, Cerrado, etc
        );
        //  Construye la serie de datos
        $series1 = new DataSeries(
            DataSeries::TYPE_BARCHART, // plotType
            DataSeries::GROUPING_STANDARD, // plotGrouping STANDARD CLUSTERED
            range(0, count($dataSeriesValues1) - 1), // plotOrder
            $dataseriesLabels1, // plotLabel
            $xAxisTickValues, // plotCategory
            $dataSeriesValues1                              // plotValues
        );

        $series1->setPlotDirection(DataSeries::DIRECTION_COL);
        $plotarea = new PlotArea(NULL, array($series1));
        $legend = new Legend(Legend::POSITION_RIGHT, NULL, false);

        //  Crea el grÃ¡fico
        $chart = new Chart(
            'Grafico', // name
            NULL, //title
            $legend, // legend
            $plotarea
        );
        //  Establezca la posiciÃ³n donde debe aparecer el grÃ¡fico en la hoja de trabajo
        $inicia_grafico = $fila_ + 3;
        $termina_grafico = $inicia_grafico + 15;
        $chart->setTopLeftPosition('A' . $inicia_grafico);
        $chart->setBottomRightPosition('J' . $termina_grafico);
        //  Agregue el grÃ¡fico a la hoja de trabajo
        $sheet_2->addChart($chart);
        $chart->render($spread);
        $writer->setIncludeCharts(TRUE);

        $documento_generado = "GABINETE_COMPROMISOS_" . date('Y-m-d') . ".xlsx";
        //$writer->save("reporte_compromisos_gabinete.xlsx");
        $writer->save("storage/COMPROMISOS_GENERADOS/" . $documento_generado);
        //$writer->save("storage/COMPROMISOS_GENERADOS/GABINETE_COMPROMISOS.xlsx");

        $array_response['status'] = 200;
        $array_response['documento_nombre'] = $documento_generado;

        return response()->json($array_response, 200);
    }
*/
  //MAPA CALOR
  public function filtro_compromiso_consulta(request $request)
  {
    if ($request->gestion_id == 0) {
      $compromiso_filtro = Compromiso::select(["id", DB::RAW("CONCAT(codigo,'/ ',nombre_compromiso) as nombre_compromiso")])
        ->where('estado', 'ACT')->get(); //pluck('nombre_compromiso','id');
    } else {
      $compromiso_filtro = Compromiso::select(["id", DB::RAW("CONCAT(codigo,'/ ',nombre_compromiso) as nombre_compromiso")])
        ->where('estado', 'ACT')->where('estado_porcentaje_id', $request->gestion_id)->get(); //pluck('nombre_compromiso','id');
    }
    $array_response['status'] = 200;
    $array_response['datos'] = $compromiso_filtro;
    return response()->json($array_response, 200);
  }

  private function retornaSelectCompromisoUbicacion($provincia_filtro='false')
  {
     if($provincia_filtro){ //FCago23
      return Compromiso::select(
        Compromiso::raw(
          "(select count(distinct u1.compromiso_id)
                  from sc_compromisos.ubicaciones u1,sc_compromisos.compromisos c
                  where c.estado='ACT' and c.codigo is not null and u1.compromiso_id=c.id and u1.id in (provincia_.id)) as contador"
        ),
        Compromiso::raw(
          "(select count(distinct u1.compromiso_id)
                  from sc_compromisos.ubicaciones u1,sc_compromisos.compromisos c
                  where
                  c.estado='ACT' and
                  c.codigo is not null and
                  u1.compromiso_id=c.id and
                  u1.parametro_id=1074 and
                  u1.parametro_id in (provincia_.id))
                  as contador_nacional"
        ),
        'provincia_.id as ubicacionId',
        'provincia_.descripcion as ubicacion',
        'provincia_.latitud as latitud',
        'provincia_.longitud as longitud',
        'provincia_.clase as clase'
      );
    }
   else
    {
      return Compromiso::select(
        Compromiso::raw(
          "(select count(distinct u1.compromiso_id)
                  from sc_compromisos.ubicaciones u1,sc_compromisos.compromisos c
                  where c.estado='ACT' and c.codigo is not null and u1.compromiso_id=c.id and u1.parametro_id in (provincia_.id)) as contador"
        ),
        Compromiso::raw(
          "(select count(distinct u1.compromiso_id)
                  from sc_compromisos.ubicaciones u1,sc_compromisos.compromisos c
                  where
                  c.estado='ACT' and
                  c.codigo is not null and
                  u1.compromiso_id=c.id and
                  u1.parametro_id=1074 and
                  u1.parametro_id in (provincia_.id))
                  as contador_nacional"
        ),
        'provincia_.id as ubicacionId',
        'provincia_.descripcion as ubicacion',
        'provincia_.latitud as latitud',
        'provincia_.longitud as longitud',
        'provincia_.clase as clase'
      );
    }
  }

  private function retornaSelectCompromisoUbicacionDetalle($query)
  {
    // recogemos el resto de campos...
    $query->addSelect(
      'estado_gestion.color',
      'sc_compromisos.compromisos.nombre_compromiso',
      'sc_compromisos.compromisos.cumplimiento',
      'sc_compromisos.compromisos.avance as porcentaje_avance',
    );
    // retorna...
    return $query;
  }

  private function retornaAgrupacion($query,$provincia_filtro='false')
  {
    if ($provincia_filtro) //FCago23
      return $query->groupBy([
        'provincia_.parametro_id',
        'provincia_.id',
        'provincia_.descripcion',
        'provincia_.latitud',
        'provincia_.longitud',
        'provincia_.clase'

      ]);
    else
      return $query->groupBy([
        'provincia_.id',
        'provincia_.descripcion',
        'provincia_.latitud',
        'provincia_.longitud',
        'provincia_.clase'

      ]);
  }

  private function retornaAgrupacionDetalle($query)
  {
    return $query->groupBy([
      'sc_compromisos.compromisos.nombre_compromiso',
      'sc_compromisos.compromisos.cumplimiento',
      'sc_compromisos.compromisos.avance',
      'estado_gestion.color'
    ]);
  }
  protected function consultaCompromisosPorUbicacion($request, $data_post)
  {
    $gestion_filtro = $request->filtro_gestion;
    $provincia_filtro = $request->filtro_ubicacion;
    $compromiso_filtro = $request->filtro_compromiso;
    $institucion_filtro = $request->filtro_institucion;
    $gabinete_filtro = $request->filtro_gabinete;

    $objSelect = new RepositorioController();
    $objSelectCompromiso = new CompromisosController();

    $fill = new \stdClass();
    $fill->institucion_id_exportar_monitor = $institucion_filtro;
    $fill->gabinete_id_exportar_monitor = $gabinete_filtro;
    $fill->provincia_id_exportar_monitor = $provincia_filtro;
    $fill->canton_id_exportar_monitor = [];
    $fill->parroquia_id_exportar_monitor = [];
    $fill->estado_id_exportar_monitor = $gestion_filtro;
    $fill->estado_porcentaje_id_exportar_monitor = [];

    $data = $objSelect->selectConsultaCompromisos();
    $data = $objSelectCompromiso->filtrosProvinciasCantones($data, $fill);
    $data = $objSelectCompromiso->filtroInstitucional($data, $fill, false);
    $data = $objSelectCompromiso->filtroEstadosCompromiso($data, $fill);
    if ($compromiso_filtro != 0 && $compromiso_filtro != "0")   $data = $data->whereIn('compromisos.id', explode(",", $compromiso_filtro));
    $data = $data->whereNotNull('compromisos.codigo');
    $data = $data->whereBetween('compromisos.fecha_inicio', [$request->fecha_inicio, $request->fecha_fin]);
    $data = $data->distinct()->get();
    if (!$data_post) {
      return (new CollectionDataTable($data))
        ->addIndexColumn()
        ->addColumn('fecha_creacion', function ($row) {
          return $row->created_at;
        })
        ->addColumn('registro_', function ($row) {
          return $row->reg_;
        })
        ->rawColumns(['','fecha_creacion','registro_'])
        ->toJson();
    } else return $data;
  }

  protected function cargarDatosDashboard($request, $tipo_detalle, $compromisos = false, $data_post = false)
  {
    //dd("cargarDatosDashboard->",$request," tipo_detalle->",$tipo_detalle," compromisos->",$compromisos, " data_post->", $data_post);
    $objRepositorio = new RepositorioController();

    $gestion_filtro = $request->filtro_gestion;
    $provincia_filtro = $request->filtro_ubicacion;
    $compromiso_filtro = $request->filtro_compromiso;
    $institucion_filtro = $request->filtro_institucion;
    $gabinete_filtro = $request->filtro_gabinete;

    $total_compromisos = Ubicacion::raw("
    (select count(distinct u1.compromiso_id)
            from sc_compromisos.ubicaciones u1,sc_compromisos.compromisos c
            where c.estado='ACT' and c.codigo is not null and u1.compromiso_id=c.id
            and u1.parametro_id in (provincia_.id))
            +

            (
                    select count(distinct c.id)
                    from sc_compromisos.ubicaciones as u, sc_compromisos.compromisos c where
                    c.estado='ACT' and c.codigo is not null
                    and u.compromiso_id=c.id and u.parametro_id in
                    (select pc1.id from core.parametro_ciudad pc1 where pc1.parametro_id=provincia_.id)
                    and u.compromiso_id not in
                    (select u1.compromiso_id from sc_compromisos.ubicaciones u1 where u1.parametro_id = provincia_.id)
                    and u.compromiso_id not in
                    ( select distinct u1.compromiso_id
                        from sc_compromisos.ubicaciones u1,sc_compromisos.compromisos c
                        where c.estado='ACT' and c.codigo is not null and u1.compromiso_id=c.id and u1.parametro_id in (provincia_.id)
                    )
          )

            as total_compromisos");
    if ($objRepositorio->validarArregloVacio($provincia_filtro)) { //FCago23
      $contador_cantones = Ubicacion::raw(
        "(
          select count(distinct u2.compromiso_id)
          from sc_compromisos.ubicaciones as u2, sc_compromisos.compromisos c where
          c.estado='ACT' and c.codigo is not null
          and u2.compromiso_id=c.id and u2.parametro_id in
          (select pc1.id from core.parametro_ciudad pc1 where pc1.parametro_id in (provincia_.id))
          and u2.compromiso_id not in
          ( select compromiso_id from sc_compromisos.ubicaciones where parametro_id in (provincia_.parametro_id) 
          )
        ) as contador_cantones "
      );
    } 
    else{
      $contador_cantones = Ubicacion::raw(  
        "(
                          select count(distinct c.id)
                          from sc_compromisos.ubicaciones as u, sc_compromisos.compromisos c where
                          c.estado='ACT' and c.codigo is not null
                          and u.compromiso_id=c.id and u.parametro_id in
                          (select pc1.id from core.parametro_ciudad pc1 where pc1.parametro_id in (provincia_.id))
  
                          and u.compromiso_id not in
                          (select u1.compromiso_id from sc_compromisos.ubicaciones u1 where u1.parametro_id = provincia_.id)
  
                          and u.compromiso_id not in
                          ( select distinct u1.compromiso_id
                              from sc_compromisos.ubicaciones u1,sc_compromisos.compromisos c
                              where c.estado='ACT' and c.codigo is not null and u1.compromiso_id=c.id and u1.parametro_id in (provincia_.id)
                          )
                ) as contador_cantones "
      );
    }
    
    /* $contador_cantones = Ubicacion::raw(
            "(
                        select count(distinct c.id)
                        from sc_compromisos.ubicaciones as u, sc_compromisos.compromisos c where
                        c.estado='ACT' and c.codigo is not null
                        and u.compromiso_id=c.id and u.parametro_id in
                        (select pc1.id from core.parametro_ciudad pc1 where pc1.parametro_id=provincia_.id)

                        and u.compromiso_id not in
                        (select u1.compromiso_id from sc_compromisos.ubicaciones u1 where u1.parametro_id = provincia_.id)


              ) as contador_cantones "
        );*/
    $avances = Compromiso::select(DB::RAW("COUNT(avance)"))
      ->whereColumn('id', 'sc_compromisos.compromisos.id')
      ->whereNotIn('avance', [0]);


    try {
      if ($compromisos)  return $this->consultaCompromisosPorUbicacion($request, $data_post);
      // s3lect columnas iniciales... //FC 1ago23
        $query = $this->retornaSelectCompromisoUbicacion($objRepositorio->validarArregloVacio($provincia_filtro));
      // si quiere el detalle... la bandera serÃ¡ true
      if ($tipo_detalle == "true") $query = $this->retornaSelectCompromisoUbicacionDetalle($query);
      // sigue con la consulta...
      $data = $query
        ->addSelect(['avances' => $avances])
        ->addSelect(['contador_cantones' => $contador_cantones])
        ->addSelect(['total_compromisos' => $total_compromisos])
        ->leftjoin('sc_compromisos.ubicaciones as ubicacion', 'ubicacion.compromiso_id', 'compromisos.id');

      if ($tipo_detalle == "false")  $data = $data->crossjoin('core.parametro_ciudad as provincia_');
      else  $data = $data->leftjoin('core.parametro_ciudad as provincia_', 'provincia_.id', 'ubicacion.parametro_id');

      $data = $data->leftjoin('sc_compromisos.estados_porcentaje as estado_gestion', 'estado_gestion.id', 'compromisos.estado_porcentaje_id')
        ->leftjoin('sc_compromisos.responsables as responsable', 'responsable.compromiso_id', 'compromisos.id')
        ->leftjoin('sc_compromisos.instituciones as institucion', 'institucion.id', 'responsable.institucion_id')
        ->where('responsable.estado', 'ACT')
        ->whereNotNull('codigo');


      if ($compromiso_filtro != 0 && $compromiso_filtro != "0")   $data = $data->whereIn('compromisos.id', explode(",", $compromiso_filtro));
      if ($objRepositorio->validarArregloVacio($gestion_filtro)) $data = $data->whereIn('estado_gestion.id', $gestion_filtro);
      if ($objRepositorio->validarArregloVacio($provincia_filtro)) $data = $data->whereIn('provincia_.parametro_id', $provincia_filtro); //FCago23 $data->whereIn('provincia_.id', $provincia_filtro)
      if ($objRepositorio->validarArregloVacio($institucion_filtro)) $data = $data->whereIn('institucion.id', $institucion_filtro);
      if ($objRepositorio->validarArregloVacio($gabinete_filtro))  $data = $data->leftjoin('sc_compromisos.instituciones as gabinete', 'gabinete.id', 'institucion.institucion_id')->whereIn('gabinete.id', $gabinete_filtro);

      // sql de la parte superior...
      if (!$objRepositorio->validarArregloVacio($provincia_filtro)){
        $data = $data->where('provincia_.nivel', 3);
      }
      $data = $data->where('provincia_.descripcion', '<>', 'EXTERIOR');
      $data = $data->whereBetween('compromisos.fecha_inicio', [$request->fecha_inicio, $request->fecha_fin]);

      // sql agrupacion inicial por ubicacion...
      $data = $this->retornaAgrupacion($data,$objRepositorio->validarArregloVacio($provincia_filtro));
      // group by por compromisos por ubicacion detallado...
      if ($tipo_detalle == "true") $data = $this->retornaAgrupacionDetalle($data);
      // continua con el query...
      //dd($tipo_detalle,"AQUI data->",$data->toSql());
      $data = $data->orderBy('provincia_.descripcion', 'asc')->get();
      if ($tipo_detalle == "true") return $data;
      // retorna la coleccion datatable...
      if (!$data_post) {
        return (new CollectionDataTable($data))
          ->addIndexColumn()
          ->rawColumns([''])  
          ->toJson();
      } else
        return $data;
    } catch (\Throwable $th) {
      //throw $th;
      return  $th;
    }
  }
  public function consultaDatosUbicacionDashboard(Request $request)
  {
    $array['status'] = 200;
    $array['mapa'] = $this->cargarDatosDashboard($request, "true", false);
    $array['datatable'] = $this->cargarDatosDashboard($request, "false", false);
    $array['compromisos'] = $this->cargarDatosDashboard($request, "false", true);
    return response()->json($array, 200);
  }
  //${filtro_gestion}/${filtro_compromiso}/${filtro_ubicacion}/${filtro_gabinete}/${filtro_institucion}/${tipo_detalle}
  public function getDatatableReporteCompromisoIndividualServerSide($gestion_filtro, $compromiso_filtro, $provincia_filtro, $gabinete_filtro, $institucion_filtro, $tipo_detalle = false)
  {
    try {
      //code...
      /*$contador_cantones = DB::connection('pgsql_compromisos')
                ->table('core.parametro_ciudad as ciudad')
                ->select(DB::RAW("COUNT(ciudad.id)"))
                ->whereColumn('ciudad.parametro_id', 'provincia_.id')
		->where('nivel', 4);*/
      $contador_cantones = Ubicacion::raw(
        "(select count(distinct c.id) from sc_compromisos.ubicaciones as u, sc_compromisos.compromisos c where
                  c.estado='ACT' and c.codigo is not null and u.compromiso_id=c.id and u.parametro_id in (select pc1.id from core.parametro_ciudad pc1 where pc1.parametro_id=provincia_.id)
                  and u.compromiso_id not in (select u1.compromiso_id from sc_compromisos.ubicaciones u1 where u1.parametro_id = provincia_.id)) as contador_cantones "
      );
      $avances = Compromiso::select(DB::RAW("COUNT(avance)"))
        ->whereColumn('id', 'sc_compromisos.compromisos.id')
        ->whereNotIn('avance', [0]);
      // s3lect columnas iniciales...
      $query = $this->retornaSelectCompromisoUbicacion();
      // si quiere el detalle... la bandera serÃ¡ true
      if ($tipo_detalle == "true") $query = $this->retornaSelectCompromisoUbicacionDetalle($query);
      // sigue con la consulta...
      $data = $query
        ->addSelect(['avances' => $avances])
        ->addSelect(['contador_cantones' => $contador_cantones])
        ->leftjoin('sc_compromisos.ubicaciones as ubicacion', 'ubicacion.compromiso_id', 'compromisos.id');
      if ($tipo_detalle == "false")
        $data = $data->crossjoin('core.parametro_ciudad as provincia_');
      else
        $data = $data->leftjoin('core.parametro_ciudad as provincia_', 'provincia_.id', 'ubicacion.parametro_id');

      $data = $data->leftjoin('sc_compromisos.estados_porcentaje as estado_gestion', 'estado_gestion.id', 'compromisos.estado_porcentaje_id')
        ->leftjoin('sc_compromisos.responsables as responsable', 'responsable.compromiso_id', 'compromisos.id')
        ->leftjoin('sc_compromisos.instituciones as institucion', 'institucion.id', 'responsable.institucion_id')
        ->where('compromisos.estado', 'ACT')->whereNotNull('codigo');
      if ($gestion_filtro != 0 && $gestion_filtro != "0") {
        $data = $data->where('estado_gestion.id', $gestion_filtro);
      }
      //   dd($provincia_filtro,$compromiso_filtro,$gestion_filtro);
      if ($provincia_filtro != 0 && $provincia_filtro != "0") {
        $data = $data->where('provincia_.id', $provincia_filtro);
      }
      if ($compromiso_filtro != 0 && $compromiso_filtro != "0") {
        $arregloCompromisos = explode(",", $compromiso_filtro);
        $data = $data->whereIn('compromisos.id', $arregloCompromisos);
      }
      if ($institucion_filtro != 0 && $institucion_filtro != "0") {
        $data = $data->where('institucion.id', $institucion_filtro);
      }
      if ($gabinete_filtro != 0 && $gabinete_filtro != "0") {
        $data = $data->leftjoin('sc_compromisos.instituciones as gabinete', 'gabinete.id', 'institucion.institucion_id')
          ->where('gabinete.id', $gabinete_filtro);
      }
      // sql de la parte superior...
      $data = $data->where('provincia_.nivel', 3)->where('provincia_.id', '<>', 1074);
      // sql agrupacion inicial por ubicacion...
      $data = $this->retornaAgrupacion($data);
      // group by por compromisos por ubicacion detallado...
      if ($tipo_detalle == "true") $data = $this->retornaAgrupacionDetalle($data);
      // continua con el query...
      $data = $data->orderBy('provincia_.descripcion', 'asc')
        ->get();
      // dd($data->toArray());
      //$data = $data->get();
      if ($tipo_detalle == "true") return $data;
      // retorna la coleccion datatable...
      return (new CollectionDataTable($data))
        ->addIndexColumn()
        ->addColumn('fecha_creacion', function ($row) {
          return $row->created_at;
        })
        ->addColumn('registro_', function ($row) {
          return $row->reg_;
        })
        ->rawColumns(['', 'fecha_creacion', 'registro_'])
        ->toJson();
    } catch (\Throwable $th) {
      //throw $th;
      throw $th;
    }
  }
  protected function generacionDataGraficoEstado($request)
  {
    $objRepositorio = new RepositorioController();
    $data = Compromiso::select(
      'estados_porcentaje.descripcion as estado_gestion',
      DB::RAW('COUNT(compromisos.id) as contador')
    )
      ->leftjoin('sc_compromisos.estados_porcentaje', 'estados_porcentaje.id', 'compromisos.estado_porcentaje_id')
      ->whereNotNull('codigo');

    //ERROR EN LINEA 1394
    if ($objRepositorio->validarArregloVacio($request->filtro_ubicacion)) {
      $obj = new \stdClass();
      $obj->provincia_id_exportar_monitor = $request->filtro_ubicacion;
      $obj->canton_id_exportar_monitor = [];
      $obj->parroquia_id_exportar_monitor = [];
      $data = (new CompromisosController())->filtrosProvinciasCantones($data, $obj);

      /*       $data = $data->leftjoin('sc_compromisos.ubicaciones', 'ubicaciones.compromiso_id', 'compromisos.id')
                ->leftjoin('core.parametro_ciudad', 'parametro_ciudad.id', 'ubicaciones.parametro_id')
                ->where('core.parametro_ciudad.nivel', 3)
                ->whereIn('core.parametro_ciudad.id', $request->ubicacion_filtro);*/
    }
    if ($objRepositorio->validarArregloVacio($request->filtro_gestion)) $data = $data->whereIn('estados_porcentaje.id', $request->filtro_gestion);

    if ($objRepositorio->validarArregloVacio($request->filtro_institucion)) {
      $data = $data->leftjoin('sc_compromisos.responsables as responsable', 'responsable.compromiso_id', 'compromisos.id')
        ->leftjoin('sc_compromisos.instituciones as institucion', 'institucion.id', 'responsable.institucion_id')
        ->whereIn('institucion.id', $request->filtro_institucion);
    }
    if ($objRepositorio->validarArregloVacio($request->filtro_gabinete)) {
      $data = $data->leftjoin('sc_compromisos.instituciones as gabinete', 'gabinete.id', 'institucion.institucion_id')
        ->whereIn('gabinete.id', $request->filtro_gabinete);
    }
    $data = $data->where('compromisos.estado', 'ACT')
      ->groupBy('estado_gestion')
      ->orderby('contador', 'desc')->get()->toArray();
    return $data;
  }
  //MAPA CALOR2 TODOS LOS COMPROMISOS
  public function consultaMostrarGraficoEstado(request $request)
  {

    $data = $this->generacionDataGraficoEstado($request);
    $array_response['status'] = 200;
    $array_response['datos'] = $data;

    return response()->json($array_response, 200);
  }

  protected function cargarTablaCompromisos($compromisos)
  {
    $html_compromisos = '<div class="Table" style="display: table;width: 90%!important;">';
    $html_compromisos .= '        <div class="Heading">';
    $html_compromisos .= '            <div class="Cell">';
    $html_compromisos .= '                <p>Reg</p>';
    $html_compromisos .= '            </div>';
    $html_compromisos .= '            <div class="Cell" >';
    $html_compromisos .= '                <p>Nombre del Compromiso</p>';
    $html_compromisos .= '            </div>';
    $html_compromisos .= '            <div class="Cell">';
    $html_compromisos .= '            <p>  Institución</p> </div>';
    $html_compromisos .= '            <div class="Cell">';
    $html_compromisos .= '            <p>  Fecha de Inicio</p> </div>';
    $html_compromisos .= '            <div class="Cell">';
    $html_compromisos .= '            <p>  Provincia</p> </div>';
    $html_compromisos .= '            <div class="Cell">';
    $html_compromisos .= '            <p>  Cantón</p> </div>';
    $html_compromisos .= '            <div class="Cell">';
    $html_compromisos .= '            <p>  Fecha Avance </p> </div>';
    $html_compromisos .= '            <div class="Cell">';
    $html_compromisos .= '            <p>  Ultimo Avance </p> </div>';
    $html_compromisos .= '            <div class="Cell">';
    $html_compromisos .= '            <p> Estado de Gestión</p> </div>';
    $html_compromisos .= '            <div class="Cell">';
    $html_compromisos .= '            <p> Estado de Compromiso</p> </div>';
    $html_compromisos .= '        </div>';
    $html_compromisos .= '';
    if (count($compromisos) == 0) {
      $html_compromisos .= ' <div class="Row">';
      $html_compromisos .= '     <div class="Cell" style="border-right:0px;"><p></p></div>';
      $html_compromisos .= '     <div class="Cell" style="border-left:0px;border-right:0px;"><p></p></div>';
      $html_compromisos .= '     <div class="Cell" style="border-left:0px;border-right:0px;"><p></p></div>';
      $html_compromisos .= '     <div class="Cell" style="border-left:0px;border-right:0px;"><p></p></div>';
      $html_compromisos .= '     <div class="Cell" style="border-left:0px;border-right:0px;"><p>Información No Encontrada</p></div>';
      $html_compromisos .= '     <div class="Cell" style="border-left:0px;border-right:0px;"><p></p></div>';
      $html_compromisos .= '     <div class="Cell"  style="border-left:0px;border-right:0px;"><p></p></div>';
      $html_compromisos .= '     <div class="Cell" style="border-left:0px;border-right:0px;"><p></p></div>';
      $html_compromisos .= '     <div class="Cell" style="border-left:0px;border-right:0px;"><p></p></div>';
      $html_compromisos .= '     <div class="Cell" style="border-left:0px;"><p></p></div>';
      $html_compromisos .= ' </div>';
    }
    foreach ($compromisos as $value) {
      $html_compromisos .= ' <div class="Row">';
      $html_compromisos .= '     <div class="Cell"><p style="font-size:8px!important">' . $value->reg_ . '</p></div>';
      $html_compromisos .= '     <div class="Cell"><p style="font-size:8px!important">' . $value->nombre_ . '</p></div>';
      $html_compromisos .= '     <div class="Cell"><p style="font-size:8px!important">' . $value->institucion_ . '</p></div>';
      $html_compromisos .= '     <div class="Cell"><p style="font-size:8px!important">' . $value->fecha_inicio_ . '</p></div>';
      $html_compromisos .= '     <div class="Cell"><p style="font-size:8px!important">' .  str_replace(",", "<br/> ", $value->provincias)  . '</p></div>';
      $html_compromisos .= '     <div class="Cell"><p style="font-size:8px!important">' . str_replace(",", "<br/> ", $value->cantones) . '</p></div>';
      $html_compromisos .= '     <div class="Cell"><p style="font-size:8px!important">' . $value->fecha_revisa . '</p></div>';
      $html_compromisos .= '     <div class="Cell"><p style="font-size:8px!important">' . $value->ultimo_avance_aprobado . '</p></div>';
      $html_compromisos .= '     <div class="Cell"><p style="font-size:8px!important">' . $value->estado_porcentaje_ . '</p></div>';
      $html_compromisos .= '     <div class="Cell"><p style="font-size:8px!important">' . $value->estado_ . '</p></div>';
      $html_compromisos .= ' </div>';
    }
    $html_compromisos .= ' </div>';

    return $html_compromisos;
  }
  /*  protected function cargarTablaCompromisos($compromisos)
    {
        $html_compromisos = '<table class="tablepe" >';
        $html_compromisos .= '<tbody>';
        $html_compromisos .= ' <tr>';
        $html_compromisos .= '     <td class="td_actividad td_cabecera">';
        $html_compromisos .= '         <h3>Compromisos</h3>';
        $html_compromisos .= '     </td>';
        $html_compromisos .= '';
        $html_compromisos .= ' </tr>';
        $html_compromisos .= '     <tr>';
        $html_compromisos .= '         <td class="td_actividad">';
        $html_compromisos .= '             <table class="border" width="100%" cellspacing="0" cellpadding="0" border="0">';
        $html_compromisos .= '                 <tr>';
        $html_compromisos .= '                     <th class="td_actividad center th" style="border-top:0px" width="10%">';
        $html_compromisos .= '                         <h3>Reg</h3>';
        $html_compromisos .= '                     </th>';
        $html_compromisos .= '                     <th class="td_actividad center th"style="border-top:0px" width="10%">';
        $html_compromisos .= '                         <h3>Nombre del Compromiso</h3>';
        $html_compromisos .= '                     </th>';
        $html_compromisos .= '                     <th class="td_actividad center th"style="border-top:0px" width="10%">';
        $html_compromisos .= '                         <h3>Institución</h3>';
        $html_compromisos .= '                     </th>';
        $html_compromisos .= '                     <th class="td_actividad center th"style="border-top:0px" width="10%">';
        $html_compromisos .= '                         <h3>Fecha de Inicio</h3>';
        $html_compromisos .= '                     </th>';
        $html_compromisos .= '                     <th class="td_actividad center th"style="border-top:0px" width="10%">';
        $html_compromisos .= '                         <h3>Provincia</h3>';
        $html_compromisos .= '                     </th>';
        $html_compromisos .= '                     <th class="td_actividad center th"style="border-top:0px" width="10%">';
        $html_compromisos .= '                         <h3>Cantón</h3>';
        $html_compromisos .= '                     </th>';
        $html_compromisos .= '                     <th class="td_actividad center th"style="border-top:0px" width="10%">';
        $html_compromisos .= '                         <h3>Fecha Avance Aprobado</h3>';
        $html_compromisos .= '                     </th>';
        $html_compromisos .= '                     <th class="td_actividad center th"style="border-top:0px" width="10%">';
        $html_compromisos .= '                         <h3>Ultimo Avance Aprobado</h3>';
        $html_compromisos .= '                     </th>';
        $html_compromisos .= '                     <th class="td_actividad center th"style="border-top:0px" width="10%">';
        $html_compromisos .= '                         <h3>Estado de Gestión</h3>';
        $html_compromisos .= '                     </th>';
        $html_compromisos .= '                     <th class="td_actividad center th"style="border-top:0px" width="10%">';
        $html_compromisos .= '                         <h3>Estado del Compromiso</h3>';
        $html_compromisos .= '                     </th>';
        $html_compromisos .= '                 </tr>';
        foreach ($compromisos as $value) {
            $html_compromisos .= '                     <tr>';
            $html_compromisos .= '                         <td class="td_actividad" style="text-align: justify">' . $value->reg_ . '</td>';
            $html_compromisos .= '                         <td class="td_actividad">' . $value->nombre_ . '</td>';
            $html_compromisos .= '                         <td class="td_actividad">' . $value->institucion_ . '</td>';
            $html_compromisos .= '                         <td class="td_actividad">' . $value->fecha_inicio_ . '</td>';
            $html_compromisos .= '                         <td class="td_actividad">' . str_replace(",", "<br/> ", $value->provincias) . '</td>';
            $html_compromisos .= '                         <td class="td_actividad">' . str_replace(",", "<br/> ", $value->cantones) . '</td>';
            $html_compromisos .= '                         <td class="td_actividad">' . $value->fecha_revisa . '</td>';
            $html_compromisos .= '                         <td class="td_actividad">' . $value->ultimo_avance_aprobado . '</td>';
            $html_compromisos .= '                         <td class="td_actividad">' . $value->estado_porcentaje_ . '</td>';
            $html_compromisos .= '                         <td class="td_actividad">' . $value->estado_ . '</td>';
            $html_compromisos .= '                     </tr>';
        }

        $html_compromisos .= '             </table>';
        $html_compromisos .= '         </td>';
        $html_compromisos .= '     </tr>';
        $html_compromisos .= ' </tbody>';
        $html_compromisos .= '</table>';
        return $html_compromisos;
    }*/
  protected function grafico_estados($request)
  {
    $data = $this->generacionDataGraficoEstado($request);
    $grafico_estados = '<div id="principal">';
    $inicial = 0;
    foreach ($data as $key => $value) {
      //  $valor=100;
      $valor = $value['contador'];
      if ($key == 0) {
        $inicial = $valor;
        $porcentaje_px = 20 . '%';
      } else {
        $porcentaje_px = (($valor * 20) / $inicial) . '%';
      }
      $porcentaje = $valor . '%';

      $titulo = $value['estado_gestion'];
      $grafico_estados .= '<div id="barra">';
      $grafico_estados .= '<div class="cor' . $key . '" style="height: ' . $porcentaje_px . '"> ' . $porcentaje . ' </div>';
      $grafico_estados .= '<br/>';
      $grafico_estados .= '<label style="font-size:7px;font-weight:bold">' . $titulo . '<label>';
      $grafico_estados .= '</div>';
    }
    $grafico_estados .= '</div>';

    /*<?php
        // definindo porcentagem
        $height1 = '28%';
        $height2 = '49%';
        $height3 = '33%';
        $height4 = '13%';
        $total  = 4; // total de barras
        ?>

            <p>Porcentagem de Acessos</p>
            <?php
            for($i=1;$i <= $total;$i++)
            {
                $height = $height2;
                ?>
                <div id="barra">
                    <div class="cor<?= $i ?>" style="height:<?= $height ?>"> <?= $height ?> </div>
                </div>
            <?php } ?>
        </div>*/
    return $grafico_estados;
  }
  protected function variablesPdf($request)
  {
    $objSelect = new SelectController();

    $img_encabezado = Grafico::select('imagen')->where('tipo', 'CABECERA DOCUMENTO')->where('eliminado', false)->first()->imagen;
    $img_pie = Grafico::select('imagen')->where('tipo', 'PIE DOCUMENTO ALTERNO')->where('eliminado', false)->first()->imagen;
    $array['mapa'] = $this->cargarDatosDashboard($request, "true", false, true);
    $array['datatable'] = $this->cargarDatosDashboard($request, "false", false, true);
    $array['compromisos'] = $this->cargarDatosDashboard($request, "false", true, true);
    $html_compromisos = $this->cargarTablaCompromisos($array['compromisos']);
    $array_contadores['provincia'] = ($array['datatable']->sum('contador')) - $array['datatable']->sum('contador_nacional');
    $array_contadores['canton'] =  $array['datatable']->sum('contador_cantones');
    $array_contadores['nacional'] = $array['datatable']->sum('contador_nacional');
    $array_contadores['total'] = $array_contadores['nacional'] + $array_contadores['canton'] + $array_contadores['provincia'];
    $grafico_estados = $this->grafico_estados($request);

    /*  $ruta = public_path() . "/images/compromisos_grafico_ambito.png";
    try {
      $ambito =  $objSelect->base64_to_imagen($request->imagen, $ruta);
      echo "resuelto 1";
    } catch (\Exception  $e) {
      echo "1";
    }
    $ruta_imagen = url('/images/compromisos_grafico_ambito.png');
    $grafico_ambito = "<img src=\"$ruta_imagen\" width='300px' heigh='300px'>";*/
    $base_64 = $request->imagen;
    $imagen_mapa_svg = $request->imagen_mapa_svg;
    $grafico_ambito = "<img src=\"$base_64\" width='300px' heigh='300px'>";
    $grafico_mapa = "<img src=\"$imagen_mapa_svg\">";

    return [
      'array_contadores' => $array_contadores,
      'array' => $array,
      'img_encabezado' => $img_encabezado,
      'img_pie' => $img_pie,
      'html_compromisos' => $html_compromisos,
      'grafico_estados' => $grafico_estados,
      'grafico_ambito' => $grafico_ambito,
      'grafico_mapa' => $grafico_mapa
    ];
  }
  public function formatoImprimir()
  {

    $request = new \stdClass();
    $request->fecha_fin = "2023-12-31";
    $request->fecha_inicio = "2021-01-01";
    $request->filtro_compromiso = 0;
    $request->filtro_gabinete = [];
    $request->filtro_institucion = [];
    $request->filtro_gestion = [];
    $request->filtro_ubicacion = [
      0  => "1076",
      1  => "1079",
      2  => "1081"

    ];
    $request->tipo_detalle = true;
    $request->imagen = "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";
    $request->imagen_mapa_svg = "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAASwAAACWCAYAAABkW7XSAAAEYklEQVR4Xu3UAQkAAAwCwdm/9HI83BLIOdw5AgQIRAQWySkmAQIEzmB5AgIEMgIGK1OVoAQIGCw/QIBARsBgZaoSlAABg+UHCBDICBisTFWCEiBgsPwAAQIZAYOVqUpQAgQMlh8gQCAjYLAyVQlKgIDB8gMECGQEDFamKkEJEDBYfoAAgYyAwcpUJSgBAgbLDxAgkBEwWJmqBCVAwGD5AQIEMgIGK1OVoAQIGCw/QIBARsBgZaoSlAABg+UHCBDICBisTFWCEiBgsPwAAQIZAYOVqUpQAgQMlh8gQCAjYLAyVQlKgIDB8gMECGQEDFamKkEJEDBYfoAAgYyAwcpUJSgBAgbLDxAgkBEwWJmqBCVAwGD5AQIEMgIGK1OVoAQIGCw/QIBARsBgZaoSlAABg+UHCBDICBisTFWCEiBgsPwAAQIZAYOVqUpQAgQMlh8gQCAjYLAyVQlKgIDB8gMECGQEDFamKkEJEDBYfoAAgYyAwcpUJSgBAgbLDxAgkBEwWJmqBCVAwGD5AQIEMgIGK1OVoAQIGCw/QIBARsBgZaoSlAABg+UHCBDICBisTFWCEiBgsPwAAQIZAYOVqUpQAgQMlh8gQCAjYLAyVQlKgIDB8gMECGQEDFamKkEJEDBYfoAAgYyAwcpUJSgBAgbLDxAgkBEwWJmqBCVAwGD5AQIEMgIGK1OVoAQIGCw/QIBARsBgZaoSlAABg+UHCBDICBisTFWCEiBgsPwAAQIZAYOVqUpQAgQMlh8gQCAjYLAyVQlKgIDB8gMECGQEDFamKkEJEDBYfoAAgYyAwcpUJSgBAgbLDxAgkBEwWJmqBCVAwGD5AQIEMgIGK1OVoAQIGCw/QIBARsBgZaoSlAABg+UHCBDICBisTFWCEiBgsPwAAQIZAYOVqUpQAgQMlh8gQCAjYLAyVQlKgIDB8gMECGQEDFamKkEJEDBYfoAAgYyAwcpUJSgBAgbLDxAgkBEwWJmqBCVAwGD5AQIEMgIGK1OVoAQIGCw/QIBARsBgZaoSlAABg+UHCBDICBisTFWCEiBgsPwAAQIZAYOVqUpQAgQMlh8gQCAjYLAyVQlKgIDB8gMECGQEDFamKkEJEDBYfoAAgYyAwcpUJSgBAgbLDxAgkBEwWJmqBCVAwGD5AQIEMgIGK1OVoAQIGCw/QIBARsBgZaoSlAABg+UHCBDICBisTFWCEiBgsPwAAQIZAYOVqUpQAgQMlh8gQCAjYLAyVQlKgIDB8gMECGQEDFamKkEJEDBYfoAAgYyAwcpUJSgBAgbLDxAgkBEwWJmqBCVAwGD5AQIEMgIGK1OVoAQIGCw/QIBARsBgZaoSlAABg+UHCBDICBisTFWCEiBgsPwAAQIZAYOVqUpQAgQMlh8gQCAjYLAyVQlKgIDB8gMECGQEDFamKkEJEDBYfoAAgYyAwcpUJSgBAgbLDxAgkBEwWJmqBCVAwGD5AQIEMgIGK1OVoAQIGCw/QIBARsBgZaoSlACBB1YxAJfjJb2jAAAAAElFTkSuQmCC";
    $nombrePdf = Auth::user()->id . '.pdf';
    $pdf = $this->variablesPdf($request);
    // $pdf = PDF::loadView('modules.compromisos.reporte.formatoImprimir', $pdf);
    // return $pdf->stream($nombrePdf);
    return view('modules.compromisos.controlPanel.formatoImprimir', $pdf);
  }
  public function generarPdf(Request $request)
  {
    $pdf = $this->variablesPdf($request);
    $pdf = PDF::loadView('modules.compromisos.controlPanel.formatoImprimir', $pdf);
    $nombrePdf = Auth::user()->id . '.pdf';
    $pdf->stream($nombrePdf);
    $storage_path_local = storage_path('app');
    $ruta = '/COMPROMISOS/EXPORTS/' . $nombrePdf;
    $fichero = $storage_path_local . $ruta;
    file_put_contents($fichero, $pdf->output());

    $array_response['status'] = 200;
    $array_response['message'] = $ruta;
    return response()->json($array_response, 200);
  }

  //REPORTES CALENDARIO REPORTES
  public function exportarExcelCalendarioReportes(request $request)
  {
    $objSelect = new SelectController();

    $fecha = str_replace('.', '-', $request->mes); //remplazo de . por - en fecha enviada por calendario
    $mes = substr(strrchr($request->mes, "."), 1); //recupero el el mes en numero
    $año = strstr($request->mes, '.', true); //recupero el año en numeros
    $fecha_inicio = date($fecha . '-01'); //fecha enviada por calendario concateno con el primer día
    $dias = cal_days_in_month(CAL_GREGORIAN, $mes, $año); //devolución del numero de dñias que tiene el mes del año para la fecha especifica
    $fecha_fin = date($fecha . -$dias);  //mes completo
    $mesLetras = $objSelect->saber_mes($fecha_inicio);

    $objSelect = new RepositorioController();
    $url = 'storage/FORMATOS_COMPROMISOS/CALENDARIO_REPORTES.xlsx';
    $reader = IOFactory::createReader("Xlsx");
    $spread = $reader->load($url);
    $spread->setActiveSheetIndex(0);
    $sheet = $spread->getActiveSheet();
    $writer = IOFactory::createWriter($spread, 'Xlsx');
    $this->imagen_institucional  = $this->cargarImagenInstitucional();
    $imagen = $this->cargarImagenExcel();
    $imagen->setWorksheet($sheet);
    $data = $objSelect->selectConsultaCompromisos();
    if ($request->tipo == '_f')
      $data = $data->whereBetween('compromisos.fecha_fin', [$fecha_inicio, $fecha_fin])->get();
    else
      $data = $data->whereBetween('compromisos.fecha_reporte', [$fecha_inicio, $fecha_fin])->get();

    $sheet->setCellValue("A3", $mesLetras . ' - ' . $año);
    $sheet->setCellValue("B4", date('Y-m-d'));
    $this->dimension_style_logo = $sheet->getRowDimension(1);
    $this->dimension_style_cabecera_1 = $sheet->getRowDimension(2);
    $this->dimension_style_cabecera_2 = $sheet->getRowDimension(6);

    foreach ($data as $key => $compromiso) {
      $datos = $this->consultarArregloDatosCompromisos($compromiso);
      $fila_ = ($this->inicializadorContenido + 1) + $key;
      foreach ($this->abecedario as $key => $letra) {
        if (count($datos) - 1 < $key)  break;
        if ($key == 0) $letra_inicial = $letra;
        $letra_final = $letra;
        $sheet->setCellValue($letra . $fila_, $datos[$key]);
      }
      $sheet->getStyle($letra_inicial . $fila_ . ':' . $letra_final . $fila_)->applyFromArray($this->styleCompromisos);
      $sheet->getRowDimension($fila_)->setRowHeight($this->dimension_style_cabecera_2->getRowHeight());
    }

    $documento_generado = "CALENDARIO_" . $request->tipo . "_COMPROMISOS_" . date('Y-m-d') . ".xlsx";
    $writer->save("storage/COMPROMISOS_GENERADOS/" . $documento_generado);

    $array_response['status'] = 200;
    $array_response['documento_nombre'] = $documento_generado;

    return response()->json($array_response, 200);
  }
  protected function consultarArregloDatosCompromisos($compromiso)
  {
    $data[0] = $compromiso['reg_'];
    $data[1] = $compromiso['institucion_'];
    $data[2] = $compromiso['nombre_'];
    $data[3] = $compromiso['provincias'];
    $data[4] = $compromiso['cantones'];
    $data[5] = $compromiso['fecha_inicio_'];
    $data[6] = $compromiso['fecha_fin_'];
    $data[7] = $compromiso['fecha_reporte'];
    $data[8] = $compromiso['ultimo_avance_aprobado'];
    $data[9] = $compromiso['fecha_revisa'];
    return $data;
  }
}