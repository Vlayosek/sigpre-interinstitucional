<?php

namespace App\Core\Entities\Agenda_territorial;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Delegado extends Model
{
    protected $table = 'sc_agenda_territorial.delegados';
    protected $connection = 'pgsql_presidencia';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = [
        'nombres',
        'identificacion',
        'email',
        'institucion_id',
        'cargo',
        'telefono',
        'celular'
     ];
 
}
