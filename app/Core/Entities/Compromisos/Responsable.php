<?php

namespace App\Core\Entities\Compromisos;

use Illuminate\Database\Eloquent\Model;

class Responsable extends Model
{
    protected $table = 'sc_compromisos.responsables';
    protected $connection = 'pgsql_presidencia';
    public $timestamps = false;

    public function institucion(){
        return $this->hasOne('App\Core\Entities\Admin\Institucion','id','institucion_id');
    }
}
