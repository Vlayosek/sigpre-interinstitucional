<?php

namespace App\Core\Entities\Compromisos;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class VistaCompromisosMinisterios extends Model
{
    protected $table = 'sc_compromisos.vista_compromisos_ministerios';
    protected $connection = 'pgsql_presidencia';
    public $timestamps = false;


}
