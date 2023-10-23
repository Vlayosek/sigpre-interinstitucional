<?php

namespace App\Core\Entities\Compromisos;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class CambioEstado extends Model
{
    protected $table = 'sc_compromisos.cambio_estado';
    protected $connection = 'pgsql_presidencia';
    public $timestamps = false;


}
