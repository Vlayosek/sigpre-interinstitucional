<?php

namespace App\Core\Entities\Compromisos;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Delegado extends Model
{
    protected $table = 'sc_compromisos.delegados';
    protected $connection = 'pgsql_presidencia';
    public $timestamps = false;
    protected $fillable = [
        'nombres',
        'identificacion',
        'email',
        'cargo',
        'institucion_id',
        'telefono',
        'celular',
    ];
     
}
