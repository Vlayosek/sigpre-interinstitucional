<?php

namespace App\Core\Entities\Admin;

use Illuminate\Database\Eloquent\Model;

class Notificacion extends Model
{
    protected $table = 'core.notificaciones';
    protected $connection = 'pgsql_presidencia';
    public $timestamps = false;

}
