<?php

namespace App\Exports;

use App\Core\Entities\TalentoHumano\Teletrabajo\Actividad;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use App\Http\Controllers\Ajax\SelectController;
use App\User;
use App\Core\Entities\Compromisos\Compromiso;
use App\Core\Entities\Compromisos\Responsable;
use App\Core\Entities\Compromisos\Corresponsable;
use App\Core\Entities\Compromisos\Estado;
use App\Core\Entities\Compromisos\EstadoPorcentaje;
use App\Core\Entities\Admin\Institucion;

use Auth;
use DB;

class CompromisosMinisterioExport implements FromCollection
{
    protected $inicio;
    protected $fin;
    protected $tipo;
    protected $tabla;
    protected $asignaciones;
    protected $temporales;
    protected $pendientes;
    protected $filtro;
    protected $institucion_id;
    protected $gabinete_id;
    
    public function __construct(string $inicio, string $fin,string $tipo, string $tabla, string $asignaciones,string $temporales,string $pendientes,string $filtro,string $institucion_id,string $gabinete_id)
    {
        $this->inicio = $inicio;
        $this->fin = $fin;
        $this->tipo = $tipo;
        $this->tabla = $tabla;
        $this->asignaciones = $asignaciones;
        $this->temporales = $temporales;
        $this->pendientes = $pendientes;

        $this->filtro = $filtro;
        $this->institucion_id = $institucion_id;
        $this->gabinete_id = $gabinete_id;
    }

    public function collection()  {
        $arrregloConsultaTeletrabajo =[];
        $estado=$this->tipo;
        $tabla=$this->tabla;
        $asignaciones=$this->asignaciones;
        $temporales=$this->temporales;
        $pendientes=$this->pendientes;
        $filtro=$this->filtro;
        $institucion_id=$this->institucion_id;
        $gabinete_id=$this->gabinete_id;

        $data=Compromiso::select(
            'compromisos.id as id',
            'compromisos.codigo as codigo_compromiso',
            'compromisos.nombre_compromiso as nombre_compromiso',
            'estado_gestion.descripcion as estado_gestion',
            'estado_compromiso.descripcion as estado_compromiso',
            'compromisos.avance as porcentaje_avance',
            'compromisos.avance_compromiso as avance_compromiso',
            'institucion_.descripcion as nombre_institucion'
        )
        ->leftjoin('sc_compromisos.responsables as responsable_','responsable_.compromiso_id','compromisos.id')
        ->join('core.instituciones as institucion_','institucion_.id','responsable_.institucion_id')
        ->leftjoin('sc_compromisos.estados as estado_compromiso','estado_compromiso.id','compromisos.estado_id')
        ->leftjoin('sc_compromisos.estados_porcentaje as estado_gestion','estado_gestion.id','compromisos.estado_porcentaje_id')
        ->where('compromisos.estado','ACT');
        if($institucion_filtro != '--')
            $data = $data->where('institucion_.id',$institucion_filtro);
        
        $data=$data->orderby('compromisos.id','desc')
        ->cursor()->collect();
            // dd($data);

        $cabecera = collect([[
            'Reg', 
            'Codigo compromiso',
                'Nombre del compromiso',
                'Estado de Gestión',
                'Estado del Compromiso',
                '% de Avance',
                'Avance',
                ]]);
            $datas = $cabecera->concat($data);
            return $datas;

    }

 
}
