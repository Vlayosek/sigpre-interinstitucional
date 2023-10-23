<?php

namespace App\Core\Entities\Compromisos;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Objetivo extends Model
{
    protected $table = 'sc_compromisos.objetivos';
    protected $connection = 'pgsql_presidencia';
    public $timestamps = false;
    public function temporalidad()
    {
        return $this->hasOne('App\Core\Entities\Compromisos\Temporalidad', 'id', 'temporalidad_id');
    }
    public function tipo_objetivo()
    {
        return $this->hasOne('App\Core\Entities\Compromisos\Tipo_objetivo', 'id', 'tipo_objetivo_id');
    }
}
