<?php

namespace App\Core\Entities\Admin;

use Illuminate\Database\Eloquent\Model;

class parametro_ciudad extends Model
{
    public $timestamps = true;
    protected $table = 'core.parametro_ciudad';
    protected $connection = 'pgsql_presidencia';
    protected $primaryKey = 'id';
    protected $fillable = [
                            'descripcion',
                            'parametro_id',
                            'estado',
                            'created_at',
                            'updated_at',
                            'nivel'
    ];
   
    public function fatherpara(){
        return $this->hasOne('App\Core\Entities\Admin\parametro_ciudad','id','parametro_id');
    }
  
    public function lista_detalle(){
        return $this->hasMany('App\Core\Entities\Admin\parametro_ciudad', 'parametro_id', 'id');
    }
}
