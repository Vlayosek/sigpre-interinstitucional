@extends('layouts.app')
@section('contentheader_title')
    Administrador
@endsection

@section('contentheader_description')
    Agregar Rol
@endsection

@section('javascript')
    <script>
        $("#name").on("keyup", function() {
            $(this).val($(this).val().toUpperCase());
        });
    </script>
@endsection
@section('content')
    {!! Form::open(['method' => 'POST', 'route' => ['admin.roles.store']]) !!}
        <div class="col-md-6 offset-md-3">
            <div class="card">

                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            {!! Form::label('name', 'Name*', ['class' => 'control-label']) !!}
                            {!! Form::text('name', old('name'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}
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
                            {!! Form::select('permission[]', $permissions, old('permission'), ['class' => 'form-control select2', 'multiple' => 'multiple','required'=>'required']) !!}
                            <p class="help-block"></p>
                            @if ($errors->has('permission'))
                                <p class="help-block">
                                    {{ $errors->first('permission') }}
                                </p>
                            @endif
                        </div>
                    </div>
                    <div class="row">
                        <hr />
                        <div class="col-lg-12">
                            {!! Form::submit(trans('global.app_save'), ['class' => 'btn btn-primary', 'id' => 'savebtn']) !!}
                            <a href="{{ route('admin.roles.index') }}" class="btn btn-danger">Regresar</a>
                            {!! Form::close() !!}
                        </div>
                    </div>

                </div>
            </div>
        </div>

    @stop
