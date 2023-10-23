<?php

namespace App\Core\Entities\Admin;

use Illuminate\Database\Eloquent\Model;

class mhr extends Model
{
    protected $table = 'core.model_has_roles';
    protected $connection = 'pgsql_presidencia';
    public $timestamps = false;

}
