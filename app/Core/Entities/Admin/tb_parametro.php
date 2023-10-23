<?php

namespace App\Core\Entities\Admin;

use Illuminate\Database\Eloquent\Model;

class tb_parametro extends Model
{
    public $timestamps = true;
    protected $table = 'core.tb_parametro';
    protected $connection = 'pgsql_presidencia';
    protected $primaryKey = 'id';
    protected $fillable = [
                            'descripcion',
                            'parametro_id',
                            'estado',
                            'created_at',
                            'updated_at',
                            'nivel',
                            'verificacion',
                            'categoria_id',
                            'area_id',
                            'nivel_id'
    ];
    public function categoria(){
        return $this->hasOne('App\Core\Entities\Admin\tb_parametro', 'id', 'categoria_id');

    }
    public function area(){
        return $this->hasOne('App\Core\Entities\Admin\tb_parametro', 'id', 'area_id');
        
    }
    public function nivel(){
        return $this->hasOne('App\Core\Entities\Admin\tb_parametro', 'id', 'nivel_id');
    }
   
    public function listado_primario(){
        return $this->hasOne('App\Core\Entities\Admin\tb_parametro','id','principal_listado_id');
    }
    public function listado_secundario(){
        return $this->hasOne('App\Core\Entities\Admin\tb_parametro','id','secundario_listado_id');
    }
    public function lista_detalle(){
        return $this->hasMany('App\Core\Entities\Admin\tb_parametro', 'parametro_id', 'id');
    }
    public function fatherpara(){
        return $this->hasOne('App\Core\Entities\Admin\tb_parametro','id','parametro_id');
    }
}
