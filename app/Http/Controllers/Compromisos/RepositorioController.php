<?php

namespace App\Http\Controllers\Compromisos;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Core\Entities\Solicitudes\Solicitud;
use Yajra\Datatables\Datatables;
use Yajra\DataTables\CollectionDataTable;
use App\Core\Entities\Compromisos\Tipo;
use App\Core\Entities\Compromisos\Estado;
use App\Core\Entities\Compromisos\EstadoPorcentaje;
use App\Core\Entities\Admin\mhr;
use App\Core\Entities\Admin\Role;
use App\Core\Entities\Compromisos\Institucion;
use App\Core\Entities\Compromisos\Compromiso;
use App\Core\Entities\Compromisos\Antecedente;
use App\Core\Entities\Compromisos\Archivo;
use App\Core\Entities\Compromisos\Mensaje;
use App\Core\Entities\Compromisos\Origen;
use App\Core\Entities\Compromisos\Temporalidad;
use App\Core\Entities\Compromisos\Objetivo;
use App\Core\Entities\Compromisos\Delegado;
use App\Core\Entities\Compromisos\Transaccion;
use App\Core\Entities\Compromisos\Responsable;
use App\Core\Entities\Compromisos\Corresponsable;
use App\Core\Entities\Compromisos\Ubicacion;
use App\Core\Entities\Compromisos\Codigo;
use App\Core\Entities\Compromisos\Avance;
use App\Core\Entities\Compromisos\Monitor;
use App\Core\Entities\Compromisos\Periodo;
use App\Core\Entities\Compromisos\Tipo_objetivo;

use App\Core\Entities\Admin\parametro_ciudad;
use App\Core\Entities\Compromisos\Notificaciones;
use App\Core\Entities\Compromisos\FechaPeriodoConsulta;
use App\Http\Controllers\Ajax\SelectController;

use Auth;
use App\User;
use DB;

class RepositorioController extends Controller
{

  public function selectAvances()
  {
    return Avance::select(
      'avances.id',
      'avances.descripcion',
      'avances.estado',
      'avances.usuario_ingresa',
      'avances.usuario_actualiza',
      'avances.created_at',
      'avances.updated_at',
      'avances.compromiso_id',
      'avances.numero',
      'avances.fecha_revisa',
      'avances.usuario_revisa',
      'avances.motivo',
      'avances.aprobado',
      'user_institucion.descripcion as emite',
      'user_institucion_r.descripcion as revisa',
      'user_emite.nombres',
      'user_revisa.nombres as usuario_leido',
      'ministro_institucion_emite.descripcion as emite_ministro',
      'ministro_institucion_rv.descripcion as revisa_ministro'
    )
      ->leftjoin('core.users as user_emite', 'user_emite.id', 'avances.usuario_ingresa')
      ->leftjoin('core.users as user_revisa', 'user_revisa.id', 'avances.usuario_revisa')
      ->leftjoin('core.instituciones as user_institucion', 'user_institucion.id', 'user_emite.institucion_id')
      ->leftjoin('core.instituciones as user_institucion_r', 'user_institucion_r.id', 'user_revisa.institucion_id')
      ->leftjoin('sc_compromisos.instituciones as ministro_institucion_emite', 'ministro_institucion_emite.ministro_usuario_id', 'avances.usuario_ingresa')
      ->leftjoin('sc_compromisos.instituciones as ministro_institucion_rv', 'ministro_institucion_rv.ministro_usuario_id', 'avances.usuario_revisa');
  }

  public  function selectArchivos()
  {
    return Archivo::select(
      'archivos.id',
      'archivos.descripcion',
      'archivos.created_at',
      'archivos.updated_at',
      'archivos.usuario_ingresa',
      'archivos.usuario_actualiza',
      'archivos.compromiso_id',
      'archivos.leido',
      'archivos.nombre',
      'archivos.estado',
      'archivos.fecha_revisa',
      'archivos.usuario_revisa',
      'user_institucion.descripcion as emite',
      'user_institucion_r.descripcion as revisa',
      'user_emite.nombres',
      'user_revisa.nombres as usuario_leido',
      'ministro_institucion_emite.descripcion as emite_ministro',
      'ministro_institucion_rv.descripcion as revisa_ministro'
    )
      ->leftjoin('core.users as user_emite', 'user_emite.id', 'archivos.usuario_ingresa')
      ->leftjoin('core.users as user_revisa', 'user_revisa.id', 'archivos.usuario_revisa')
      ->leftjoin('core.instituciones as user_institucion', 'user_institucion.id', 'user_emite.institucion_id')
      ->leftjoin('core.instituciones as user_institucion_r', 'user_institucion_r.id', 'user_revisa.institucion_id')
      ->leftjoin('sc_compromisos.instituciones as ministro_institucion_emite', 'ministro_institucion_emite.ministro_usuario_id', 'archivos.usuario_ingresa')
      ->leftjoin('sc_compromisos.instituciones as ministro_institucion_rv', 'ministro_institucion_rv.ministro_usuario_id', 'archivos.usuario_revisa')
      ->where('archivos.estado', 'ACT');
  }

  public function selectMensajes()
  {
    return Mensaje::select(
      'mensajes.id',
      'mensajes.descripcion',
      'mensajes.estado',
      'mensajes.usuario_ingresa',
      'mensajes.usuario_actualiza',
      'mensajes.created_at',
      'mensajes.created_at',
      'mensajes.updated_at',
      'mensajes.compromiso_id',
      'mensajes.leido',
      'mensajes.fecha_revisa',
      'mensajes.usuario_revisa',
      'mensajes.monitor',
      'mensajes.corresponsable',
      'mensajes.visualizacion_envio',
      'user_institucion.descripcion as emite',
      'user_institucion_r.descripcion as revisa',
      'user_emite.nombres',
      'user_revisa.nombres as usuario_leido',
      'ministro_institucion_emite.descripcion as emite_ministro',
      'ministro_institucion_rv.descripcion as revisa_ministro'
    )
      ->leftjoin('core.users as user_emite', 'user_emite.id', 'mensajes.usuario_ingresa')
      ->leftjoin('core.users as user_revisa', 'user_revisa.id', 'mensajes.usuario_revisa')
      ->leftjoin('core.instituciones as user_institucion', 'user_institucion.id', 'user_emite.institucion_id')
      ->leftjoin('core.instituciones as user_institucion_r', 'user_institucion_r.id', 'user_revisa.institucion_id')
      ->leftjoin('sc_compromisos.instituciones as ministro_institucion_emite', 'ministro_institucion_emite.ministro_usuario_id', 'mensajes.usuario_ingresa')
      ->leftjoin('sc_compromisos.instituciones as ministro_institucion_rv', 'ministro_institucion_rv.ministro_usuario_id', 'mensajes.usuario_revisa')
      ->where('mensajes.estado', 'ACT');
  }
  public function retornaOpcionesDatatableMensajes($dt, $consulta_externa = false)
  {
    return  $dt->addIndexColumn()->addColumn('institucion', function ($row) {
      return is_null($row->emite_ministro) ? (is_null($row->emite) ? '--' : $row->emite) : $row->emite_ministro;
    })
      ->addColumn('institucion_leida', function ($row) {
        if (!is_null($row->usuario_leido))
          return is_null($row->revisa_ministro) ? (is_null($row->revisa) ? '--' : $row->revisa) : $row->revisa_ministro;
        return '--';
      })
      ->addColumn('', function ($row) use ($consulta_externa) {
        $btn = '';

        if (Auth::user()->evaluarole(['MONITOR'])) {
          if ($row->leido == 'NO' && !$consulta_externa)  $btn .= '&nbsp;<td style="padding:2px"><button title="Editar" class="btn btn-primary  btn-xs"  onclick="app.editarMensaje(\'' . $row->id . '\')" ><i class="fa fa-edit"></i>&nbsp;editar</button></td>';
          $btn .= '&nbsp;<td style="padding:2px"><button title="Eliminar" class="btn btn-danger  btn-xs"  onclick="app.eliminarMensaje(\'' . $row->id . '\')" ><i class="fa fa-times"></i></button></td>';
        }
        if ($row->usuario_ingresa != Auth::user()->id) {
          if ($row->leido == 'NO') {
            $mostrar_mensaje = true;
            if($row->visualizacion_envio){
              $asignacion_responsable_corresponsable=$this->consultaResponsableCorresponsable($row->compromiso_id);
              if ($row->corresponsable&&!$asignacion_responsable_corresponsable['corresponsable_compromiso'])  $mostrar_mensaje = false;
              if ($row->monitor && Auth::user()->evaluarole(['MONITOR'])) $mostrar_mensaje = false;
              if (!$row->monitor && Auth::user()->evaluarole(['MINISTRO'])) $mostrar_mensaje = false;
              /*  if( $row->corresponsable &&
                  !$asignacion_responsable_corresponsable['corresponsable_compromiso']
              )
               $mostrar_mensaje = false;*/
            }

            if ($mostrar_mensaje) $btn = '<td style="padding:2px"><button title="Leer" class="btn btn-info  btn-xs"  onclick="app.leerMensaje(\'' . $row->id . '\')" ><i class="fa fa-envelope"></i>&nbsp;Leer Mensaje</button></td>';
          } else $btn = '<td style="padding:2px"><button title="Leido" class="btn btn-info  btn-xs" disabled><i class="fa fa-envelope-open"></i>&nbsp;Leido</button></td>';
        }
        if($btn=='') return 'Sin Acciones';
        return '<table border=0 ><tr>'. $btn.'</tr></table>' ;
      })
      ->rawColumns(['', 'institucion', 'institucion_leida']);
  }
  public function consultaResponsableCorresponsable($compromiso_id)
  {
    $responsable = Responsable::select('institucion_id')->where('compromiso_id', $compromiso_id)->where('estado', 'ACT')->pluck('institucion_id');
    $corresponsables = Corresponsable::select('institucion_corresponsable_id')->where('compromiso_id', $compromiso_id)->where('estado', 'ACT')->pluck('institucion_corresponsable_id');

    $ministros_corresponsables = Institucion::whereIn('id', $corresponsables)->pluck('ministro_usuario_id')->toArray();
    $ministros_responsables = Institucion::whereIn('id', $responsable)->pluck('ministro_usuario_id')->toArray();

    $array['responsable'] = $ministros_responsables;
    $array['corresponsable'] = $ministros_corresponsables;
    $array['corresponsable_compromiso'] = in_array(Auth::user()->id, $ministros_corresponsables);
    $array['responsable_compromiso'] = in_array(Auth::user()->id, $ministros_responsables);
    return $array;
  }
  public function retornaOpcionesDatatableArchivos($dt)
  {
    return  $dt->addIndexColumn()
      ->addColumn('institucion', function ($row) {
        return is_null($row->emite_ministro) ? (is_null($row->emite) ? '--' : $row->emite) : $row->emite_ministro;
      })
      ->addColumn('institucion_leida', function ($row) {
        if (!is_null($row->usuario_leido)) return is_null($row->revisa_ministro) ? (is_null($row->revisa) ? '--' : $row->revisa) : $row->revisa_ministro;
        return '--';
      })
      ->addColumn('', function ($row) {
        $btn = '';
        //  if ($row->usuario_ingresa != Auth::user()->id)
        $btn = '<button title="Descargar" class="btn btn-info btn-xs" onclick="app.descargarArchivo(\'' . $row->id . '\',\'' . $row->descripcion . '\',\'' . $row->leido . '\')" ><i class="fa fa-download"></i>&nbsp;Descargar</button>';
        if (Auth::user()->evaluarole(['MONITOR']))
          $btn .= '&nbsp;<button title="Eliminar" class="btn btn-danger  btn-xs"  onclick="app.eliminarArchivo(\'' . $row->id . '\')" ><i class="fa fa-times"></i></button>';

        return $btn;
      })

      ->rawColumns(['', 'institucion', 'institucion_leida']);
  }
  public function retornaOpcionesDatatableAvances($dt, $consulta_externa = false)
  {
    return  $dt->addIndexColumn()
      ->addColumn('institucion', function ($row) {
        return is_null($row->emite_ministro) ? (is_null($row->emite) ? '--' : $row->emite) : $row->emite_ministro;
      })
      ->addColumn('institucion_leida', function ($row) {
        if (!is_null($row->usuario_leido)) return is_null($row->revisa_ministro) ? (is_null($row->revisa) ? '--' : $row->revisa) : $row->revisa_ministro;
        return '--';
      })
      ->addColumn('', function ($row) {
        $btn = '<table><tr>';
        if ((is_null($row->fecha_revisa) || $row->fecha_revisa == '') && Auth::user()->evaluarole(['MONITOR'])) {
          $btn .= '<td style="padding:2px"><button class="btn btn-primary  btn-xs"  onclick="app.aprobarAvances(\'' . $row->id . '\')" title="Aprobar"><i class="fa fa-check"></i></button></td>';
          $btn .= ' <td style="padding:2px"><button class="btn btn-danger  btn-xs" data-toggle="modal" data-target="#modal-negar" onclick="app.agregarNegarAvances(\'' . $row->id . '\')" title="Rechazar"><i class="fa fa-times"></i></button></td>';
        } else {
          if (is_null($row->fecha_revisa) || $row->fecha_revisa == '')
            $btn .= 'PENDIENTE DE REVISIÓN';
          else
            $btn .= is_null($row->motivo) ? 'APROBADO' : 'RECHAZADO';
        }

        $btn .= '</tr></table>';
        return $btn;
      })

      ->rawColumns(['', 'institucion', 'institucion_leida']);
  }

  public function retornaOpcionesDatatableObjetivos($dt, $consulta_externa = false)
  {
    return  $dt->addIndexColumn()
      ->addColumn('', function ($row) use ($consulta_externa) {
        $btn = '<table><tr>';
        $label_aprobado = '<td style="padding:1px">&nbsp;<label title="Aprobar" class="label label-default  label-xs"  >APROBADO</label></td>';
        $label_rechazado = '<td style="padding:1px">&nbsp;<label title="Rechazado" class="label label-default  label-xs"  >RECHAZADO</label></td>';

        if (Auth::user()->evaluarole(['MONITOR'])) {
          $desbloquear = false;
          if (!$consulta_externa)
            $btn .= ' <td style="padding:1px"><button title="Editar" class="btn btn-primary  btn-xs"  onclick="app.editarObjetivo(\'' . $row->id . '\',\'' . $row->objetivo . '\',\'' . $row->descripcion . '\',\'' . $row->temporalidad_id . '\',\'' . $row->fecha_inicio . '\',\'' . $row->fecha_fin . '\',\'' . $row->meta . '\',\'' . $row->tipo_objetivo_id . '\',\'' . $desbloquear . '\')"><i class="fa fa-cog"></i></button></td>';
          if (is_null($row->aprobado)) {
            $btn .= '<td style="padding:1px"><button title="Eliminar" class="btn btn-danger  btn-xs"  onclick="app.eliminarObjetivo(\'' . $row->id . '\')" ><i class="fa fa-times"></i></button></td>';
            $btn .= '<td style="padding:1px"><button title="Aprobar" class="btn btn-primary  btn-xs"  onclick="app.aprobarObjetivo(\'' . $row->id . '\')" ><i class="fa fa-thumbs-up"></i></button></td>';
            $btn .= '<td style="padding:1px"><button title="Rechazar" class="btn btn-danger  btn-xs"  onclick="app.rechazarObjetivo(\'' . $row->id . '\')" ><i class="fa fa-thumbs-down"></i></button></td>';
          } else {
            if ($row->aprobado) {
              $btn .= $label_aprobado;
              $btn .= '<td style="padding:1px"><button title="Desbloquear" class="btn btn-warning  btn-xs"  onclick="app.desbloquearObjetivo(\'' . $row->id . '\')" ><i class="fa fa-unlock"></i></button></td>';
            } else
              $btn .= $label_rechazado;
          }
        }
        if (Auth::user()->evaluarole(['MINISTRO'])) {
          $desbloquear = $row->desbloquear;
          if (is_null($row->aprobado))
            $btn .= ' <td style="padding:1px"><button title="Editar" class="btn btn-primary  btn-xs"  onclick="app.editarObjetivo(\'' . $row->id . '\',\'' . $row->objetivo . '\',\'' . $row->descripcion . '\',\'' . $row->temporalidad_id . '\',\'' . $row->fecha_inicio . '\',\'' . $row->fecha_fin . '\',\'' . $row->meta . '\',\'' . $row->tipo_objetivo_id . '\',\'' . $desbloquear . '\')"><i class="fa fa-cog"></i></button></td>';
          else {
            if ($row->aprobado) {
              $btn .= $label_aprobado;
            } else
              $btn .= $label_rechazado;
          }
        }

        return $btn . '</tr></table>';
      })


      ->rawColumns(['']);
  }

  public function selectPeriodos()
  {
    return  Periodo::with(['objetivo' => function ($q) {
      $q->with('tipo_objetivo');
    }]);
  }
  public function selectObjetivos()
  {
    //return Objetivo::with(['temporalidad']);

    return Objetivo::select(
      'objetivos.id',
      'objetivos.id as numero',
      'objetivos.meta',
      'objetivos.objetivo',
      'objetivos.descripcion',
      'objetivos.fecha_inicio',
      'objetivos.fecha_fin',
      'objetivos.temporalidad_id',
      'temporalidades.descripcion as temporalidad',
      'user_emite.nombres',
      'user_institucion.descripcion as institucion',
      'tipos_objetivos.descripcion as tipo_objetivo',
      'objetivos.aprobado',
    )
      ->leftjoin('sc_compromisos.temporalidades as temporalidades', 'temporalidades.id', 'objetivos.temporalidad_id')
      ->leftjoin('core.users as user_emite', 'user_emite.id', 'objetivos.usuario_ingresa')
      ->leftjoin('core.instituciones as user_institucion', 'user_institucion.id', 'user_emite.institucion_id')
      ->leftjoin('sc_compromisos.tipos_objetivos as tipos_objetivos', 'tipos_objetivos.id', 'objetivos.tipo_objetivo_id');
  }
  public function selectHistorico()
  {
    return Transaccion::select(
      'sc_compromisos.transacciones.id',
      'sc_compromisos.transacciones.created_at as fecha',
      'sc_compromisos.transacciones.descripcion as descripcion',
      'sc_compromisos.transacciones.compromiso_id',
      'sc_compromisos.transacciones.visible',
      'sc_compromisos.transacciones.usuario_ingresa',
      'user_emite.nombres as usuario',
      'ministro_institucion_emite.descripcion as emite_ministro',
      'user_institucion.descripcion as emite',
    )
      ->join('core.users as user_emite', 'user_emite.id', 'sc_compromisos.transacciones.usuario_ingresa')
      ->leftjoin('core.instituciones as user_institucion', 'user_institucion.id', 'user_emite.institucion_id')
      ->leftjoin('sc_compromisos.instituciones as ministro_institucion_emite', 'ministro_institucion_emite.ministro_usuario_id', 'sc_compromisos.transacciones.usuario_ingresa');
  }
  public function selectAntecedente()
  {
    return Antecedente::where('estado', 'ACT');
  }
  public function restriccionesPorEstado($data, $estado = 'data', $tabla = null, $filtros_monitor = [], $datatable = 2)
  {
    ///  dd($data,$estado,$tabla,$filtros_monitor);

    /*  if($datatable==1)
        dd($data->get()->toArray(),Estado::where('abv', $estado)->pluck('id'),$estado);*/
    // INDICA ANDREA RODRIGUEZ QUE NO ES NECESARIO
    // else $data = $data->whereNotIn('compromisos.estado_porcentaje_id', EstadoPorcentaje::select('id')->whereIn('abv', $this->ESTADOS_NO_INICIALES)->pluck('id'));
    if ($estado != "data" && $estado != "ACT") {
      if ($tabla != "1")  $data = $data->whereIn('compromisos.estado_id', Estado::where('abv', $estado)->pluck('id'));
      else  $data = $data->whereIn('compromisos.estado_porcentaje_id', EstadoPorcentaje::where('abv', $estado)->pluck('id'));
    }
    if (!Auth::user()->evaluarole(['MINISTRO'])) {
      if ($filtros_monitor != []) {
        //  if ($filtros_monitor['temporales'] == "false") $data = $data->where('r.estado', 'ACT');
        if ($filtros_monitor['asignaciones'] == "true" && Auth::user()->evaluarole(['MONITOR']))
          $data = $data->where('compromisos.monitor_id', Auth::user()->id);

        if ($filtros_monitor['temporales'] == "true") $data = $data->whereNull('compromisos.codigo');
        if ($filtros_monitor['temporales'] == "false") $data = $data->whereNotNull('compromisos.codigo');
        if ($filtros_monitor['pendientes'] == "true"){
          $obj = new \StdClass();
          $obj->institucion_id_busqueda = null;
          $obj->gabinete_id_busqueda = null;
          $obj->monitor_busqueda = $filtros_monitor['asignaciones'] != "false"?Auth::user()->id:null;
          $array_response = (new BusquedasAvanzadasController())->consultaEstadosBusqueda($obj,clone $data->pluck('id'));
          $data = $data->whereIn('compromisos.id', $array_response['compromisos_intervinientes']);

        }
       // if ($filtros_monitor['pendientes'] == "true") $data = $data->where('compromisos.pendientes', '>', 0);
      }
    }
    //->where('r.estado', 'ACT');
    return $data;
  }
  public function restriccionesPorRol($data, $corresponsable = false)
  {
    $datos = $data;
    if (Auth::user()->evaluarole(['MINISTRO'])) {
      if (!$corresponsable) {
        $institucion_id = Responsable::select('compromiso_id')
          ->where('institucion_id', $this->consultaInstitucionporMinistro(Auth::user()->id))
          ->where('estado', 'ACT')
          ->pluck('compromiso_id');
      } else {
        $institucion_id = Corresponsable::select('compromiso_id')
          ->where('institucion_corresponsable_id', $this->consultaInstitucionporMinistro(Auth::user()->id))
          ->where('estado', 'ACT')
          ->pluck('compromiso_id');
      }

      $data = $data->whereNotNull('compromisos.codigo')->whereIn('compromisos.id', $institucion_id);
      $datos = $data;
    }
    return $datos;
  }
  public function selectParametroCiudad($verificacion = 'PROVINCIA', $especificas = null)
  {
    $consultaProvincias = parametro_ciudad::select('id', 'parametro_id', 'descripcion')
      ->where('estado', 'A')
      ->where('verificacion', 'PROVINCIA');
    $consultaCantones = parametro_ciudad::select('id', 'parametro_id', 'descripcion')
      ->where('estado', 'A')
      ->where('verificacion', 'CANTON');
    $consultaParroquias = parametro_ciudad::select('id', 'parametro_id', 'descripcion')
      ->where('estado', 'A')
      ->where('verificacion', 'PARROQUIA');
    $especificas = is_array($especificas) ? $especificas : [$especificas];
    if ($verificacion == 'PROVINCIA') {
      if (!is_null($especificas)) {
        $consultaProvincias = $consultaProvincias->whereIn('id', $especificas);
        $provincias = (clone $consultaProvincias)->pluck('id');
        $consultaCantones = $consultaCantones->whereIn('parametro_id', $provincias);
        $consultaParroquias = $consultaParroquias->whereIn('parametro_id', (clone $consultaCantones)->pluck('id'));
      }
    }
    if ($verificacion == 'CANTON') {
      if (!is_null($especificas)) {
        $consultaCantones = $consultaCantones->whereIn('id', $especificas);
        $consultaParroquias = $consultaParroquias->whereIn('parametro_id', (clone $consultaCantones)->pluck('id'));
        $consultaProvincias = $consultaProvincias->whereIn('id', (clone $consultaCantones)->pluck('parametro_id'));
      }
    }
    if ($verificacion == 'PARROQUIA') {
      if (!is_null($especificas)) {
        $consultaParroquias = $consultaParroquias->whereIn('id', $especificas);
        $consultaCantones = $consultaCantones->whereIn('id', (clone $consultaParroquias)->pluck('parametro_id'));
        $consultaProvincias = $consultaProvincias->whereIn('id', (clone $consultaCantones)->pluck('parametro_id'));
      }
    }
    $consultaDatos = $consultaProvincias->union($consultaCantones)->union($consultaParroquias)->distinct('id')->pluck('id');
    return $consultaDatos;
  }
  public function selectVerificacion($vericacion = 'NACIONAL', $parametro_id = false, $especificos = null, $relacional = true)
  {
    if ($vericacion == 'NACIONAL') {
      $data = Ubicacion::select('pc.id as id')
        ->whereColumn('ubicaciones.compromiso_id', 'compromisos.id')
        ->join('core.parametro_ciudad as pc', 'pc.id', 'ubicaciones.parametro_id')
        ->where('pc.descripcion', $vericacion)
        ->orderBy('compromisos.id');
      return $data;
    }
    if ($relacional) {
      if (!is_null($especificos)) {
        if ($parametro_id)   $data =   parametro_ciudad::select('parametro_ciudad.parametro_id as id');
        else  $data =   parametro_ciudad::select('parametro_ciudad.id as id');

        $data = $data->where('parametro_ciudad.verificacion', $vericacion)
          ->whereIn('parametro_ciudad.id', $especificos);
      } else {
        if ($parametro_id)   $data =   Ubicacion::select('pc.parametro_id as id');
        else  $data =   Ubicacion::select('pc.id as id');

        $data = $data->whereColumn('compromisos.id', 'ubicaciones.compromiso_id')
          ->join('core.parametro_ciudad as pc', 'pc.id', 'ubicaciones.parametro_id')
          ->where('pc.verificacion', $vericacion)
          ->orderBy('compromisos.id');
      }
    } else {
      if ($parametro_id)   $data =   parametro_ciudad::select('parametro_ciudad.parametro_id as id');
      else  $data =   parametro_ciudad::select('parametro_ciudad.id as id');
      $data = $data->where('parametro_ciudad.verificacion', $vericacion);
      if (!is_null($especificos))
        $data = $data->whereIn('parametro_ciudad.id', $especificos);
    }

    return $data;
  }
  public function consultaUbicacionesDatatable()
  {
    $selectVerficacion = $this->selectVerificacion('CANTON');
    $selectParroquias = $this->selectVerificacion('PARROQUIA', true);
    $selectCantones = $this->selectVerificacion('CANTON', true);
    $array['union_cantones'] =  $selectVerficacion->union($selectParroquias);
    $array['union_cantones_clase'] = clone $array['union_cantones'];
    $array['union_parroquias'] = $this->selectVerificacion('PARROQUIA');;  //FC
    $selectVerficacion = $this->selectVerificacion('PROVINCIA');
    $selectNacional = $this->selectVerificacion();
    $selectCantonesEspecificos = $this->selectVerificacion('CANTON', true, $array['union_cantones']);
    //   dd($selectVerficacion,$selectCantones,$selectCantonesEspecificos);
    $array['union_provincias'] =  $selectVerficacion->union($selectCantonesEspecificos)->union($selectNacional);
    //Para visualizar las parroquias FCago23
    $array['union_parroquias'] = parametro_ciudad::select(DB::RAW("REPLACE(REGEXP_REPLACE(regexp_replace(ARRAY_TO_STRING(ARRAY_AGG(parametro_ciudad.descripcion), ','), '[^a-zA-Z0-9]+','')  ,'[[:digit:]]','','g'),'-','')"))
      ->whereIn(
        'parametro_ciudad.id',
        $array['union_parroquias']
      );
    //
    $array['union_cantones'] = parametro_ciudad::select(DB::RAW("REPLACE(REGEXP_REPLACE(regexp_replace(ARRAY_TO_STRING(ARRAY_AGG(parametro_ciudad.descripcion), ','), '[^a-zA-Z0-9]+','')  ,'[[:digit:]]','','g'),'-','')"))
      ->whereIn(
        'parametro_ciudad.id',
        $array['union_cantones']
      );

    $array['union_cantones_clase'] = parametro_ciudad::select(DB::RAW("ARRAY_TO_STRING(ARRAY_AGG(parametro_ciudad.clase), ',.')"))
      ->whereIn(
        'parametro_ciudad.id',
        $array['union_cantones_clase']
      );
    $array['union_provincias'] = parametro_ciudad::select(DB::RAW("REPLACE(REGEXP_REPLACE(regexp_replace(ARRAY_TO_STRING(ARRAY_AGG(parametro_ciudad.descripcion), ','), '[^a-zA-Z0-9]+','')  ,'[[:digit:]]','','g'),'-','')"))
      ->whereIn(
        'parametro_ciudad.id',
        $array['union_provincias']
      );
    return $array;
  }
  public function selectConsultaCompromisos($conteo = false, $exportar = false, $temporales = "false")
  {

    $data = Compromiso::where('compromisos.estado', 'ACT');
    /*   if ($temporales == 'false')
            $data =  $data->leftjoin('sc_compromisos.responsables as r', function ($join) {
                $join->on('r.compromiso_id', 'compromisos.id')
                    ->where('r.estado', 'ACT');
            });
        else {*/
    $data = $data->leftjoin('sc_compromisos.responsables as r', function ($join) {
      $join->on('r.compromiso_id', 'compromisos.id')
        ->where('r.estado', 'ACT');
    });
    //  }


    $consultaUbicacion = $this->consultaUbicacionesDatatable();

    if (!$conteo) {
      if ($exportar) {
        $data = $data->select(
          DB::RAW("CASE WHEN compromisos.codigo IS NOT NULL THEN REPLACE(compromisos.codigo,' ','') ELSE compromisos.id::varchar(15) END as reg_"),
          'compromisos.nombre_compromiso as nombre_',
          'gabinete.descripcion as gabinete_',
          'institucion.descripcion as institucion_',
          'compromisos.fecha_inicio as fecha_inicio_',
          'compromisos.fecha_fin as fecha_fin_',
          'estado_porcentaje.descripcion as estado_porcentaje_',
          'estado.descripcion as estado_',
          'compromisos.fecha_reporte',
          'tipo.descripcion as tipo_',
        );
      } else {
        $data = $data->select(
          'compromisos.created_at',
          'compromisos.id  as id',
          DB::RAW("REPLACE(compromisos.codigo,' ','') as codigo_"),
          'compromisos.codigo',
          DB::RAW("CASE WHEN compromisos.codigo IS NOT NULL THEN REPLACE(compromisos.codigo,' ','') ELSE compromisos.id::varchar(15) END as reg_"),
          'compromisos.nombre_compromiso as nombre_',
          'gabinete.descripcion as gabinete_',
          'gabinete.id as gabinete_id',
          'institucion.id as institucion_id',
          'institucion.descripcion as institucion_',
          'compromisos.fecha_inicio as fecha_inicio_',
          'compromisos.fecha_fin as fecha_fin_',
          'estado_porcentaje.descripcion as estado_porcentaje_',
          'estado.descripcion as estado_',
          'compromisos.fecha_reporte',
          'compromisos.avance as avance_compromiso_',
          'tipo.descripcion as tipo_',
          'compromisos.notas_compromiso as notas_compromiso_',
        )
          ->addSelect(
            [
              'union_cantones_clase' => $consultaUbicacion['union_cantones_clase']
            ]
          );
      }

      $data = $data->leftjoin('sc_compromisos.instituciones as institucion', 'institucion.id', 'r.institucion_id')
        ->leftjoin('sc_compromisos.instituciones as gabinete', 'gabinete.id', 'institucion.institucion_id')
        ->leftjoin('sc_compromisos.tipos_compromiso as tipo', 'tipo.id', 'compromisos.tipo_compromiso_id')
        ->leftjoin('sc_compromisos.estados as estado', 'estado.id', 'compromisos.estado_id')
        ->leftjoin('sc_compromisos.estados_porcentaje as estado_porcentaje', 'estado_porcentaje.id', 'compromisos.estado_porcentaje_id');


      $data = $data
        //->leftjoin('sc_compromisos.ubicaciones as ubicaciones', 'ubicaciones.compromiso_id', 'compromisos.id')
        ->addSelect(
          [
            'corresponsables' =>
            Corresponsable::select(DB::RAW("array_to_string(ARRAY_AGG(i.descripcion),',')"))
              ->whereColumn('compromisos.id', 'corresponsables.compromiso_id')
	      ->join('sc_compromisos.instituciones as i', 'i.id', 'corresponsables.institucion_corresponsable_id')
	      ->where('corresponsables.estado', 'ACT') //incidencia 13jul23
          ]
        )
        ->addSelect(
          [
            'provincias' => $consultaUbicacion['union_provincias']
          ]
        )
        ->addSelect(
          [
            'cantones' => $consultaUbicacion['union_cantones']
          ]
        )//FCago23
        ->addSelect(
          [
            'parroquias' => $consultaUbicacion['union_parroquias']
          ]
        )//
        ->addSelect(
          [
            'fecha_antecedente' =>
            Antecedente::select('antecedentes.fecha_antecedente')
              ->whereColumn('antecedentes.compromiso_id',  'compromisos.id')
              ->where('antecedentes.estado', 'ACT')->orderby('antecedentes.id', 'desc')->limit(1)
          ]
        )
        ->addSelect(
          [
            'descripcion' =>
            Antecedente::select('antecedentes.descripcion')
              ->whereColumn('antecedentes.compromiso_id',  'compromisos.id')
              ->where('antecedentes.estado', 'ACT')->orderby('antecedentes.id', 'desc')->limit(1)
          ]
        )
        ->addSelect(
          [
            'ultimo_avance_aprobado' =>
            Avance::select('avances.descripcion')
              ->whereColumn('compromisos.id', 'avances.compromiso_id')
              ->orderBy('avances.fecha_revisa', 'desc')
              ->limit(1)
          ]
        )
        ->addSelect(
          [
            'fecha_revisa' =>
            Avance::select(DB::RAW("to_char(avances.fecha_revisa,'yyyy-mm-dd')"))
              ->whereColumn('compromisos.id', 'avances.compromiso_id')
              ->orderBy('avances.fecha_revisa', 'desc')
              ->limit(1)
          ]
        )
        ->addSelect(
          [
            'fecha_cumplido' =>
            DB::table('sc_compromisos.compromisos as comp')->select(DB::RAW("CASE WHEN estado_porcentaje_.descripcion ='CUMPLIDO' OR estado_porcentaje_.descripcion ='CERRADO' THEN compromisos.updated_at::varchar(10) ELSE '--' END as fecha_cumplido"))
              ->whereColumn('comp.id', 'compromisos.id')
              ->leftjoin('sc_compromisos.estados_porcentaje as estado_porcentaje_', 'estado_porcentaje_.id', 'compromisos.estado_porcentaje_id')
              ->limit(1)

          ]
        )
        ->addSelect(
          [
            'monitor' =>
            DB::table('core.users as user')
              ->select('user.nombres')
              ->whereColumn('user.id', 'compromisos.monitor_id')
              ->limit(1)

          ]
        );
    } else {
      $data = $data->select(
        'compromisos.id  as id',
      );
    }
    return $data;
  }


  public function selectConsultaCompromisosCorresponsable()
  {
    return Compromiso::with([
      'tipo',
      'estado',
      'estado_porcentaje',
      'latest_responsable' => function ($q) {
        $q->with(['institucion' => function ($q1) {
          $q1->with('gabinete');
        }])->where('estado', 'ACT');
      },
      'corresponsables' => function ($q) {
        $q->with(['institucion'])->where('estado', 'ACT');
      }
    ]);
  }
  public function notificarUsuario($usuario, $descripcion)
  {

    if (config('app.NOTIFICAR')) {
      $cqlArregloUsuarios = User::select('email')
        ->whereIn('id', $usuario)
        ->pluck('email');

      foreach ($cqlArregloUsuarios as $for) {
        try {
          $objSelect = new SelectController();
          $objSelect->NotificarSinRegistro($descripcion, $for);
        } catch (\Exception $ex) {
          echo $ex->getMessage();
        }
      }
    }

    return true;
  }
  public function cabeceraExcel()
  {
    return [
      'Código',
      'Nombre',
      'Gabinete',
      'Responsable',
      'Fecha Inicio',
      'Fecha Fin',
      'Estado de Gestión',
      'Estado del Compromiso',
      'Fecha de Reporte',
      'Tipo',
      'Corresponsables',
      'Provincia',
      'Cantón',
      'Fecha Antecedente',
      'Descripción Antecedente',
      'Ultimo Avance Realizado',
      'Fecha Ultimo Avance',
      'Fecha Cumplido',
      'Monitor'

    ];
  }
  public function consultaUsuariosRol($tipo, $institucion_id = null)
  {
    $role = Role::where('name', $tipo)->get()->first();
    $role = $role != null ? $role->id : 0;

    $model = mhr::select('model_id')
      ->where('role_id', $role)
      ->pluck('model_id');

    if (!is_null($institucion_id)) {
      $model = Institucion::select('ministro_usuario_id')
        ->where('id', $institucion_id)
        ->whereIn('ministro_usuario_id', $model)
        ->pluck('ministro_usuario_id');
    }
    return $model;
  }
  public function variablesGlobales($corresponsable_global)
  {
    $tipos = Tipo::pluck('descripcion', 'id');
    $gabinete = Institucion::where('nivel', 1)->pluck('descripcion', 'id');
    $estados = Estado::where('eliminado', false)->pluck('descripcion', 'id');
    $estados_porcentaje = EstadoPorcentaje::pluck('descripcion', 'id');
    $origenes = Origen::pluck('descripcion', 'id');
    $temporalidades = Temporalidad::where('eliminado', false)->orderby('id', 'asc')->pluck('descripcion', 'id');
    $tipos_objetivos = Tipo_objetivo::orderby('id', 'asc')->where('eliminado', false)->pluck('descripcion', 'id');
    $delegados = Delegado::pluck('nombres', 'id');
    $monitores = User::select(["id", "nombres"])
      ->whereIn('id', Monitor::select('usuario_id')
        ->pluck('usuario_id'))
      ->get()->pluck('nombres', 'id');
    $provincias_compromisos = parametro_ciudad::select('id', 'descripcion')->where('verificacion', 'PROVINCIA')
      ->where('estado', 'A')
      ->pluck('descripcion', 'id');
    $corresponsable = $corresponsable_global;
    $fecha_inicio = FechaPeriodoConsulta::where('estado', 'ACT')->first();
    $fecha_inicio = is_null($fecha_inicio) ? date('Y-m-d') : $fecha_inicio->fecha_inicio;
    return [
      'tipos' => $tipos,
      'estados' => $estados,
      'gabinete' => $gabinete,
      'origenes' => $origenes,
      'temporalidades' => $temporalidades,
      'monitores' => $monitores,
      'delegados' => $delegados,
      'tipos_objetivos' => $tipos_objetivos,
      'estados_porcentaje' => $estados_porcentaje,
      'corresponsable' => $corresponsable,
      'provincias_compromisos' => $provincias_compromisos,
      'fecha_inicio' => $fecha_inicio,

    ];
  }
  public function nuevaTransaccion($descripcion, $compromiso_id, $query = true, $visible = true)
  {
    /* try {
            DB::connection('pgsql_presidencia')->beginTransaction();
*/
    $compromisoAfectado = $compromiso_id;
    if ($query) {
      $compromisoAfectado = Compromiso::find($compromiso_id);
      $compromisoAfectado = $compromisoAfectado->codigo != null && $compromisoAfectado->codigo != '' ? $compromisoAfectado->codigo : ('Temporal ' . $compromisoAfectado->id);
    }

    $hoy = date("Y-m-d H:i:s");

    $cql1 = new Transaccion();
    $cql1->descripcion = $descripcion . ', al compromiso ' . $compromisoAfectado;
    $cql1->compromiso_id = $compromiso_id;
    $cql1->created_at = $hoy;
    $cql1->visible = $visible == true || $visible == 1 ? "true" : "false";
    $cql1->estado = 'ACT';
    $cql1->usuario_ingresa = is_null(Auth::user()) ? 0 : Auth::user()->id;
    $cql1->save();

    return $cql1->id;
    //   DB::connection('pgsql_presidencia')->commit();
    /*    } catch (\Exception $e) {
            DB::connection('pgsql_presidencia')->rollBack();

            return 0;
        }*/
  }
  public function validarCompromiso($compromiso, $cambioResponsable = 0, $cambioCorresponsable = 0)
  {
    $array['status'] = 300;
    $array['message'] = 0;
    if (is_null(Auth::user())) return $array;

    $hoy = date("Y-m-d H:i:s");
    /*   try{
            DB::connection('pgsql_presidencia')->beginTransaction();
*/
    if ($compromiso->id == 0) {
      $cqlCompromiso = new Compromiso();
      $cqlCompromiso->usuario_ingresa = Auth::user()->id;
      $cqlCompromiso->created_at = $hoy;
      if (
        !is_null($compromiso->fecha_reporte)
        && $compromiso->fecha_reporte != "null"
      ) {
        if (
          !is_null($compromiso->fecha_fin)
          && $compromiso->fecha_fin != "null"
        ) {
          $cqlCompromiso->fill($compromiso->only(
            'tipo_compromiso_id',
            'origen_id',
            'fecha_inicio',
            'fecha_fin',
            'nombre_compromiso',
            'detalle_compromiso',
            'avance_compromiso',
            'notas_compromiso',
            'estado_porcentaje_id',
            'estado_id',
            'cumplimiento',
            'avance',
            'monitor_id',
            'avance_id',
            'fecha_reporte'
          ))->save();
        } else {
          $cqlCompromiso->fill($compromiso->only(
            'tipo_compromiso_id',
            'origen_id',
            'fecha_inicio',
            'nombre_compromiso',
            'detalle_compromiso',
            'avance_compromiso',
            'notas_compromiso',
            'estado_porcentaje_id',
            'estado_id',
            'cumplimiento',
            'avance',
            'monitor_id',
            'avance_id',
            'fecha_reporte'
          ))->save();
        }
      } else {
        if (
          !is_null($compromiso->fecha_fin)
          && $compromiso->fecha_fin != "null"
        ) {
          $cqlCompromiso->fill($compromiso->only(
            'tipo_compromiso_id',
            'origen_id',
            'fecha_inicio',
            'fecha_fin',
            'nombre_compromiso',
            'detalle_compromiso',
            'avance_compromiso',
            'notas_compromiso',
            'estado_porcentaje_id',
            'estado_id',
            'cumplimiento',
            'avance',
            'monitor_id',
            'avance_id'
          ))->save();
        } else {
          $cqlCompromiso->fill($compromiso->only(
            'tipo_compromiso_id',
            'origen_id',
            'fecha_inicio',
            'nombre_compromiso',
            'detalle_compromiso',
            'avance_compromiso',
            'notas_compromiso',
            'estado_porcentaje_id',
            'estado_id',
            'cumplimiento',
            'avance',
            'monitor_id',
            'avance_id'
          ))->save();
        }
      }
    } else {
      $cqlCompromiso = Compromiso::find($compromiso->id);

      $cq_3 = EstadoPorcentaje::where('abv', 'CER')->get()->first()->id;
      $cq_2 = EstadoPorcentaje::where('abv', 'CUM')->get()->first()->id;

      if (
        $compromiso->estado_porcentaje_id == $cq_3 ||
        $compromiso->estado_porcentaje_id == $cq_2
      ) {
        $cqlCompromiso->cerrado = "true";
      } else {
        $cqlCompromiso->cerrado = "false";
      }

      $cqlCompromiso->usuario_actualiza = is_null(Auth::user()) ? 0 : Auth::user()->id;
      $cqlCompromiso->updated_at = $hoy;
      $fecha_reporte_entrante = ($compromiso->fecha_reporte != "null" && $compromiso->fecha_reporte != null ? $compromiso->fecha_reporte : null);
      if ($fecha_reporte_entrante != $cqlCompromiso->fecha_reporte) {
        $cqlCompromiso->fecha_reporte =
          $this->comparaCampos(
            ($compromiso->fecha_reporte != "null" && $compromiso->fecha_reporte != null ? $compromiso->fecha_reporte : null),
            $cqlCompromiso->fecha_reporte,
            $cqlCompromiso->id,
            ('Fecha de Reporte del Compromiso de: ' . $cqlCompromiso->fecha_reporte . ' al, ' . $compromiso->fecha_reporte),
            true
          );
      }

      $cqlCompromiso->tipo_compromiso_id =
        $this->comparaCampos(
          $compromiso->tipo_compromiso_id,
          $cqlCompromiso->tipo_compromiso_id,
          $cqlCompromiso->id,
          ('Tipo del Compromiso de: ' . Tipo::find($cqlCompromiso->tipo_compromiso_id)->descripcion . ' al, ' . Tipo::find($compromiso->tipo_compromiso_id)->descripcion),
          true
        );
      $cqlCompromiso->origen_id = $this->comparaCampos(
        $compromiso->origen_id,
        $cqlCompromiso->origen_id,
        $cqlCompromiso->id,
        ('Origen de: ' .
          Origen::find($cqlCompromiso->origen_id)->descripcion .
          ' al, ' .
          Origen::find($compromiso->origen_id)->descripcion
        ),
        true
      );
      $cqlCompromiso->fecha_inicio = $this->comparaCampos(
        $compromiso->fecha_inicio,
        $cqlCompromiso->fecha_inicio,
        $cqlCompromiso->id,
        'Fecha de Inicio del Compromiso',
        false
      );
      $cqlCompromiso->fecha_fin = $this->comparaCampos(
        $compromiso->fecha_fin,
        $cqlCompromiso->fecha_fin,
        $cqlCompromiso->id,
        'Fecha Fin del Compromiso',
        false
      );
      $cqlCompromiso->nombre_compromiso = $this->comparaCampos(
        $compromiso->nombre_compromiso,
        $cqlCompromiso->nombre_compromiso,
        $cqlCompromiso->id,
        'Nombre del Compromiso',
        false
      );
      $cqlCompromiso->detalle_compromiso = $this->comparaCampos(
        $compromiso->detalle_compromiso,
        $cqlCompromiso->detalle_compromiso,
        $cqlCompromiso->id,
        'Detalle del Compromiso',
        false
      );
      $cqlCompromiso->avance_compromiso = $this->comparaCampos(
        $compromiso->avance_compromiso,
        $cqlCompromiso->avance_compromiso,
        $cqlCompromiso->id,
        'Ultimo Avance del Compromiso',
        false
      );
      $cqlCompromiso->notas_compromiso = $this->comparaCampos(

        $compromiso->notas_compromiso,
        $cqlCompromiso->notas_compromiso,
        $cqlCompromiso->id,
        'Notas del Compromiso',
        false,
        false
      );
      if ($cqlCompromiso->monitor_id != 0 && $cqlCompromiso->monitor_id != null) {

        $anterior = User::where('id', $cqlCompromiso->monitor_id)->first();
        $anterior = is_null($anterior) ? '' : $anterior->nombres;

        $actual = User::where('id', $compromiso->monitor_id)->first();
        $actual = is_null($actual) ? '' : $actual->nombres;

        $cqlCompromiso->monitor_id = $this->comparaCampos(
          $compromiso->monitor_id,
          $cqlCompromiso->monitor_id,
          $cqlCompromiso->id,
          ('Monitor del Compromiso de: ' .
            $anterior .
            ' al, ' .
            $actual),
          true
        );
      }
      $propuesto = EstadoPorcentaje::find($compromiso->estado_porcentaje_id)->descripcion;
      $propuesto = is_null($propuesto) ? '' : $propuesto;
      $anterior = EstadoPorcentaje::find($cqlCompromiso->estado_porcentaje_id)->descripcion;
      $anterior = is_null($anterior) ? '' : $anterior;

      $cqlCompromiso->estado_porcentaje_id =
        $this->comparaCampos(
          $propuesto,
          $anterior,
          $cqlCompromiso->id,
          ('Estado de Gestión de ' . $anterior . ' al ' . $propuesto . ''),
          true,
          true,
          true,
          true
        );
      $propuesto = Estado::find($compromiso->estado_id)->descripcion;
      $propuesto = is_null($propuesto) ? '' : $propuesto;

      $anterior = Estado::find($cqlCompromiso->estado_id)->descripcion;
      $anterior = is_null($anterior) ? '' : $anterior;

      $cqlCompromiso->estado_id =
        $this->comparaCampos(
          $propuesto,
          $anterior,
          $cqlCompromiso->id,
          ('Estado del Compromiso de ' . $anterior . ' al ' . $propuesto),
          true,
          true,
          true,
          true
        );
      $cqlCompromiso->cumplimiento =
        $this->comparaCampos(
          $compromiso->cumplimiento,
          $cqlCompromiso->cumplimiento,
          $cqlCompromiso->id,
          'Porcentaje de Cumplimiento',
          false
        );
      $cqlCompromiso->avance =
        $this->comparaCampos(
          $compromiso->avance,
          $cqlCompromiso->avance,
          $cqlCompromiso->id,
          'Porcentaje de Avance',
          false
  );
            $compromiso['fecha_fin'] = $compromiso->fecha_fin=="null"?null:$compromiso->fecha_fin;
      if ($compromiso->avance_id == "null" || $compromiso->avance_id == "" || $compromiso->avance_id == null)
        $cqlCompromiso->fill($compromiso->only([
          'tipo_compromiso_id',
          'origen_id',
          'fecha_inicio',
          'fecha_fin',
          'nombre_compromiso',
          'detalle_compromiso',
          'avance_compromiso',
          'notas_compromiso',
          'estado_porcentaje_id',
          'estado_id',
          'cumplimiento',
          'avance',
          'monitor_id'
        ]))->save();
      else
        $cqlCompromiso->fill($compromiso->only(
          'tipo_compromiso_id',
          'origen_id',
          'fecha_inicio',
          'fecha_fin',
          'nombre_compromiso',
          'detalle_compromiso',
          'avance_compromiso',
          'notas_compromiso',
          'estado_porcentaje_id',
          'estado_id',
          'cumplimiento',
          'avance',
          'monitor_id',
          'avance_id'
        ))->save();
    }
    /*if(is_null($compromiso->fecha_fin)){
      $cqlCompromiso->fecha_fin=$compromiso->fecha_fin;
      $cqlCompromiso->save();
    }*/
    if (!is_null($compromiso->fecha_fin) && $compromiso->fecha_fin != "null") $cqlCompromiso->fecha_fin = $compromiso->fecha_fin;
    $cqlCompromiso->save();

    if ($compromiso->id == 0) {
      $descripcion = 'Se creo el compromiso ';
      $error = $this->nuevaTransaccion($descripcion, $cqlCompromiso->id);
      if ($error == 0)   throw new \Exception("Error al generar la transacción de crear el compromiso");
    }

    //if(($cambioResponsable!=0||$compromiso->id==0)&&$compromiso->responsable_id!=null){

    if (($compromiso->id == 0 || ($compromiso->institucion_id != "null" && $compromiso->institucion_id != null)) && $compromiso->modifica_responsable == "true") {
      Responsable::where('compromiso_id', $compromiso->id)
        ->update([
          'estado' => 'INA',
          'usuario_actualiza' => is_null(Auth::user()) ? 0 : Auth::user()->id
        ]);

      $cqlResposanble = new Responsable();
      $cqlResposanble->institucion_id = $compromiso->institucion_id;
      $cqlResposanble->compromiso_id = $cqlCompromiso->id;
      $cqlResposanble->usuario_ingresa = is_null(Auth::user()) ? 0 : Auth::user()->id;
      $cqlResposanble->estado = 'ACT';
      $cqlResposanble->save();


      $descripcion = 'Se Agrego Nuevo Responsable ' . Institucion::find($compromiso->institucion_id)->descripcion;
      $error = $this->nuevaTransaccion($descripcion, $cqlCompromiso->id);
      if ($error == 0)   throw new \Exception("Error al generar la transacción de crear el responsable");
    }
    $cqlCompromiso->estado = 'ACT';
    $cqlCompromiso->pendientes = 0;
    $cqlCompromiso->save();


    if (($compromiso->id == 0 ||
      ($compromiso->instituciones_corresponsables != "null"
        && $compromiso->instituciones_corresponsables != null
        && $compromiso->instituciones_corresponsables != []
      )) && $compromiso->modifica_corresponsable == "true") {
      $cadena = "";
      $arregloCorresponsables = [];
      if (is_array($compromiso->instituciones_corresponsables)) {
        $arregloCorresponsables[0] = $compromiso->instituciones_corresponsables;
        $pos = strpos($arregloCorresponsables[0], ",");
        if ($pos)
          $arregloCorresponsables = explode(",", $arregloCorresponsables[0]);
        else
          $arregloCorresponsables[0] = $arregloCorresponsables[0];
      } else {
        $pos = strpos($compromiso->instituciones_corresponsables, ",");
        if ($pos)
          $arregloCorresponsables = explode(",", $compromiso->instituciones_corresponsables);
        else
          $arregloCorresponsables[0] = $compromiso->instituciones_corresponsables;
      }

      $usuarios = Institucion::select('descripcion')
        ->whereIn('id', $arregloCorresponsables)
        ->pluck('descripcion')->toArray();

      foreach ($arregloCorresponsables as $key => $value) {
        if ($key == 0) {
          Corresponsable::where('compromiso_id', $compromiso->id)
            ->update([
              'estado' => 'INA',
              'usuario_actualiza' => is_null(Auth::user()) ? 0 : Auth::user()->id
            ]);
        }
        $cqlResposanble = new Corresponsable();
        if ($value != null && $value != "") {
          $cqlResposanble->institucion_corresponsable_id = $value;
          $cqlResposanble->compromiso_id = $cqlCompromiso->id;
          $cqlResposanble->estado = 'ACT';
          $cqlResposanble->usuario_ingresa = is_null(Auth::user()) ? 0 : Auth::user()->id;
          $cqlResposanble->save();
        }
      }

      $cadena = implode(',', $usuarios);
      if ($cadena != [] && $cadena != null) {
        $descripcion = 'Se Agrego Nuevo Corresponsable ' . $cadena;
        $error = $this->nuevaTransaccion($descripcion, $cqlCompromiso->id);
        if ($error == 0)   throw new \Exception("Error al generar la transacción de crear el corresponsable");
        ///////NOTIFICAR

      }
    }
    $array['status'] = 200;
    $array['message'] = $cqlCompromiso->id;
    /*      DB::connection('pgsql_presidencia')->commit();

        }catch(\Exception $e){
            DB::connection('pgsql_presidencia')->rollBack();

            $array['status']=300;
            $array['message']=$e->getMessage();

        }*/
    return $array;
  }
  public function comparaCampos(
    $campo1,
    $campo2,
    $id,
    $tipo,
    $campoCompleto = false,
    $visible = true,
    $notificar = false,
    $estados_porcentaje = false
  ) {
    if ($campo1 != $campo2) {
      $descripcion = "Se cambiaron los datos del campo ," . $tipo;
      if (!$campoCompleto)  $descripcion .= " del campo ," . $campo2 . " al " . $campo1;

      $descripcion_estados = $descripcion;
      $this->nuevaTransaccion($descripcion, $id, true, $visible);

      $cqlCompromiso = Compromiso::find($id);
      $codigo = $cqlCompromiso->codigo;

      $arregloUsuarios[0] = $cqlCompromiso->monitor_id;
      $model = $arregloUsuarios;
      $msj = $descripcion . ' en el compromiso: ' . $codigo;
      $this->notificarUsuario($model, $msj);

      if ($estados_porcentaje) {
        $descripcion = $descripcion_estados;
        $compromiso_id = $cqlCompromiso->id;
        $codigo = $cqlCompromiso->codigo;
        $this->agregarNotificaciones($descripcion, $compromiso_id, 'ESTADO');
      }
      //   }
    }
    return $campo1;
  }
  public function validarActualizacionObjetivo($cql, $request)
  {
    if ($cql->temporalidad_id != $request->temporalidad_id) {
      $tmp = Temporalidad::find($request->temporalidad_id);
      $this->crearPeriodosCalendarios(
        $request->fecha_inicio_objetivo,
        $request->fecha_fin_objetivo,
        $tmp,
        $cql->id,
        true
      );
    }
    $cql->fecha_inicio = $this->comparaCampos(
      $request->fecha_inicio_objetivo,
      $cql->fecha_inicio,
      $cql->compromiso_id,
      ("Fecha de inicio del Objetivo " . $cql->numero),
      false
    );

    $cql->fecha_fin = $this->comparaCampos(
      $request->fecha_fin_objetivo,
      $cql->fecha_fin,
      $cql->compromiso_id,
      ("Fecha Fin del Objetivo " . $cql->numero),
      false
    );

    $cql->temporalidad_id = $this->comparaCampos(
      $request->temporalidad_id,
      $cql->temporalidad_id,
      $cql->compromiso_id,
      ("Temporalidad de " .
        Temporalidad::find($cql->temporalidad_id)->descripcion .
        ' a ' .
        Temporalidad::find($request->temporalidad_id)->descripcion),
      true
    );
  }
  public function crearPeriodosCalendarios(
    $fecha_inicio,
    $fecha_fin,
    $tiempo = null,
    $obj_id,
    $cambio = false
  ) {

    date_default_timezone_set('UTC');
    $date = $fecha_inicio;
    $end_date = $fecha_fin;
    $html = [];
    $numero = 0;
    $meta_acumulada = 0;
    $cumplimiento_acumulado = 0;

    if ($cambio) {
      $objPeriodo = Periodo::where('objetivo_id', $obj_id)
        ->where('estado', 'ACT')
        ->orderby('numero', 'asc')
        ->where('eliminado', false)
        ->get()->first();

      if (!is_null($objPeriodo)) {
        $date = $objPeriodo->fecha_inicio_periodo;
        $numero = $objPeriodo->numero;
        $meta_acumulada = $objPeriodo->meta_acumulada;
        $cumplimiento_acumulado = $objPeriodo->cumplimiento_acumulado;

        $cqlDelete = Periodo::where('objetivo_id', $obj_id)->where('estado', 'INA')->update(['eliminado' => true]);
      }
    }
    for ($i = $date; $i <= $end_date; $i = date("Y-m-d", strtotime($i . "+ " . $tiempo->tiempo . " days"))) {
      $numero = $numero + 1;
      $tiempoAlternativo = (date("Y-m-d", strtotime($i . "+ " . ($tiempo->tiempo - 1) . " days")));

      $fecha_fin_temp = $tiempo->tiempo != 1 ? $tiempoAlternativo : $i;

      $cql = new Periodo();
      $cql->numero = $numero;
      $cql->temporalidad = $tiempo->descripcion;
      $cql->fecha_inicio_periodo = $i;
      $cql->fecha_fin_periodo = (strtotime($fecha_fin_temp) > strtotime($end_date) ? $end_date : $fecha_fin_temp);
      $cql->objetivo_id = $obj_id;
      $cql->usuario_ingresa = Auth::user()->id;
      $cql->created_at = date("Y-m-d H:i:s");

      $cql->cumplimiento_periodo = 0;
      $cql->meta_periodo = 0;
      $cql->cumplimiento_periodo_porcentaje = 0;
      $cql->pendiente_periodo = 0;

      $cql->cumplimiento_acumulado = 0;
      $cql->pendiente_acumulado = 0;
      $cql->meta_acumulada = 0;

      $cql->valor_anterior_meta_acumulada = $meta_acumulada;
      $cql->valor_anterior_cumplimiento_acumulado = $cumplimiento_acumulado;
      $cql->estado = 'INA';

      $cql->save();
    }
  }
  public function crearPeriodos($fecha_inicio, $fecha_fin, $tiempo = null, $obj_id = 0, $cambio = false)
  {

    date_default_timezone_set('UTC');
    $date = $fecha_inicio;
    $end_date = $fecha_fin;
    $html = [];
    $numero = 0;
    $meta_acumulada = 0;
    $cumplimiento_acumulado = 0;
    $cortes = explode(",", env('CORTES_TRIMESTRAL'));

    if ($cambio) {
      $objPeriodo = Periodo::where('objetivo_id', $obj_id)
        ->where('estado', 'ACT')
        ->orderby('numero', 'asc')
        ->where('eliminado', false)
        ->get()->first();

      if (!is_null($objPeriodo)) {
        $date = $objPeriodo->fecha_inicio_periodo;
        $numero = $objPeriodo->numero;
        $meta_acumulada = $objPeriodo->meta_acumulada;
        $cumplimiento_acumulado = $objPeriodo->cumplimiento_acumulado;

        $cqlDelete = Periodo::where('objetivo_id', $obj_id)->where('estado', 'INA')->update(['eliminado' => true]);
      }
    }
    //dd($numero);
    if ($tiempo->descripcion == 'SEMANAL') {
      if ($this->saber_dia($fecha_inicio) != 'LUNES') {
        $numero = $numero + 1;
        $fin_periodo = date("Y-m-d", strtotime($fecha_inicio . ' next friday'));
        $this->nuevoPeriodo($obj_id, $numero, $fecha_inicio, $tiempo, $meta_acumulada, $cumplimiento_acumulado, $fin_periodo);
      }
      $lunes = $this->getMondaysInRange($fecha_inicio, $fecha_fin);
      foreach ($lunes as $value) {
        $numero = $numero + 1;
        $fin_periodo = date("Y-m-d", strtotime($value . ' next friday'));

        if (date("Y-m-d", strtotime($value . ' next friday')) <= $fecha_fin) {
          $this->nuevoPeriodo($obj_id, $numero, $value, $tiempo, $meta_acumulada, $cumplimiento_acumulado, $fin_periodo);
        }
      }
    }
    if ($tiempo->descripcion == 'MENSUAL') {

      do {
        $numero = $numero + 1;
        $fin_periodo = date("Y-m-t", strtotime($fecha_inicio));
        if ($fin_periodo <= $fecha_fin) {
          $fin_anterior = $this->saber_dia($fin_periodo) == 'SABADO' ? date("Y-m-d", strtotime($fin_periodo . "- 1 days")) : ($this->saber_dia($fin_periodo) == 'DOMINGO' ? date("Y-m-d", strtotime($fin_periodo . "- 2 days")) : $fin_periodo);
          $inicio_despues = $this->saber_dia($fecha_inicio) == 'SABADO' ? date("Y-m-d", strtotime($fecha_inicio . "+ 2 days")) : ($this->saber_dia($fecha_inicio) == 'DOMINGO' ? date("Y-m-d", strtotime($fecha_inicio . "+ 1 days")) : $fecha_inicio);
          $this->nuevoPeriodo($obj_id, $numero, $inicio_despues, $tiempo, $meta_acumulada, $cumplimiento_acumulado, $fin_anterior);
        }
        $fecha_inicio = date("Y-m-d", strtotime($fin_periodo . "+ 1 days"));
      } while ($fin_periodo <= $fecha_fin);
    }
    if ($tiempo->descripcion == 'TRIMESTRAL') {
      do {

        for ($i = 0; $i <= count($cortes) - 1; $i++) {
          $numero = $numero + 1;
          $fin_periodo = date("Y-" . $cortes[$i], strtotime($fecha_inicio));
          if ($fin_periodo <= $fecha_fin && $fin_periodo >= $fecha_inicio) {
            $fin_anterior = $this->saber_dia($fin_periodo) == 'SABADO' ? date("Y-m-d", strtotime($fin_periodo . "- 1 days")) : ($this->saber_dia($fin_periodo) == 'DOMINGO' ? date("Y-m-d", strtotime($fin_periodo . "- 2 days")) : $fin_periodo);
            $inicio_despues = $this->saber_dia($fecha_inicio) == 'SABADO' ? date("Y-m-d", strtotime($fecha_inicio . "+ 2 days")) : ($this->saber_dia($fecha_inicio) == 'DOMINGO' ? date("Y-m-d", strtotime($fecha_inicio . "+ 1 days")) : $fecha_inicio);
            $this->nuevoPeriodo($obj_id, $numero, $inicio_despues, $tiempo, $meta_acumulada, $cumplimiento_acumulado, $fin_anterior);
          }
          $fecha_inicio = date("Y-m-d", strtotime($fin_periodo . "+ 1 days"));
        }
      } while ($fin_periodo <= $fecha_fin);
    }
    if ($tiempo->descripcion == 'QUINCENAL') {

      do {
        $numero = $numero + 1;
        $fin_periodo = date("Y-m-t", strtotime($fecha_inicio));
        $fin_periodo_15 = date("Y-m-15", strtotime($fecha_inicio));
        if ($fin_periodo_15 <= $fecha_fin) {
          $inicio_despues = $this->saber_dia($fecha_inicio) == 'SABADO' ? date("Y-m-d", strtotime($fecha_inicio . "+ 2 days")) : ($this->saber_dia($fecha_inicio) == 'DOMINGO' ? date("Y-m-d", strtotime($fecha_inicio . "+ 1 days")) : $fecha_inicio);
          $fin_anterior = $this->saber_dia($fin_periodo_15) == 'SABADO' ? date("Y-m-d", strtotime($fin_periodo_15 . "- 1 days")) : ($this->saber_dia($fin_periodo_15) == 'DOMINGO' ? date("Y-m-d", strtotime($fin_periodo_15 . "- 2 days")) : $fin_periodo_15);
          $this->nuevoPeriodo($obj_id, $numero, $inicio_despues, $tiempo, $meta_acumulada, $cumplimiento_acumulado, $fin_anterior);
        }
        if ($fin_periodo <= $fecha_fin) {
          $fecha_inicio_15 = date("Y-m-d", strtotime($fin_anterior . "+ 1 days"));
          $inicio_despues = $this->saber_dia($fecha_inicio_15) == 'SABADO' ? date("Y-m-d", strtotime($fecha_inicio_15 . "+ 2 days")) : ($this->saber_dia($fecha_inicio_15) == 'DOMINGO' ? date("Y-m-d", strtotime($fecha_inicio_15 . "+ 1 days")) : $fecha_inicio_15);
          $fin_anterior = $this->saber_dia($fin_periodo) == 'SABADO' ? date("Y-m-d", strtotime($fin_periodo . "- 1 days")) : ($this->saber_dia($fin_periodo) == 'DOMINGO' ? date("Y-m-d", strtotime($fin_periodo . "- 2 days")) : $fin_periodo);
          $this->nuevoPeriodo($obj_id, $numero, $inicio_despues, $tiempo, $meta_acumulada, $cumplimiento_acumulado, $fin_anterior);
        }
        $fecha_inicio = date("Y-m-d", strtotime($fin_periodo . "+ 1 days"));
      } while ($fin_periodo <= $fecha_fin);
    }
    if ($tiempo->descripcion == 'DIARIO (L-D)' || $tiempo->descripcion == 'DIARIO SEMANA LABORAL (L-V)') {
      for ($i = $date; $i <= $end_date; $i = date("Y-m-d", strtotime($i . "+ " . $tiempo->tiempo . " days"))) {
        $tiempoAlternativo = (date("Y-m-d", strtotime($i . "+ " . ($tiempo->tiempo - 1) . " days")));
        $fecha_fin_temp = $tiempo->tiempo != 1 ? $tiempoAlternativo : $i;
        $fecha_fin_periodo = (strtotime($fecha_fin_temp) > strtotime($end_date) ? $end_date : $fecha_fin_temp);
        $temporal = true;
        switch ($tiempo->descripcion) {
          case 'DIARIO (L-D)':
            $temporal = true;
            break;
          case 'DIARIO SEMANA LABORAL (L-V)':
            if ($this->saber_dia($fecha_fin_periodo) == 'SABADO' || $this->saber_dia($fecha_fin_periodo) == 'DOMINGO' || $this->saber_dia($i) == 'SABADO' || $this->saber_dia($i) == 'DOMINGO')
              $temporal = false;
            break;
        }

        if ($temporal) {
          $fin_periodo = $fecha_fin_periodo;
          $numero = $numero + 1;
          $this->nuevoPeriodo($obj_id, $numero, $i, $tiempo, $meta_acumulada, $cumplimiento_acumulado, $fin_periodo);
        }
      }
    }
  }
  protected function saber_dia($nombredia)
  {
    //  if(date('N', strtotime($nombredia))==7)
    // dd(date('N', strtotime($nombredia)),$nombredia);
    $dias = array('DOMINGO', 'LUNES', 'MARTES', 'MIERCOLES', 'JUEVES', 'VIERNES', 'SABADO', 'DOMINGO');

    $fecha = $dias[date('N', strtotime($nombredia))];

    return  $fecha;
  }
  protected function getMondaysInRange($dateFromString, $dateToString)
  {
    $dateFrom = new \DateTime($dateFromString);
    $dateTo = new \DateTime($dateToString);
    $dates = [];

    if ($dateFrom > $dateTo) {
      return $dates;
    }

    if (1 != $dateFrom->format('N')) {
      $dateFrom->modify('next monday');
    }

    while ($dateFrom <= $dateTo) {
      $dates[] = $dateFrom->format('Y-m-d');
      $dateFrom->modify('+1 week');
    }

    return $dates;
  }
  protected function rangosFechas($inicio, $fin)
  {
    $start = $inicio;
    $end = $fin;
    $range = array();
    if (is_string($start) === true) $start = strtotime($start);
    if (is_string($end) === true) $end = strtotime($end);

    do {
      $range[] = date('Y-m-d', $start);
      $start = strtotime("+ 30 day", $start);
    } while ($start <= $end);
    return $range;
  }
  protected function nuevoPeriodo($obj_id, $numero, $value, $tiempo, $meta_acumulada, $cumplimiento_acumulado, $fin_periodo)
  {
    $cql = new Periodo();
    $cql->numero = $numero;
    $cql->temporalidad = $tiempo->descripcion;
    $cql->fecha_inicio_periodo = $value;
    $cql->fecha_fin_periodo = $fin_periodo;
    $cql->objetivo_id = $obj_id;
    $cql->usuario_ingresa = Auth::user()->id;
    $cql->created_at = date("Y-m-d H:i:s");

    $cql->cumplimiento_periodo = 0;
    $cql->meta_periodo = 0;
    $cql->cumplimiento_periodo_porcentaje = 0;
    $cql->pendiente_periodo = 0;

    $cql->cumplimiento_acumulado = 0;
    $cql->pendiente_acumulado = 0;
    $cql->meta_acumulada = 0;

    $cql->valor_anterior_meta_acumulada = $meta_acumulada;
    $cql->valor_anterior_cumplimiento_acumulado = $cumplimiento_acumulado;
    $cql->estado = 'INA';

    $cql->save();
  }
  public function actualizarCumplimiento($objetivo_id)
  {
    $cqlConsultaObjetivo = Objetivo::where('id', $objetivo_id)->first();

    $compromisos_id = $cqlConsultaObjetivo->compromiso_id;

    $cqlObjetivo = Objetivo::where('compromiso_id', $compromisos_id)
      ->where('registro_cumplimiento', true)
      ->where('estado', 'ACT')
      ->where('aprobado', true)
      ->pluck('id')
      ->toArray();

    $cqlConsultaPeriodo = Periodo::where('eliminado', false)
      ->where('estado', 'ACT')
      ->whereIn('objetivo_id', $cqlObjetivo)
      ->sum('cumplimiento_acumulado');
    $conteoObjetivo = count($cqlObjetivo);
    $cumplimiento = $cqlConsultaPeriodo / $conteoObjetivo;

    $cqlCompromisoUpdate = Compromiso::find($compromisos_id);
    $cqlCompromisoUpdate->cumplimiento = $cumplimiento;
    $cqlCompromisoUpdate->save();

    return $cumplimiento;
  }
  public function agregarPendientes($id)
  {
    $cqlCompromiso = Compromiso::find($id);
    $cqlCompromiso->pendientes = $cqlCompromiso->pendientes + 1;
    $cqlCompromiso->save();
  }
  public function consultaInstitucionporMinistro($usuario_id)
  {
    $cqlMinistro = Institucion::where('ministro_usuario_id', $usuario_id)->first();
    $cqlMinistro = $cqlMinistro != null ? $cqlMinistro->id : 0;
    return $cqlMinistro;
  }
  public function verificacionCodigo($institucion_id, $compromiso_id)
  {
    /* try {
            DB::connection('pgsql_presidencia')->beginTransaction();*/
    $hoy = date("Y-m-d H:i:s");
    $codigo = 'S/C';
    $cqlCodigo = Codigo::where('institucion_id', $institucion_id)
      ->first();

    if ($cqlCodigo != null) {
      $cqlCodigo->numero = $cqlCodigo->numero + 1;
      $cqlCodigo->updated_at = $hoy;
      $cqlCodigo->usuario_actualiza = Auth::user()->id;
      $cqlCodigo->save();
      $codigo = trim(trim($cqlCodigo->abv) . '-' . trim($cqlCodigo->numero));
    } else {
      $cqlCodigo_new = new Codigo();
      $cqlCodigo_new->numero = 1;
      $cqlCodigo_new->institucion_id = $institucion_id;
      $cqlCodigo_new->abv = Institucion::find($institucion_id)->siglas;
      $cqlCodigo_new->usuario_ingresa = Auth::user()->id;
      $cqlCodigo_new->created_at = $hoy;
      $cqlCodigo_new->save();

      $codigo = trim(trim($cqlCodigo_new->abv) . '-' . trim($cqlCodigo_new->numero));
    }

    $cqlCompromiso = Compromiso::find($compromiso_id);
    $cqlCompromiso->codigo = $codigo;
    $cqlCompromiso->save();

    if ($compromiso_id != 0) {
      $cqlResposanble = Responsable::where('compromiso_id', $compromiso_id)
        ->where('estado', 'ACT')
        ->get()->first();
      if (!is_null($cqlResposanble)) {
        $model = (new RepositorioController())->consultaUsuariosRol('MINISTRO', $cqlResposanble->institucion_id);
        $msj = 'se ha <strong>Asignado</strong> el Compromiso: ' . $codigo . ' ';
        (new RepositorioController())->notificarUsuario($model, $msj);

	//Enviar correo al delegado de la institucion
        (new RepositorioController())->notificarUsuarioDelegado($cqlResposanble->institucion_id, $msj);

        $arregloUsuarios[0] = $cqlCompromiso->monitor_id;
        $model = $arregloUsuarios;
        (new RepositorioController())->notificarUsuario($model, $msj);
      }
    }
    return $codigo;

    /*
            DB::connection('pgsql_presidencia')->commit();
        } catch (\Exception $e) {
            DB::connection('pgsql_presidencia')->rollBack();
            return null;
        }*/
  }


  public function agregarNotificaciones($descripcion, $compromiso_id, $tipo, $codigo = null)
  {
    $cqlResposanble = Responsable::where('compromiso_id', $compromiso_id)
      ->where('estado', 'ACT')
      ->get()->first();
    $cqlResposanble = is_null($cqlResposanble) ? 0 : $cqlResposanble->institucion_id;

    try {
      $monitor_id = 0;
      $cqlCompromiso = Compromiso::where('id', $compromiso_id)->first();
      $codigo_id = is_null($cqlCompromiso) ? '--' : $cqlCompromiso->codigo;
      if (!is_null(Auth::user())) {
        if (Auth::user()->evaluarole(['MINISTRO'])) {
          $monitor_id = is_null($cqlCompromiso) ? $monitor_id : $cqlCompromiso->monitor_id;
        }
      }

      DB::connection('pgsql_presidencia')->beginTransaction();
      $cqlNotificaciones_new = new Notificaciones();
      $cqlNotificaciones_new->descripcion = $descripcion;
      $cqlNotificaciones_new->institucion_id =  $cqlResposanble;
      $cqlNotificaciones_new->compromiso_id = $compromiso_id;
      $cqlNotificaciones_new->codigo = $codigo_id;
      $cqlNotificaciones_new->tipo = $tipo;
      $cqlNotificaciones_new->usuario_ingresa = is_null(Auth::user()) ? 'sistema' : Auth::user()->name;
      $cqlNotificaciones_new->monitor = $monitor_id;
      $cqlNotificaciones_new->save();

      DB::connection('pgsql_presidencia')->commit();
    } catch (\Exception $e) {
      DB::connection('pgsql_presidencia')->rollBack();
      echo $e->getMessage();
    }

    $model = $this->consultaUsuariosRol('MINISTRO', $cqlResposanble);
    $msj = $descripcion . ' en el compromiso: ' . $codigo;
    $this->notificarUsuario($model, $msj);
    //Enviar correo al delegado de la institucion
    $this->notificarUsuarioDelegado($cqlResposanble, $msj);
  }

  public function verifcarDatoArray($data)
  {

    if ($data == null || $data == "null" || $data == "" || $data == "0") $data = [];
    if (!is_array($data))  $data = explode(",", $data);

    return $data;
  }
  public function validarArregloVacio($data)
  {
    if (
      $data != []  &&
      !in_array("null", $data) &&
      !in_array(null, $data) &&
      !in_array("", $data)
    )
      return true;
    return false;
  }

  public function creacionObjetoExportacion(
    $fecha_inicio_exportar_monitor,
    $fecha_fin_exportar_monitor,
    $estado,
    $tabla,
    $asignaciones,
    $temporales,
    $pendientes,
    $filtro,
    $institucion_id_exportar_monitor,
    $gabinete_id_exportar_monitor,
    $gabinete_id_corresponsable_exportar_monitor,
    $institucion_id_corresponsable_exportar_monitor,
    $fecha_inicio_fin_exportar_monitor,
    $fecha_fin_fin_exportar_monitor,
    $tipo_id_exportar_monitor,
    $estado_id_exportar_monitor,
    $estado_porcentaje_id_exportar_monitor,
    $descripcion_antecedente_exportar_monitor,
    $fecha_inicio_antecedente_exportar_monitor,
    $fecha_fin_antecedente_exportar_monitor,
    $fecha_inicio_cuumplido_exportar_monitor,
    $fecha_fin_cumplido_exportar_monitor,
    $provincia_id_exportar_monitor,
    $canton_id_exportar_monitor,
    $parroquia_id_exportar_monitor,
    $nombre_compromiso_exportar_monitor,
    $codigo_compromiso_exportar_monitor,
    $fecha_inicio_avance_exportar_monitor,
    $fecha_fin_avance_exportar_monitor,
    $habilitarFechaInicio,
    $habilitarFechaFin,
    $habilitarFechaCumplido,
    $habilitarFechaAntecedente,
    $habilitarFechaUltimoAvance,
    $monitor_id_exportar_monitor,
  ) {

    $data = new \stdClass();
    $data->fecha_inicio_exportar_monitor = $fecha_inicio_exportar_monitor;
    $data->fecha_fin_exportar_monitor = $fecha_fin_exportar_monitor;
    $data->estado = $estado;
    $data->tabla = $tabla;
    $data->asignaciones = $asignaciones;
    $data->temporales = $temporales;
    $data->pendientes = $pendientes;
    $data->filtro = $filtro;
    $data->institucion_id_exportar_monitor = $institucion_id_exportar_monitor;
    $data->gabinete_id_exportar_monitor = $gabinete_id_exportar_monitor;
    $data->gabinete_id_corresponsable_exportar_monitor = $gabinete_id_corresponsable_exportar_monitor;
    $data->institucion_id_corresponsable_exportar_monitor = $institucion_id_corresponsable_exportar_monitor;
    $data->fecha_inicio_fin_exportar_monitor = $fecha_inicio_fin_exportar_monitor;
    $data->fecha_fin_fin_exportar_monitor = $fecha_fin_fin_exportar_monitor;
    $data->tipo_id_exportar_monitor = $tipo_id_exportar_monitor;
    $data->estado_id_exportar_monitor = $estado_id_exportar_monitor;
    $data->estado_porcentaje_id_exportar_monitor = $estado_porcentaje_id_exportar_monitor;
    $data->descripcion_antecedente_exportar_monitor = $descripcion_antecedente_exportar_monitor;
    $data->fecha_inicio_antecedente_exportar_monitor = $fecha_inicio_antecedente_exportar_monitor;
    $data->fecha_fin_antecedente_exportar_monitor = $fecha_fin_antecedente_exportar_monitor;
    $data->fecha_inicio_cuumplido_exportar_monitor = $fecha_inicio_cuumplido_exportar_monitor;
    $data->fecha_fin_cumplido_exportar_monitor = $fecha_fin_cumplido_exportar_monitor;
    $data->provincia_id_exportar_monitor = $provincia_id_exportar_monitor;
    $data->canton_id_exportar_monitor = $canton_id_exportar_monitor;
    $data->parroquia_id_exportar_monitor = $parroquia_id_exportar_monitor; //FC
    $data->nombre_compromiso_exportar_monitor = $nombre_compromiso_exportar_monitor;
    $data->codigo_compromiso_exportar_monitor = $codigo_compromiso_exportar_monitor;
    $data->fecha_inicio_avance_exportar_monitor = $fecha_inicio_avance_exportar_monitor;
    $data->fecha_fin_avance_exportar_monitor = $fecha_fin_avance_exportar_monitor;
    $data->habilitarFechaInicio = $habilitarFechaInicio;
    $data->habilitarFechaFin = $habilitarFechaFin;
    $data->habilitarFechaCumplido = $habilitarFechaCumplido;
    $data->habilitarFechaAntecedente = $habilitarFechaAntecedente;
    $data->habilitarFechaUltimoAvance = $habilitarFechaUltimoAvance;
    $data->monitor_id_exportar_monitor = $monitor_id_exportar_monitor;  //FC
    $data->corresponsable = false;
    return $data;
  }

  public function notificarUsuarioDelegado($institucion, $descripcion)
  {

    if (config('app.NOTIFICAR')) {
      $cqlArregloUsuarios = Delegado::select('email')
        ->where('institucion_id', $institucion)
        ->where('estado','ACT')
        ->pluck('email');

      foreach ($cqlArregloUsuarios as $for) {
        try {
          $objSelect = new SelectController();
          $objSelect->NotificarSinRegistro($descripcion, $for);
        } catch (\Exception $ex) {
          echo $ex->getMessage();
        }
      }
    }

    return true;
  }
}
