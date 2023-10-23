<?php

namespace App\Core\Entities\Admin;

use Illuminate\Database\Eloquent\Model;

class ModelRol extends Model
{
    protected $table = 'core.model_has_roles';
    protected $connection = 'pgsql_presidencia';
}
