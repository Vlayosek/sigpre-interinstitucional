<?php

namespace App\Core\Entities\Compromisos;

use Illuminate\Database\Eloquent\Model;

class FechaPeriodoConsulta extends Model
{
    protected $table = 'sc_compromisos.fecha_periodos_consulta';
    protected $connection = 'pgsql_presidencia';
    public $timestamps = false;

}
