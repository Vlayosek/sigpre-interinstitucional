<?php

namespace App\Core\Entities\Agenda_territorial;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ObraComplementaria extends Model
{
    protected $table = 'sc_agenda_territorial.obras_complementarias';
    protected $connection = 'pgsql_presidencia';
    public $timestamps = false;
    protected $primaryKey = 'id';
    protected $fillable = [
        'descripcion',
        'porcentaje_avance',
        'responsable',
        'agenda_territorial_id',
     ];
}
