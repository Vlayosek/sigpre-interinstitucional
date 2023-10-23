@extends('layouts.app')
@section('contentheader_title')
 Administrador 
@endsection

@section('contentheader_description')
 Roles y Permisos   
@endsection

@section('css')
    <link href="{{ url('adminlte/plugins/notifications/sweetalert.css') }}" rel="stylesheet">
@endsection
@section('javascript')
    <script src="{{ url('js/modules/admin/menu_rol.js') }}"></script>
@endsection
@section('content')
    <div class="modal fade" id="ModalEditRole" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="exampleModalLabel">Roles y Opciones</h4>
                </div>
                <div class="modal-body">                        <input type="hidden" id="var" value="0"/>

                    <div class="panel-body">
                        <div class="row">
                            <div class="col-xs-12 form-group">
                                {!! Form::label('name', 'Name*', ['class' => 'control-label']) !!}
                                {!! Form::select('roles',$roles,null, ['class' => 'form-control select2']) !!}

                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 form-group">
                                {!! Form::label('permission', 'Permissions', ['class' => 'control-label']) !!}
                                {!! Form::select('permission[]', $permissions, null, ['class' => 'form-control select2', 'multiple' => 'multiple']) !!}

                            </div>
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <div style="text-align: center;">
                        {!! Form::button('<b><i class="fa fa-save"></i></b> Agregar Cambios', array('type' => 'button', 'class' => 'btn btn-primary','id' => "btnGuardar")) !!}
                        {!! Form::button('<b><i class="glyphicon glyphicon-remove"></i></b> Cerrar', array('type' => 'button', 'class' => 'btn btn-danger','id' => "btnCancelar", 'data-dismiss'=>"modal")) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>




    <div class="card">
        <div class="card-heading">
            <div class="col-12 btnTop" >
                <a href="{{ route('admin.roles.create') }}" class="btn btn-primary " title="Agregar Nuevo Rol"> <i class="fa fa-plus"></i>&nbsp; Nuevo Rol</a>    
            </div>
        </div>

        <div class="card-body">
            <br/>
            <table class="table table-bordered table-striped " id="dtop" style="width:100%!important">
                <thead>

                <th></th>
                <th>Roles</th>
                <th></th>

                <th>Permisos</th>

                </thead>
                <tbody id="tbobyop">

                </tbody>
            </table>
        </div>
    </div>
@endsection
@section('javascript')
    <script>
        window.route_mass_crud_entries_destroy = '{{ route('admin.roles.mass_destroy') }}';
    </script>
@endsection