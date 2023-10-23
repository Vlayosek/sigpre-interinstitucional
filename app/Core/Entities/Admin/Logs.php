<?php

namespace App\Core\Entities\Admin;

use Illuminate\Database\Eloquent\Model;

class Logs extends Model
{
    protected $table = 'core.logs';
    protected $connection = 'pgsql_presidencia';
    public $timestamps = false;

}
