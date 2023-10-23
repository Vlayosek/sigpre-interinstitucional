<?php

namespace App\Core\Entities\Compromisos;

use Illuminate\Database\Eloquent\Model;

class Notificaciones extends Model
{
    protected $table = 'sc_compromisos.notificaciones';
    protected $connection = 'pgsql_presidencia';
    public $timestamps = false;

    protected $fillable = [
        'codigo',
        'descripcion',
        'institucion_id',
        'compromiso_id',
    ];

    public function institucion()
    {
        return $this->hasOne('App\Core\Entities\Admin\Institucion', 'id', 'institucion_id');
    }

    public function compromiso()
    {
        return $this->hasOne('App\Core\Entities\Admin\Compromiso', 'id', 'compromiso_id');
    }
}
