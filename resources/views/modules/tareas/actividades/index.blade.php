@extends('layouts.app')

@section('contentheader_title')
    Tareas Administrativas
@endsection

@section('contentheader_description')
    Actividades
@endsection


@section('css')
    <link href="{{ url('adminlte/plugins/notifications/sweetalert.css') }}" rel="stylesheet">
    <link href="{{ url('adminlte/style_moderno2.css') }}" rel="stylesheet">
    <style>

    </style>

@endsection
@section('javascript')

    <script src="{{ url('adminlte/plugins/datepicker/') }}/bootstrap-datepicker.js"></script>
    <script src="{{ url('js/modules/tareas/tareas_actividades.js?v=10') }}"></script>
    <script>
        $("#nueva_tarea_asignada").on("change", function() {
            app.nueva_tarea_asignada = $(this).val();
        });
        /*     $("#fecha_inicio").on("change", function() {
                 if($(this).val()!=null&&$(this).val()!=""){

                 var current_datetime = addDays($(this).val(), 7);
                 let formatted_date = formatearFecha(current_datetime.getFullYear()) + "-" + formatearFecha(
                     current_datetime.getMonth() + 1) + "-" + formatearFecha(current_datetime.getDate());

                 $("#fecha_fin").val(formatted_date);
                 }else{
                       $("#fecha_fin").val(null);
                 }
             })*/
        $("#fecha_inicio_actividad").on("change", function() {
            if ($(this).val() != null && $(this).val() != "") {
                var current_datetime = addDays($(this).val(), 1);
                let formatted_date = formatearFecha(current_datetime.getFullYear()) + "-" + formatearFecha(
                    current_datetime.getMonth() + 1) + "-" + formatearFecha(current_datetime.getDate());

                app.formCrear.fecha_fin = formatted_date;
            } else {
                app.formCrear.fecha_fin = null
            }

        });
    </script>
@endsection
@section('content')

    <div id="main">
        <div class="card">
            <div class="card-heading ">
                <div class="col-md-12 " style="padding-bottom:0px!important;padding-top:0px!important">
                    <div class="">
                        <div class="col-12 col-sm-9 col-md-3 float-left btnTop">
                            <div class="info-box info-box-t">
                                <span
                                    :class="currentTab === 1?'info-box-icon info-box-icon-t bg-primary elevation-1 ':'info-box-icon info-box-icon-t bg-primary elevation-1 '"
                                    v-text="activos">
                                </span>
                                <div :class="currentTab === 1?'info-box-content ':'info-box-content'">
                                    <a href="#" class="info-box-text h6 " v-on:click="currentTab = 1;"
                                        :class="{link_seleccionado: currentTab === 1}" onclick="$('.selectAll').addClass('hidden');datatableCargar('ACT');$('.aprobarPermisos').addClass('hidden');$('.aprobarPermisos').closest('a').addClass('hidden');">
                                        APROBADOS</a>
                                </div>
                            </div>
                            <!-- /.info-box -->
                        </div>

                        <div class="col-12 col-sm-9 col-md-3 float-left btnTop">
                            <div class="info-box info-box-t">
                                <span
                                    :class="currentTab === 2?'info-box-icon info-box-icon-t bg-primary elevation-1 ':'info-box-icon info-box-icon-t bg-primary elevation-1 '"
                                    v-text="pendientes" style="color: #ffffff!important;">
                                </span>
                                <div :class="currentTab === 2?'info-box-content ':'info-box-content'">
                                    <a href="#" class="info-box-text h6 " v-on:click="currentTab = 2;"
                                        :class="{link_seleccionado: currentTab === 2}" onclick="$('.selectAll').removeClass('hidden');datatableCargar('PEN',2);$('.aprobarPermisos').removeClass('hidden');$('.aprobarPermisos').closest('a').removeClass('hidden');">
                                        PENDIENTES</a>
                                </div>
                            </div>
                            <!-- /.info-box -->
                        </div>
                        <div class="col-12 col-sm-9 col-md-3 float-left btnTop">
                            <div class="info-box info-box-t">
                                <span
                                    :class="currentTab === 3?'info-box-icon info-box-icon-t bg-primary elevation-1 ':'info-box-icon info-box-icon-t bg-primary elevation-1 '"
                                    v-text="inactivos" style="color: #ffffff!important;">
                                </span>
                                <div :class="currentTab === 3?'info-box-content ':'info-box-content'">
                                    <a href="#" class="info-box-text h6 " v-on:click="currentTab = 3;"
                                        :class="{link_seleccionado: currentTab === 3}" onclick="$('.selectAll').addClass('hidden');datatableCargar('INA');$('.aprobarPermisos').addClass('hidden');$('.aprobarPermisos').closest('a').addClass('hidden');">
                                        NEGADOS</a>
                                </div>
                            </div>
                            <!-- /.info-box -->
                        </div>

                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 ">
                        <label class="col-md-12">&nbsp;</label>
                        <button class="btn btn-primary" data-toggle="modal" data-target="#modal-default"
                            v-on:click="limpiar()">Crear Nueva
                            Tarea</button>
                    </div>
                    <div class="col-md-3">
                        <label>fecha inicio:</label>

                        <div class="input-group">
                            <input type="date" class="form-control" id="fecha_inicio" value="<?php echo date('Y-m-01'); ?>">

                        </div>
                    </div>

                    <div class="col-md-3">
                        <label>fecha fin:</label>

                        <div class="input-group">
                            <input type="date" class="form-control" id="fecha_fin" value="<?php echo date('Y-m-t'); ?>">
                            <span class="input-group-btn">
                                <button class="btn btn-default" type="button" onclick="datatableCargar()">
                                    <span class="fa fa-search"></span>
                                </button>

                            </span>
                        </div>
                    </div>
                 
                    <div class="col-md-1 btnTop" style="float:right;padding-right: 0px;
                            padding-left: 0px;width:20%" v-show="!filtro_grafico">
                        <br />

                        <button class="btn btn-default  btn-sm" v-on:click="mostrarGrafico()" style="float:right"><img src="{{url('/images/icons/pie_chart.png')}}" width="15px" style="padding:0px"></button>
                    </div>  
                    <div class="col-md-1 btnTop" style="float:right;padding-right: 0px;
                    padding-left: 0px;width:20%" v-show="filtro_grafico">
                        <br />

                        <button class="btn btn-primary  btn-sm" v-on:click="quitarFiltroGrafico()" style="float:right"><img src="{{url('/images/icons/pie_chart.png')}}" width="15px" style="padding:0px"></button>
                    </div>  
                    <div class="col-md-1 btnTop" style="float:right;padding-right: 0px;
                                padding-left: 0px;width:60%">
                        <br />

                        <button class="btn btn-default btn-block  btn-sm" data-toggle="modal"
                            data-target="#modal-filtrar-marcacion" data-backdrop="static" data-keyboard="false"><i
                                class="fa fa-filter"></i>&nbsp;Filtrar</button>
                    </div>
                    <div class="col-md-1 btnTop" style="float:right;padding-right: 0px;
                                                    padding-left: 0px;width:20%" v-show="filtro">
                        <br />

                        <button class="btn btn-primary  btn-sm" v-on:click="quitarFiltro()"><i
                                class="fa fa-list"></i></button>
                    </div>
                    <div class="col-md-1 btnTop" style="float:right;padding-right: 0px;
                                                        padding-left: 0px;width:20%" v-show="!filtro">
                        <br />

                        <button class="btn btn-primary   btn-sm" disabled><i class="fa fa-circle"
                                style="font-size: 10px;"></i> </button>
                    </div>

                    <div class="col-md-12" v-show="!filtro_grafico">
                        <br />
                        <div class="">
                            <input type="hidden" value="{{ $lider }}" id="liderVerifica">

                            <div class="table table-responsive" id="tablaConsulta">
                                <table class="table table-bordered table-striped" id="dtmenu" style="width:100%!important">
                                    <thead>
                                        <th><input type="checkbox" class="selectAll" name="selectAll" value="all"></th>
                                        <th>Grupo</th>
                                        <th>Lider</th>
                                        <th>Tarea Asignada</th>
                                        <th>Inicio</th>
                                        <th>Fin</th>
                                        <th>Asignado</th>
                                        <th>% Avance</th>
                                        <th>% Cump.</th>
                                        <th>Estado</th>
                                        <th></th>
                                    </thead>
                                    <tbody id="tbobymenu" class="menu-pen">

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12" v-show="filtro_grafico" style="overflow-y: scroll;overflow-x: scroll">
                        <hr/>
                        
                        <div id="grafico_estadistico"
                            style="min-width: 100%; height: 600px; max-width: 600px; margin: 0 auto" v-show="!cargando">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="modal-default">
            <div class='modal-dialog modal-lg'>
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="col-md-12">
                            <div class="row">
                                <div class='col-md-12'>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label class="col-md-12">Nombre de la Tareas:</label>
                                            <div class="row">
                                                <div class="col-md-7" v-show="nuevaTarea">
                                                    {!! Form::select('nueva_tarea_asignada', $tareas, null, ['id' => 'nueva_tarea_asignada', 'class' => 'form-control select2', 'placeholder' => 'SELECCIONE UNA TAREA', 'maxlength' => '500']) !!}
                                                </div>

                                                <div class="col-md-2" v-show="nuevaTarea">
                                                    <button class="btn btn-primary btn-block"
                                                        v-on:click="nuevaTarea=false;formCrear.descripcion=nueva_tarea_asignada">
                                                        <i class="fa fa-cog"></i>&nbsp;Editar</button>
                                                </div>
                                                <div class="col-md-3" v-show="nuevaTarea">
                                                    <button class="btn btn-default"
                                                        v-on:click="nuevaTarea=false;formCrear.descripcion=''">
                                                        <i class="fa fa-plus"></i>&nbsp;Nueva Tarea</button>
                                                </div>
                                                <div class="col-md-10" v-show="!nuevaTarea">
                                                    <input type="text" class="form-control mayuscula_"
                                                        v-model="formCrear.descripcion">
                                                </div>
                                                <div class="col-md-2" v-show="!nuevaTarea">
                                                    <button class="btn btn-default" v-on:click="nuevaTarea=true">
                                                        <i class="fa fa-arrow-left"></i>&nbsp;Regresar</button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label>Fecha Inicio:</label>
                                            <input type="date" class="form-control" v-model="formCrear.fecha_inicio"
                                                id="fecha_inicio_actividad">
                                        </div>
                                        <div class="col-md-6">
                                            <label>Fecha Fin:</label>
                                            <input type="date" class="form-control" v-model="formCrear.fecha_fin">
                                        </div>
                                        <div class="col-md-12" v-show="!editar&&lider">
                                            <label>Asignado:</label>
                                            {!! Form::select('usuario_asignado_id', $usuario, null, ['id' => 'usuario_asignado_id', 'class' => 'form-control select2', 'multiple' => 'multiple']) !!}
                                        </div>

                                        <div class="col-md-12" v-show="editar||!lider">
                                            <label>Asignado:</label>
                                            {!! Form::select('usuario_asignado_unico_id', $usuario, null, ['id' => 'usuario_asignado_unico_id', 'class' => 'form-control', 'placeholder' => 'SELECCIONE UN FUNCIONARIO']) !!}
                                        </div>
                                        <div class="col-md-12">
                                            <label>Autocompletar:</label>
                                            <input type="checkbox" v-model="formCrear.autocompletar" checked>
                                        </div>
                                    </div>
                                </div>

                            </div>

                        </div>

                        <div class="modal-footer justify-content-end">
                            <button class="btn btn-primary" disabled v-show="cargando"><img
                                    src="{{ url('/spinner.gif') }}">&nbsp;Guardar Tarea</button>
                            <button class="btn btn-primary" v-show="!cargando" v-on:click='guardarTarea()'><i
                                    class="fa fa-save"></i>&nbsp;Guardar Tarea</button>

                            <button class="btn btn-default cerrarmodal" data-dismiss="modal" id="cerrar_registro"
                                v-show="!cargando"><b><i class="fa fa-times"></i></b>
                                Cerrar</button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
        </div>
        <div class="modal fade" id="modal-actividades">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6> <strong>TAREA:</strong>&nbsp;<h6 v-text="formCrear.descripcion.toUpperCase()">

                            </h6>
                        </h6>


                    </div>

                    <div class="modal-body">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div id="container"
                                                style="min-width: 250px; height: 250px; max-width: 250px; margin: 0 auto">
                                            </div>
                                        </div>
                                        <div class="col-md-9">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <label>Nombre de la Actividad:</label>
                                                    <textarea class="form-control-t mayuscula_"
                                                        v-model="formActividad.descripcion" maxlength="500"></textarea>
                                                </div>
                                                <div class="col-md-6" v-show="!formCrear.autocompletar">
                                                    <label>Fecha Inicio:</label>
                                                    <input type="date" class="form-control"
                                                        v-model="formActividad.fecha_inicio">
                                                </div>
                                                <div class="col-md-6" v-show="!formCrear.autocompletar">
                                                    <label>Fecha Fin:</label>
                                                    <input type="date" class="form-control"
                                                        v-model="formActividad.fecha_fin">
                                                </div>
                                                <div class="col-md-12" v-show="formCrear.autocompletar">
                                                    <button class="btn btn-default" v-on:click="limpiarActividad()"><i
                                                            class="fa fa-eraser"></i>&nbsp;Limpiar</button>
                                                    <button class="btn btn-info" disabled v-show="cargando"><img
                                                            src="{{ url('/spinner.gif') }}">&nbsp;Guardar</button>
                                                    <button class="btn btn-info " v-show="!cargando"
                                                        v-on:click="guardarActividad()"><i
                                                            class="fa fa-plus"></i>&nbsp;Guardar</button>
                                                    <button class="btn btn-info" disabled v-show="cargando"><img
                                                            src="{{ url('/spinner.gif') }}">&nbsp;Guardar Actividad con
                                                        Ticket</button>
                                                        @if($ticket)

                                                    <button class="btn btn-info " v-show="!cargando"
                                                        v-on:click="guardarActividad(true)"><i
                                                            class="fa fa-plus"></i>&nbsp;Guardar con Ticket </button>
                                                            @endif

                                                </div>

                                                <div class="col-md-12" v-show="!formCrear.autocompletar">
                                                    <label>Porcentaje:</label>
                                                    <div class="input-group">
                                                        <input type="text" class="form-control numero" maxlength="3"
                                                            v-model="formActividad.porcentaje">
                                                        <span class="input-group-btn">
                                                            <button class="btn btn-default"
                                                                v-on:click="limpiarActividad()"><i
                                                                    class="fa fa-eraser"></i>&nbsp;Limpiar</button>
                                                            <button class="btn btn-info" disabled v-show="cargando"><img
                                                                    src="{{ url('/spinner.gif') }}">&nbsp;Guardar
                                                            </button>
                                                            <button class="btn btn-info " v-show="!cargando"
                                                                v-on:click="guardarActividad()"><i
                                                                    class="fa fa-plus"></i>&nbsp;Guardar</button>
                                                            <button class="btn btn-info" disabled v-show="cargando"><img
                                                                    src="{{ url('/spinner.gif') }}">&nbsp;Guardar
                                                                Actividad
                                                                con Ticket</button>
                                                                @if($ticket)
                                                            <button class="btn btn-info " v-show="!cargando"
                                                                v-on:click="guardarActividad(true)"><i
                                                                    class="fa fa-plus"></i>&nbsp;Guardar Actividad con
                                                                Ticket </button>
                                                                @endif
                                                        </span>
                                                    </div>

                                                </div>

                                            </div>

                                        </div>

                                        <div class="col-md-12">
                                            <hr />
                                            <div class="table table-responsive" id="tablaConsulta">
                                                <table class="table table-bordered table-striped" id="dtmenuParticipantes"
                                                    style="width:100%!important">
                                                    <thead>
                                                        <th class="hidden">Id</th>
                                                        <th>Fecha de Registro</th>
                                                        <th>Actividad</th>
                                                        <th>Fecha Inicio</th>
                                                        <th>Fin</th>
                                                        <th>Porcentaje</th>
                                                        <th></th>
                                                    </thead>
                                                    <tbody id="tbobymenuParticipantes" class="menu-pen">

                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="modal-footer justify-content-end">

                            <button class="btn btn-default cerrarmodal" data-dismiss="modal"><b><i
                                        class="fa fa-times"></i></b>
                                Cerrar</button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
        </div>
        <div class="modal fade" id="modal-filtrar-marcacion">
            <div class='modal-dialog '>

                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Filtro por funcionario asignado</h4>
                    </div>
                    <div class="modal-body">
                        <div class="col-md-12">
                            <div class="col-md-12">
                                <label>Usuarios:</label>
                                {!! Form::select('filtro_funcionario', $usuario, null, ['class' => 'select2 form-control form-control-sm select2', 'id' => 'filtro_funcionario', 'placeholder' => 'SELECCIONE UNA OPCION']) !!}
                            </div>
                        </div>

                        <div class="modal-footer justify-content-end">
                            <button class="btn btn-primary" data-dismiss="modal"
                                onclick='app.filtro=true;datatableCargar()'><i
                                    class="fa fa-save"></i>&nbsp;Filtrar</button>
                            <button class="btn btn-default cerrarmodal" data-dismiss="modal" id="cerrar_registro"
                                v-show="!cargando"><b><i class="fa fa-times"></i></b>
                                Cerrar</button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
        </div>
    </div>

@endsection
@section('vue_js')
    <script src="{{ url('js/modules/tareas/vue_tareas_actividades.js?v=7') }}"></script>
@endsection
