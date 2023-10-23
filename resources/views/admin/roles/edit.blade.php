@extends('layouts.app')
@section('contentheader_title')
    Administrador
@endsection

@section('contentheader_description')
    Agregar Permisos
@endsection
@section('css')
    <link href="{{ url('adminlte/plugins/notifications/sweetalert.css') }}" rel="stylesheet">
@endsection
@section('javascript')
    <script>
        $("#name").on("keyup", function() {
            $(this).val($(this).val().toUpperCase());
        });
    </script>
    <script src="{{ url('js/modules/admin/menu_rol.js') }}"></script>
@endsection

@section('content')

    {!! Form::model($role, ['method' => 'PUT', 'route' => ['admin.roles.update', $role->id]]) !!}
    <div class="col-md-6 offset-md-3">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col">
                        {!! Form::label('name', 'Name*', ['class' => 'control-label']) !!}
                        {!! Form::text('name', old('name'), ['class' => 'form-control', 'placeholder' => '', 'required' => '', 'disabled' => 'disabled']) !!}
                        <p class="help-block"></p>
                        @if ($errors->has('name'))
                            <p class="help-block">
                                {{ $errors->first('name') }}
                            </p>
                        @endif
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        {!! Form::label('permission', 'Permissions', ['class' => 'control-label']) !!}
                        {!! Form::select('permission[]', $permissions, null, ['class' => 'form-control select2', 'multiple' => 'multiple']) !!}
                        <p class="help-block"></p>
                        @if ($errors->has('permission'))
                            <p class="help-block">
                                {{ $errors->first('permission') }}
                            </p>
                        @endif
                    </div>
                </div>
                <div>
                    {!! Form::button('<b><i class="fa fa-save"></i></b> Guardar Cambios', ['type' => 'button', 'class' => 'btn btn-primary', 'id' => 'btnG']) !!}
                    {!! Form::close() !!}
                    <a href="{{ route('admin.roles.index') }}" class="btn btn-danger">Regresar</a>

                </div>
            </div>
        </div>
    </div>

@endsection
