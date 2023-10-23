<div class="box box-info padding-1">
    <div class="box-body">
        
                <div class="form-group">
            
                    {{ Form::label('nombre') }}
                    {{ Form::text('nombre', $libro->nombre, ['class' => 'form-control' . ($errors->has('nombre') ? ' is-invalid' : ''), 'placeholder' => 'Nombre']) }}
                    {!! $errors->first('nombre', '<div class="invalid-feedback">:message</p>') !!}
                </div>                <div class="form-group">
            
                    {{ Form::label('apellidos') }}
                    {{ Form::text('apellidos', $libro->apellidos, ['class' => 'form-control' . ($errors->has('apellidos') ? ' is-invalid' : ''), 'placeholder' => 'Apellidos']) }}
                    {!! $errors->first('apellidos', '<div class="invalid-feedback">:message</p>') !!}
                </div>                <div class="form-group">
            
                    {{ Form::label('cedula') }}
                    {{ Form::text('cedula', $libro->cedula, ['class' => 'form-control' . ($errors->has('cedula') ? ' is-invalid' : ''), 'placeholder' => 'Cedula']) }}
                    {!! $errors->first('cedula', '<div class="invalid-feedback">:message</p>') !!}
                </div>
    </div>
    <div class="box-footer mt20">
        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i>&nbsp;Guardar</button>
    </div>
</div>