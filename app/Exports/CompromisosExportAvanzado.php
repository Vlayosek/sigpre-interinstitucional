<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use App\Http\Controllers\Compromisos\CompromisosController;
use App\Http\Controllers\Compromisos\RepositorioController;
use stdClass;
use DB;

class CompromisosExportAvanzado implements FromCollection
{
    public $fecha_inicio_exportar_monitor;
    public $fecha_fin_exportar_monitor;
    public $estado;
    public $tabla;
    public $asignaciones;
    public $temporales;
    public $pendientes;
    public $filtro;
    public $institucion_id_exportar_monitor;
    public $gabinete_id_exportar_monitor;
    public $gabinete_id_corresponsable_exportar_monitor;
    public $institucion_id_corresponsable_exportar_monitor;
    public $fecha_inicio_fin_exportar_monitor;
    public $fecha_fin_fin_exportar_monitor;
    public $tipo_id_exportar_monitor;
    public $estado_id_exportar_monitor;
    public $estado_porcentaje_id_exportar_monitor;
    public $descripcion_antecedente_exportar_monitor;
    public $fecha_inicio_antecedente_exportar_monitor;
    public $fecha_fin_antecedente_exportar_monitor;
    public $fecha_inicio_cuumplido_exportar_monitor;
    public $fecha_fin_cumplido_exportar_monitor;
    public $provincia_id_exportar_monitor;
    public $canton_id_exportar_monitor;
    public $parroquia_id_exportar_monitor;
    public $nombre_compromiso_exportar_monitor;
    public $codigo_compromiso_exportar_monitor;
    public $fecha_inicio_avance_exportar_monitor;
    public $fecha_fin_avance_exportar_monitor;
    public $habilitarFechaInicio;
    public $habilitarFechaFin;
    public $habilitarFechaCumplido;
    public $habilitarFechaAntecedente;
    public $habilitarFechaUltimoAvance;
    public $corresponsable = false;
    public $descripcion_avance_exportar_monitor;
    public $monitor_id_exportar_monitor;
    public function __construct(
        string $fecha_inicio_exportar_monitor,
        string $fecha_fin_exportar_monitor,
        string $tipo,
        string $tabla,
        string $asignaciones,
        string $temporales,
        string $pendientes,
        string $filtro,
        string $institucion_id_exportar_monitor,
        string $gabinete_id_exportar_monitor,
        string $gabinete_id_corresponsable_exportar_monitor,
        string $institucion_id_corresponsable_exportar_monitor,
        string $fecha_inicio_fin_exportar_monitor,
        string $fecha_fin_fin_exportar_monitor,
        string $tipo_id_exportar_monitor,
        string $estado_id_exportar_monitor,
        string $estado_porcentaje_id_exportar_monitor,
        string $descripcion_antecedente_exportar_monitor,
        string $fecha_inicio_antecedente_exportar_monitor,
        string $fecha_fin_antecedente_exportar_monitor,
        string $fecha_inicio_cuumplido_exportar_monitor,
        string $fecha_fin_cumplido_exportar_monitor,
        string $provincia_id_exportar_monitor,
        string $canton_id_exportar_monitor,
        string $parroquia_id_exportar_monitor,
        string $nombre_compromiso_exportar_monitor,
        string $codigo_compromiso_exportar_monitor,
        string $fecha_inicio_avance_exportar_monitor,
        string $fecha_fin_avance_exportar_monitor,
        string $habilitarFechaInicio,
        string $habilitarFechaFin,
        string $habilitarFechaCumplido,
        string $habilitarFechaAntecedente,
        string $habilitarFechaUltimoAvance,
        string $descripcion_avance_exportar_monitor,
        string $monitor_id_exportar_monitor,

    ) {
        $this->fecha_inicio_exportar_monitor = $fecha_inicio_exportar_monitor;
        $this->fecha_fin_exportar_monitor = $fecha_fin_exportar_monitor;
        $this->estado = $tipo;
        $this->tabla = $tabla;
        $this->asignaciones = $asignaciones;
        $this->temporales = $temporales;
        $this->pendientes = $pendientes;
        $this->filtro = $filtro;
        $this->institucion_id_exportar_monitor = $institucion_id_exportar_monitor;
        $this->gabinete_id_exportar_monitor = $gabinete_id_exportar_monitor;
        $this->gabinete_id_corresponsable_exportar_monitor = $gabinete_id_corresponsable_exportar_monitor;
        $this->institucion_id_corresponsable_exportar_monitor = $institucion_id_corresponsable_exportar_monitor;
        $this->fecha_inicio_fin_exportar_monitor = $fecha_inicio_fin_exportar_monitor;
        $this->fecha_fin_fin_exportar_monitor = $fecha_fin_fin_exportar_monitor;
        $this->tipo_id_exportar_monitor = $tipo_id_exportar_monitor;
        $this->estado_id_exportar_monitor = $estado_id_exportar_monitor;
        $this->estado_porcentaje_id_exportar_monitor = $estado_porcentaje_id_exportar_monitor;
        $this->descripcion_antecedente_exportar_monitor = $descripcion_antecedente_exportar_monitor;
        $this->fecha_inicio_antecedente_exportar_monitor = $fecha_inicio_antecedente_exportar_monitor;
        $this->fecha_fin_antecedente_exportar_monitor = $fecha_fin_antecedente_exportar_monitor;
        $this->fecha_inicio_cuumplido_exportar_monitor = $fecha_inicio_cuumplido_exportar_monitor;
        $this->fecha_fin_cumplido_exportar_monitor = $fecha_fin_cumplido_exportar_monitor;
        $this->provincia_id_exportar_monitor = $provincia_id_exportar_monitor;
        $this->canton_id_exportar_monitor = $canton_id_exportar_monitor;
        $this->parroquia_id_exportar_monitor = $parroquia_id_exportar_monitor;
        $this->nombre_compromiso_exportar_monitor = $nombre_compromiso_exportar_monitor;
        $this->codigo_compromiso_exportar_monitor = $codigo_compromiso_exportar_monitor;
        $this->fecha_inicio_avance_exportar_monitor = $fecha_inicio_avance_exportar_monitor;
        $this->fecha_fin_avance_exportar_monitor = $fecha_fin_avance_exportar_monitor;
        $this->habilitarFechaInicio = $habilitarFechaInicio;
        $this->habilitarFechaFin = $habilitarFechaFin;
        $this->habilitarFechaCumplido = $habilitarFechaCumplido;
        $this->habilitarFechaAntecedente = $habilitarFechaAntecedente;
        $this->habilitarFechaUltimoAvance = $habilitarFechaUltimoAvance;
        $this->descripcion_avance_exportar_monitor = $descripcion_avance_exportar_monitor;
        $this->monitor_id_exportar_monitor = $monitor_id_exportar_monitor;

    }

    public function collection()
    {

        $data =(new CompromisosController())
            ->filtrarDatosConsultaCompromisos($this, true)->cursor()->collect();
        $cabecera = collect([[
            'Código',
            'Nombre',
            'Gabinete',
            'Responsable',
            'Fecha Inicio',
            'Fecha Fin',
            'Estado de Gestión',
            'Estado del Compromiso',
            'Fecha de Reporte',
            'Tipo',
            'Corresponsables',
            'Provincia',
            'Canton',
            'Parroquia',
            'Fecha Antecedente',
            'Descripción Antecedente',
            'Ultimo Avance Realizado',
            'Fecha Ultimo Avance',
            'Fecha Cumplido',
            'Monitor'

        ]]);
        $datas = $cabecera->concat($data);
        return $datas;
    }
    protected function consultaUbicacionesDatatable()
    {
        return (new RepositorioController())->consultaUbicacionesDatatable();
    }
}
