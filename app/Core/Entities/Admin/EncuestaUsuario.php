<?php

namespace App\Core\Entities\Admin;

use Illuminate\Database\Eloquent\Model;

class EncuestaUsuario extends Model
{
    protected $table = 'core.encuestas_usuarios';
    protected $connection = 'pgsql_presidencia';
    public $timestamps = false;

}
