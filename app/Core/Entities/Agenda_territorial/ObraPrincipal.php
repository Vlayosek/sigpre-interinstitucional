<?php

namespace App\Core\Entities\Agenda_territorial;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ObraPrincipal extends Model
{
    protected $table = 'sc_agenda_territorial.obras_principales';
    protected $connection = 'pgsql_presidencia';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = [
        'visita_presidente',
        'fecha_ultima_visita',
        'situacion_actual',
        'ejecutor_proyecto',
        'constructor_obra',
        'numero_beneficiarios_directos',
        'numero_beneficiarios_indirectos',
        'fecha_inicio',
        'fecha_fin',
        'porcentaje_avance',
        'costo_proyecto',
        'fuente_financiamiento',
        'agenda_territorial_id',
     ];
 
}
