<?php

namespace App\Http\Controllers\Accion_personal;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Yajra\Datatables\Datatables;
use Yajra\DataTables\CollectionDataTable;
use App\Http\Controllers\Ajax\SelectController;
use App\Mail\DemoEmail as Notificar;
use Maatwebsite\Excel\Facades\Excel;
use App\Core\Entities\TalentoHumano\Distributivo\Persona;
use App\Core\Entities\TalentoHumano\Distributivo\Historia_laboral;


use Auth;
use App\User;
use Mail;
use DB;

class AccionController extends Controller
{
    //Ingreso de solicitud funcionario
    public function index()
    {
        $funcionarios=Persona::select(
            DB::RAW("CONCAT(identificacion,' / ',apellidos,' ',nombres) as nombres_completos"),"id")
                ->pluck('nombres_completos','id');
            
        $objTipo=new SelectController();
        $tipo_accion = $objTipo->getParametro('TIPO ACCION DE PERSONAL','http');
        return view('modules.accion_personal.index',compact('funcionarios','tipo_accion'));
    }
    
   
    public function mostrarHistorial(request $request)//EDITAR un registro
    {
        //dd($request); //aqui recibo persona_id
        $array_response['status'] = 200;
        $array_response['persona'] =Persona::where('id',$request->persona_id)->first();
        $array_response['datos'] =Historia_laboral::select(
            DB::RAW("CONCAT(historias_laborales.estado,' / ',area.nombre,' / ',cargo.nombre,' / ',historias_laborales.id) as historia_laboral"),
             DB::RAW("CONCAT(ap.nombre,'|',area.nombre,'|',cargo.nombre,'|',historias_laborales.numero_partida_presupuestaria,'|',den.nombre,'|',den.remuneracion) as id_"))
            ->leftjoin('sc_distributivo_.denominaciones as den','den.id','historias_laborales.denominacion_id')
            ->leftjoin('sc_distributivo_.cargos as cargo','cargo.id','historias_laborales.cargo_id')
            ->leftjoin('sc_distributivo_.areas as area','area.id','historias_laborales.area_id')
            ->leftjoin('sc_distributivo_.areas as ap','ap.id','area.area_id')
            ->where('sc_distributivo_.historias_laborales.persona_id',$request->persona_id)
           // ->where('sc_distributivo_.historias_laborales.estado','ACT')
            ->where('sc_distributivo_.historias_laborales.eliminado',false)
            ->get();
        return response()->json($array_response, 200);
    }   
}