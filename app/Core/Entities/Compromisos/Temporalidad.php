<?php

namespace App\Core\Entities\Compromisos;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Temporalidad extends Model
{
    protected $table = 'sc_compromisos.temporalidades';
    protected $connection = 'pgsql_presidencia';
    public $timestamps = false;
    
 
}
