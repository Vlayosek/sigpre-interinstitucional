<?php

namespace App\Core\Entities\Admin\Administrar_ip\db_presidencia;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Ip extends Model{
    protected $table = 'core.ips';
    protected $connection = 'pgsql_presidencia';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = [
        'objeto1',
        'objeto2',
        'objeto3',
        'objeto4',
        'tipo',
        'seccion',
    ];
}  