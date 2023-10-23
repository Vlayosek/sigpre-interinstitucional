<?php

namespace App\Core\Entities\Admin;

use Illuminate\Database\Eloquent\Model;

class Institucion extends Model
{
    protected $table = 'core.instituciones';
    protected $connection = 'pgsql_presidencia';

    public function lista_detalle(){
        return $this->hasMany('App\Core\Entities\Admin\Institucion', 'institucion_id', 'id');
    }
    public function fatherpara(){
        return $this->hasOne('App\Core\Entities\Admin\Institucion','id','institucion_id');
    }
    public function delegado(){
        return $this->hasMany('App\Core\Entities\Compromisos\Delegado','institucion_id','id');
    }
    public function delegado_agenda(){
        return $this->hasMany('App\Core\Entities\Agenda_territorial\Delegado','institucion_id','id');
    }
    public function gabinete(){
        return $this->hasOne('App\Core\Entities\Admin\Institucion','id','institucion_id');
    }
    public function usuarios_monitor_agenda(){
        return $this->hasMany('App\Core\Entities\Agenda_territorial\Monitor', 'institucion_id', 'id');
    }
    public function usuarios_monitor(){
        return $this->hasMany('App\Core\Entities\Compromisos\Monitor', 'institucion_id', 'id');
    }
    public function usuarios_ministro(){
        return $this->hasMany('App\User', 'institucion_id', 'id');
    }
}
