@extends('layouts.app')

@section('contentheader_title')
    Grupos
@endsection

@section('contentheader_description')

@endsection

@section('content')
@section('css')
    <link href="{{ url('adminlte/plugins/notifications/sweetalert.css') }}" rel="stylesheet">
   
    {!! Html::style('adminlte/plugins/fileinput/fileinput.min.css') !!}

@endsection
@section('javascript')
    {!! Html::script('adminlte/plugins/fileinput/fileinput.min.js') !!}
    <script src="{{ url('js/modules/grupos/grupos.js?v=2') }}"></script>
    <script>
    $(document).ready(function() {

            $("#identificacion_usuario").select2({
                placeholder: "SELECCIONE UNA OPCION",
                ajax: {
                    url: "{{ route('getCargaDatosFuncionarioSIGPRE') }}",
                    type: "post",
                    delay: 250,
                    dataType: 'json',
                    data: function(params) {
                        return {
                            'tipo':'persona_id',
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
            $("#identificacion_asignado").select2({
                placeholder: "SELECCIONE UNA OPCION",
                ajax: {
                    url: "{{ route('getCargaDatosFuncionarioSIGPRE') }}",
                    type: "post",
                    delay: 250,
                    dataType: 'json',
                    data: function(params) {
                        return {
                            'tipo':'persona_id',
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
<a href="#" class="hidden" id="botonDescargar" download></a>
<div id="main">
    <div class="card">
        <div class="card-heading">
            <div class="col-12">
                <div class="row">

                    <div class="col-md-8 btnTop">

                        <button type="button" id="myBtn" class="btn btn-primary btnLetra" v-on:click="limpiar();"
                            data-toggle="modal" data-target="#modal-default">
                            <i class="fa fa-plus"></i>&nbsp; Nuevo Grupo
                        </button>

                    </div>

                </div>
            </div>

        </div>

        <div class="card-body ">
            <div class="table table-responsive" id="tablaConsulta">
                <table class="table table-bordered table-striped" id="dtmenu" style="width:100%!important">
                    <thead>
                        <th>Id</th>
                        <th>Nombre del Grupo</th>
                        <th>Departamento</th>
                        <th>Asignaci&oacute;n</th>
                        <th>Integrantes</th>
                        <th></th>
                    </thead>
                    <tbody id="tbobymenu" class="menu-pen">

                    </tbody>
                </table>
            </div>
        </div>
    </div>


    <div class="modal fade" id="modal-default">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="col-md-12">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="tabbable" id="tabs-699863">
                                        <ul class="nav nav-tabs">
                                            <li class="nav-item">
                                                <a class="nav-link active show" href="#tab1" data-toggle="tab">Información General</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" href="#tab2" data-toggle="tab">Integrantes</a>
                                            </li>
                                        </ul>
                                        <div class="tab-content">
                                            <div class="tab-pane active" id="tab1">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <label>Nombre del Grupo:</label>
                                                        <input type="text" class="form-control mayuscula" v-model="formCrear.descripcion">
                                                    </div>
                                                    <h4 class="col-md-12">Asignaci&oacute;n del Responsable </h4>
                                                    <div class="col-md-12">
                                                                <label class="col-md-12"> Usuario: <span v-text="usuario_asignado_text"></span></label>
                                                                <select class="form-control select2" id="identificacion_asignado" ></select>
                                                            </div>
                                                </div>
                                                
                                            </div>
                                            <div class="tab-pane" id="tab2">
                                                <div class="row">
                       
                                                    <div class="col-md-12">
                                                        <h4>Integrantes del Grupo</h4>
                                                        <div class="row">
                                                             <div class="col-md-12">
                                                                <label class="col-md-12"> Usuario:</label>
                                                                <select class="form-control select2" id="identificacion_usuario" ></select>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <label>&nbsp;</label>
                                                                <button class="btn btn default" v-show="cargando"><img
                                                                 src="{{ url('/spinner.gif') }}">Guardando </button>
                                                                <button class="btn btn-default col-md-12" type="button" v-on:click="agregarFuncionario()" v-show="!cargando">
                                                                    Agregar
                                                                </button>
                                                            </div>
                                                        </div>
                        
                        
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="table table-responsive" id="tablaConsulta">
                                                            <table class="table table-bordered table-striped" id="dtmenuParticipantes"
                                                                style="width:100%!important">
                                                                <thead>
                                                                    <th>Departamento</th>
                                                                    <th>Integrante</th>
                                                                    <th>Lider</th>
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
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-end">
                        Habilitar Envio de Tickets
                        <input type="checkbox" v-model="formCrear.ticket">

                        <button class="btn btn default" v-show="cargando"><img
                            src="{{ url('/spinner.gif') }}">Guardando </button>
                        {!! Form::button('<b><i class="fa fa-save"></i></b> Guardar Grupo', ['v-show'=>'!cargando','type' => 'button', 'class' => 'btn btn-primary cerrarmodal', 'id' => 'btnGuardar', 'v-on:click' => 'guardarGrupo()']) !!}
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
</div>
    <script src="{{ url('js/vue.js') }}"></script>
    <script src="{{ url('js/axios.js') }}"></script>
    <script src="{{ url('js/modules/grupos/vue_grupos.js?v=2') }}"></script>

@endsection
