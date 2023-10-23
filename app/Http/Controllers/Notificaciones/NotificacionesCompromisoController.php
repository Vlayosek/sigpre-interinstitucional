<?php

namespace App\Http\Controllers\Notificaciones;

use App\Core\Entities\Compromisos\Notificaciones;
use App\Http\Controllers\Admin\TareasProgramadasController;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Ajax\SelectController;
use App\Http\Controllers\Compromisos\RepositorioController;
use Auth;
use DB;
use Yajra\DataTables\DataTables;


class NotificacionesCompromisoController extends Controller
{
    public function consultarNotificacionesCompromisos(request $request)
    {
        $objSelect = new RepositorioController();
        $institucion = $objSelect->consultaInstitucionporMinistro(Auth::user()->id);

        $array_response['status'] = 200;
        $array_response['cantidad_avance'] = 0;
        $array_response['cantidad_archivo'] = 0;
        $array_response['cantidad_mensaje'] = 0;
        $array_response['cantidad_objetivo'] = 0;

        try {
            if (Auth::user()->evaluarole(['MINISTRO'])) {

                $cantidad_avance = Notificaciones::where('tipo', 'AVANCE')->where('institucion_id', $institucion)
                    ->where('monitor', 0)
                    ->where('leido', false)->get()->count();
                $cantidad_archivo = Notificaciones::where('tipo', 'ARCHIVO')->where('institucion_id', $institucion)
                    ->where('monitor', 0)
                    ->where('leido', false)->get()->count();
                $cantidad_mensaje = Notificaciones::where('tipo', 'MENSAJE')->where('institucion_id', $institucion)
                    ->where('monitor', 0)

                    ->where('leido', false)->get()->count();
                $cantidad_objetivo = Notificaciones::where('tipo', 'OBJETIVO')->where('institucion_id', $institucion)
                    ->where('monitor', 0)

                    ->where('leido', false)->get()->count();
               //     dd(1);
            } else {

                $cantidad_avance = Notificaciones::where('tipo', 'AVANCE')->where('monitor', Auth::user()->id)
                    ->where('leido', false)->get()->count();
                $cantidad_archivo = Notificaciones::where('tipo', 'ARCHIVO')->where('monitor', Auth::user()->id)
                    ->where('leido', false)->get()->count();
                $cantidad_mensaje = Notificaciones::where('tipo', 'MENSAJE')->where('monitor', Auth::user()->id)
                    ->where('leido', false)->get()->count();
                $cantidad_objetivo = Notificaciones::where('tipo', 'OBJETIVO')->where('monitor', Auth::user()->id)
                    ->where('leido', false)->get()->count();
                   // dd(2);

            }


            $array_response['cantidad_avance'] = $cantidad_avance;
            $array_response['cantidad_archivo'] = $cantidad_archivo;
            $array_response['cantidad_mensaje'] = $cantidad_mensaje;
            $array_response['cantidad_objetivo'] = $cantidad_objetivo;
        } catch (\Exception $e) {
            $e = false;
        }

        return response()->json($array_response, 200);
    }

    public function getDatatableNotificacionesCompromisosServerSide($tipoActual)
    {
        if ($tipoActual == 'OBJETIO') $tipoActual = 'OBJETIVO';
        $objSelect = new RepositorioController();
        $institucion = $objSelect->consultaInstitucionporMinistro(Auth::user()->id);

        if (!is_null($tipoActual != null)) {

            $cql_notificaciones = Notificaciones::select(
                'notificaciones.id',
                'notificaciones.codigo',
                'notificaciones.descripcion',
                'i.descripcion as institucion',
                'c.nombre_compromiso as compromiso',
                'notificaciones.estado'
            )
                ->leftjoin('sc_compromisos.instituciones as i', 'i.id', 'notificaciones.institucion_id')
                ->leftjoin('sc_compromisos.compromisos as c', 'c.id', 'notificaciones.compromiso_id')
                ->where('notificaciones.estado', 'ACT')
                ->where('notificaciones.eliminado', false);
            if (Auth::user()->evaluarole(['MINISTRO']))   $cql_notificaciones = $cql_notificaciones->where('notificaciones.monitor', 0)->where('notificaciones.institucion_id', $institucion);
            if (Auth::user()->evaluarole(['MONITOR']))   $cql_notificaciones = $cql_notificaciones->where('notificaciones.monitor', Auth::user()->id);

            $cql_notificaciones = $cql_notificaciones->where('notificaciones.leido', false);
            if ($tipoActual != 'TODOS') $cql_notificaciones->where('tipo', $tipoActual);

            $cql_notificaciones->get();
            return DataTables::of($cql_notificaciones)
                ->addIndexColumn()
                ->make(true);
        }
    }

    public function cambiarEstadoLeido(Request $request)
    {
        $tipo = $request->tipo;
        if ($tipo == 'OBJETIO') $tipo = 'OBJETIVO';
        try {
            $objSelect = new RepositorioController();
            $institucion = $objSelect->consultaInstitucionporMinistro(Auth::user()->id);

            DB::connection('pgsql_presidencia')->beginTransaction();
            if (Auth::user()->evaluarole(['MINISTRO'])){
                Notificaciones::where('tipo', $tipo)
                ->where('institucion_id', $institucion)
                ->update([
                    'leido' => true,
                    'usuario_actualiza' => Auth::user()->name,
                    'fecha_actualiza' => date('Y-m-d H:i:s')
                ]);
            }else{
                Notificaciones::where('tipo', $tipo)
                ->where('monitor', Auth::user()->id)
                ->update([
                    'leido' => true,
                    'usuario_actualiza' => Auth::user()->name,
                    'fecha_actualiza' => date('Y-m-d H:i:s')
                ]);
            }


            $array_response['status'] = 200;
            $array_response['message'] = 'Se actualizaron las notificaciones';

            DB::connection('pgsql_presidencia')->commit();
        } catch (\Exception $e) {
            DB::connection('pgsql_presidencia')->rollBack();

            $array_response['status'] = 300;
            $array_response['message'] = 'Error al eliminar el registro';
        }
        return response()->json($array_response, 200);
    }
}
