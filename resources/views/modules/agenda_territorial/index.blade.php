@extends('layouts.app')

@section('contentheader_title')
    Agenda Territorial
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
@endsection
@section('javascript')
    <script src="{{ url('adminlte/plugins/datepicker/') }}/bootstrap-datepicker.js"></script>
    <script src="{{ url('js/modules/agenda_territorial/script.js?v=23') }}"></script>
    <script src="{{ url('js/modules/agenda_territorial/responsables.js?v=8') }}"></script>

    <script>
        $(document).ready(function() {

            $("#porcentaje_avance_obra_complementaria").on({
                "keyup": function(event) {
                    $(event.target).val(function(index, value) {
                        var vari = value.replace(/\D/g, "");
                        if (vari > 100 || vari < 5) {
                            alertToast("El porcentaje de avance es de 5 a 100");
                            return false;
                        }
                        return vari;
                    });
                }
            });
            $("#responsable_id").select2({
                placeholder: "SELECCIONE UNA OPCION",
                ajax: {
                    url: "{{ route('agenda_territorial/buscarResponsable') }}",
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
                    url: "{{ route('agenda_territorial/buscarInstitucion') }}",
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

            /*   $("#institucion_id_exportar").select2({
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
               });*/

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
            $("#fecha_inicio").on("change", function() {
                var fecha_inicio = $("#fecha_inicio").val();
                if (fecha_inicio != null&&fecha_inicio!="") {
                    var fecha1 = moment();
                    var fecha2 = moment(fecha_inicio);
                    var fecha3 = fecha2.diff(fecha1, 'days');
                    if (fecha3 < 0) {
                        alertToast("La fecha sugerida no puede ser menor a la fecha actual", 3500);
                        app.formCrear.fecha_inicio='';
                        return false;
                    }
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
    <div class="card">
        <div class="card-heading">
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
                                                    :class="currentTab === 0?'info-box-icon info-box-icon-t bg-primary elevation-1 btnActivo':'info-box-icon info-box-icon-t bg-primary elevation-1 '"
                                                    v-text="registrados">
                                                </span>
                                                <div
                                                    :class="currentTab === 0?'info-box-content btnActivo':'info-box-content'">
                                                    <a href="#" class="info-box-text h6 " v-on:click="currentTab = 0;"
                                                        :class="{link_seleccionado: currentTab === 0}"
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
                                                        :class="currentTab === 7?'info-box-icon info-box-icon-t bg-primary elevation-1 btnActivo':'info-box-icon info-box-icon-t bg-primary elevation-1 '"
                                                        v-text="planificacion">
                                                    </span>
                                                    <div
                                                        :class="currentTab === 7?'info-box-content btnActivo':'info-box-content'">
                                                        <a href="#" class="info-box-text h6 "
                                                            v-on:click="currentTab = 7;"
                                                            :class="{link_seleccionado: currentTab === 7}"
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
                                                        :class="currentTab === 6?'info-box-icon info-box-icon-t bg-primary elevation-1 btnActivo':'info-box-icon info-box-icon-t bg-primary elevation-1 '"
                                                        v-text="agendado">
                                                    </span>
                                                    <div
                                                        :class="currentTab === 6?'info-box-content btnActivo':'info-box-content'">
                                                        <a href="#" class="info-box-text h6 "
                                                            v-on:click="currentTab = 6;"
                                                            :class="{link_seleccionado: currentTab === 6}"
                                                            onclick="datatableCargar('AGE',2)">
                                                            Agendado</a>
                                                    </div>
                                                </div>
                                                <!-- /.info-box -->
                                            </div>
                                            <div class="col-12 col-sm-9 col-md-2 float-left">
                                                <div class="info-box info-box-t">
                                                    <span
                                                        :class="currentTab === 10?'info-box-icon info-box-icon-t bg-primary elevation-1 btnActivo':'info-box-icon info-box-icon-t bg-primary elevation-1 '"
                                                        v-text="cumplido" style="color: #ffffff!important;">
                                                    </span>
                                                    <div
                                                        :class="currentTab === 10?'info-box-content btnActivo':'info-box-content'">
                                                        <a href="#" class="info-box-text h6 "
                                                            v-on:click="currentTab = 10;"
                                                            :class="{link_seleccionado: currentTab === 10}"
                                                            onclick="datatableCargar('CUM',2)">
                                                            Cumplido</a>
                                                    </div>
                                                </div>
                                                <!-- /.info-box -->
                                            </div>
                                            <div class="col-12 col-sm-9 col-md-2 float-left">
                                                <div class="info-box info-box-t">
                                                    <span
                                                        :class="currentTab === 9?'info-box-icon info-box-icon-t bg-primary elevation-1 btnActivo':'info-box-icon info-box-icon-t bg-primary elevation-1 '"
                                                        v-text="descartado">
                                                    </span>
                                                    <div
                                                        :class="currentTab === 9?'info-box-content btnActivo':'info-box-content'">
                                                        <a href="#" class="info-box-text h6 "
                                                            v-on:click="currentTab = 9;"
                                                            :class="{link_seleccionado: currentTab === 9}"
                                                            onclick="datatableCargar('DES',2)">
                                                            Descartado</a>
                                                    </div>
                                                </div>
                                                <!-- /.info-box -->
                                            </div>

                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="row ">

                                    <div :class="rolMinistro==0?'col-md-12':'hidden'">
                                        <div class="info-box info-box-t">
                                            <span
                                                :class="asignaciones?'info-box-icon info-box-icon-t bg-primary  btnActivo':'info-box-icon info-box-icon-t bg-primary elevation-1'"
                                                v-text="asignaciones_">
                                            </span>
                                            <div :class="asignaciones?'info-box-content btnActivo':'info-box-content'">
                                                <a href="#" class="info-box-text h6 "
                                                    v-on:click="currentTab = 11;buscarAsignaciones()"
                                                    :class="{link_seleccionado: currentTab === 11}">
                                                    Mis asignaciones</a>
                                            </div>
                                        </div>
                                        <!-- /.info-box -->
                                    </div>
                                    <div class="col-md-12">
                                        <div class="row">

                                            <div class="col-md-12">
                                                <div class="input-group" style="padding-top:5px">
                                                    <button class="btn btn-default   btn-sm col-md-10"
                                                        data-toggle="modal" data-target="#modal-excel"
                                                        data-backdrop="static" data-keyboard="false"><i
                                                            class="fa fa-filter"></i>&nbsp;Filtrar</button>
                                                    <span class="input-group-btn">&nbsp;
                                                        <button class="btn btn-primary  btn-sm" v-show="filtro"
                                                            v-on:click="quitarFiltro()"><i
                                                                class="fa fa-list"></i></button>
                                                        <button class="btn btn-primary   btn-sm" disabled
                                                            v-show="!filtro"><i class="fa fa-circle"
                                                                style="font-size: 10px;"></i> </button>
                                                    </span>
                                                </div>
                                            </div>

                                        </div>

                                    </div>

                                </div>

                            </div>


                        </div>
                    </div>

                    <div class="col-md-9 btnTop card-body">
                        <button type="button" :class="rolMinistro==1?'btn btn-primary btnTop':'hidden' "
                            data-toggle="modal" data-target="#modal-default"
                            v-on:click="limpiarForm();buscarResponsable();" data-backdrop="static"
                            data-keyboard="false">
                            <i class="fa fa-plus"></i>&nbsp; Nueva Actividad
                        </button>
                    </div>


                    <div class="col-md-3">
                        <label>Buscar: </label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="buscarAgenda"
                                placeholder="(Tema, Prioridad, Reg)">
                            <span class="input-group-btn">&nbsp;
                                <button class="btn btn-default" type="button" onclick="datatableCargar()">
                                    <span class="fa fa-search"></span>&nbsp;Buscar
                                </button>
                            </span>
                        </div>
                    </div>

                </div>
            </div>

        </div>
        <div class="card-body ">
            <div class="table table-responsive" id="tablaConsulta">
                <table class="table table-bordered table-striped" id="dtmenuAgenda" style="width:100%!important">
                    <thead>
                        <th>Reg</th>
                        <th>Fecha</th>
                        <th>Tipo de Actividad</th>
                        <th >Institucion</th>
                        <th >Gabinete</th>
                        <th>Tema</th>
                        <th>Descripcion</th>
                        <th class="hidden">Inversión</th>
                        <th class="hidden">Beneficiarios</th>
                        <th>Provincia</th>
                        <th>Ciudad</th>
                        <th>Parroquia</th>
                        <th>Estado</th>
                        <th>Coyuntura</th>
                        <th>Impacto</th>
                        <th>Observación</th>
                        <th>Acciones</th>
                    </thead>
                    <tbody id="tbobymenuAgenda" class="menu-pen">

                    </tbody>
                </table>

            </div>
        </div>
        <div class="card-body hidden">
            <button id="BotonDatatable" onclick="datatableCargar()" class="hidden"></button>
            <div class="table table-responsive" id="tablaConsulta">
                <table class="table table-bordered table-striped" id="dtmenu" style="width:100%!important">
                    <thead>
                        <th width='5%'>Id</th>
                        <th width='40%'>Tema</th>
                        <th width='10%'>Fecha Sugerida</th>
                        <th width='10%'>Prioridad</th>
                        <th width='10%'>Estado</th>
                        <th width="5%"></th>
                    </thead>
                    <tbody id="tbobymenu" class="menu-pen">
                        <tr v-for="valor in datos">
                            <td v-text="valor.reg_"></td>
                            <td v-text="valor.nombre_"></td>
                            <td v-text="valor.fecha_inicio_"></td>
                            <td v-text="valor.estado_"></td>
                            <td v-text="valor.estado_porcentaje_"></td>
                            <td>


                            </td>
                        </tr>
                        <tr v-show="datos.length==0">
                            <td style="text-align:center" colspan="12">No se encuentran datos para mostrar</td>
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
    </div>
    @include('modules.agenda_territorial.modal')

</div>

<script src="{{ url('js/vue.js') }}"></script>
<script src="{{ url('js/axios.js') }}"></script>
<script src="{{ url('js/modules/agenda_territorial/vue_script.js?v=27') }}"></script>

@endsection
