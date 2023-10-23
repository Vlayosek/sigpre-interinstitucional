<?php

namespace App\Core\Entities\Agenda_territorial;

use Illuminate\Database\Eloquent\Model;

class Mensaje extends Model
{
    protected $table = 'sc_agenda_territorial.mensajes';
    protected $connection = 'pgsql_presidencia';
    public $timestamps = false;
    
    public function usuario()
    {
        return $this->hasOne('App\User', 'id', 'usuario_ingresa');
    }
    public function usuario_leido()
    {
        return $this->hasOne('App\User', 'id', 'usuario_revisa');
    }
}
