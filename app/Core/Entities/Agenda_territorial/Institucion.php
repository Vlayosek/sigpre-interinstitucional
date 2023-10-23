<?php

namespace App\Core\Entities\Agenda_territorial;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Institucion extends Model
{
    protected $table = 'sc_agenda_territorial.instituciones';
    protected $connection = 'pgsql_presidencia';

    public function lista_detalle(){
        return $this->hasMany('App\Core\Entities\Agenda_territorial\Institucion', 'institucion_id', 'id');
    }
    public function fatherpara(){
        return $this->hasOne('App\Core\Entities\Agenda_territorial\Institucion','id','institucion_id');
    }
    public function delegado(){
        return $this->hasMany('App\Core\Entities\Compromisos\Delegado','institucion_id','id');
    }
    public function delegado_agenda(){
        return $this->hasMany('App\Core\Entities\Agenda_territorial\Delegado','institucion_id','id');
    }
    public function gabinete(){
        return $this->hasOne('App\Core\Entities\Agenda_territorial\Institucion','id','institucion_id');
    }
    public function usuarios_monitor_agenda(){
        return $this->hasMany('App\Core\Entities\Agenda_territorial\Monitor', 'institucion_id', 'id')->where('eliminado',false);
    }
    public function usuarios_monitor(){
        return $this->hasMany('App\Core\Entities\Compromisos\Monitor', 'institucion_id', 'id');
    }
    public function usuarios_ministro(){
        return $this->hasOne('App\User', 'id', 'ministro_usuario_id');
    }
  
}
