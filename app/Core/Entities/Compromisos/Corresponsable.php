<?php

namespace App\Core\Entities\Compromisos;

use Illuminate\Database\Eloquent\Model;

class Corresponsable extends Model
{
    protected $table = 'sc_compromisos.corresponsables';
    protected $connection = 'pgsql_presidencia';
    public $timestamps = false;
    public function institucion(){
        return $this->hasOne('App\Core\Entities\Admin\Institucion','id','institucion_corresponsable_id');
    }
}
