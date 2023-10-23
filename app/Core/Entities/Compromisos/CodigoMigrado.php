<?php

namespace App\Core\Entities\Compromisos;

use Illuminate\Database\Eloquent\Model;

class CodigoMigrado extends Model
{
    protected $table = 'sc_compromisos.codigos_migrados';
    protected $connection = 'pgsql_presidencia';
    public $timestamps = false;

    protected $fillable = [
        'codigo_anterior',
        'codigo_actual',
        'motivo',
        'estado',
    ];
}
