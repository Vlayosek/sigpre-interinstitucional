@extends('layouts.app')

@section('contentheader_title')
    Administrador
@endsection

@section('contentheader_description')
    Editar Usuario
@endsection
@section('css')
    <link href="{{ url('adminlte3/plugins/Multiselect/css/bootstrap-multiselect.min.css') }}" rel="stylesheet">
@endsection

@section('javascript')
    <script src="{{ url('adminlte3/plugins/Multiselect/dist/js/bootstrap-multiselect.min.js') }}"></script>

    <script>
      /*  $(document).ready(function() {
            $('.multiselect').multiselect({
                enableFiltering: true,
                includeSelectAllOption: true,
                dropUp: true
            });
        });*/
    </script>
@endsection
@section('content')
    {!! Form::model($user, ['method' => 'PUT', 'route' => ['admin.users.update', $user->id]]) !!}
    <div class="row">
        <div class="col-md-10 offset-md-1">
            <div class="card">
                <div class="card-body" style="margin:25px">
                    <div class="row">
                        <div class="col-md-6">

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
                                    {!! Form::label('email', 'Correo de Usuario*', ['class' => 'control-label']) !!}
                                    {!! Form::email('email', old('email'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}
                                    <p class="help-block"></p>
                                    @if ($errors->has('email'))
                                        <p class="help-block">
                                            {{ $errors->first('email') }}
                                        </p>
                                    @endif
                                </div>
                            </div>

                            <div class="row">
                                <div class="col">
                                    {!! Form::label('password', 'Contraseña', ['class' => 'control-label']) !!}
                                    {!! Form::password('password', ['class' => 'form-control', 'placeholder' => '']) !!}
                                    <p class="help-block"></p>
                                    @if ($errors->has('password'))
                                        <p class="help-block">
                                            {{ $errors->first('password') }}
                                        </p>
                                    @endif
                                </div>
                                <div class="col">
                                    {!! Form::label('cargo', 'Cargo*', ['class' => 'control-label']) !!}
                                    {!! Form::text('cargo', old('cargo'), ['class' => 'form-control', 'required' => '', 'id' => 'cargo']) !!}
                                </div>
                            </div>
                            <div class="row" style="">
                                <div class="col">
                                    {!! Form::label('identificacion', 'Identificacion*', ['class' => 'control-label']) !!}
                                    {!! Form::text('identificacion', old('name'), [
                                        'class' => 'form-control',
                                        'required' => '',
                                        'id' => 'identificacion',
                                    ]) !!}
                                </div>
                                <div class="col">
                                    {!! Form::label('nombreCompleto', 'Nombre Completo*', ['class' => 'control-label']) !!}
                                    {!! Form::text('nombres', old('nombres'), ['class' => 'form-control', 'required' => '', 'id' => 'nombres']) !!}
                                    {!! Form::hidden('nombreCompletodata', old('nombreCompletodata'), [
                                        'class' => 'form-control',
                                        'required' => '',
                                        'id' => 'nombreCompletodata',
                                    ]) !!}
                                    {!! Form::hidden('nombreCompleto', old('nombreCompleto'), [
                                        'class' => 'form-control',
                                        'required' => '',
                                        'id' => 'nombreCompleto',
                                    ]) !!}
                                    <p class="help-block"></p>
                                    @if ($errors->has('roles'))
                                        <p class="help-block">
                                            {{ $errors->first('nombreCompleto') }}
                                        </p>
                                    @endif
                                </div>
                            </div>



                            <div class="row">
                                <div class="col-md-12">
                                    {!! Form::label('institucion_id', 'Instituci&oacute;n*', ['class' => 'control-label']) !!}
                                    <br />

                                    {!! Form::select('institucion_id', $instituciones, old('institucion_id'), [
                                        'placeholder' => 'SELECCIONE UNA OPCION',
                                        'class' => 'form-control select2',
                                        'style' => 'width:100%!important',
                                    ]) !!}
                                    <p class="help-block"></p>
                                    @if ($errors->has('institucion_id'))
                                        <p class="help-block">
                                            {{ $errors->first('institucion_id') }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                            <div class="row">

                                <div class="row">

                                    <div class="col-xs-12 form-group">
                                        <hr />
                                        {!! Form::submit(trans('global.app_update'), ['class' => 'btn btn-lg btn-primary hidden', 'id' => 'savebtn']) !!}
                                        <a href="#" class="btn btn-primary btn-sm"
                                            onclick="$('#savebtn').click()">Guardar</a>
                                        <a href="{{ route('admin.users.index') }}"
                                            class="btn btn-danger btn-sm">Regresar</a>

                                        {!! Form::close() !!}
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="col-md-6 " style="max-width:400px">
                            <div class="row">
                                <div class="col">
                                    {!! Form::label('roles', 'Roles*', ['class' => 'control-label']) !!}
                                    <br />
                                    {!! Form::select('roles[]', $roles, old('roles') ? old('role') : $user->roles()->pluck('name', 'name'), [
                                        'class' => 'form-control select2',
                                        'multiple' => 'multiple',
                                        'required' => '',
                                        'style' => 'width:auto!important;min-width:100%!important',
                                    ]) !!}
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

                </div>
            </div>
        </div>
    </div>
@endsection
