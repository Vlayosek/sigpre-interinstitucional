@extends('layouts.app')

@section('contentheader_title')
    Compromisos
@endsection

@section('contentheader_description')
    @auth
        Gesti&oacute;n / {!! Auth::user()->evaluarole(['MINISTRO']) ? 'Ministro' : 'Monitor' !!}
    @endauth
@endsection

@section('content')
@section('css')
    <link href="{{ url('adminlte/plugins/notifications/sweetalert.css') }}" rel="stylesheet">
    <link href="{{ url('adminlte/style_moderno.css') }}" rel="stylesheet">
    <link href="{{ url('adminlte3/plugins/tui-calendar/css/tui-calendar.css') }}" rel="stylesheet">
    <link href="{{ url('adminlte3/plugins/tui-calendar/css/tui-date-picker.css') }}" rel="stylesheet">
    <link href="{{ url('adminlte3/plugins/tui-calendar/css/icons.css') }}" rel="stylesheet">
@endsection
@section('javascript')
    <script src="{{ url('adminlte3/plugins/tui-calendar/js/tui-code-snippet.min.js') }}"></script>
    <script src="{{ url('adminlte3/plugins/tui-calendar/js/tui-time-picker.min.js') }}"></script>
    <script src="{{ url('adminlte3/plugins/tui-calendar/js/tui-date-picker.min.js') }}"></script>
    <script src="{{ url('adminlte3/plugins/tui-calendar/js/tui-calendar.js') }}"></script>

    <script src="{{ url('adminlte/plugins/datepicker/') }}/bootstrap-datepicker.js"></script>
    <script src="{{ url('js/modules/compromisos/compromisos.js?v=19') }}"></script>
    <script src="{{ url('js/modules/compromisos/calendario/calendario.js') }}"></script>
    <script src="{{ url('js/modules/compromisos/calendario/templates.js') }}"></script>
    <script src="{{ url('js/modules/compromisos/responsables.js') }}"></script>
    <script src="{{ url('js/modules/compromisos/datatableCompromisos.js?v=6') }}"></script>
    <script>
        $(function() {
            $(".ic-arrow-line-left").addClass("fa fa-arrow-left");
            $(".ic-arrow-line-right").addClass("fa fa-arrow-right");
            $("#responsable_id").select2({
                placeholder: "SELECCIONEdata-auto-height=\"true\" UNA OPCION",
                ajax: {
                    url: "{{ route('buscarResponsable') }}",
                    type: "post",
                    delay: 250,
                    dataType: 'json',
                    data: function(params) {
                        return {
                            query: params.term, // search term
                            "_token": "{{ csrf_token() }}",
                        };
                    },
                    processResults: function(response) {
                        return {
                            results: response
                        };
                    },
                    cache: true
                }
            });

            $("#institucion_id").select2({
                placeholder: "SELECCIONE UNA OPCION",
                ajax: {
                    url: "{{ route('buscarInstitucion') }}",
                    type: "post",
                    delay: 250,
                    dataType: 'json',
                    data: function(params) {
                        return {
                            query: params.term, // search term
                            "_token": "{{ csrf_token() }}",
                        };
                    },
                    processResults: function(response) {
                        return {
                            results: response
                        };
                    },
                    cache: true
                }
            });
            $("#instituciones_corresponsables").select2({
                ajax: {
                    url: "{{ route('buscarInstitucionCo') }}",
                    type: "post",
                    delay: 250,
                    dataType: 'json',
                    data: function(params) {
                        return {
                            query: params.term, // search term
                            "_token": "{{ csrf_token() }}",
                        };
                    },
                    processResults: function(response) {
                        return {
                            results: response
                        };
                    },
                    cache: true
                }
            });
        });
    </script>
@endsection

<div id="main">
    @auth
        <input type="hidden" id="rolMinistro" content="{{ Auth::user()->evaluarole(['MINISTRO']) }}"
            v-model="rolMinistro">
    @endauth
    @guest
        <input type="hidden" id="rolMinistro" content="0" v-model="rolMinistro">
    @endguest

    <a href="#" class="hidden" id="botonImprimir" target="_blank" download>Boton Imprimir</a>

    <button id="limpiarJQUERY" class="hidden" onclick="resetCombo()"></button>

    <div class="col-md-12">

        <div class="card">

            <div class="card-heading">

                <div class="row">

                    <!--PESTAÑAS-->
                    <div class="col-md-7" v-show="gestiones && currentTab_==4"></div>
                    <div :class="rolMinistro == 0 ? 'col-md-8 hidden' : 'col-md-10'"
                        v-show="calendario || currentTab_!=4"></div>
                    <div :class="rolMinistro == 0 ? 'col-md-9 hidden' : 'hidden'" v-show="busquedas || currentTab_!=4">
                    </div>


                    <!--FIN FILTRO PASTEL Y PERZONALIZADO-->
                    <div class="col-md-1 " style="float:right;padding-right: 0px;padding-left: 0px;width:20%"
                        v-show="!gestiones" v-on:click="cargarGestiones()">
                        <button class="btn btn-default btn-block  btn-sm">&nbsp;Gestiones</button>
                    </div>
                    <div class="col-md-1 " style="float:right;padding-right: 0px; padding-left: 0px;width:20%"
                        v-show="gestiones">
                        <button class="btn btn-primary btn-block  btn-sm" disabled>&nbsp;Gestiones</button>
                    </div>
                    <div class="col-md-1 " style="float:right;padding-right: 0px;padding-left: 0px;width:60%"
                        v-show="!calendario" v-on:click="cargarCalendario()">
                        <button class="btn btn-default btn-block  btn-sm">&nbsp;Calendario</button>
                    </div>
                    <div class="col-md-1 " style="float:right;padding-right: 0px; padding-left: 0px;width:20%"
                        v-show="calendario">
                        <button class="btn btn-primary btn-block  btn-sm" disabled>&nbsp;Calendario</button>
                    </div>
                    <div :class="rolMinistro == 0 ? 'col-md-1 hidden' : 'hidden'"
                        style="float:right;padding-right: 0px;padding-left: 0px;width:60%" v-show="!busquedas"
                        v-on:click="cargarBusquedas();">
                        <button class="btn btn-default btn-block  btn-sm">&nbsp;Búsquedas</button>
                    </div>
                    <div :class="rolMinistro == 0 ? 'col-md-1 hidden' : 'hidden'"
                        style="float:right;padding-right: 0px; padding-left: 0px;width:20%" v-show="busquedas">
                        <button class="btn btn-primary btn-block  btn-sm" disabled>&nbsp;Búsquedas</button>
                    </div>
                    <!--FIN PESTAÑAS-->

                    <div class="col-md-12" v-show="gestiones">

                        <div class="col-md-12 btnTop">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-10">
                                            <div class="row">
                                                <div class="col-md-12"
                                                    style="padding-bottom:0px!important;padding-top:0px!important">
                                                    <div class="col-12 col-sm-9 col-md-2 float-left">
                                                        <div class="info-box info-box-t">
                                                            <span
                                                                :class="currentTab === 0 ?
                                                                    'info-box-icon info-box-icon-t bg-primary elevation-1 btnActivo' :
                                                                    'info-box-icon info-box-icon-t bg-primary elevation-1 '"
                                                                v-text="registrados">
                                                            </span>
                                                            <div
                                                                :class="currentTab === 0 ? 'info-box-content btnActivo' :
                                                                    'info-box-content'">
                                                                <a href="#" class="info-box-text h6 "
                                                                    v-on:click="currentTab = 0;"
                                                                    :class="{ link_seleccionado: currentTab === 0 }"
                                                                    onclick="datatableCargar()">
                                                                    Registrados</a>
                                                            </div>
                                                        </div>
                                                        <!-- /.info-box -->
                                                    </div>

                                                </div>
                                                <div class="col-md-12"
                                                    style="padding-bottom:0px!important;padding-top:0px!important">
                                                    <div class="">
                                                        <div class="col-12 col-sm-9 col-md-2 float-left">
                                                            <div class="info-box info-box-t">
                                                                <span
                                                                    :class="currentTab === 7 ?
                                                                        'info-box-icon info-box-icon-t bg-primary elevation-1 btnActivo' :
                                                                        'info-box-icon info-box-icon-t bg-primary elevation-1 '"
                                                                    v-text="planificacion">
                                                                </span>
                                                                <div
                                                                    :class="currentTab === 7 ? 'info-box-content btnActivo' :
                                                                        'info-box-content'">
                                                                    <a href="#" class="info-box-text h6 "
                                                                        v-on:click="currentTab = 7;"
                                                                        :class="{ link_seleccionado: currentTab === 7 }"
                                                                        onclick="datatableCargar('PLA',2)">
                                                                        En Planificaci&oacute;n</a>
                                                                </div>
                                                            </div>
                                                            <!-- /.info-box -->
                                                        </div>
                                                        <div class="col-12 col-sm-9 col-md-2 float-left"
                                                            style="background:#007bff17">
                                                            <div class="info-box info-box-t">
                                                                <span
                                                                    :class="currentTab === 6 ?
                                                                        'info-box-icon info-box-icon-t bg-primary elevation-1 btnActivo' :
                                                                        'info-box-icon info-box-icon-t bg-primary elevation-1 '"
                                                                    v-text="ejecucion">
                                                                </span>
                                                                <div
                                                                    :class="currentTab === 6 ? 'info-box-content btnActivo' :
                                                                        'info-box-content'">
                                                                    <a href="#" class="info-box-text h6 "
                                                                        v-on:click="currentTab = 6;"
                                                                        :class="{ link_seleccionado: currentTab === 6 }"
                                                                        onclick="datatableCargar('EJE',2)">
                                                                        En Ejecuci&oacute;n</a>
                                                                </div>
                                                            </div>
                                                            <!-- /.info-box -->
                                                        </div>
                                                        <div class="col-12 col-sm-9 col-md-2 float-left">
                                                            <div class="info-box info-box-t">
                                                                <span
                                                                    :class="currentTab === 10 ?
                                                                        'info-box-icon info-box-icon-t bg-primary elevation-1 btnActivo' :
                                                                        'info-box-icon info-box-icon-t bg-primary elevation-1 '"
                                                                    v-text="standby" style="color: #ffffff!important;">
                                                                </span>
                                                                <div
                                                                    :class="currentTab === 10 ? 'info-box-content btnActivo' :
                                                                        'info-box-content'">
                                                                    <a href="#" class="info-box-text h6 "
                                                                        v-on:click="currentTab = 10;"
                                                                        :class="{ link_seleccionado: currentTab === 10 }"
                                                                        onclick="datatableCargar('STA',2)">
                                                                        Stand By</a>
                                                                </div>
                                                            </div>
                                                            <!-- /.info-box -->
                                                        </div>
                                                        <div class="col-12 col-sm-9 col-md-2 float-left">
                                                            <div class="info-box info-box-t">
                                                                <span
                                                                    :class="currentTab === 9 ?
                                                                        'info-box-icon info-box-icon-t bg-primary elevation-1 btnActivo' :
                                                                        'info-box-icon info-box-icon-t bg-primary elevation-1 '"
                                                                    v-text="cerrado">
                                                                </span>
                                                                <div
                                                                    :class="currentTab === 9 ? 'info-box-content btnActivo' :
                                                                        'info-box-content'">
                                                                    <a href="#" class="info-box-text h6 "
                                                                        v-on:click="currentTab = 9;"
                                                                        :class="{ link_seleccionado: currentTab === 9 }"
                                                                        onclick="datatableCargar('CER',2)">
                                                                        Cerrado</a>
                                                                </div>
                                                            </div>
                                                            <!-- /.info-box -->
                                                        </div>
                                                        <div class="col-12 col-sm-9 col-md-2 float-left">
                                                            <div class="info-box info-box-t">
                                                                <span
                                                                    :class="currentTab === 8 ?
                                                                        'info-box-icon info-box-icon-t bg-primary elevation-1 btnActivo' :
                                                                        'info-box-icon info-box-icon-t bg-primary elevation-1 '"
                                                                    v-text="cumplido">
                                                                </span>
                                                                <div
                                                                    :class="currentTab === 8 ? 'info-box-content btnActivo' :
                                                                        'info-box-content'">
                                                                    <a href="#" class="info-box-text h6 "
                                                                        v-on:click="currentTab = 8;"
                                                                        :class="{ link_seleccionado: currentTab === 8 }"
                                                                        onclick="datatableCargar('CUM',2)">
                                                                        Cumplido</a>
                                                                </div>
                                                            </div>
                                                            <!-- /.info-box -->
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-12"
                                                    style="padding-bottom:0px!important;padding-top:0px!important;background:#007bff17">
                                                    <div class="col-12 col-sm-9 col-md-2 float-left">
                                                        <div class="info-box info-box-t">
                                                            <span
                                                                :class="currentTab === 1 ?
                                                                    'info-box-icon info-box-icon-t bg-primary elevation-1 btnActivo' :
                                                                    'info-box-icon info-box-icon-t bg-primary elevation-1 '"
                                                                v-text="optimo">
                                                            </span>
                                                            <div
                                                                :class="currentTab === 1 ? 'info-box-content btnActivo' :
                                                                    'info-box-content'">
                                                                <a href="#" class="info-box-text h6 "
                                                                    v-on:click="currentTab = 1;"
                                                                    :class="{ link_seleccionado: currentTab === 1 }"
                                                                    onclick="datatableCargar('OPT')">
                                                                    Óptimo</a>
                                                            </div>
                                                        </div>
                                                        <!-- /.info-box -->
                                                    </div>
                                                    <div class="col-12 col-sm-9 col-md-2 float-left">
                                                        <div class="info-box info-box-t">
                                                            <span
                                                                :class="currentTab === 2 ?
                                                                    'info-box-icon info-box-icon-t bg-primary elevation-1 btnActivo' :
                                                                    'info-box-icon info-box-icon-t bg-primary elevation-1 '"
                                                                v-text="bueno">
                                                            </span>
                                                            <div
                                                                :class="currentTab === 2 ? 'info-box-content btnActivo' :
                                                                    'info-box-content'">
                                                                <a href="#" class="info-box-text h6 "
                                                                    v-on:click="currentTab = 2;"
                                                                    :class="{ link_seleccionado: currentTab === 2 }"
                                                                    onclick="datatableCargar('BUE')">
                                                                    Bueno</a>
                                                            </div>
                                                        </div>
                                                        <!-- /.info-box -->
                                                    </div>
                                                    <div class="col-12 col-sm-9 col-md-2 float-left">
                                                        <div class="info-box info-box-t">
                                                            <span
                                                                :class="currentTab === 3 ?
                                                                    'info-box-icon info-box-icon-t bg-warning elevation-1 btnActivo' :
                                                                    'info-box-icon info-box-icon-t bg-warning elevation-1 '"
                                                                v-text="leve" style="color: #ffffff!important;">
                                                            </span>
                                                            <div
                                                                :class="currentTab === 3 ? 'info-box-content btnActivo' :
                                                                    'info-box-content'">
                                                                <a href="#" class="info-box-text h6 "
                                                                    v-on:click="currentTab = 3;"
                                                                    :class="{ link_seleccionado: currentTab === 3 }"
                                                                    onclick="datatableCargar('LEV')">
                                                                    Atraso Leve</a>
                                                            </div>
                                                        </div>
                                                        <!-- /.info-box -->
                                                    </div>
                                                    <div class="col-12 col-sm-9 col-md-2 float-left">
                                                        <div class="info-box info-box-t">
                                                            <span
                                                                :class="currentTab === 4 ?
                                                                    'info-box-icon info-box-icon-t bg-warning elevation-1 btnActivo' :
                                                                    'info-box-icon info-box-icon-t bg-warning elevation-1 '"
                                                                v-text="moderado" style="color: #ffffff!important;">
                                                            </span>
                                                            <div
                                                                :class="currentTab === 4 ? 'info-box-content btnActivo' :
                                                                    'info-box-content'">
                                                                <a href="#" class="info-box-text h6 "
                                                                    v-on:click="currentTab = 4;"
                                                                    :class="{ link_seleccionado: currentTab === 4 }"
                                                                    onclick="datatableCargar('MOD')">
                                                                    Atraso Moderado</a>
                                                            </div>
                                                        </div>
                                                        <!-- /.info-box -->
                                                    </div>
                                                    <div class="col-12 col-sm-9 col-md-2 float-left">
                                                        <div class="info-box info-box-t">
                                                            <span
                                                                :class="currentTab === 5 ?
                                                                    'info-box-icon info-box-icon-t bg-danger elevation-1 btnActivo' :
                                                                    'info-box-icon info-box-icon-t bg-danger elevation-1 '"
                                                                v-text="grave">
                                                            </span>
                                                            <div
                                                                :class="currentTab === 5 ? 'info-box-content btnActivo' :
                                                                    'info-box-content'">
                                                                <a href="#" class="info-box-text h6 "
                                                                    v-on:click="currentTab = 5;"
                                                                    :class="{ link_seleccionado: currentTab === 5 }"
                                                                    onclick="datatableCargar('GRA')">
                                                                    Atraso Grave</a>
                                                            </div>
                                                        </div>
                                                        <!-- /.info-box -->
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="row">
                                                <div :class="rolMinistro == 0 ? 'col-md-12' : 'hidden'">
                                                    <div class="info-box info-box-t">
                                                        <span
                                                            :class="btnTemporal ?
                                                                'info-box-icon info-box-icon-t bg-primary  btnActivo' :
                                                                'info-box-icon info-box-icon-t bg-primary elevation-1'"
                                                            v-text="temporales_">
                                                        </span>
                                                        <div
                                                            :class="btnTemporal ? 'info-box-content btnActivo' :
                                                                'info-box-content'">
                                                            <a href="#" class="info-box-text h6 "
                                                                v-on:click="currentTab = 13;buscarTemporales()"
                                                                :class="{ link_seleccionado: currentTab === 13 }">
                                                                Temporales</a>
                                                        </div>
                                                    </div>
                                                    <!-- /.info-box -->
                                                </div>
                                                <div :class="rolMinistro == 0 ? 'col-md-12' : 'hidden'">
                                                    <div class="info-box info-box-t">
                                                        <span
                                                            :class="asignaciones ?
                                                                'info-box-icon info-box-icon-t bg-primary  btnActivo' :
                                                                'info-box-icon info-box-icon-t bg-primary elevation-1'"
                                                            v-text="asignaciones_">
                                                        </span>
                                                        <div
                                                            :class="asignaciones ? 'info-box-content btnActivo' :
                                                                'info-box-content'">
                                                            <a href="#" class="info-box-text h6 "
                                                                v-on:click="currentTab = 11;buscarAsignaciones()"
                                                                :class="{ link_seleccionado: currentTab === 11 }">
                                                                Mis asignaciones</a>
                                                        </div>
                                                    </div>
                                                    <!-- /.info-box -->
                                                </div>
                                                <div :class="rolMinistro == 0 ? 'col-md-12' : 'hidden'">
                                                    <div class="info-box info-box-t">
                                                        <span
                                                            :class="btnPendientes ?
                                                                'info-box-icon info-box-icon-t bg-primary btnActivo' :
                                                                'info-box-icon info-box-icon-t bg-primary elevation-1'"
                                                            v-text="pendientes_">
                                                        </span>
                                                        <div
                                                            :class="btnPendientes ? 'info-box-content btnActivo' :
                                                                'info-box-content'">
                                                            <a href="#" class="info-box-text h6 "
                                                                v-on:click="currentTab = 12;buscarPendientes()"
                                                                :class="{ link_seleccionado: currentTab === 12 }">
                                                                Mis Pendientes</a>
                                                        </div>
                                                    </div>
                                                    <!-- /.info-box -->
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-10 btnTop card-body">
                                    <button type="button" :class="rolMinistro == 0 ? 'btn btn-primary btnTop' : 'hidden'"
                                        data-toggle="modal" data-target="#modal-default" v-on:click="limpiarForm()"
                                        data-backdrop="static" data-keyboard="false">
                                        <i class="fa fa-plus"></i>&nbsp; Nuevo Compromiso
                                    </button>
                                </div>

                                <div class="col-md-1 btnTop  card-body"
                                    style="float:right;padding-right: 0px;
                        padding-left: 0px;">
                                    <button class="btn btn-default btn-block  btn-sm" data-toggle="modal"
                                        data-target="#modal-excel" data-backdrop="static" data-keyboard="false"><i
                                            class="fa fa-filter"></i>&nbsp;Filtrar</button>

                                </div>
                                <div class="col-md-1 btnTop  card-body"
                                    style="float:right;padding-right: 0px;
                                        padding-left: 0px;"
                                    v-show="filtro">
                                    <button class="btn btn-primary  btn-sm" v-on:click="quitarFiltro()"><i
                                            class="fa fa-list"></i></button>
                                </div>
                                <div class="col-md-1 btnTop  card-body"
                                    style="float:right;padding-right: 0px;
                                            padding-left: 0px;"
                                    v-show="!filtro">
                                    <button class="btn btn-primary   btn-sm" disabled><i class="fa fa-circle"
                                            style="font-size: 10px;"></i> </button>
                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>
            <div class="card-body ">
                <div class="col-md-12" v-show="gestiones">
                    <button id="BotonDatatable" onclick="datatableCargar()" class="hidden"></button>
                    <div class="table table-responsive" id="tablaConsulta">
                        <table class="table table-bordered table-striped" id="dtmenu"
                            style="width:100%!important">
                            <thead>
                                <th width='5%'>Id</th>
                                <th width='40%'>Nombre del Compromiso</th>
                                <th width='10%'>Instituci&oacute;n</th>
                                <th width='10%'>Gabinete</th>
                                <th width='10%'>Fecha Inicio</th>
                                <th width='10%'>Fecha Fin</th>
                                <th width='10%'>Estado de Gestión</th>
                                <th width='10%'>Estado del Compromiso</th>
                                <th width="5%"></th>
                            </thead>
                            <tbody id="tbobymenu" class="menu-pen">
                                <tr v-for="valor in datos">
                                    <td v-text="valor.reg_"></td>
                                    <td v-text="valor.nombre_"></td>
                                    <td v-text="valor.institucion_"></td>
                                    <td v-text="valor.gabinete_"></td>
                                    <td v-text="valor.fecha_inicio_"></td>
                                    <td v-text="valor.fecha_fin_"></td>
                                    <td v-text="valor.estado_porcentaje_"></td>
                                    <td v-text="valor.estado_"></td>
                                    <td>
                                        <button title="Editar" class="btn btn-primary  btn-xs" data-toggle="modal"
                                            data-target="#modal-default" v-on:click="editar(valor.id,'aprobado')"
                                            data-backdrop="static" data-keyboard="false"><i
                                                class="fa fa-edit"></i></button>

                                    </td>
                                </tr>
                                <tr v-show="datos.length==0">
                                    <td style="text-align:center" colspan="12">No se encuentran datos para mostrar
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <nav>
                            <ul class="pagination">
                                <li v-if="pagination.current_page > 1" class="paginador">
                                    <a href="#" @click.prevent="changePage(pagination.current_page - 1)">
                                        <span>Atras</span>
                                    </a>
                                </li>

                                <li v-for="page in pagesNumber"
                                    v-bind:class="[ page == isActived ? 'active_page paginador' : 'paginador']">
                                    <a href="#" @click.prevent="changePage(page)"
                                        v-bind:class="[ page == isActived ? 'active_page ' : '']">
                                        @{{ page }}
                                    </a>
                                </li>

                                <li v-if="pagination.current_page < pagination.last_page" class="paginador">
                                    <a href="#" @click.prevent="changePage(pagination.current_page + 1)">
                                        <span>Siguiente</span>
                                    </a>
                                </li>
                            </ul>
                        </nav>
                    </div>

                </div>

                <div class="col-md-12" v-show="calendario">
                    <div id="menu">
                        <span id="menu-navi">
                            <button type="button" class="btn btn-default btn-sm move-today"
                                data-action="move-today">Hoy</button>
                            <button type="button" class="btn btn-default btn-sm move-day" data-action="move-prev">
                                <i class="calendar-icon ic-arrow-line-left" data-action="move-prev"></i>
                            </button>

                            <button type="button" class="btn btn-default btn-sm move-day" data-action="move-next">
                                <i class="calendar-icon ic-arrow-line-right" data-action="move-next"></i>
                            </button>
                        </span>

                        <span id="renderRange" class="render-range"></span>

                    </div>
                    <div id="calendar"></div>
                </div>
                <!-- PESTAÑA BUSQUEDAS AVANZADAS -->
                <div class="col-md-12" v-show="busquedas" id="busquedas_avanzadas"><br>
                    <div class="row">
                        <!--CONSULTA DE ESTADOS-->
                        <div class="col-md-6 btnTop" style="padding-bottom:0px!important;padding-top:0px!important">
                            <br>
                            <div class="row">
                                <div class="col-12 col-sm-9 col-md-2 float-left">
                                    <div class="info-box info-box-t">
                                        <span
                                            :class="currentTab_ === 5 ?
                                                'info-box-icon info-box-icon-t bg-primary elevation-1 ' :
                                                'info-box-icon info-box-icon-t bg-primary elevation-1 '"
                                            v-text="mensajes_busqueda" style="color: #ffffff!important;width: 50px;">
                                        </span>
                                        <div :class="currentTab_ === 5 ? 'info-box-content ' : 'info-box-content'">
                                            <a href="#" class="info-box-text h6 " v-on:click="currentTab_ = 5;"
                                                :class="{ link_seleccionado: currentTab_ === 4 }"
                                                onclick="datatableCompromisosBusquedas('MENSAJES')">
                                                MENSAJES</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-9 col-md-2 float-left">
                                    <div class="info-box info-box-t">
                                        <span
                                            :class="currentTab_ === 5 ?
                                                'info-box-icon info-box-icon-t bg-primary elevation-1 ' :
                                                'info-box-icon info-box-icon-t bg-primary elevation-1 '"
                                            v-text="archivos_busqueda" style="color: #ffffff!important;width: 50px;">
                                        </span>
                                        <div :class="currentTab_ === 5 ? 'info-box-content ' : 'info-box-content'">
                                            <a href="#" class="info-box-text h6 " v-on:click="currentTab_ = 5;"
                                                :class="{ link_seleccionado: currentTab_ === 5 }"
                                                onclick="datatableCompromisosBusquedas('ARCHIVOS')">
                                                ARCHIVOS
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-9 col-md-2 float-left">
                                    <div class="info-box info-box-t">
                                        <span
                                            :class="currentTab_ === 5 ?
                                                'info-box-icon info-box-icon-t bg-primary elevation-1 ' :
                                                'info-box-icon info-box-icon-t bg-primary elevation-1 '"
                                            v-text="avances_busqueda" style="color: #ffffff!important;width: 50px;">
                                        </span>
                                        <div :class="currentTab_ === 5 ? 'info-box-content ' : 'info-box-content'">
                                            <a href="#" class="info-box-text h6 " v-on:click="currentTab_ = 5;"
                                                :class="{ link_seleccionado: currentTab_ === 5 }"
                                                onclick="datatableCompromisosBusquedas('AVANCES')">
                                                AVANCES</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--FIN - CONSULTA DE ESTADOS-->
                        <!--CONSULTA POR GABINETE, INSTITUCION, MONITOR-->
                        <div class="col-sm-6">
                            <div class="row">
                                <div class="col-md-4">
                                    <label>Gabinete:</label>
                                    <div class="input-group">
                                        <select class="form-control form-control-sm select2" id="gabinete_id_busqueda"
                                            name="gabinete_id_busqueda">
                                            <option value="" selected>TODOS LOS GABINETES</option>
                                            <option v-for="value in arrayGabineteBusqueda" :value="value.id"
                                                v-text="value.descripcion">
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label>Institución responsable:</label>
                                    <div class="input-group">
                                        <select class="form-control form-control-sm select2"
                                            id="institucion_id_busqueda" name="institucion_id_busqueda">
                                            <option value="" selected>TODAS LAS INSTITUCIONES</option>
                                            <option v-for="value in arrayInstitucionBusqueda" :value="value.id"
                                                v-text="value.descripcion">
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label>Monitor:</label>
                                    {!! Form::select('monitor_busqueda', $monitores, null, [
                                        'placeholder' => 'TODOS LOS MONITORES',
                                        'class' => 'form-control ',
                                        'id' => 'monitor_busqueda',
                                    ]) !!}
                                </div>
                                <span class="input-group-btn">&nbsp;
                                    <button class="btn btn-default" type="button"
                                        onclick="datatableCompromisosBusquedas()">
                                        <span class="fa fa-search">&nbsp;Buscar</span>
                                    </button>
                                </span>
                            </div>
                        </div>
                        <!--FIN CONSULTA POR GABINETE, INSTITUCION, MONITOR-->
                        <div class="card-body">
                            <div class="table table-responsive" id="tablaConsulta">
                                <table class="table table-bordered table-striped" id="dtmenu_busquedas"
                                    style="width:100%!important">
                                    <thead>
                                        <th>Id</th>
                                        <th>Nombre del Compromiso</th>
                                        <th>Instituci&oacute;n</th>
                                        <th>Gabinete</th>
                                        <th>Fecha Inicio</th>
                                        <th>Fecha Fin</th>
                                        <th>Estado de Gesti&oacute;n</th>
                                        <th>Acciones</th>
                                    </thead>
                                    <tbody id="tbobymenu" class="menu-pen">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>


    </div>
    @include('modules.compromisos.ingreso')
    @include('modules.compromisos.modal_busquedas')
    <!--formulario-->
</div>

<script src="{{ url('js/vue.js') }}"></script>
<script src="{{ url('js/axios.js') }}"></script>
<script src="{{ url('js/modules/compromisos/vue_compromisos.js?v=13') }}"></script>

@endsection
