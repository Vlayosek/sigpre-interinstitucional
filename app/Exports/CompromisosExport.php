<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use App\Http\Controllers\Compromisos\CompromisosController;
use App\Http\Controllers\Compromisos\RepositorioController;
use stdClass;

class CompromisosExport implements FromCollection
{
    public $fecha_inicio;
    public $fecha_fin;
    public $estado;
    public $tabla;
    public $asignaciones;
    public $temporales;
    public $pendientes;
    public $filtro;
    public $institucion_id_exportar;
    public $gabinete_id_exportar;

    public function __construct(string $fecha_inicio, string $fecha_fin, string $tipo, string $tabla, string $asignaciones, string $temporales, string $pendientes, string $filtro, string $institucion_id_exportar, string $gabinete_id_exportar)
    {
        $this->fecha_inicio = $fecha_inicio;
        $this->fecha_fin = $fecha_fin;
        $this->estado = $tipo;
        $this->tabla = $tabla;
        $this->asignaciones = $asignaciones;
        $this->temporales = $temporales;
        $this->pendientes = $pendientes;
        $this->filtro = $filtro;
        $this->institucion_id_exportar = $institucion_id_exportar;
        $this->gabinete_id_exportar = $gabinete_id_exportar;
    }

    public function collection()
    {

        $estado = $this->tipo;
        $tabla = $this->tabla;
        $asignaciones = $this->asignaciones;
        $temporales = $this->temporales;
        $pendientes = $this->pendientes;
        $filtro = $this->filtro;
        $institucion_id = $this->institucion_id;
        $gabinete_id = $this->gabinete_id;

        $data = Compromiso::select(
            'compromisos.codigo as reg_',
            'compromisos.fecha_inicio as fecha_inicio_',
            'compromisos.fecha_fin as fecha_fin_',
            'compromisos.nombre_compromiso as nombre_',
            'institucion.descripcion as institucion_',
            'gabinete.descripcion as gabinete_',
            'tipo.descripcion as tipo_',
            'estado.descripcion as estado_',
            'estado_porcentaje.descripcion as estado_porcentaje_',
            DB::RAW("CASE WHEN estado_porcentaje.descripcion ='CUMPLIDO' OR estado_porcentaje.descripcion ='CERRADO' THEN compromisos.updated_at::varchar(10) ELSE '--' END as fecha_cumplido"),
            'compromisos.avance_compromiso as avance_compromiso_',
            'antecedente.fecha_antecedente',
            'antecedente.descripcion'
        )
            ->leftjoin('sc_compromisos.responsables as r', 'r.compromiso_id', 'compromisos.id')
            ->leftjoin('sc_compromisos.instituciones as institucion', 'institucion.id', 'r.institucion_id')
            ->leftjoin('sc_compromisos.instituciones as gabinete', 'gabinete.id', 'institucion.institucion_id')
            ->leftjoin('sc_compromisos.tipos_compromiso as tipo', 'tipo.id', 'compromisos.tipo_compromiso_id')
            ->leftjoin('sc_compromisos.estados as estado', 'estado.id', 'compromisos.estado_id')
            ->leftjoin('sc_compromisos.estados_porcentaje as estado_porcentaje', 'estado_porcentaje.id', 'compromisos.estado_porcentaje_id')
            ->leftJoin('sc_compromisos.antecedentes as antecedente', function($leftJoin)
            {
                $leftJoin->on('antecedente.compromiso_id', '=', 'compromisos.id');
                $leftJoin->where('antecedente.estado','ACT');
            })
            ->addSelect(
                [
                    'corresponsables' =>
                    Corresponsable::select(DB::RAW("array_to_string(ARRAY_AGG(i.descripcion),',')"))
                        ->whereColumn('compromisos.id', 'corresponsables.compromiso_id')
                        ->join('sc_compromisos.instituciones as i', 'i.id', 'corresponsables.institucion_corresponsable_id')
                ]
            )
            ->where('compromisos.estado', 'ACT')
            ->whereDate('compromisos.fecha_inicio', '>=', $this->inicio)
            ->whereDate('compromisos.fecha_inicio', '<=', $this->fin)
            ->where('r.estado', 'ACT');

        if ($estado != "data") {
            if ($tabla == "1")
                $data = $data->where('compromisos.estado_id', Estado::where('abv', $estado)->get()->first()->id);
            else
                $data = $data->where('compromisos.estado_porcentaje_id', EstadoPorcentaje::where('abv', $estado)->get()->first()->id);
        }

        if (Auth::user()->evaluarole(['MINISTRO'])) {
            $cqlCompromisosResponsables = Responsable::select('compromiso_id')
            ->where('institucion_id', $this->consultaInstitucionporMinistro(Auth::user()->id))
            ->where('estado', 'ACT')
            ->pluck('compromiso_id')->toArray();

            $data = $data->whereNotNull('compromisos.codigo');
            $data = $data->whereIn('compromisos.id', $cqlCompromisosResponsables);
        } else {
            if ($asignaciones == "true")
                $data = $data->where('compromisos.monitor_id', Auth::user()->id);
            if ($temporales == "true")
                $data = $data->whereNull('compromisos.codigo');
            else
                $data = $data->whereNotNull('compromisos.codigo');

            if ($pendientes == "true")
                $data = $data->where('compromisos.pendientes', '>', 0);
        }
        //if($filtro!="false"){ //INFPRUEBAS2
        if ($institucion_id != null && $institucion_id != "null") {
            $cqlCompromisosResponsables = Responsable::select('compromiso_id')
                ->where('institucion_id', $institucion_id)
                ->where('estado', 'ACT')
                ->pluck('compromiso_id')->toArray();

            $data = $data->whereIn('compromisos.id', $cqlCompromisosResponsables);
        }
        if ($gabinete_id != null && $gabinete_id != "null") {
            $cqlInsituciones = Institucion::select('id')->where('institucion_id', $gabinete_id)->pluck('id')->toArray();

            $cqlCompromisosResponsables = Responsable::select('compromiso_id')
                ->whereIn('institucion_id', $cqlInsituciones)
                ->where('estado', 'ACT')
                ->pluck('compromiso_id')->toArray();

            $data = $data->whereIn('compromisos.id', $cqlCompromisosResponsables);
        }
        //}
        $data = $data->orderby('compromisos.id', 'desc')
            ->cursor()->collect();
        $cabecera = collect([[
            'Código',
            'Fecha Inicio',
            'Fecha Fin',
            'Nombre',
            'Responsable',
            'Gabinete',
            'Tipo',
            'Estado de Gestión',
            'Estado del Compromiso',
            'Fecha Cumplido',
            'Ultimo Avance Realizado',
            'Fecha Antecedente',
            'Descripción Antecedente',
            'Corresponsables',
        ]]);
        $datas = $cabecera->concat($data);
        return $datas;
    }
    protected function consultaUbicacionesDatatable()
    {
        return (new RepositorioController())->consultaUbicacionesDatatable();
    }

}
