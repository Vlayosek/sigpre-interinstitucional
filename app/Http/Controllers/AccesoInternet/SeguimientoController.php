<?php

namespace App\Http\Controllers\AccesoInternet;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Ajax\SelectController;
use App\Http\Controllers\AccesoInternet\RepositorioController as RP;
use App\Core\Entities\AccesoInternet\Solicitud;
use App\Core\Entities\AccesoInternet\Dato_tecnico;
use App\Core\Entities\AccesoInternet\Red_byod;
use App\Core\Entities\AccesoInternet\Solicitud_byod;
use Yajra\DataTables\CollectionDataTable;
use App\Core\Entities\TalentoHumano\Distributivo\Persona;

use Auth;
use App\User;
use DB;

class SeguimientoController extends Controller
{

    public function consulta()
    {
        $tipo['NAVEGACION']='PERFILES DE NAVEGACION';
        $tipo['BYOD']='BYOD';
        return view('modules.acceso_internet.seguimiento.index')->with(['tipo'=>$tipo]);
    }
    public function getDatatableConsultaSolicitudesServerSide($persona,$tipo)
    {

        if($persona=="null")
        $identificacion=Auth::user()->identificacion;
        else
        $identificacion=Persona::where('id',$persona)->first()->identificacion;
        $objRepo = new Rp();
        if($tipo=='NAVEGACION'){
            if($persona=="null")
            $data = $objRepo->selectDatatable(null, null, null);
            else
            $data = $objRepo->selectDatatable(null, null, null)->where('solicitudes.identificacion', $identificacion);

        }
        else{
            if($persona=="null")
            $data = $objRepo->selectDatatableBYOD(null, null);
            else
            $data = $objRepo->selectDatatableBYOD(null, null)->where('solicitudes_byod.identificacion_jefe',  $identificacion);

        }
        $data = $data->get();
        
        return (new CollectionDataTable($data))
            ->addIndexColumn()
            ->addColumn('', function ($row)use($tipo) {
                $btn = '<button class="btn btn-success btn-sm" onclick="app_seguimiento.consultarSeguimiento(\'' . $row->id . '\',\'' . $tipo . '\')"  data-toggle="modal" data-target="#modal-seguimiento">Seguimiento</button>';
                return $btn;
            })
            ->addColumn('tipo_', function ($row)use($tipo) {
                return $tipo;
            })
            ->rawColumns([''])
            ->toJson();
    }

}
