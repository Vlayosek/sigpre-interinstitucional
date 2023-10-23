<?php

namespace App\Core\Entities\Admin;

use Illuminate\Database\Eloquent\Model;

class AsignacionFuncionario extends Model
{
    protected $table = 'core.asignaciones_funcionarios';
    protected $connection = 'pgsql_presidencia';
    public $timestamps = false;

}
