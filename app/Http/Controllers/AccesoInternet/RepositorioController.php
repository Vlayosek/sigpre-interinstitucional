<?php

namespace App\Http\Controllers\AccesoInternet;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Ajax\SelectController;
use App\Core\Entities\AccesoInternet\Solicitud;
use App\Core\Entities\AccesoInternet\Solicitud_byod;

use App\Core\Entities\AccesoInternet\Transaccion;
use App\Core\Entities\AccesoInternet\Log;
use App\Core\Entities\AccesoInternet\EstadoSolicitud;
use App\Core\Entities\AccesoInternet\EstadoSolicitudByod;
use App\Core\Entities\AccesoInternet\Estado;
use Auth;
use DB;

class RepositorioController extends Controller
{
    protected $TABLA = 'solicitudes';
    public function updateSolicitudBorrador()
    {
        $arregloSolcitudes = Solicitud::select('id')->where('estado', 'PENDIENTE')
        ->where('fecha_borrador', '<=', date('Y-m-d H:i:s'))->pluck('id');
        foreach ($arregloSolcitudes as $value) {
            $cqlUpdateSolicitud = Solicitud::find($value);
            $cqlUpdateSolicitud->estado = 'ELIMINADO';
            $cqlUpdateSolicitud->eliminado = true;
            $cqlUpdateSolicitud->save();
        }
        return true;
    }
    public function crearTransaccion($descripcion, $solicitud_id, $cambios = 'CAMBIO DE ESTADO')
    {
        $cqlUpdate = Transaccion::where('estado', 'ACT')->update(['estado' => 'INA']);
        $cqlUpdateSolicitud = new Transaccion();
        $cqlUpdateSolicitud->descripcion = $descripcion;
        $cqlUpdateSolicitud->solicitud_id = $solicitud_id;
        $cqlUpdateSolicitud->fecha_inserta = date('Y-m-d H:i:s');
        $cqlUpdateSolicitud->usuario_inserta = Auth::user()->name;
        $cqlUpdateSolicitud->estado = 'ACT';
        $cqlUpdateSolicitud->cambios = $cambios;
        $cqlUpdateSolicitud->save();
    }
    public function guardarLogs($arregloCambios, $id, $tabla = null)
    {
        if (is_null($tabla))
            $tabla = $this->TABLA;
        foreach ($arregloCambios as $key => $value) {
            $cqlInserta = new Log();

            $cqlInserta->usuario_id_inserta = Auth::user()->id;
            $cqlInserta->usuario_inserta = Auth::user()->nombres;
            $cqlInserta->fecha_inserta = date('Y-m-d H:i:s');
            $cqlInserta->eliminado = false;
            $cqlInserta->tabla_id = $id;
            $cqlInserta->campo = $key;
            $cqlInserta->tabla = $tabla;
            $cqlInserta->anterior = $value['anterior'];
            $cqlInserta->actual = $value['actual'];
            $cqlInserta->save();
        }
    }
    public function verificarCambios($request, $consulta, $excepciones = [])
    {
        $arregloObservacion = [];
        if ($request['id'] != 0) {
            $excepcionAuditoria = ['id', 'eliminado', 'fecha_modifica', 'fecha_inserta', 'usuario_inserta', 'usuario_modifica'];
            $request = $request->except(array_merge($excepcionAuditoria, $excepciones));
            foreach ($request as $key => $value) {
                // dd($consulta->attributes,isset($consulta->{$key}));
                if (isset($consulta->{$key}) && !is_array($value)) {
                    if ($consulta[$key] != $value) {
                        $arregloObservacion[$key]['anterior'] = $consulta[$key];
                        $arregloObservacion[$key]['actual'] = $value;
                    }
                }
            }
        }
        return $arregloObservacion;
    }
    public function guardarEstado($id, $numero, $observacion = '', $tipo = 'NAVEGACION', $eliminado = false)
    {

        $objSelect = new SelectController();
        $historial_ = $objSelect->buscarDatosUath(Auth::user()->identificacion);
        if (!is_array($numero))  $numero = [$numero];
        if (!is_array($observacion))  $observacion = [$observacion];
        $elementoAnterior = $numero[count($numero) - 1] - 1;
        foreach ($numero as $key => $orden) {
            $cqlUpdate = $this->escogerModelo($tipo, $id)
                ->update([
                    'estado' => 'INA',
                    'fecha_modifica' => date('Y-m-d H:i:s'),
                    'usuario_modifica' => Auth::user()->nombres,
                ]);
            $cqlUpdate = Estado::where('orden', $orden)->where('eliminado', false)->where('eliminado', false)->where('tipo', $tipo)->first();
            $descripcion = $cqlUpdate->descripcion;
            if ($eliminado) {
                $cqlUpdate = $this->escogerModelo($tipo, $id)
                    ->where('descripcion', $descripcion)
                    ->where('eliminado', false)
                    ->update([
                        'fecha_modifica' => date('Y-m-d H:i:s'),
                        'usuario_modifica' => Auth::user()->nombres,
                        'historia_laboral_id' =>  $historial_['historia_laboral_id'],
                        'eliminado' =>  true
                    ]);
                if ($orden == $numero[count($numero) - 1]) {
                    $cqlUpdate = Estado::where('orden', $elementoAnterior)->where('eliminado', false)->where('eliminado', false)->first();
                    $descripcion = $cqlUpdate->descripcion;
                    $cqlUpdate = $this->escogerModelo($tipo, $id)
                        ->where('descripcion', $descripcion)
                        ->where('eliminado', false)
                        ->update([
                            'fecha_modifica' => date('Y-m-d H:i:s'),
                            'usuario_modifica' => Auth::user()->nombres,
                            'historia_laboral_id' =>  $historial_['historia_laboral_id'],
                            'estado' =>  'ACT'
                        ]);
                }
            } else {
                if ($tipo == 'NAVEGACION')
                    $cqlInserta = new EstadoSolicitud();
                else
                    $cqlInserta = new EstadoSolicitudByod();

                $cqlInserta->usuario_id_inserta = Auth::user()->identificacion;
                $cqlInserta->usuario_inserta = Auth::user()->nombres;
                $cqlInserta->fecha_inserta = date('Y-m-d H:i:s');
                $cqlInserta->descripcion = $descripcion;
                $cqlInserta->eliminado = false;
                $cqlInserta->tabla_id = $id;
                $cqlInserta->estado = 'ACT';
                $cqlInserta->historia_laboral_id = $historial_['historia_laboral_id'];
                $cqlInserta->observacion = $observacion[$key] != '' ? $observacion[$key] : $descripcion;
                $cqlInserta->save();
            }
        }
    }
    protected function escogerModelo($tipo, $id)
    {
        if ($tipo == 'NAVEGACION')
            return EstadoSolicitud::where('tabla_id', $id);
        else
            return EstadoSolicitudByod::where('tabla_id', $id);
    }
    public function consultarSeguimiento($id, $tipo = 'NAVEGACION')
    {
        $esquema = $tipo == 'NAVEGACION' ? 'estados_solicitudes' : 'estados_solicitudes_byod';
        $consultaDatosEstados = $this->escogerModelo($tipo, $id)
            ->select('descripcion', 'fecha_inserta')
            ->where('eliminado', false)
            ->orderby('id', 'asc')
            ->pluck('fecha_inserta', 'descripcion')
            ->toArray();

        $consultaDatosUsuario = $this->escogerModelo($tipo, $id)->select(
            $esquema . '.descripcion',
            $esquema . '.fecha_inserta',
            'user.name'
        )
            ->join('core.users as user', 'user.identificacion', $esquema . '.usuario_id_inserta')
            ->where($esquema . '.eliminado', false)
            ->orderby($esquema . '.id', 'asc')
            ->pluck('user.name', $esquema . '.descripcion')->toArray();

        $consultaDatosObservacion = $this->escogerModelo($tipo, $id)->select('descripcion', 'observacion')
            ->where('eliminado', false)
            ->orderby('id', 'asc')
            ->pluck('observacion', 'descripcion')
            ->toArray();

        $consultaDatos = $this->escogerModelo($tipo, $id)->select(
            $esquema . '.descripcion',
            $esquema . '.id',
            'est.visible',
            DB::RAW("CONCAT(est.visible,'|'," . $esquema . ".descripcion) as data")
        )
            ->join('sc_acceso_internet.estados as est', 'est.descripcion', $esquema . '.descripcion')
            ->where('est.tipo', $tipo)
            ->where($esquema . '.eliminado', false)
            ->orderby('est.orden', 'asc')
            ->pluck('data', $esquema . '.id')->toArray();

        $estados = Estado::select('id', 'orden', 'descripcion', 'visible', DB::RAW("CONCAT(visible,'|',descripcion) as data"))
            ->where('visible', true)
            ->where('tipo', $tipo)
            ->orderby('orden', 'asc')
            ->pluck('data', 'id')
            ->toArray();

        $array_response['status'] = 200;
        $array_response['datos_usuario'] = $consultaDatosUsuario;
        $array_response['datos'] = $consultaDatosObservacion;
        // dd($consultaDatos,$estados);
        $merge = array_merge($consultaDatos, $estados);

        $array_response['estados'] = $consultaDatosEstados;
        $array_response['conteo_estados'] = array_count_values($merge);
        // dd($array_response);
        return $array_response;
    }
    public function selectDatatable($fecha_inicio = null, $fecha_fin = null, $estado = null)
    {
        $data = Solicitud::select(
            'solicitudes.id',
            'solicitudes.historia_laboral_id',
            'solicitudes.sistema_operativo_id',
            'solicitudes.mac_address_wifi',
            'solicitudes.estado',
            'solicitudes.eliminado',
            'solicitudes.usuario_ingresa',
            'solicitudes.fecha_ingresa as fecha_solicitud',
            'solicitudes.usuario_modifica',
            'solicitudes.fecha_modifica',
            'solicitudes.justificacion_descargas',
            'solicitudes.justificacion_videos',
            'solicitudes.justificacion_redes',
            'solicitudes.identificacion',
            'solicitudes.seccion_descargas',
            'solicitudes.seccion_videos',
            'solicitudes.seccion_redes',
            'solicitudes.gestion',
            'solicitudes.codigo_solicitud',
            'solicitudes.mac_address_ethernet',
            'solicitudes.otro_descargas',
            'solicitudes.otro_redes',
            'solicitudes.fecha_borrador',
            'solicitudes.observacion_jefe',
            'solicitudes.valida_descargas',
            'solicitudes.valida_videos',
            'solicitudes.valida_redes',
            'solicitudes.fecha_aprobacionji',
            'solicitudes.fecha_atendido',
            'solicitudes.fecha_vigencia',
            'solicitudes.identificacion_jefe_aprueba',
            'solicitudes.cargo_funcionario',
            'solicitudes.area_funcionario',
            'solicitudes.revisado_soporte',
            'solicitudes.ip_address_wifi',
            'solicitudes.ip_address_ethernet',
            'solicitudes.seccion_mensajeria',
            'solicitudes.otro_mensajeria',
            'solicitudes.justificacion_mensajeria',
            'solicitudes.otro_videos',
            'solicitudes.valida_mensajeria',
            'user.nombres as funcionario',
            'est.descripcion as estado_solicitud'
        )
            ->with(['tipo_'])
            ->join('sc_acceso_internet.estados_solicitudes as est', function ($join) {
                $join->on('est.tabla_id', 'solicitudes.id')
                    ->where('est.estado', 'ACT');
            })

            ->join('core.users as user', 'user.identificacion', 'solicitudes.identificacion');


        if ($fecha_inicio != null) {
            $data = $data->whereDate('solicitudes.fecha_solicitud', '>=', $fecha_inicio)
                ->whereDate('solicitudes.fecha_solicitud', '<=', $fecha_fin);
        }
        if ($estado != null) {
            $tipo = '=';
            if (!is_array($estado)) {
                $tipo = '>';
                $estado = [$estado];
            }
            $data = $data
                ->where(function ($query) use ($estado) {
                    $query->select(DB::RAW('COUNT(est_solicitud.id)'))
                        ->from('sc_acceso_internet.estados_solicitudes as est_solicitud')
                        ->whereColumn('est_solicitud.tabla_id', 'solicitudes.id')
                        ->where('est_solicitud.eliminado', false)
                        ->whereIn('est_solicitud.descripcion', $estado);
                }, $tipo, 0);
        }
        return $data;
    }
    public function selectDatatableAprobar($fecha_inicio = null, $fecha_fin = null, $estado = null)
    {

        $data = Solicitud::select(
            'solicitudes.id as id',
            'solicitudes.fecha_solicitud as fecha_solicitud',
            'solicitudes.codigo_solicitud as codigo_solicitud',
            'solicitudes.estado as estado',
            'dato_tecnico.perfiles as perfiles',
            'persona.apellidos_nombres as funcionario',
            'parametro.descripcion as computador',
            'solicitudes.revisado_soporte',
            'est.descripcion as ultimo_estado'
        )
            ->leftjoin('sc_acceso_internet.datos_tecnicos as dato_tecnico', 'dato_tecnico.solicitud_id', 'solicitudes.id')
            ->leftjoin('sc_distributivo_.personas as persona', 'persona.identificacion', 'solicitudes.identificacion')
            ->leftjoin('core.tb_parametro as parametro', 'parametro.id', 'solicitudes.sistema_operativo_id')
            ->join('sc_acceso_internet.estados_solicitudes as est', function ($join) {
                $join->on('est.tabla_id', 'solicitudes.id')
                    ->where('est.estado', 'ACT');
            })
            ->where('solicitudes.eliminado', false);
        if ($fecha_inicio != null) {
            $data = $data->whereDate('solicitudes.fecha_solicitud', '>=', $fecha_inicio)
                ->whereDate('solicitudes.fecha_solicitud', '<=', $fecha_fin);
        }
        if ($estado != null) {
            $tipo = '=';
            if (!is_array($estado)) {
                $tipo = '>';
                $estado = [$estado];
            }
            $data = $data
                ->where(function ($query) use ($estado) {
                    $query->select(DB::RAW('COUNT(est_solicitud.id)'))
                        ->from('sc_acceso_internet.estados_solicitudes as est_solicitud')
                        ->whereColumn('est_solicitud.tabla_id', 'solicitudes.id')
                        ->where('est_solicitud.eliminado', false)
                        ->whereIn('est_solicitud.descripcion', $estado);
                }, $tipo, 0);
        }
        return $data;
    }
    public function selectDatatableAutorizar($fecha_inicio = null, $fecha_fin = null, $estado = null)
    {
        $data = Solicitud::select(
            'solicitudes.id as id',
            'solicitudes.codigo_solicitud as codigo_solicitud',
            'solicitudes.fecha_solicitud as fecha_solicitud',
            'solicitudes.estado as estado',
            'solicitudes.area_funcionario as area_funcionario',
            'solicitudes.identificacion as identificacion',
            'solicitudes.fecha_atendido as fecha_atendido',
            'parametro.descripcion as descripcion',
            'funcionario.apellidos_nombres as apellidos_nombres',
            'funcionario_.apellidos_nombres as jefe_inmediato',
            'dato_tecnico.perfiles as perfiles',
            'est.descripcion as estado_solicitud'
        )
            ->leftjoin('sc_distributivo_.personas as funcionario', 'funcionario.identificacion', 'solicitudes.identificacion')
            ->leftjoin('sc_distributivo_.personas as funcionario_', 'funcionario_.identificacion', 'solicitudes.identificacion_jefe_aprueba')
            ->leftjoin('sc_acceso_internet.datos_tecnicos as dato_tecnico', 'dato_tecnico.solicitud_id', 'solicitudes.id')
            ->leftjoin('core.tb_parametro as parametro', 'parametro.id', 'solicitudes.sistema_operativo_id')
            ->join('sc_acceso_internet.estados_solicitudes as est', function ($join) {
                $join->on('est.tabla_id', 'solicitudes.id')
                    ->where('est.estado', 'ACT');
            })
            ->join('core.users as user', 'user.identificacion', 'solicitudes.identificacion')
            ->where('solicitudes.eliminado', false)
            ->where('solicitudes.revisado_soporte', true);
        if ($fecha_inicio != null) {
            $data = $data->whereDate('solicitudes.fecha_solicitud', '>=', $fecha_inicio)
                ->whereDate('solicitudes.fecha_solicitud', '<=', $fecha_fin);
        }
        if ($estado != null) {
            $tipo = '=';
            if (!is_array($estado)) {
                $tipo = '>';
                $estado = [$estado];
            }
            $data = $data
                ->where(function ($query) use ($estado) {
                    $query->select(DB::RAW('COUNT(est_solicitud.id)'))
                        ->from('sc_acceso_internet.estados_solicitudes as est_solicitud')
                        ->whereColumn('est_solicitud.tabla_id', 'solicitudes.id')
                        ->where('est_solicitud.eliminado', false)
                        ->whereIn('est_solicitud.descripcion', $estado);
                }, $tipo, 0);
        }
        return $data;
    }
    public function selectDatatableBYOD($fecha_inicio = null, $fecha_fin = null)
    {
        $data = Solicitud_byod::select(
            'solicitudes_byod.id as id',
            'solicitudes_byod.fecha_ingresa as fecha_solicitud',
            'solicitudes_byod.estado_solicitud as estado_solicitud',
            'jefe.apellidos_nombres as funcionario'
        )
            ->leftjoin('sc_distributivo_.personas as jefe', 'jefe.identificacion', 'solicitudes_byod.identificacion_jefe')
            ->leftjoin('sc_distributivo_.personas as solicitante', 'solicitante.identificacion', 'solicitudes_byod.identificacion_solicitante')
            ->where('solicitudes_byod.eliminado', false)
            ->where('solicitudes_byod.estado', 'ACT');

        if ($fecha_inicio != null) {
            $data = $data->whereDate('solicitudes_byod.fecha_aprueba_jefe', '>=', $fecha_inicio)
                ->whereDate('solicitudes_byod.fecha_aprueba_jefe', '<=', $fecha_fin);
        }

        return $data;
    }
}
