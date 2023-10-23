<?php

namespace App\Core\Entities\Compromisos;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Estado extends Model
{
    protected $table = 'sc_compromisos.estados';
    protected $connection = 'pgsql_presidencia';
    public $timestamps = false;
   
 
}
