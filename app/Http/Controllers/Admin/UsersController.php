<?php

namespace App\Http\Controllers\Admin;

use App\User;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreUsersRequest;
use App\Http\Requests\Admin\UpdateUsersRequest;
use App\Http\Controllers\Ajax\SelectController;
use App\Core\Entities\Solicitudes\Empleados;
use App\Core\Entities\Admin\Institucion;
use App\Core\Entities\Admin\AsignacionFuncionario;

use DB;
use Auth;

class UsersController extends Controller
{
    /**
     * Display a listing of User.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::all()->sortByDesc("created_at");
        $objSelect = new SelectController();
        
        return view('admin.users.index', compact('users'));
    }
    public function create()
    {

        $roles = Role::get()->pluck('name', 'name');
        $instituciones = Institucion::get()->pluck('nombre', 'id');
        
        return view('admin.users.create',compact('instituciones'))->with(['roles' => $roles]);
    }

    /**
     * Store a newly created User in storage.
     *
     * @param  \App\Http\Requests\StoreUsersRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreUsersRequest $request)
    {
        
        $rules = [
            'name' => 'required',
            'email' => 'required',
        ];
        $messages = [
            'name.required' => 'Escriba el nombre ',
            'email.unique' => 'El email es requerido',

        ];
        $this->validate($request, $rules, $messages);

            $user = User::create($request->all());
            $roles = $request->input('roles') ? $request->input('roles') : [];
            $user->assignRole($roles);
      

        return redirect()->route('admin.users.index');
    }


    /**
     * Show the form for editing User.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $roles = Role::get()->pluck('name', 'name');

        $user = User::findOrFail($id);

        $objSelect = new SelectController();
        $instituciones = Institucion::get()->pluck('nombre', 'id');
   
        return view('admin.users.edit',compact('instituciones'))->with(['user'=>$user, 
              'roles'=>$roles,
        ]);
    }

    /**
     * Update User in storage.
     *
     * @param  \App\Http\Requests\UpdateUsersRequest $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUsersRequest $request, $id)
    {
   

        if($id==0)
        {  
            $result = DB::connection('pgsql_presidencia')
            ->table('core.users')
            ->where('name', $request->name)
            ->orwhere('email', $request->email)
            ->get()->toArray();
            if (count($result) > 0) {
                $users = User::all()->sortByDesc("created_at");
                $m='El usuario ya esta registrado';
                return view('admin.users.index', compact('users','m'));
            }
            $user = User::create($request->all());
            $roles = $request->input('roles') ? $request->input('roles') : [];
            $agregados=$roles;

            $objSelect = new SelectController();
            foreach($agregados as $value){
                $objSelect->logsCRUDRegistro($value,$user,'ASIGNACION ROL');
            }
         
            $user->assignRole($roles);

        }else{
            $user = User::findOrFail($id);
            $user->update($request->all());
           
            $roles = $request->input('roles') ? $request->input('roles') : [];
            $eliminados = array_diff( $user->roles()->pluck('name')->toArray(),$roles);
            $agregados = array_diff( $roles,$user->roles()->pluck('name')->toArray());
            
            $objSelect = new SelectController();
            foreach($agregados as $value){
                $objSelect->logsCRUDRegistro($value,$user,'ASIGNACION ROL');
            }
            foreach($eliminados as $value){
                $objSelect->logsCRUDRegistro($value,$user,'ELIMINACION ROL');
            }
          //  dd($coincidencias,$coincidencias2);
            $user->syncRoles($roles);
        }
        return redirect()->route('admin.users.index');

    }

    /**
     * Remove User from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('admin.users.index');
    }
    public function userstate($id)
    {
        $objSelect = new SelectController();

        $user = User::findOrFail($id);
        if($user->estado=='A')
        {
            $user->estado='I';
            $objSelect->logsCRUDRegistro('BAJA',$user,'INACTIVACION DE USUARIO');

        }else
        {
            $user->estado='A';
            $objSelect->logsCRUDRegistro('ALTA',$user,'REACTIVACION DE USUARIO');

        }
        $user->save();

        return redirect()->route('admin.users.index');
    }
    public function restaurar2FA($id)
    {
    
        $user = User::findOrFail($id);
        $user->token_login=null;
        $user->valida_qr=false;
        $user->save();
  
        return redirect()->route('admin.users.index');
    }
    public function tokenExpira($id)
    {

        $user = User::findOrFail($id);
        if($user->token_expire==true)
        $user->token_expire=false;
        else
        $user->token_expire=true;

        $user->save();

        return redirect()->route('admin.users.index');
    }
    public function habilitar_token()
    {
        $cqlConsulta=User::where('token_expire',false)->get()->count();
        if($cqlConsulta>0)
        $user = User::where('estado','A')->update(['token_expire'=>true]);
        else
        $user = User::where('estado','A')->update(['token_expire'=>false]);

        return redirect()->route('admin.users.index');
    }
    
    /**
     * Delete all selected User at once.
     *
     * @param Request $request
     */
    public function massDestroy(Request $request)
    {

        if ($request->input('ids')) {
            $entries = User::whereIn('id', $request->input('ids'))->get();

            foreach ($entries as $entry) {
                $entry->estado='I';
                $entry->save();
            }
        }
    }



}
