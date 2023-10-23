@extends('layouts.app')

@section('contentheader_title')
    Compromisos
@endsection

@section('contentheader_description')
    @auth
        Gesti&oacute;n / {!! Auth::user()->evaluarole(['MINISTRO']) ? 'Ministro' : 'Monitor' !!}
    @endauth
@endsection

@section('css')
    <link href="{{ url('adminlte/plugins/notifications/sweetalert.css') }}" rel="stylesheet">
    <link href="{{ url('adminlte/style_moderno.css') }}" rel="stylesheet">
    <link href="{{ url('adminlte3/plugins/tui-calendar/css/tui-calendar.css') }}" rel="stylesheet">
    <link href="{{ url('adminlte3/plugins/tui-calendar/css/tui-date-picker.css') }}" rel="stylesheet">
    <link href="{{ url('adminlte3/plugins/tui-calendar/css/icons.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('adminlte3/plugins/fullcalendar/main.css') }}">

    <style>
        .modal-dialog_ {
            width: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
        }

        .info-box .info-box-content {
            display: -webkit-flex;
            display: -ms-flexbox;
            display: flex;
            -webkit-flex-direction: column;
            -ms-flex-direction: column;
            flex-direction: column;
            -webkit-justify-content: center;
            -ms-flex-pack: center;
            justify-content: center;
            line-height: 1.8;
            -webkit-flex: 1;
            -ms-flex: 1;
            flex: 1;
            padding: 0 5px;
            padding-right: 5px !important;
            padding-right: 0px !important;
        }
    </style>
@endsection
@section('javascript')
    @include('modules.compromisos.scripts_selectores')
@endsection
@section('content')
    <div id="main">
        @auth
            <input type="hidden" id="rolMinistro" content="{{ Auth::user()->evaluarole(['MINISTRO']) }}" v-model="rolMinistro">
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
                        <div class="col-md-12">
                            <div class="row">
                                <!--FIN FILTRO PASTEL Y PERZONALIZADO-->
                                <div class="col-md-2 " style="float:right;padding-right: 0px" v-show="!gestiones"
                                    v-on:click="cargarGestiones()">
                                    <button class="btn btn-default btn-block  btn-sm">&nbsp;Gestiones</button>
                                </div>
                                <div class="col-md-2 " style="float:right;padding-right: 0px" v-show="gestiones">
                                    <button class="btn btn-primary btn-block  btn-sm" disabled>&nbsp;Gestiones</button>
                                </div>
                                <div class="col-md-2 " style="float:right;padding-right: 0px;padding-left: 0px"
                                    v-show="!calendario" v-on:click="cargarCalendario()">
                                    <button class="btn btn-default btn-block  btn-sm">&nbsp;Calendario de Reportes</button>
                                </div>
                                <div class="col-md-2 " style="float:right;padding-right: 0px; padding-left: 0px"
                                    v-show="calendario">
                                    <button class="btn btn-primary btn-block  btn-sm" disabled>&nbsp;Calendario de
                                        Reportes</button>
                                </div>
                                <div class="col-md-3 " style="float:right;padding-right: 0px; padding-left: 0px"
                                    v-show="!calendario_finalizacion" v-on:click="cargarCalentarioFinalizaciones();">
                                    <button class="btn btn-default btn-block  btn-sm">&nbsp;Calendario de
                                        Finalización</button>
                                </div>
                                <div class="col-md-4 " style="float:right;padding-right: 0px; padding-left: 0px"
                                    v-show="calendario_finalizacion">
                                    <button class="btn btn-primary btn-block  btn-sm" disabled>&nbsp;Calendario de
                                        Finalización</button>
                                </div>
                            
                                <div :class="rolMinistro == 0 ? 'col-md-2 ' : 'hidden'"
                                    style="float:right;padding-right: 0px;padding-left: 0px"
                                    v-show="!exportaciones" onclick="cargarExportaciones();">
                                    <button class="btn btn-default btn-block  btn-sm">&nbsp;Exportaciones</button>
                                </div>
                                <div :class="rolMinistro == 0 ? 'col-md-2 ' : 'hidden'"
                                    style="float:right;padding-right: 0px; padding-left: 0px"
                                    v-show="exportaciones">
                                    <button class="btn btn-primary btn-block  btn-sm" disabled>&nbsp;Exportaciones</button>
                                </div>
                                <!--FIN PESTAÑAS-->
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body ">
                    <!--GESTIONES-->
                    <div class="col-md-12" v-show="gestiones">
                        <div class="col-md-12 btnTop">
                            <div class="row">
                                @include('modules.compromisos.filtro_estados')
                                <div class="col-md-10 btnTop card-body">
                                    <button type="button"
                                        :class="rolMinistro == 0 ? 'btn btn-primary btnTop' : 'hidden'"
                                        data-toggle="modal" data-target="#modal-default" v-on:click="limpiarForm()"
                                        data-backdrop="static" data-keyboard="false">
                                        <i class="fa fa-plus"></i>&nbsp; Nuevo Compromiso
                                    </button>
                                </div>
                                @auth
                                    @if (!Auth::user()->evaluarole(['MINISTRO']))
                                        <div class="col-md-1 btnTop  card-body"
                                            style="float:right;padding-right: 0px;padding-left: 0px;">
                                            <button class="btn btn-default btn-block  btn-sm" data-toggle="modal"
                                                data-target="#modal-excel-monitor" data-backdrop="static"
                                                data-keyboard="false" onclick="app.cargarDatosPorDefecto()"><i
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
                                    @endif
                                @endauth
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12" v-show="gestiones">
                        <button id="BotonDatatable" onclick="datatableCargar()" class="hidden"></button>
                        <div class="table table-responsive hidden">
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
                                            <table>
                                                <tr>
                                                    <td style="padding:2px">
                                                        <button title="Editar" class="btn btn-primary  btn-xs"
                                                            data-toggle="modal" data-target="#modal-default"
                                                            v-on:click="editar(valor.id,'aprobado')"
                                                            data-backdrop="static" data-keyboard="false"><i
                                                                class="fa fa-edit"></i></button>
                                                    </td>
                                                    @if (!Auth::user()->evaluarole(['MINISTRO']))
                                                        <td style="padding:2px"> <button title="Eliminar"
                                                                v-show="valor.codigo==null" class="btn btn-danger  btn-xs"
                                                                v-on:click="eliminarCompromiso(valor.id)"><i
                                                                    class="fa fa-times"></i></button></td>
                                                    @endif
                                                </tr>
                                            </table>
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
                        <div class="table table-responsive">
                            <table class="table table-bordered table-striped" id="dtCompromisos"
                                style="width:100%!important">
                                <thead>

                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>

                    </div>
                    <!--CALENDARIO REPORTES-->
                    <!--CALENDARIO FINALIZACION-->
                    <div id="pagina_calendar" class="pagina-calendar" v-show="calendario || calendario_finalizacion">

                    </div>
                    <!--BUSQUEDAS-->
                    @include('modules.compromisos.busquedas_avanzadas')
                </div>
            </div>
        </div>

        @include('modules.compromisos.modales_gestion.modales')

    </div>

    <script src="{{ url('js/vue.js') }}"></script>
    <script src="{{ url('js/axios.js') }}"></script>
    <script src="{{ url('js/modules/compromisos/vue_compromisos.js?v=65') }}"></script>
@endsection
