<?php

namespace App\Core\Entities\Admin;

use Illuminate\Database\Eloquent\Model;

class IPSFuncionarioRestringido extends Model
{
    protected $table = 'core.ips_funcionarios_restringidos';
    protected $connection = 'pgsql_presidencia';
    public $timestamps = false;

}
