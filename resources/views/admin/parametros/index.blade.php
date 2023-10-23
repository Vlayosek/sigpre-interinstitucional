@extends('layouts.app')
@section('contentheader_title')

@endsection
@section('contentheader_description')
Parametros de los Sistemas
@endsection
@section('content')
@section('css')
    <link href="{{ url('adminlte/plugins/notifications/sweetalert.css') }}" rel="stylesheet">
    <style>
    .form-control{
        text-transform: none!important;
    }
    </style>
@endsection
@section('javascript')
    <script src="{{ url('js/modules/admin/parameter.js') }}"></script>

<script>

$("#verificacion").on("change",function(){
    var ver=$('select[name="verificacion"] option:selected').text().toUpperCase();
    var hdiv=ver!='TICKET'?$("#dniveles").addClass('hidden'):$("#dniveles").removeClass('hidden');
});
</script>
@endsection

<div class="modal fade " id="modal-opciones">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Parametros</h4>
                <button type="button" class="btn btn-danger btn-sm cerrarmodal" data-dismiss="modal" aria-label="Close">
                    <i class="fa fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="card-body">
                    <input type="hidden" id="var" value="0"/>

                    <div class="col">
                        {!! Form::label('father','Seleccione el parametro báse:',["class"=>"control-label"]) !!}

                    </div>
                    <div class="col-lg-12">
                        {!! Form::select('father', $father, null,['class' => 'form-control select2']) !!}
                    </div>

                    <div class="col">
                        {!! Form::label('name','Nombre del parametro:',[]) !!}
                    </div>
                    <div class="col">

                        {!! Form::text('name', null,["required"=>"required","class"=>"form-control" ,"placeholder"=>"Nombre de la Opción",'id'=>'name']) !!}
                    </div>
                    <div class="col">
                        {!! Form::label('Estado','Estado:',[]) !!}

                        {!! Form::select('optionid', $estado, null,['placeholder'=>'ESTADO','class' => 'form-control select2','id'=>'optionid']) !!}
                    </div>
                    <div class="col">

                        {!! Form::label('Verificacion','Verificacion: ',[]) !!}
                    </div>
                    <div class="col">

                        {!! Form::select('verificacion',
                         $verificacion, 0,
                        ['id'=>'verificacion','placeholder'=>'VERIFICACION','class' => 'form-control select2 col-lg-1']) !!}
                    </div>
                    <div class="hidden" id="dniveles">
                        <div class="col">
                        {!! Form::label('categorias','Categorias: ',[]) !!}
                        {!! Form::select('categorias',
                        $categorias, 0,
                        ['id'=>'categorias','placeholder'=>'CATEGORIAS','class' => 'form-control select2 col-lg-1']) !!}
                        </div>
                        <div class="col-lg-6 form-group">
                        {!! Form::label('niveles','Niveles: ',[]) !!}
                            {!! Form::select('niveles',
                            $niveles, 0,
                            ['id'=>'niveles','placeholder'=>'NIVELES','class' => 'form-control select2 col-lg-1']) !!}
                        </div>
                        <div class="col-lg-6 form-group">
                        {!! Form::label('area','Area Tecnológica: ',[]) !!}
                        {!! Form::select('areas',
                        $areas, 0,
                        ['id'=>'areas ','placeholder'=>'AREA TECNOLÓGICA','class' => 'form-control select2 col-lg-1']) !!}
                        </div>
                        <div class="col-lg-6 form-group">
                        {!! Form::label('listado','1er Lista Dinámica: ',[]) !!}
                        {!! Form::select('principal_listado_id',
                        $listado, 0,
                        ['id'=>'principal_listado_id','placeholder'=>'SELECCIONE LISTA DINÁMICA','class' => 'form-control select2']) !!}
                        </div>
                        <div class="col-lg-6 form-group">
                        {!! Form::label('tp_lista_p','Tipo Lista Dinámica: ',[]) !!}
                        {!! Form::select('tipo_lista_primaria',
                        ['SIMPLE'=>'SIMPLE','MULTIPLE'=>'MULTIPLE','FORMULARIO'=>'FORMULARIO'], 'SIMPLE',
                        ['id'=>'tipo_lista_primaria','class' => 'form-control select2']) !!}
                        </div>
                        <div class="col-lg-6 form-group">
                        {!! Form::label('listado','2da Lista Dinámica: ',[]) !!}
                        {!! Form::select('secundario_listado_id',
                        $listado, 0,
                        ['id'=>'secundario_listado_id','placeholder'=>'SELECCIONE LISTA DINÁMICA','class' => 'form-control select2']) !!}
                        </div>
                        <div class="col-lg-6 form-group">
                        {!! Form::label('tp_lista_s','Tipo Lista Dinámica: ',[]) !!}
                        {!! Form::select('tipo_lista_secundaria',
                        ['SIMPLE'=>'SIMPLE','MULTIPLE'=>'MULTIPLE','FORMULARIO'=>'FORMULARIO'], 'SIMPLE',
                        ['id'=>'tipo_lista_secundaria','class' => 'form-control select2']) !!}
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <div style="text-align: center;">
                    {!! Form::button('<b><i class="fa fa-save"></i></b> Guardar Cambios', array('type' => 'button', 'class' => 'btn btn-primary cerrarmodal','id' => "btnGuardar", 'data-dismiss'=>"modal")) !!}
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-heading">
        <div class="col-12 btnTop" >
            <a href="#" onclick="limpiar();" class="btn btn-primary" data-toggle="modal"
            data-target="#modal-opciones" title="Agregar Nuevo Parametro"> <i class="fa fa-plus"></i>&nbsp; Nuevo Parametro</a>    
        </div>
    </div>

    <div class="card-body">
        <table class="table table-bordered table-striped " id="dtmenu" style="width:100%!important">
            <thead>

            <th>Nombre del parametro</th>
            <th>Tipo</th>
            <th>Estado</th>

            <th>Opciones</th>

            </thead>
            <tbody id="tbobymenu">

            </tbody>
        </table>
    </div>
</div>

@endsection
