<?php

namespace App\Core\Entities\Compromisos;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class EstadoPorcentaje extends Model
{
    protected $table = 'sc_compromisos.estados_porcentaje';
    protected $connection = 'pgsql_presidencia';
    public $timestamps = false;
    
 
}
