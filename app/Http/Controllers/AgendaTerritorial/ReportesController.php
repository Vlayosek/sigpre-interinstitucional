<?php

namespace App\Http\Controllers\AgendaTerritorial;

use App\Http\Controllers\Controller;
use App\Exports\CompromisosExport;
use Maatwebsite\Excel\Facades\Excel;

class ReportesController extends Controller
{
    public function ExportarExcelGET($inicio, $fin, $tipo, $tabla, $asignaciones, $temporales, $pendientes, $filtro, $institucion_id, $gabinete_id)
    {
        ob_end_clean();
        return Excel::download(new CompromisosExport($inicio, $fin, $tipo, $tabla, $asignaciones, $temporales, $pendientes, $filtro, $institucion_id, $gabinete_id), 'compromisos_' . $inicio . '_' . $fin . '_' . 'SIGPRE.xlsx');
    }
}
