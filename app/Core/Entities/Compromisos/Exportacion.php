<?php

namespace App\Core\Entities\Compromisos;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Exportacion extends Model
{
  protected $table = 'sc_compromisos.exportaciones';
  protected $connection = 'pgsql_presidencia';
  public $timestamps = false;

}
