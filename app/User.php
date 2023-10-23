<?php
namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Hash;
use App\Notifications\MailResetPasswordToken;
use Spatie\Permission\Models\Role;
use App\Core\Entities\Admin\mhr;
use Lab404\Impersonate\Models\Impersonate;
/**
 * Class User
 *
 * @package App
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string $remember_token
*/
class User extends Authenticatable
{
    use Notifiable;
    use HasRoles;
    use Impersonate;
    protected $dates =[
            'updated_at',
            'created_at',
            'two_factor_expires_at'
    ];
    protected $fillable = ['name', 'email', 'password', 'remember_token',
        'estado','nombreCompleto','nombres','cargo','identificacion','institucion_id',
        'two_factor_code',
        'two_factor_expires_at'
    ];

    protected $append = ['roles_label','estado_label','roles_type'];    

    protected $connection = 'pgsql_presidencia';
    protected $table = 'core.users';
    
    /**
     * Hash password
     * @param $input
     */
    public function setPasswordAttribute($input)
    {
        if ($input)
            $this->attributes['password'] = app('hash')->needsRehash($input) ? Hash::make($input) : $input;
    }
    
    
    public function role()
    {
        return $this->belongsToMany(Role::class, 'role_user');
    }
    public function evaluarole($arrayRoles)
    {

        return ($this->roles()
        ->whereIn('role_id',$this->roles()->whereIn('name',$arrayRoles)->pluck('id')->toArray())->count());

    }
    public function buscarRoles($user_id)
    {
        $cql= mhr::where('model_id',$user_id)->pluck('role_id')->toArray();
        return $cql;
        //return $this->belongsToMany(Role::class, 'role_user');
    }
   /* public function evaluarole($arrayRoles){

        return ($this->roles()->whereIn('role_id',Role::whereIn('name',$arrayRoles)->pluck('id')->toArray())->count());
    }
    */

    public function getRolesLabelAttribute(){

        $label='';
        foreach ($this->roles()->pluck('name') as $role){
            $label.='<span class="label label-info label-many">'.$role.'</span> ';
        }

        return $label;
    }

    public function getRolesTypeAttribute(){

        $roleTypeArray=[];
        foreach ($this->roles as $role){
            $roleType[]=$role->abv.'-'.$role->id;
        }

        return $roleType;
    }

    public function getEstadoLabelAttribute(){

        $label='<span class="label label-default label-many">Sin definir</span> ';

        if($this->estado=='A'){
            $label='<span class="label label-success label-many">Activo</span> ';
        }elseif($this->estado=='I'){
            $label='<span class="label label-danger label-many">Bloqueado</span> ';
        }

        return $label;
    }

   
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new MailResetPasswordToken($token));
    }
 
    public function encuestaPartido()
    {
        return $this->hasOne('App\Core\Entities\Admin\EncuestaUsuario', 'identificacion', 'identificacion')->where('tipo','PARTIDO 29/03/2022');
    }
    
    public function institucion()
    {
        return $this->hasOne('App\Core\Entities\Admin\Institucion', 'id', 'institucion_id');
    }
    public function generaTwoFactorCode(){
        $this->timestanps=false;
        $this->two_factor_code=rand(100000,999999);
        $this->two_factor_expores_at=now()->addMinutes(10);
        $this->save();
    }
    public function resetTwoFactorCode(){
        $this->timestanps=false;
        $this->two_factor_code=null;
        $this->two_factor_expores_at=null;
        $this->save();
    }
}

