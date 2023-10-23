@extends('layouts.app')

@section('contentheader_title')
    Compromisos
@endsection

@section('contentheader_description')
    Asignación de Delegados
@endsection

@section('css')
    <link href="{{ url('adminlte/plugins/notifications/sweetalert.css') }}" rel="stylesheet">

@endsection
@section('javascript')

    <script src="{{ url('adminlte/plugins/datepicker/') }}/bootstrap-datepicker.js"></script>
    <script src="{{ url('js/modules/compromisos/delegado/script_delegado.js?v=3') }}"></script>
  
    <script>
            $(document).ready(function() {

                    $("#usuario_id").select2({
                        placeholder: "SELECCIONE UNA OPCION",
                        ajax: {
                            url: "{{ route('buscarMonitor') }}",
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
            <div class="col-md-12 btnTop">
                <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modal-ASIGNAR_DELEGADO"
                    v-on:click="limpiarFormDelegado();" data-backdrop="static" data-keyboard="false">
                    <i class="fa fa-plus"></i>&nbsp; Nueva Asignaci&oacute;n
                </button>
            </div>
        </div>
        <div class="card-body">
            <button id="BotonDatatable" onclick="datatableCargarDelegado()" class="hidden"></button>
            <div class="table table-responsive" id="tablaConsulta">
                <table class="table table-bordered table-striped" id="dtmenuDelegadosInstitucion" style="width:100%!important">
                    <thead>
                        <th>No</th>
                        <th>Identificación</th>
                        <th>Nombres</th>
                        <th>Institución</th>
                        <th>Cargo</th>
                        <th>Teléfono</th>
                        <th>Celular</th>
                        <th>Acciones</th>
                    </thead>
                    <tbody id="tbobymenu" class="menu-pen">

                    </tbody>
                </table>
            </div>
        </div>
    </div>

@include('modules.compromisos.delegado.modal_delegado')
</div>

<script src="{{ url('js/vue.js') }}"></script>
<script src="{{ url('js/axios.js') }}"></script>
<script src="{{ url('js/modules/compromisos/delegado/vue_delegado.js?v=3') }}"></script>

@endsection
