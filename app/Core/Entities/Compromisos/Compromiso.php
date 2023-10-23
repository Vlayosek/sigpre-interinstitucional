<?php

namespace App\Core\Entities\Compromisos;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Compromiso extends Model
{
    protected $table = 'sc_compromisos.compromisos';
    protected $connection = 'pgsql_presidencia';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id',
        'tipo_compromiso_id',
        'origen_id',
        'fecha_inicio',
        'fecha_fin',
        'nombre_compromiso',
        'detalle_compromiso',
        'avance_compromiso',
        'notas_compromiso',
        'estado_porcentaje_id',
        'estado_id',
        'cumplimiento',
        'avance',
        'monitor_id',
        'avance_id',
        'fecha_reporte',
    ];
    public $timestamps = false;
    public function usuario()
    {
        return $this->hasOne('App\User', 'id', 'usuario_ingresa');
    }
    public function estado()
    {
        return $this->hasOne('App\Core\Entities\Compromisos\Estado', 'id', 'estado_id');
    }
    public function estado_porcentaje()
    {
        return $this->hasOne('App\Core\Entities\Compromisos\EstadoPorcentaje', 'id', 'estado_porcentaje_id');
    }
    public function tipo()
    {
        return $this->hasOne('App\Core\Entities\Compromisos\Tipo', 'id', 'tipo_compromiso_id');
    }

    public function responsables(){
        return $this->hasMany('App\Core\Entities\Compromisos\Responsable', 'compromiso_id', 'id');
    }

    public function corresponsables(){
        return $this->hasMany('App\Core\Entities\Compromisos\Corresponsable', 'compromiso_id', 'id');
    }
    public function avances(){
        return $this->hasMany('App\Core\Entities\Compromisos\Avance', 'compromiso_id', 'id');
    }
    public function objetivos(){
        return $this->hasMany('App\Core\Entities\Compromisos\Objetivo', 'compromiso_id', 'id');
    }
    public function latest_responsable()
    {
        return $this->hasOne('App\Core\Entities\Compromisos\Responsable')
                    ->orderBy('id','asc');
    }
}
