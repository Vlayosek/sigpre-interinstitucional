<?php

namespace App\Core\Entities\Compromisos;

use Illuminate\Database\Eloquent\Model;

class Monitor extends Model
{
    protected $table = 'sc_compromisos.monitores';
    protected $connection = 'pgsql_presidencia';
    public $timestamps = false;
    public function usuario()
    {
        return $this->hasOne('App\User', 'id', 'usuario_id');
    }
    public function institucion()
    {
        return $this->hasOne('App\Core\Entities\Admin\Institucion', 'id', 'institucion_id');
    }
}
