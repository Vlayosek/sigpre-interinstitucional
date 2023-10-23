@extends('layouts.app')

@section('contentheader_title')
    Agenda Territorial
@endsection

@section('contentheader_description')
    Administración de Instituciones
@endsection

@section('css')
    <link href="{{ url('adminlte/plugins/notifications/sweetalert.css') }}" rel="stylesheet">

@endsection
@section('javascript')

    <script src="{{ url('adminlte/plugins/datepicker/') }}/bootstrap-datepicker.js"></script>
    <script src="{{ url('js/modules/agenda_territorial/instituciones/script.js?v=6') }}"></script>
  
    <script>
            $(document).ready(function() {

                
                    $("#institucion_id").select2({
                        placeholder: "SELECCIONE UNA OPCION",
                        ajax: {
                            url: "{{ route('agenda_territorial/buscarInstitucionMonitor') }}",
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
                        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modal-INSTITUCIONES"
                            v-on:click="limpiarForm();" data-backdrop="static" data-keyboard="false">
                            <i class="fa fa-plus"></i>&nbsp; Nueva Instituci&oacute;n
                        </button>
                    </div>
                    
                    <div class="col-md-1 btnTop">
                        <button type="button" :class="gabinete?'btn btn-default btn-sm':'btn btn-info btn-sm'"  v-on:click="cambioVisualizador()" v-html="gabinete?text_gabinete:text_institucion">
                             
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
            <div class="card-body" v-show="gabinete">
                <h4>GABINETES</h4>
                <div class="table table-responsive">
                    <table class="table table-bordered table-striped" id="dtmenuGabinete" style="width:100%!important">
                        <thead>
                            <th>No</th>
                            <th>Principal</th>
                            <th>Nombres</th>
                            <th>Siglas</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </thead>
                        <tbody id="tbobymenuGabinete" class="menu-pen">

                        </tbody>
                    </table>
                  </div>
            </div>
            <div class="card-body" v-show="!gabinete">
            <h4>INSTITUCIONES</h4>
            <div class="table table-responsive" id="tablaConsulta">
                <table class="table table-bordered table-striped" id="dtmenu" style="width:100%!important">
                    <thead>
                        <th>No</th>
                        <th>Gabinete</th>
                        <th>Nombres</th>
                        <th>Siglas</th>
                        <th>Ministro</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </thead>
                    <tbody id="tbobymenu" class="menu-pen">

                    </tbody>
                </table>
            </div>
        </div>
    </div>

@include('modules.agenda_territorial.instituciones.modal_registro')
</div>

<script src="{{ url('js/vue.js') }}"></script>
<script src="{{ url('js/axios.js') }}"></script>
<script src="{{ url('js/modules/agenda_territorial/instituciones/vue_script.js?v=2') }}"></script>

@endsection
