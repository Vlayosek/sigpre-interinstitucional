@extends('layouts.app')

@section('content')
    {!! Form::open(['method' => 'POST', 'route' => ['admin.roles.store']]) !!}
<div class="row">
			<div class="col-md-8 col-md-offset-2">
    <div class="panel panel-default">
        <div class="panel-heading">
            Creacion de Nuevo Rol
        </div>
        
        <div class="panel-body" style="margin:25px">
            <div class="row">
                <div class="col-xs-12 form-group">
                    {!! Form::label('name', 'Name*', ['class' => 'control-label']) !!}
                    {!! Form::text('name', old('name'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('name'))
                        <p class="help-block">
                            {{ $errors->first('name') }}
                        </p>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 form-group">
                    {!! Form::label('permission', 'Permissions', ['class' => 'control-label']) !!}
                    {!! Form::select('permission[]', $permissions, old('permission'), ['class' => 'form-control select2', 'multiple' => 'multiple']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('permission'))
                        <p class="help-block">
                            {{ $errors->first('permission') }}
                        </p>
                    @endif
                </div>
            </div>
<div class="row">
    <hr/>
    <div class="col-lg-12" >
{!! Form::submit(trans('global.app_save'), ['class' => 'btn btn-lg btn-primary hidden','id'=>'savebtn']) !!}
             <a href="#" class="btn btn-primary onclick="$('#savebtn').click()">Guardar</a>
             <a href="{{ route('admin.roles.index') }}" class="btn btn-danger">Regresar</a>
            {!! Form::close() !!}   
</div>
</div>
            
        </div>
    </div>
</div>
 
@stop

