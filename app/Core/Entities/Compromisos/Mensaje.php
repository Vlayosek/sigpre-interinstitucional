<?php

namespace App\Core\Entities\Compromisos;

use Illuminate\Database\Eloquent\Model;

class Mensaje extends Model
{
    protected $table = 'sc_compromisos.mensajes';
    protected $connection = 'pgsql_presidencia';
    public $timestamps = false;
    protected $fillable = [
        'descripcion',
        'estado',
        'compromiso_id',
        'leido',
        'fecha_revisa',
        'usuario_revisa',
    ];
    public function usuario()
    {
        return $this->hasOne('App\User', 'id', 'usuario_ingresa');
    }
    public function usuario_leido()
    {
        return $this->hasOne('App\User', 'id', 'usuario_revisa');
    }
}
