<?php

namespace App\Core\Entities\Compromisos;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ViewMinisterioCompromiso extends Model
{
    protected $table = 'sc_compromisos.v_gestion_compromiso';
    protected $connection = 'pgsql_presidencia';
    public $timestamps = false;
    protected $fillable = [
        'estado_gestion',
        'obj',
    ];
     
}