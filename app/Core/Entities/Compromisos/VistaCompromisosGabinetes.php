<?php

namespace App\Core\Entities\Compromisos;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class VistaCompromisosGabinetes extends Model
{
    protected $table = 'sc_compromisos.vista_compromisos_gabinetes';
    protected $connection = 'pgsql_presidencia';
    public $timestamps = false;


}
