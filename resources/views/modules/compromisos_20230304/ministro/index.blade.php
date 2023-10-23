@extends('layouts.app')

@section('contentheader_title')
    Compromisos
@endsection

@section('contentheader_description')
    Administración de Ministros
@endsection

@section('css')
    <link href="{{ url('adminlte/plugins/notifications/sweetalert.css') }}" rel="stylesheet">
@endsection
@section('javascript')
    <script src="{{ url('adminlte/plugins/datepicker/') }}/bootstrap-datepicker.js"></script>
    <script src="{{ url('js/modules/agenda_territorial/ministro/script.js?v=4') }}"></script>

    <script>
        $(document).ready(function() {


            $("#institucion_id").select2({
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
    </script>
@endsection
@section('content')
    <div id="main">
        <div class="card">
            <div class="card-heading">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-11 btnTop">
                            <button type="button" class="btn btn-primary btn-sm" data-toggle="modal"
                                data-target="#modal-Usuario" v-on:click="limpiarForm();" data-backdrop="static"
                                data-keyboard="false">
                                <i class="fa fa-plus"></i>&nbsp; Nuevo Usuario
                            </button>
                        </div>

                    </div>
                </div>
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-12 btnTop">
                            &nbsp;
                        </div>

                    </div>
                </div>
            </div>

            <div class="table table-responsive" id="tablaConsulta">
                <table class="table table-bordered table-striped" id="dtmenu" style="width:100%!important">
                    <thead>
                        <th>No</th>
                        <th>Identificaci&oacute;</th>
                        <th>Nombres</th>
                        <th>Email</th>
                        <th>Cargo</th>
                        <th>Tel&eacute;fono</th>
                        <th>Acciones</th>
                    </thead>
                    <tbody id="tbobymenu" class="menu-pen">

                    </tbody>
                </table>
            </div>
        </div>
        @include('modules.agenda_territorial.ministro.modal_registro')

    </div>

    </div>

    <script src="{{ url('js/vue.js') }}"></script>
    <script src="{{ url('js/axios.js') }}"></script>
    <script src="{{ url('js/modules/agenda_territorial/ministro/vue_script.js?v=2') }}"></script>
@endsection
