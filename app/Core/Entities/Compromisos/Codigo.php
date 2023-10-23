<?php

namespace App\Core\Entities\Compromisos;

use Illuminate\Database\Eloquent\Model;

class Codigo extends Model
{
    protected $table = 'sc_compromisos.codigos';
    protected $connection = 'pgsql_presidencia';
    public $timestamps = false;
   
 
}
