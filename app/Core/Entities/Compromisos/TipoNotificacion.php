<?php

namespace App\Core\Entities\Compromisos;

use Illuminate\Database\Eloquent\Model;

class TipoNotificacion extends Model
{
    protected $table = 'sc_compromisos.tipos_notificaciones';
    protected $connection = 'pgsql_presidencia';

    public $timestamps = false;
 
}
