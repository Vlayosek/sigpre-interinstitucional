<?php

namespace App\Core\Entities\Compromisos;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Ubicacion extends Model
{
    protected $table = 'sc_compromisos.ubicaciones';
    protected $connection = 'pgsql_presidencia';
    public $timestamps = false;

    public function parroquia(){
        return $this->hasOne('App\Core\Entities\Admin\parametro_ciudad','id','parametro_id');
    }
}
