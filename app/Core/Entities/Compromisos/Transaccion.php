<?php

namespace App\Core\Entities\Compromisos;

use Illuminate\Database\Eloquent\Model;

class Transaccion extends Model
{
    protected $table = 'sc_compromisos.transacciones';
    protected $connection = 'pgsql_presidencia';
    public $timestamps = false;
    public function usuario()
    {
        return $this->hasOne('App\User', 'id', 'usuario_ingresa');
    }
  
}
