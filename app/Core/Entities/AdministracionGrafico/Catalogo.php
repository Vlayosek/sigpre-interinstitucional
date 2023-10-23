<?php

namespace App\Core\Entities\AdministracionGrafico;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Catalogo extends Model
{
    protected $table = 'sc_administracion_grafica.catalogo';
    protected $connection = 'pgsql_presidencia';
    protected $primaryKey = 'id';
    public $timestamps = false;
}       
