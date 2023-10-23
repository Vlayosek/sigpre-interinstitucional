@extends('layouts.app')
@section('contentheader_title')
    Administrador
@endsection

@section('contentheader_description')
    Menu
@endsection
@section('css')
    <link href="{{ url('adminlte/plugins/notifications/sweetalert.css') }}" rel="stylesheet">
    <style>
        .form-control {
            text-transform: none !important;
        }
   
    </style>
@endsection
@section('javascript')
    <script>
   
        $("#name").on("keyup", function() {
            $(this).val($(this).val().toUpperCase());
        });
    </script>
    <script src="{{ url('js/modules/admin/menu.js?v=2') }}"></script>
@endsection
@section('content')
    <div class="modal fade " id="modal-opciones">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Opciones del menu</h4>
                    <button type="button" class="btn btn-danger btn-sm cerrarmodal" data-dismiss="modal" aria-label="Close">
                        <i class="fa fa-times"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="var" value="0" />
                    <div class="form-group hidden">
                        {!! Form::label('father_segundo_nivel', 'Opcion del menu:', ['class' => 'text-bold col-lg-12 control-label']) !!}


                        <div class="col-lg-12">
                            {!! Form::select('father_segundo_nivel', $father, 0, ['class' => 'form-control select2', 'style' => 'width:100%']) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('optionid', 'Opci&oacute;n Padre:', ['class' => 'text-bold col-lg-12 control-label']) !!}


                        <div class="col-lg-12">
                            {!! Form::select('optionid', $father, null, ['class' => 'form-control select2', 'style' => 'width:100%']) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('name', 'Nombre Opcion:', ['class' => 'text-bold col-lg-12 control-label']) !!}

                        <div class="col-lg-12">
                            {!! Form::text('name', null, ['required' => 'required', 'class' => 'form-control', 'placeholder' => 'Nombre de la Opción']) !!}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            {!! Form::label('prefix', 'Prefijo:', ['class' => 'text-bold col-lg-12 control-label']) !!}
                            <div class="col-lg-12">
                                {!! Form::number('prefix', null, ['required' => 'required', 'class' => 'form-control', 'min' => '0']) !!}
                            </div>
                        </div>
                        <div class="col">
                            {!! Form::label('url', 'URL de la opci&oacute;n:', ['class' => 'text-bold col-lg-12 control-label']) !!}
                            <div class="col-lg-12">
                                {!! Form::text('url', null, ['class' => 'form-control', 'placeholder' => 'prefijo/NombredeOpcion']) !!}
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('descripcion', 'Descripi&oacute;n de la opci&oacute;n:', ['class' => 'text-bold col-lg-12 control-label']) !!}
                        <div class="col-lg-12">
                            {!! Form::textarea('descripcion', null, ['id' => 'descripcion', 'class' => 'form-control-t', 'placeholder' => 'Descripcion']) !!}
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    {!! Form::button('<b><i class="fa fa-save"></i></b> Guardar Cambios', ['type' => 'button', 'class' => 'btn btn-primary cerrarmodal', 'id' => 'btnGuardar', 'data-dismiss' => 'modal']) !!}
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

    <div class="card">

        <div class="card-heading">
            <div class="col btnTop">
                <button type="button" onclick="limpiar();" class="btn btn-primary" data-toggle="modal"
                    data-target="#modal-opciones">
                    <i class="fa fa-plus"></i>&nbsp;Nueva Opci&oacute;n
                </button>
            </div>

        </div>

        <div class="card-body">
            <hr>
            <table class="table table-bordered table-striped " id="dtmenu" style="width:100%!important">
                <thead>

                    <th>Módulo</th>
                    <th>Nombre de la Opción</th>
                    <th>Url</th>
                    <th>Descripci&oacute;n</th>
                    <th>Opciones</th>

                </thead>
                <tbody id="tbobymenu">

                </tbody>
            </table>
        </div>
    </div>

@endsection
