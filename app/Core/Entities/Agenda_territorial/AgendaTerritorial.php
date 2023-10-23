<?php

namespace App\Core\Entities\Agenda_territorial;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
//use OwenIt\Auditing\Contracts\Auditable;

//class AgendaTerritorial extends Model implements Auditable
class AgendaTerritorial extends Model
{
   // use \OwenIt\Auditing\Auditable;
    protected $table = 'sc_agenda_territorial.agenda_territorial';
    protected $connection = 'pgsql_presidencia';
    protected $primaryKey = 'id';
    protected $fillable = [
       'estado_id',
       'estado_porcentaje_id',
       'fecha_inicio',
       'fecha_fin',
       'origen_id',
       'monitor_id',
       'justificacion',
       'lugar',
       'antecedente',
       'estado',
       'avance_id',
       'pendientes',
       'cerrado',
       'tipo_id',
       'tema',
       'objetivo',
       'descripcion',
       'duracion',
       'observacion',
       'impacto',
       'coyuntura'
        
    ];
    public $timestamps = false;

    public function usuario()
    {
        return $this->hasOne('App\User', 'id', 'usuario_ingresa');
    }
    public function estado()
    {
        return $this->hasOne('App\Core\Entities\Agenda_territorial\Estado', 'id', 'estado_id');
    }
    public function estado_porcentaje()
    {
        return $this->hasOne('App\Core\Entities\Agenda_territorial\EstadoPorcentaje', 'id', 'estado_porcentaje_id');
    }
    public function obra_principal()
    {
        return $this->hasOne('App\Core\Entities\Agenda_territorial\ObraPrincipal', 'agenda_territorial_id', 'id');
    }
    
    public function tipo()
    {
        return $this->hasOne('App\Core\Entities\Agenda_territorial\Tipo', 'id', 'tipo_id');
    }

    public function responsables(){
        return $this->hasMany('App\Core\Entities\Agenda_territorial\Responsable', 'agenda_territorial_id', 'id');
    }

    public function corresponsables(){
        return $this->hasMany('App\Core\Entities\Agenda_territorial\Corresponsable', 'agenda_territorial_id', 'id');
    }
    public function avances(){
        return $this->hasMany('App\Core\Entities\Agenda_territorial\Avance', 'agenda_territorial_id', 'id');
    }
    public function objetivos(){
        return $this->hasMany('App\Core\Entities\Agenda_territorial\Objetivo', 'agenda_territorial_id', 'id');
    }
    public function latest_responsable()
    {
        return $this->hasOne('App\Core\Entities\Agenda_territorial\Responsable')
                    ->orderBy('id','asc');
    }


}
