<?php

namespace App\Http\Controllers\Acta;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Ajax\SelectController;
use App\Core\Entities\Solicitudes\Solicitud;
use App\Core\Entities\Solicitudes\Estado;
use App\Core\Entities\Informes\Seccion;
use App\Core\Entities\Informes\Detalle_Seccion;
use App\Core\Entities\Informes\Plantilla;
use App\Core\Entities\Informes\Plantilla_Seccion;
use App\Core\Entities\Informes\Informe;
use App\Core\Entities\Informes\Archivo;
use App\Core\Entities\Informes\DetalleInforme;
use App\Core\Entities\Inventario\Transaccion;
use Spatie\Permission\Models\Role;  

use App\Core\Entities\Admin\mhr;
use App\Core\Entities\Solicitudes\Tipo;
use Yajra\Datatables\Datatables;
use App\Mail\DemoEmail as Notificar;
use App\Core\Entities\Admin\tb_parametro;
use DB;
use Auth;
use App\User;
use Mail;
use PDF;

class ActaController extends Controller
{
   public function index(){
     $soporte2= Auth::user()->roles()->pluck('name','id')->toArray();

       $role_id=array_search("INFORMES",$soporte2);
       if($role_id==false)
         $plantillas=null;
       else{
           $plantillas=Plantilla::select('id','descripcion')
           ->where('eliminado','0')
           ->where('role_id',$role_id)
           ->get()
           ->pluck('descripcion','id');
       }
        return view('modules.acta.index',compact('plantillas'));
   }
    
}
