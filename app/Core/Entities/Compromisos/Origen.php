<?php

namespace App\Core\Entities\Compromisos;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Origen extends Model
{
    protected $table = 'sc_compromisos.origenes';
    protected $connection = 'pgsql_presidencia';
    public $timestamps = false;
}
