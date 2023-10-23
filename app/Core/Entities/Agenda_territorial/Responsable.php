<?php

namespace App\Core\Entities\Agenda_territorial;

use Illuminate\Database\Eloquent\Model;

class Responsable extends Model
{
    protected $table = 'sc_agenda_territorial.responsables';
    protected $connection = 'pgsql_presidencia';
    public $timestamps = false;

    public function institucion(){
        return $this->hasOne('App\Core\Entities\Agenda_territorial\Institucion','id','institucion_id');
    }
}
