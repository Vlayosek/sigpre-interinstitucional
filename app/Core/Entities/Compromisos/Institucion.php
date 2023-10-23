<?php

namespace App\Core\Entities\Compromisos;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Institucion extends Model
{
  protected $table = 'sc_compromisos.instituciones';
  protected $connection = 'pgsql_presidencia';

  // ruta modelo...
  private $ruta_institucion = 'App\Core\Entities\Compromisos\Institucion';
  private $ruta_delegado = 'App\Core\Entities\Compromisos\Delegado';
  private $ruta_monitor = 'App\Core\Entities\Compromisos\Monitor';
  private $ruta_usuario = 'App\User';

  // definicion de campos...
  protected $claves = ['id', 'institucion_id','ministro_usuario_id'];

  public function lista_detalle() {
    return $this->hasMany($this->ruta_institucion, $this->claves[1], $this->claves[0]);
  }
  public function fatherpara() {
    return $this->hasOne($this->ruta_institucion, $this->claves[0], $this->claves[1]);
  }
  public function delegado(){
      return $this->hasMany($this->ruta_delegado, $this->claves[1], $this->claves[0]);
  }
  public function delegado_agenda(){
      return $this->hasMany($this->ruta_delegado, $this->claves[1], $this->claves[0]);
  }
  public function gabinete(){
      return $this->hasOne($this->ruta_institucion, $this->claves[0], $this->claves[1]);
  }
  public function usuarios_monitor_agenda(){
      return $this->hasMany($this->ruta_monitor, $this->claves[1], $this->claves[0]);
  }
  public function usuarios_monitor(){
      return $this->hasMany($this->ruta_monitor, $this->claves[1], $this->claves[0])->where('eliminado',false);
  }
  public function usuarios_ministro(){
      return $this->hasMany($this->ruta_usuario, $this->claves[0], $this->claves[2]);
  }
  
}
