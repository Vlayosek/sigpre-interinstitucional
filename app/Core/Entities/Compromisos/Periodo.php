<?php

namespace App\Core\Entities\Compromisos;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Periodo extends Model
{
    protected $table = 'sc_compromisos.periodos';
    protected $connection = 'pgsql_presidencia';
    public $timestamps = false;
    protected $primaryKey = 'id';
    protected $fillable = [
        'caracterizacion'
        ,'cumplimiento_acumulado'
        ,'cumplimiento_periodo'
        ,'cumplimiento_periodo_porcentaje'
        ,'descripcion_meta'
        ,'fecha_fin_periodo'
        ,'fecha_inicio_periodo'
        ,'meta_acumulada'
        ,'meta_periodo'
        ,'numero'
        ,'objetivo_id'
        ,'observaciones'
        ,'pendiente_acumulado'
        ,'pendiente_periodo'
        ,'periodo'
        ,'temporalidad'
    ];
    public function objetivo()
    {
        return $this->hasOne('App\Core\Entities\Compromisos\Objetivo', 'id', 'objetivo_id');
    }
}
