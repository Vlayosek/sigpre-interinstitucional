<?php

namespace App\Http\Controllers\Compromisos;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\Datatables\Datatables;
use App\Core\Entities\Compromisos\Institucion;
use App\Core\Entities\Compromisos\Compromiso;
use App\Core\Entities\Compromisos\CodigoMigrado;
use App\Core\Entities\Compromisos\Responsable;


use Auth;
use App\User;
use DB;

class MigracionController extends Controller
{
  public function migracion()
  {
    return view('modules.compromisos.instituciones.migracion');
  }

  public function migrarCompromisos(Request $request)
  {
    try {
      DB::connection('pgsql_presidencia')->beginTransaction();

      $arreglo_compromisos = $request->arreglo_compromisos;

      foreach ($arreglo_compromisos as $value) {
        $cambiarInstitucionResponsable = Responsable::where('compromiso_id', $value)->where('institucion_id', $request->id_institucion)->first();
        $cambiarInstitucionResponsable->institucion_id = $request->id_institucion_;
        $cambiarInstitucionResponsable->updated_at = date("Y-m-d H:i:s");
        $cambiarInstitucionResponsable->usuario_actualiza = Auth::user()->id;
        $cambiarInstitucionResponsable->save();

        $cqlInstitucionAnterior = Institucion::find($request->id_institucion);
        $cqlInstitucionActual = Institucion::find($request->id_institucion_);

        $cqlCompromiso = Compromiso::find($value);

        $descripcion = 'Se migro el compromiso ' . $cqlCompromiso->codigo . ', de la institucion ' . $cqlInstitucionAnterior->descripcion . ' a la institucion ' . $cqlInstitucionActual->descripcion;
        (new RepositorioController())->nuevaTransaccion($descripcion, $value);

        $codigo_anterior =  $cqlCompromiso->codigo;
        $codigo_actual = (new RepositorioController())->verificacionCodigo($request->id_institucion_, $value);


        $descripcion = 'Se cambio el código del compromiso de ' . $codigo_anterior . ', al código ' . $codigo_actual;
        (new RepositorioController())->nuevaTransaccion($descripcion, $value);

        $cqlNotificaciones = new CodigoMigrado();
        $cqlNotificaciones->codigo_anterior = $codigo_anterior;
        $cqlNotificaciones->codigo_actual = $codigo_actual;
        $cqlNotificaciones->motivo = $request->motivo;
        $cqlNotificaciones->save();


        $cqlCompromiso->migrado  = true;
        $cqlCompromiso->save();
      }

      $array_response['status'] = 200;
      $array_response['message'] = 'Datos migrados correctamente';

      DB::connection('pgsql_presidencia')->commit();
    } catch (\Exception $e) {
      DB::connection('pgsql_presidencia')->rollBack();

      $array_response['status'] = 300;
      $array_response['message'] = $e->getMessage();
    }
    return response()->json($array_response, 200);
  }

  public function getDatatableCodigosMigradosServerSide()
  {
    $data_ = CodigoMigrado::select('id', 'codigo_anterior', 'codigo_actual', 'motivo')
      ->where('estado', 'ACT')
      ->where('eliminado', false)
      ->get();

    return Datatables::of($data_)
      ->addIndexColumn()
      ->make(true);
  }
  public function getDatatableBuscarCompromisosServerSide($id_institucion)
  {
    $data_ = Compromiso::select('compromisos.id', 'tc.descripcion', 'compromisos.codigo', 'compromisos.nombre_compromiso')
      ->join('sc_compromisos.tipos_compromiso as tc', 'tc.id', 'compromisos.tipo_compromiso_id')
      ->join('sc_compromisos.responsables as r', 'r.compromiso_id', 'compromisos.id')
      ->where('r.institucion_id', $id_institucion)
      ->where('compromisos.estado', 'ACT')
      ->where('r.estado', 'ACT')
      ->distinct('compromisos.id')
      ->get();

    /* $data = Compromiso::with([
            'tipo',
            'responsables' => function ($q) use ($id_institucion) {
                $q->where('estado', 'ACT')
                    ->where('institucion_id', $id_institucion);
            },
        ]); */
    return Datatables::of($data_)
      ->addIndexColumn()
      /* ->addColumn('estado_aprobado', function ($row) {
                $btn_check = '<table style="width:100%;border:0px"><tr>';
                $btn_check .= '<button title="Aprobado" class="btn btn-block btn-sm"><img src="/images/icons/approved.png" width="20px" heigh="20px"></button>';
                $btn_check .= '</tr></table>';
                return $btn_check;
            })
            ->rawColumns(['', 'estado_aprobado']) */
      ->make(true);
  }
}
