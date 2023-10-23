<?php

namespace App\Core\Entities\Compromisos;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Antecedente extends Model
{
    protected $table = 'sc_compromisos.antecedentes';
    protected $connection = 'pgsql_presidencia';
    public $timestamps = false;
    
 
}
