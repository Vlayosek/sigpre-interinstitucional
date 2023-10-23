<?php

namespace App\Core\Entities\Agenda_territorial;

use Illuminate\Database\Eloquent\Model;

class Corresponsable extends Model
{
    protected $table = 'sc_agenda_territorial.corresponsables';
    protected $connection = 'pgsql_presidencia';
    public $timestamps = false;
    public function institucion(){
        return $this->hasOne('App\Core\Entities\Agenda_territorial\Institucion','id','institucion_corresponsable_id');
    }
}
