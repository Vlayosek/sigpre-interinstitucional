<?php

namespace App\Core\Entities\TalentoHumano\Distributivo;

use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
    protected $table = 'sc_distributivo_.personas';
    protected $connection = 'pgsql_presidencia';
    protected $primaryKey = 'id';

    protected $fillable = [
        'identificacion',
        'fecha_nacimiento',
        'genero',
        'estado_civil',
        'apellidos_nombres',
        'correo_institucional',
        'correo_personal',
        'numero_telefono_casa',
        'numero_telefono_celular',
        'numero_telefono_extension',
        'provincia_id',
        'canton_id',
        'calle_principal',
        'calle_secundaria',
        'numero_domicilio',
        'sector',
        'bono_residencia',
        'jubilacion_iess',
        'tipo_sangre',
        'grupo_etnico',
        'fotografia',
        'referencia_emergencia',
        'referencia_contacto',
        'apellidos',
        'nombres',

    ];
    public $timestamps = false;
    public function discapacidades()
    {
        return $this->hasMany('App\Core\Entities\TalentoHumano\Distributivo\Discapacidad',  'persona_id','id')->where('eliminado',false);
    }
    public function canton()
    {
        return $this->hasOne('App\Core\Entities\TalentoHumano\Distributivo\Provincia', 'id', 'canton_id');
    }
    public function banco()
    {
        return $this->hasOne('App\Core\Entities\TalentoHumano\Distributivo\Cuenta_bancaria', 'persona_id', 'id')->where('eliminado',false);
    }
    public function historial_activo()
    {
        return $this->hasOne('App\Core\Entities\TalentoHumano\Distributivo\Historia_laboral',  'id','persona_id')
                    ->whereIn('estado',['ACT'])->where('eliminado_por_reingreso',false);
    }
    public function historial()
    {
        return $this->hasMany('App\Core\Entities\TalentoHumano\Distributivo\Historia_laboral',  'id','persona_id')
                    ->whereNotIn('estado',['ACT'])->where('eliminado',false)->where('eliminado_por_reingreso',false);
    }
    public function historico()
    {
        return $this->hasOne('App\Core\Entities\TalentoHumano\Distributivo\Historia_laboral',  'id','persona_id')
        ->where('eliminado',false)
        ->where('eliminado_por_reingreso',false)
        ->orderby('fecha_ingreso','desc')->limit(1);

    }
    public function cargas_familiares()
    {
        return $this->hasMany('App\Core\Entities\TalentoHumano\Distributivo\Carga_familiar',  'id','persona_id')
                    ->where('estado','ACT')->where('eliminado',false);
    }
    public function ultimo_historico()
    {
        return $this->hasOne('App\Core\Entities\TalentoHumano\Distributivo\Historia_laboral',  'id','persona_id')
        ->where('eliminado',false)
        ->where('eliminado_por_reingreso',false)
        ->orderby('fecha_ingreso','desc')->limit(1);

    }
    public function historial_completo()
    {
        return $this->hasMany('App\Core\Entities\TalentoHumano\Distributivo\Historia_laboral',  'persona_id','id')
                    ->where('estado','ACT')
                    ->where('eliminado',false)
                    ->where('eliminado_por_reingreso',false)
                    ->orderby('fecha_ingreso','desc')->limit(1);
    }

    public function solicitudes()
    {
        return $this->hasMany('App\Core\Entities\HorasExtras\Solicitud',  'id','persona_id')
                    ->where('estado','ACT')
                    ->where('eliminado',false);
    }
}

