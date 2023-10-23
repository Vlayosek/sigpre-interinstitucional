<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\CollectionDataTable;
use App\Core\Entities\Admin\Administrar_ip\Ip;
use App\Core\Entities\Admin\Administrar_ip\Ip_funcionario_restringido;
use DB;
use Auth;
use App\User;

class IpController extends Controller{
    public function administrar(){
        return view('admin.administrar_ip.administrar.index');
    }
    public function getDatatableAdministrarIpServerSide(){
        $data=Ip::select(
            'id',
            'tipo',
            'seccion',
            DB::RAW("CONCAT(objeto1,'.',objeto2,'.',objeto3,'.',objeto4) as ips_")
        )
        ->whereIn('tipo',['LIBRE','RESTRINGIDO'])
        ->get();
        return (new CollectionDataTable($data)) 
        ->addIndexColumn()
        ->addColumn('', function($row){
            $btn =' <table style="margin: 0 auto;border-collapse: collapse;"><tr>';
            $btn .='<td style="padding:1px;width:70px"><button class="btn btn-primary btn-xs btn-block"  onclick="app.editarIp(\'' . $row->id . '\')"><i class="fa fa-pen"></i>&nbsp;Editar</button></td>';
            $btn .='<td style="padding:1px;width:70px"><button class="btn btn-warning btn-xs btn-block"  onclick="app.eliminarIp(\'' . $row->id . '\')"><i class="fa fa-trash"></i>&nbsp;Eliminar</button></td>';
            $btn .=' </tr></table>';
            return $btn;
        })
        ->rawColumns([''])
        ->toJson();
    }
    //AGREGA IP
    public function agregarIp(request $request){
      //  dd($request);
        if ($request->id==0){
            $cqlIp= new Ip();
            $cqlIp->usuario_ingresa=Auth::user()->name;
            $cqlIp->created_at=date("Y-m-d h:i:s");

        }else{
            $cqlIp=Ip::find($request->id);
            $cqlIp->usuario_actualiza=Auth::user()->name;
            $cqlIp->updated_at=date("Y-m-d h:i:s");
        }
        $cqlIp->save();
        $cqlIp->fill($request->all())->save();



        $array_response['status'] = 200;
        $array_response['message'] = 'REGISTRO AGREGADO';
        return response()->json($array_response, 200);
    }
    //EDITA LA IP
    public function editarIp(request $request){
        $array_response['status'] = 200;
        $array_response['datos'] = Ip::where('id',$request->id)->first();
        //dd($array_response['datos']);
        return response()->json($array_response, 200);
    }
    //BORRA IP
    public function eliminarIp(request $request){
        if ($request->id!=null || $request->id!=0){
            $cqlIp=Ip::find($request->id);
            $cqlIp->eliminado=true;
            $cqlIp->usuario_actualiza=Auth::user()->name;
            $cqlIp->updated_at=date("Y-m-d h:i:s");
            $cqlIp->save();

    
        }
        $array_response['status'] = 200;
        $array_response['message'] = 'REGISTRO ELIMINADO';
        return response()->json($array_response, 200);
    }
    //RESTRINGIR IP
    public function restringir(){
        $cqlUser=User::select('nombres','id')
        ->where('estado','A')
        ->pluck('nombres','id');
        $cqlIpRestringidas=Ip::select(
            DB::RAW("CONCAT(objeto1,'.',objeto2,'.',objeto3,'.',objeto4) as ip_"),
            'id'
        )
        ->where('tipo','RESTRINGIDO')
        ->pluck('ip_','id');
        return view('admin.administrar_ip.restringir.index',compact('cqlUser','cqlIpRestringidas'));
    }
    //DATATABLE RESTRINGIR IP
    public function getDatatableRestringirIpServerSide(){
        $data=Ip_funcionario_restringido::select(
            'ips.tipo as tipo',
            'ips.seccion as seccion',
            'ips_funcionarios_restringidos.id as id',
            'users.nombres as usuario_nombres',
            DB::RAW("CONCAT(ips.objeto1,'.',ips.objeto2,'.',ips.objeto3,'.',ips.objeto4) as ip_restringida")
        )
        ->join('core.users as users','users.id','ips_funcionarios_restringidos.usuario_id')
        ->join('core.ips as ips','ips.id','ips_funcionarios_restringidos.ips')
        ->where('ips_funcionarios_restringidos.eliminado',false)
      //  ->where('ips.tipo','RESTRINGIDO')
        ->get();
        return (new CollectionDataTable($data)) 
        ->addIndexColumn()
        ->addColumn('', function($row){
            $btn =' <table style="margin: 0 auto;border-collapse: collapse;"><tr>';
            $btn .='<td style="padding:1px"><button class="btn btn-warning btn-xs"  onclick="app.eliminarRegistro(\'' . $row->id . '\')"><i class="fa fa-trash"></i>&nbsp;Eliminar</button></td>';
            $btn .=' </tr></table>';
            return $btn;
        })
        ->rawColumns([''])
        ->toJson();
    }
    //AGREGAR USER RESTRINGIDO
    public function restringirIp(request $request){
        if ($request->id==0){
            $cqlFuncionario= new Ip_funcionario_restringido();
            $cqlFuncionario->usuario_inserta=Auth::user()->name;
            $cqlFuncionario->fecha_inserta=date("Y-m-d h:i:s");
            $cqlFuncionario->save();
            $cqlFuncionario->fill($request->all())->save();
        }
        $array_response['status'] = 200;
        $array_response['message'] = 'IP RESTRINGIDA';
        return response()->json($array_response, 200);
    }
    //BORRA FUNCIONARIOS CON DISPOSITIVO
    public function eliminarRegistro(request $request){
        if ($request->id!=null || $request->id!=0){

            $cqlFuncionario=Ip_funcionario_restringido::find($request->id);
            $cqlFuncionario->eliminado=true;
            $cqlFuncionario->usuario_modifica=Auth::user()->name;
            $cqlFuncionario->fecha_modifica=date("Y-m-d h:i:s");
            $cqlFuncionario->save();

        }
        $array_response['status'] = 200;
        $array_response['message'] = 'REGISTRO ELIMINADO';
        return response()->json($array_response, 200);
    }
    
}