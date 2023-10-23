@extends('layouts.app')
@section('contentheader_title')
    Administrador
@endsection

@section('contentheader_description')
    Agregar Usuario
@endsection
@section('javascript')
    <script>
        function composicion() {
            var arreglo = [];
            arreglo[0] = 'CN=' + $("#nombreCompletodata").val();
            arreglo[1] = ',CARGO=' + $("#cargo").val();
            arreglo[2] = ',CEDULA=' + $("#identificacion").val();
            $("#nombreCompleto").val(arreglo[0] + arreglo[1] + arreglo[2]);
        }
        $("#cargo").on("keyup", function() {
            composicion();
        });
        $("#nombreCompletodata").on("keyup", function() {
            composicion();
        });
        $("#identificacion").on("keyup", function() {
            composicion();
        });
    </script>
@endsection
@section('content')
    <div class="col-md-10 offset-md-1">
        {!! Form::open(['method' => 'PUT', 'route' => ['admin.users.update', 0], 'enctype' => 'multipart/form-data']) !!}
        <div class="card">
            <div class="card-body" style="margin:25px">
                <div class="row">
                    <div class="col-md-6">
                        <div class="col-12">
                            <div class="row">

                                <div class="col-12">
                                    {!! Form::label('name', 'Usuario*', ['class' => 'control-label']) !!}
                                    {!! Form::text('name', old('name'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}
                                    <p class="help-block"></p>
                                    @if ($errors->has('name'))
                                        <p class="help-block">
                                            {{ $errors->first('name') }}
                                        </p>
                                    @endif
                                </div>
                                <div class="col-12">
                                    {!! Form::label('email', 'Correo*', ['class' => 'control-label']) !!}
                                    {!! Form::email('email', old('email'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}
                                    <p class="help-block"></p>
                                    @if ($errors->has('email'))
                                        <p class="help-block">
                                            {{ $errors->first('email') }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                {!! Form::label('password', 'Contraseña*', ['class' => 'control-label']) !!}
                                {!! Form::password('password', ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}
                                <p class="help-block"></p>
                                @if ($errors->has('password'))
                                    <p class="help-block">
                                        {{ $errors->first('password') }}
                                    </p>
                                @endif
                            </div>
                            <div class="col">
                                {!! Form::label('cargo', 'Cargo*', ['class' => 'control-label']) !!}
                                {!! Form::text('cargo', null, ['class' => 'form-control', 'required' => '', 'id' => 'cargo']) !!}
                            </div>
                        </div>

                        <div class="row" style="">
                            <div class="col">
                                {!! Form::label('identificacion', 'Identificacion*', ['class' => 'control-label']) !!}
                                {!! Form::text('identificacion', null, ['class' => 'form-control', 'required' => '', 'id' => 'identificacion']) !!}
                            </div>
                            <div class="col">
                                {!! Form::label('nombreCompleto', 'Nombre Completo*', ['class' => 'control-label']) !!}
                                {!! Form::text('nombres', null, ['class' => 'form-control', 'required' => '', 'id' => 'nombres']) !!}
                                {!! Form::hidden('nombreCompletodata', null, ['class' => 'form-control', 'required' => '', 'id' => 'nombreCompletodata']) !!}
                                {!! Form::hidden('nombreCompleto', null, ['class' => 'form-control', 'required' => '', 'id' => 'nombreCompleto']) !!}

                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                {!! Form::label('institucion_id', 'Instituci&oacute;n*', ['class' => 'control-label']) !!}
                                {!! Form::select('institucion_id', $instituciones, null, ['placeholder' => 'SELECCIONE UNA OPCION', 'class' => 'form-control chosen']) !!}
                                <p class="help-block"></p>
                                @if ($errors->has('institucion_id'))
                                    <p class="help-block">
                                        {{ $errors->first('institucion_id') }}
                                    </p>
                                @endif
                            </div>
                        </div>
                        <input type="hidden" name="estado" value="A" />
                    </div>
                        <div class="col-md-6">
                        <div class="row">
                            <div class="col">
                                {!! Form::label('roles', 'Roles*', ['class' => 'control-label']) !!}
                                {!! Form::select('roles[]', $roles, ['ESTANDAR'], ['class' => 'form-control select2', 'multiple' => 'multiple', 'required' => '']) !!}
                                <p class="help-block"></p>
                                @if ($errors->has('roles'))
                                    <p class="help-block">
                                        {{ $errors->first('roles') }}
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <hr />
                    {!! Form::submit(trans('global.app_save'), ['class' => 'btn btn-primary btn-sm hidden', 'id' => 'savebtn']) !!}
                    <a href="#" class="btn btn-primary btn-sm" onclick="$('#savebtn').click()">Guardar Cambios</a>

                    <a href="{{ route('admin.users.index') }}" class="btn btn-danger btn-sm">Regresar</a>

                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
