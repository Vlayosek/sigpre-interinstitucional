<?php

namespace App\Core\Entities\Compromisos;

use Illuminate\Database\Eloquent\Model;

class Archivo extends Model
{
    protected $table = 'sc_compromisos.archivos';
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
