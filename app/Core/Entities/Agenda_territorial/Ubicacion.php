<?php

namespace App\Core\Entities\Agenda_territorial;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Ubicacion extends Model
{
    protected $table = 'sc_agenda_territorial.ubicaciones';
    protected $connection = 'pgsql_presidencia';
    public $timestamps = false;
    
    public function parroquia(){
        return $this->hasOne('App\Core\Entities\Admin\parametro_ciudad','id','parametro_id');
    }
}
