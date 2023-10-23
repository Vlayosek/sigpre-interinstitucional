<?php

namespace App\Core\Entities\Compromisos;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Tipo extends Model
{
    protected $table = 'sc_compromisos.tipos_compromiso';
    protected $connection = 'pgsql_presidencia';

    public $timestamps = false;
 
}
