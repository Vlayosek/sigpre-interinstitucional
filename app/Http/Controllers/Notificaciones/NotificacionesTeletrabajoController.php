<?php

namespace App\Http\Controllers\Notificaciones;

use App\Core\Entities\Compromisos\Notificaciones;
use App\Http\Controllers\Admin\TareasProgramadasController;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Ajax\SelectController;
use App\Http\Controllers\Tareas\TareasController;
use Auth;
use DB;
use Yajra\DataTables\DataTables;


class NotificacionesTeletrabajoController extends Controller
{

    public function consultarNotificacionesTeletrabajo(request $request)
    {
        $array_response['status'] = 200;
        $array_response['actividades_pendientes_registrar'] = 0;
        $array_response['actividades_pendientes_aprobar'] = 0;
        try {
            $cqlConsulta = new TareasProgramadasController();
            $objSelect = new SelectController();

            $funcionario = Auth::user()->identificacion;
            $aprobacion = false;
            $jefe = false;

            $datosUATH = $objSelect->buscarDatosUath(Auth::user()->identificacion);

            if ($datosUATH['jefe']) {
                $aprobacion = true;
                $jefe = true;
                $datos = $cqlConsulta->funcionariosActividades($aprobacion, $funcionario, $jefe, true, true);
                $conteo_lunes = count($datos['lunes']);
                $conteo_martes = count($datos['martes']);
                $conteo_miercoles = count($datos['miercoles']);
                $conteo_jueves = count($datos['jueves']);
                $conteo_viernes = count($datos['viernes']);
                $total = $conteo_lunes + $conteo_martes + $conteo_miercoles + $conteo_jueves + $conteo_viernes;
                $array_response['actividades_pendientes_aprobar'] = $total;
            } else {

                $aprobacion = false;
                $jefe = false;
                $datos = $cqlConsulta->funcionariosActividades($aprobacion, $funcionario, $jefe, true, true);
                $conteo_lunes = count($datos['lunes']);
                $conteo_martes = count($datos['martes']);
                $conteo_miercoles = count($datos['miercoles']);
                $conteo_jueves = count($datos['jueves']);
                $conteo_viernes = count($datos['viernes']);
                $total = $conteo_lunes + $conteo_martes + $conteo_miercoles + $conteo_jueves + $conteo_viernes;
                $array_response['actividades_pendientes_registrar'] = $total;
            }
        } catch (\Exception $e) {
            $error = false;
        }

        return response()->json($array_response, 200);
    }

    /* public function consultarNotificacionesCompromisos(request $request)
    {
        $array_response['status'] = 200;
        $array_response['cantidad_avance'] = 0;
        $array_response['cantidad_archivo'] = 0;
        $array_response['cantidad_mensaje'] = 0;
        $array_response['cantidad_objetivo'] = 0;
        try {

            $cantidad_avance = Notificaciones::where('tipo', 'AVANCE')->count();
            $cantidad_archivo = Notificaciones::where('tipo', 'ARCHIVO')->count();
            $cantidad_mensaje = Notificaciones::where('tipo', 'MENSAJE')->count();
            $cantidad_objetivo = Notificaciones::where('tipo', 'OBJETIVO')->count();

            $funcionario = Auth::user()->identificacion;
            $objSelect = new SelectController();
            $datosUATH = $objSelect->buscarDatosUath(Auth::user()->identificacion);


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
            if ($tipoActual != 'TODOS') $cql_notificaciones->where('tipo', $tipoActual);

            $cql_notificaciones->get();
            return DataTables::of($cql_notificaciones)
                ->addIndexColumn()
                ->make(true);
        }
    } */
}
