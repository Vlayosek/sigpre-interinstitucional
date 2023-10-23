<?php

namespace App\Core\Entities\Agenda_territorial;

use Illuminate\Database\Eloquent\Model;

class Transaccion extends Model
{
    protected $table = 'sc_agenda_territorial.transacciones';
    protected $connection = 'pgsql_presidencia';
    public $timestamps = false;
    
    public function usuario()
    {
        return $this->hasOne('App\User', 'id', 'usuario_ingresa');
    }
  
}
