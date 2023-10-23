<?php

namespace App\Core\Entities\Agenda_territorial;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class OrdenDia extends Model
{
    protected $table = 'sc_agenda_territorial.ordenes_diarias';
    protected $connection = 'pgsql_presidencia';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = [
        'tema',
        'expositor',
        'cargo',
        'agenda_territorial_id',
        'entidad',
        'tiempo',
        'informacion_complementaria'
     ];
 
}
