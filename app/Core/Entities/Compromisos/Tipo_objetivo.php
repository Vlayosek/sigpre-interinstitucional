<?php

namespace App\Core\Entities\Compromisos;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Tipo_objetivo extends Model
{
    protected $table = 'sc_compromisos.tipos_objetivos';
    protected $connection = 'pgsql_presidencia';

    public $timestamps = false;
 
}
