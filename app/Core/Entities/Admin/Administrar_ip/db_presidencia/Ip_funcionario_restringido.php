<?php

namespace App\Core\Entities\Admin\Administrar_ip\db_presidencia;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Ip_funcionario_restringido extends Model{
    protected $table = 'core.ips_funcionarios_restringidos';
    protected $connection = 'pgsql_presidencia';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = [
        'usuario_id',
        'ips'
    ];
}  