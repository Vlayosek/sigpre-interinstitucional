<?php

namespace App\Core\Entities\Agenda_territorial;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Objetivo extends Model
{
    protected $table = 'sc_agenda_territorial.objetivos';
    protected $connection = 'pgsql_presidencia';
    public $timestamps = false;
    public function temporalidad()
    {
        return $this->hasOne('App\Core\Entities\Agenda_territorial\Temporalidad', 'id', 'temporalidad_id');
    }
}
