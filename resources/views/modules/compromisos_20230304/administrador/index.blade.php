@extends('layouts.app')

@section('contentheader_title')
    Compromisos
@endsection

@section('contentheader_description')
    Asignación de Monitor
@endsection

@section('css')
    <link href="{{ url('adminlte/plugins/notifications/sweetalert.css') }}" rel="stylesheet">

@endsection
@section('javascript')

    <script src="{{ url('adminlte/plugins/datepicker/') }}/bootstrap-datepicker.js"></script>
    <script src="{{ url('js/modules/compromisos/administrador.js') }}"></script>
  
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
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-default"
                    v-on:click="limpiarForm()" data-backdrop="static" data-keyboard="false">
                    <i class="fa fa-plus"></i>&nbsp; Nueva Asignaci&oacute;n
                </button>
            </div>
        </div>
        <div class="card-body">
            <button id="BotonDatatable" onclick="datatableCargar()" class="hidden"></button>
            <div class="table table-responsive" id="tablaConsulta">
                <table class="table table-bordered table-striped" id="dtmenu" style="width:100%!important">
                    <thead>
                        <th class="hidden">No</th>
                        <th>Usuarios</th>
                        <th>Instituciones</th>
                        <th width="20%">Acciones</th>
                    </thead>
                    <tbody id="tbobymenu" class="menu-pen">

                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-default">
        <div class="modal-dialog ">
            <div class="modal-content">
                <div class="modal-body">
                    <input type="hidden" id="id" v-model="formCrear.id" value="0">

                    <div class="col-md-12">
                        <label>Usuario:</label>
                        {!! Form::select('usuario_id', [], null, ['v-model' => 'formCrear.usuario_id', 'class' => 'form-control select2', 'required' => '', 'id' => 'usuario_id']) !!}
                    </div>
                    <div class="col-md-12">
                        <label>Instituciones:</label>
                        {!! Form::select('institucion_id[]', [], null, ['v-model' => 'formCrear.institucion_id', 'class' => 'form-control select2', 'multiple' => 'multiple', 'required' => '', 'id' => 'institucion_id']) !!}
                    </div>
                </div>
                <div class="modal-footer justify-content-end">
                    <button class="btn btn-primary"
                    v-on:click="confirmar()"><b><i
                            class="fa fa-save"></i></b>
                    Guardar Asignaci&oacute;n</button>
                
                    <button class="btn btn-default cerrarmodal"
                        data-dismiss="modal"><b><i
                                class="fa fa-times"></i></b>
                        Cerrar</button>
                    
                </div>
            </div>
        </div>
    </div>
</div>

<script src="{{ url('js/vue.js') }}"></script>
<script src="{{ url('js/axios.js') }}"></script>
<script src="{{ url('js/modules/compromisos/vue_administrador.js') }}"></script>

@endsection
