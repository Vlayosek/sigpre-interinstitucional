@extends('layouts.app')

@section('contentheader_title')
    Compromisos
@endsection

@section('contentheader_description')
    Migracion
@endsection

@section('css')
    <link href="{{ url('adminlte/plugins/notifications/sweetalert.css') }}" rel="stylesheet">
    {!! Html::style('adminlte/plugins/fileinput/fileinput.min.css') !!}
    <link href="{{ url('adminlte/style_moderno2.css') }}" rel="stylesheet">
    <link href="{{ url('adminlte3/plugins/chosen/css/chosen.css') }}" rel="stylesheet">
    <style>
        table.dataTable tr th.select-checkbox.selected::after {
            content: "✔";
            margin-top: -11px;
            margin-left: -4px;
            text-align: center;
            text-shadow: rgb(176, 190, 217) 1px 1px, rgb(176, 190, 217) -1px -1px, rgb(176, 190, 217) 1px -1px, rgb(176, 190, 217) -1px 1px;
        }

        .h6 {
            font-size: 10px;
        }

        .info-box-t .info-box-icon-t {
            border-radius: .25rem;
            -webkit-align-items: center;
            -ms-flex-align: center;
            align-items: center;
            display: -webkit-flex;
            display: -ms-flexbox;
            display: flex;
            font-size: 12px;
            -webkit-justify-content: center;
            -ms-flex-pack: center;
            justify-content: center;
            text-align: center;
            width: 30px;
        }

        .float {
            position: fixed;
            width: 60px;
            height: 60px;
            bottom: 40px;
            right: 40px;
            background-color: #0C9;
            color: #FFF;
            border-radius: 50px;
            text-align: center;
            box-shadow: 1px 1px 2px #999;
        }

        .my-float {
            margin-top: 22px;
        }

        button.capture:disabled {
            opacity: 0.25;
        }

        button.capture:active {
            opacity: 0.9;
        }

        th {
            text-align: center;
        }
    </style>
@endsection
@section('javascript')
    {!! Html::script('adminlte/plugins/fileinput/fileinput.min.js') !!}
    <script src="{{ url('adminlte/plugins/datepicker/') }}/bootstrap-datepicker.js"></script>
    <script src="{{ url('js/modules/compromisos/instituciones/script.js?v=6') }}"></script>
    <script src="{{ url('adminlte3/plugins/chosen/js/chosen.jquery.js') }}" type="text/javascript"></script>
    <script>
        $(document).ready(function() {
            $("#identificacion_institucion").select2({
                placeholder: "SELECCIONE UNA OPCION",
                ajax: {
                    url: "{{ route('buscarInstitucionMonitor') }}",
                    type: "post",
                    delay: 250,
                    dataType: 'json',
                    data: function(params) {
                        return {
                            id: app.formCrear.id, // search term
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
            $("#identificacion_institucion_").select2({
                placeholder: "SELECCIONE UNA OPCION",
                ajax: {
                    url: "{{ route('buscarInstitucionMonitor') }}",
                    type: "post",
                    delay: 250,
                    dataType: 'json',
                    data: function(params) {
                        return {
                            id: app.formCrear.id, // search term
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

        $("#identificacion_institucion").on("change", function() {
            app.formCompromiso.id_institucion = $(this).val() == null ? '' : $(this).val();
            app.formCompromiso.anterior_institucion = $("#identificacion_institucion option:selected").text()
            //app.id_institucion = $("#identificacion_institucion").val()
        });

        $("#identificacion_institucion_").on("change", function() {
            app.formCompromiso.id_institucion_ = $(this).val() == null ? '' : $(this).val();
            //app.id_institucion = $("#identificacion_institucion").val()
        });
    </script>
@endsection
@section('content')
    <div id="main">
        <div class="col-md-12">
            <button :class="activar_boton_migrados ? 'btn btn-primary' : 'btn btn-default'"
                v-on:click="activar_boton_migracion=false;activar_boton_migrados=true">Migracion</button>
            <button :class="activar_boton_migracion ? 'btn btn-primary' : 'btn btn-default'"
                v-on:click="activar_boton_migracion=true;activar_boton_migrados=false;cargarDatatableDatosMigrados()">Codigos
                Migrados</button>
        </div>
        <div :class="activar_boton_migracion ? 'card' : 'card hidden'">
            <div class="card-body ">
                <div class="table" id="tablaConsulta">
                    <table class="table table-bordered table-striped" id="dtmenuMigrados" style="width:100%!important">
                        <thead>
                            <th>Codigo Anterior</th>
                            <th>Codigo Actual</th>
                            <th>Motivo</th>
                        </thead>
                        <tbody id="tbobymenu" class="menu-pen">

                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div :class="activar_boton_migrados ? 'card' : 'card hidden'">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <label class="col-md-12"> Instituci&oacute;n:</label>
                        <select class="form-control select2 selectCompleto" id="identificacion_institucion"></select>
                    </div>
                    <div class="col-md-3">
                        <label class="col-md-12"> &nbsp;</label>
                        <button class="btn btn-primary" v-on:click="buscarCompromisos()"> BUSCAR
                            COMPROMISOS</button>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <center>
                            <div v-show="cargando" style="height:500px"><img
                                    src="{{ url('/cargando.gif') }}"style="height:200px"></div>
                        </center>
                        <div id="my_pdf_viewer" class="hidden" style="height:500px" v-show="!cargando"></div>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-12" v-show="consulta">
                        <div class="table" id="tablaConsulta">
                            <table class="table table-bordered table-striped" id="dtmenuMigracion"
                                style="width:99%!important">
                                <thead>
                                    <th><input type="checkbox" class="selectAll" name="selectAll" value="all"></th>
                                    <th>Tipo Compromiso</th>
                                    <th>Codigo</th>
                                    <th>Nombre Compromiso</th>
                                </thead>
                                <tbody id="tbobymenu" class="menu-pen">

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include('modules.compromisos.instituciones.modal_migracion')
    </div>

    <script src="{{ url('js/vue.js') }}"></script>
    <script src="{{ url('js/axios.js') }}"></script>
    <script src="{{ url('js/modules/compromisos/instituciones/vue_script.js?v=2') }}"></script>
@endsection
