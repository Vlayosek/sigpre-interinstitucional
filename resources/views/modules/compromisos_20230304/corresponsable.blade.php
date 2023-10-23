@extends('layouts.app')

@section('contentheader_title')
    Compromisos
@endsection

@section('contentheader_description')
@auth
    Gesti&oacute;n /Corresponsable / {!!Auth::user()->evaluarole(['MINISTRO'])?'Ministro':'Monitor'!!}
    @endauth
@endsection

@section('content')
@section('css')
    <link href="{{ url('adminlte/plugins/notifications/sweetalert.css') }}" rel="stylesheet">
    <link href="{{ url('adminlte/style_moderno.css') }}" rel="stylesheet">

@endsection
@section('javascript')
    <script src="{{ url('adminlte3/plugins/tableEdit/') }}/tabledit.min.js"></script>

    <script src="{{ url('adminlte/plugins/datepicker/') }}/bootstrap-datepicker.js"></script>
    <script src="{{ url('js/modules/compromisos/compromisos.js?v=19') }}"></script>
    <script src="{{ url('js/modules/compromisos/responsables.js') }}"></script>
    <script src="{{ url('js/modules/compromisos/datatableCompromisosCorresponsables.js') }}"></script>

    <script>
        $(document).ready(function() {

            $("#responsable_id").select2({
                placeholder: "SELECCIONE UNA OPCION",
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
 <input type="hidden" id="rolMinistro" content="0"
        v-model="rolMinistro">
 @endguest
    <a href="#" class="hidden" id="botonImprimir" target="_blank" download>Boton Imprimir</a>
    <button id="limpiarJQUERY" class="hidden" onclick="resetCombo()"></button>
    <div class="card">
        <div class="card-heading">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-10">
                                <div class="row">
                                    <div class="col-md-12" style="padding-bottom:0px!important;padding-top:0px!important">
                                        <div class="col-12 col-sm-9 col-md-2 float-left">
                                            <div class="info-box info-box-t">
                                                <span :class="currentTab === 0?'info-box-icon info-box-icon-t bg-primary elevation-1 btnActivo':'info-box-icon info-box-icon-t bg-primary elevation-1 '"
                                                    v-text="registrados">
                                                </span>
                                                <div :class="currentTab === 0?'info-box-content btnActivo':'info-box-content'">
                                                    <a href="#" class="info-box-text h6 " v-on:click="currentTab = 0;"
                                                        :class="{link_seleccionado: currentTab === 0}" onclick="datatableCargar()">
                                                        Registrados</a>
                                                </div>
                                            </div>
                                            <!-- /.info-box -->
                                        </div>

                                    </div>
                                    <div class="col-md-12" style="padding-bottom:0px!important;padding-top:0px!important">
                                        <div class="">
                                            <div class="col-12 col-sm-9 col-md-2 float-left">
                                                <div class="info-box info-box-t">
                                                    <span :class="currentTab === 7?'info-box-icon info-box-icon-t bg-primary elevation-1 btnActivo':'info-box-icon info-box-icon-t bg-primary elevation-1 '"
                                                        v-text="planificacion">
                                                    </span>
                                                    <div :class="currentTab === 7?'info-box-content btnActivo':'info-box-content'">
                                                        <a href="#" class="info-box-text h6 " v-on:click="currentTab = 7;"
                                                            :class="{link_seleccionado: currentTab === 7}"
                                                            onclick="datatableCargar('PLA',2)" >
                                                            En Planificaci&oacute;n</a>
                                                    </div>
                                                </div>
                                                <!-- /.info-box -->
                                            </div>
                                            <div class="col-12 col-sm-9 col-md-2 float-left" style="background:#007bff17">
                                                <div class="info-box info-box-t">
                                                    <span :class="currentTab === 6?'info-box-icon info-box-icon-t bg-primary elevation-1 btnActivo':'info-box-icon info-box-icon-t bg-primary elevation-1 '"
                                                        v-text="ejecucion">
                                                    </span>
                                                    <div :class="currentTab === 6?'info-box-content btnActivo':'info-box-content'">
                                                        <a href="#" class="info-box-text h6 " v-on:click="currentTab = 6;"
                                                            :class="{link_seleccionado: currentTab === 6}"
                                                            onclick="datatableCargar('EJE',2)" >
                                                            En Ejecuci&oacute;n</a>
                                                    </div>
                                                </div>
                                                <!-- /.info-box -->
                                            </div>
                                            <div class="col-12 col-sm-9 col-md-2 float-left">
                                                <div class="info-box info-box-t">
                                                    <span :class="currentTab === 10?'info-box-icon info-box-icon-t bg-primary elevation-1 btnActivo':'info-box-icon info-box-icon-t bg-primary elevation-1 '"
                                                    v-text="standby"
                                                        style="color: #ffffff!important;">
                                                    </span>
                                                    <div :class="currentTab === 10?'info-box-content btnActivo':'info-box-content'">
                                                        <a href="#" class="info-box-text h6 " v-on:click="currentTab = 10;"
                                                            :class="{link_seleccionado: currentTab === 10}"
                                                            onclick="datatableCargar('STA',2)">
                                                            Stand By</a>
                                                    </div>
                                                </div>
                                                <!-- /.info-box -->
                                            </div>
                                            <div class="col-12 col-sm-9 col-md-2 float-left">
                                                <div class="info-box info-box-t">
                                                    <span :class="currentTab === 9?'info-box-icon info-box-icon-t bg-primary elevation-1 btnActivo':'info-box-icon info-box-icon-t bg-primary elevation-1 '" v-text="cerrado">
                                                    </span>
                                                    <div :class="currentTab === 9?'info-box-content btnActivo':'info-box-content'">
                                                        <a href="#" class="info-box-text h6 " v-on:click="currentTab = 9;"
                                                            :class="{link_seleccionado: currentTab === 9}"
                                                            onclick="datatableCargar('CER',2)">
                                                            Cerrado</a>
                                                    </div>
                                                </div>
                                                <!-- /.info-box -->
                                            </div>
                                            <div class="col-12 col-sm-9 col-md-2 float-left">
                                                <div class="info-box info-box-t">
                                                    <span :class="currentTab === 8?'info-box-icon info-box-icon-t bg-primary elevation-1 btnActivo':'info-box-icon info-box-icon-t bg-primary elevation-1 '"
                                                        v-text="cumplido">
                                                    </span>
                                                    <div :class="currentTab === 8?'info-box-content btnActivo':'info-box-content'">
                                                        <a href="#" class="info-box-text h6 " v-on:click="currentTab = 8;"
                                                            :class="{link_seleccionado: currentTab === 8}"
                                                            onclick="datatableCargar('CUM',2)">
                                                            Cumplido</a>
                                                    </div>
                                                </div>
                                                <!-- /.info-box -->
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12" style="padding-bottom:0px!important;padding-top:0px!important;background:#007bff17">
                                        <div class="col-12 col-sm-9 col-md-2 float-left">
                                            <div class="info-box info-box-t">
                                                <span :class="currentTab === 1?'info-box-icon info-box-icon-t bg-primary elevation-1 btnActivo':'info-box-icon info-box-icon-t bg-primary elevation-1 '"
                                                v-text="optimo">
                                                </span>
                                                <div :class="currentTab === 1?'info-box-content btnActivo':'info-box-content'">
                                                    <a href="#" class="info-box-text h6 " v-on:click="currentTab = 1;"
                                                        :class="{link_seleccionado: currentTab === 1}"
                                                        onclick="datatableCargar('OPT')">
                                                        Optimo</a>
                                                </div>
                                            </div>
                                            <!-- /.info-box -->
                                        </div>
                                        <div class="col-12 col-sm-9 col-md-2 float-left">
                                            <div class="info-box info-box-t">
                                                <span :class="currentTab === 2?'info-box-icon info-box-icon-t bg-primary elevation-1 btnActivo':'info-box-icon info-box-icon-t bg-primary elevation-1 '"
                                                v-text="bueno">
                                                </span>
                                                <div :class="currentTab === 2?'info-box-content btnActivo':'info-box-content'">
                                                    <a href="#" class="info-box-text h6 " v-on:click="currentTab = 2;"
                                                        :class="{link_seleccionado: currentTab === 2}"
                                                        onclick="datatableCargar('BUE')">
                                                        Bueno</a>
                                                </div>
                                            </div>
                                            <!-- /.info-box -->
                                        </div>
                                        <div class="col-12 col-sm-9 col-md-2 float-left">
                                            <div class="info-box info-box-t">
                                                <span :class="currentTab === 3?'info-box-icon info-box-icon-t bg-warning elevation-1 btnActivo':'info-box-icon info-box-icon-t bg-warning elevation-1 '"
                                                v-text="leve"
                                                    style="color: #ffffff!important;">
                                                </span>
                                                <div :class="currentTab === 3?'info-box-content btnActivo':'info-box-content'">
                                                    <a href="#" class="info-box-text h6 " v-on:click="currentTab = 3;"
                                                        :class="{link_seleccionado: currentTab === 3}"
                                                        onclick="datatableCargar('LEV')">
                                                        Atraso Leve</a>
                                                </div>
                                            </div>
                                            <!-- /.info-box -->
                                        </div>
                                        <div class="col-12 col-sm-9 col-md-2 float-left">
                                            <div class="info-box info-box-t">
                                                <span :class="currentTab === 4?'info-box-icon info-box-icon-t bg-warning elevation-1 btnActivo':'info-box-icon info-box-icon-t bg-warning elevation-1 '"
                                                v-text="moderado"
                                                    style="color: #ffffff!important;">
                                                </span>
                                                <div :class="currentTab === 4?'info-box-content btnActivo':'info-box-content'">
                                                    <a href="#" class="info-box-text h6 " v-on:click="currentTab = 4;"
                                                        :class="{link_seleccionado: currentTab === 4}"
                                                        onclick="datatableCargar('MOD')">
                                                        Atraso Moderado</a>
                                                </div>
                                            </div>
                                            <!-- /.info-box -->
                                        </div>
                                        <div class="col-12 col-sm-9 col-md-2 float-left">
                                            <div class="info-box info-box-t">
                                                <span :class="currentTab === 5?'info-box-icon info-box-icon-t bg-danger elevation-1 btnActivo':'info-box-icon info-box-icon-t bg-danger elevation-1 '"
                                                v-text="grave">
                                                </span>
                                                <div :class="currentTab === 5?'info-box-content btnActivo':'info-box-content'">
                                                    <a href="#" class="info-box-text h6 " v-on:click="currentTab = 5;"
                                                        :class="{link_seleccionado: currentTab === 5}"
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
                                    <div :class="rolMinistro==0?'col-md-12':'hidden'">
                                        <div class="info-box info-box-t">
                                            <span :class="btnTemporal?'info-box-icon info-box-icon-t bg-primary  btnActivo':'info-box-icon info-box-icon-t bg-primary elevation-1'"
                                                v-text="temporales_">
                                            </span>
                                            <div :class="btnTemporal?'info-box-content btnActivo':'info-box-content'">
                                                <a href="#" class="info-box-text h6 " v-on:click="currentTab = 13;buscarTemporales()"
                                                    :class="{link_seleccionado: currentTab === 13}">
                                                    Temporales</a>
                                            </div>
                                        </div>
                                        <!-- /.info-box -->
                                    </div>
                                    <div :class="rolMinistro==0?'col-md-12':'hidden'">
                                        <div class="info-box info-box-t">
                                            <span :class="asignaciones?'info-box-icon info-box-icon-t bg-primary  btnActivo':'info-box-icon info-box-icon-t bg-primary elevation-1'"
                                                v-text="asignaciones_">
                                            </span>
                                            <div :class="asignaciones?'info-box-content btnActivo':'info-box-content'">
                                                <a href="#" class="info-box-text h6 " v-on:click="currentTab = 11;buscarAsignaciones()"
                                                    :class="{link_seleccionado: currentTab === 11}">
                                                    Mis asignaciones</a>
                                            </div>
                                        </div>
                                        <!-- /.info-box -->
                                    </div>
                                    <div :class="rolMinistro==0?'col-md-12':'hidden'">
                                        <div class="info-box info-box-t">
                                            <span :class="btnPendientes?'info-box-icon info-box-icon-t bg-primary btnActivo':'info-box-icon info-box-icon-t bg-primary elevation-1'"
                                                v-text="pendientes_">
                                            </span>
                                            <div :class="btnPendientes?'info-box-content btnActivo':'info-box-content'">
                                                <a href="#" class="info-box-text h6 " v-on:click="currentTab = 12;buscarPendientes()"
                                                    :class="{link_seleccionado: currentTab === 12}">
                                                    Mis Pendientes</a>
                                            </div>
                                        </div>
                                        <!-- /.info-box -->
                                    </div>
                                </div>

                            </div>


                        </div>
                    </div>

                    <div :class="rolMinistro==0?'col-md-10 btnTop':'hidden'">
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-default"
                            v-on:click="limpiarForm()" data-backdrop="static" data-keyboard="false">
                            <i class="fa fa-plus"></i>&nbsp; Nuevo Compromiso
                        </button>
                    </div>
                    <div :class="rolMinistro==0?'col-md-2':'hidden'" style="float: right;">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-md-12">
                                    <button type="button" class="btn btn-primary" style="float:right" data-toggle="modal" data-target="#modal-filtrado">
                                        Filtrado &nbsp;<i class="fa fa-filter"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="card-body ">
            <button id="BotonDatatable" onclick="datatableCargar()" class="hidden"></button>
            <div class="table table-responsive" id="tablaConsulta">
                <table class="table table-bordered table-striped" id="dtmenu" style="width:100%!important">
                    <thead>
                        <th>Id</th>
                        <th>Nombre del Compromiso</th>
                        <th>Tipo Compromiso</th>
                        <th class="hidden">Institución</th>
                        <th class="hidden">Instituci&oacute;n  Corresponsables</th>
                        <th class="hidden">Gabinete Sectorial</th>
                        <th>Fecha Inicio</th>
                        <th>Fecha Fin</th>
                        <th>Estado de Gestion</th>
                        <th>Estado del Compromiso</th>
                        <th class="hidden">Avance</th>
                        <th width="20%"></th>
                    </thead>
                    <tbody id="tbobymenu" class="menu-pen">

                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @include('modules.compromisos.ingreso')

</div>

<script src="{{ url('js/vue.js') }}"></script>
<script src="{{ url('js/axios.js') }}"></script>
<script src="{{ url('js/modules/compromisos/vue_compromisos.js') }}"></script>

@endsection
