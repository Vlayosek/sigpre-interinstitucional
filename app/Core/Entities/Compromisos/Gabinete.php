<?php

namespace App\Core\Entities\Compromisos;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Gabinete extends Model
{
    protected $table = 'sc_compromisos.gabinetes';
    protected $connection = 'pgsql_presidencia';

    public $timestamps = false;
 
}
