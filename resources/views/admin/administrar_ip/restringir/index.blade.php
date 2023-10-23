@extends('layouts.app')

@section('contentheader_title')
Ips
@endsection

@section('contentheader_description')
Restringir
@endsection

@section('css')
<link href="{{ url('adminlte/plugins/notifications/sweetalert.css') }}" rel="stylesheet">
<link href="{{ url('adminlte/style_moderno2.css') }}" rel="stylesheet">
<style>
label {
    font-size: 14px;
}

.tamanoInformacion {
    font-size: 14px;
}

.info-box-t .info-box-icon-t {
    border-radius: .25rem;
    -webkit-align-items: center;
    -ms-flex-align: center;
    align-items: center;
    display: -webkit-flex;
    display: -ms-flexbox;
    display: flex;
    font-size: 12px;
    -webkit-justify-content: center;
    -ms-flex-pack: center;
    justify-content: center;
    text-align: center;
    width: 30px;
}
</style>
@endsection
@section('javascript')

<script src="{{ url('adminlte/plugins/datepicker/') }}/bootstrap-datepicker.js"></script>
<script src="{{ url('js/modules/admin/administrador_ip/restringir/script.js?v=2') }}"></script>

@endsection
@section('content')
<div id="main">
    <div class="card">
        <div class="card-heading">
            <div class="col-md-12 btnTop">
                <div class="row">
                    <div class="col-md-12 btnTop" style="padding-bottom:0px!important;padding-top:0px!important">
                        <label>&nbsp;&nbsp;RESTRINGIR IP</label>
                    </div>
                    <div class="col-sm-1">
                        <label>&nbsp;&nbsp;USUARIO:</label>
                    </div>
                    <div class="col-sm-2">
                        {!! Form::select('filtro_usuario', $cqlUser,null, ['class'
                        => 'select2 form-control form-control-sm', 'id' => 'filtro_usuario', 'placeholder' =>
                        'SELECCIONE UN USUARIO']) !!}
                    </div>
                    <div class="col-sm-1">
                        <label>IP RESTRINGIDA:</label>
                    </div>
                    <div class="col-sm-2">
                        {!! Form::select('filtro_ip_restringidas', $cqlIpRestringidas,null, ['class'
                        => 'select2 form-control form-control-sm', 'id' => 'filtro_ip_restringidas', 'placeholder' =>
                        'SELECCIONE UNA IP']) !!}
                    </div>
                    <div class="col-sm-4">
                        <button class="btn btn-primary" disabled v-show="cargando"><img
                        src="{{ url('/spinner.gif') }}">&nbsp;Cargando</button>
                        <button type="button" class="btn btn-default btnTop btn-sm" v-on:click="restringirIp()" data-backdrop="static"
                            data-keyboard="false">
                            <i class="fa fa-plus"></i>&nbsp;RESTRINGIR
                        </button>
                    </div>
                    
                </div>
            </div>
        </div>
        <br><br>
        <div class="card-body">
            <button id="BotonDatatable" onclick="datatableCargarIpsRestringir()" class="hidden"></button>
            <div class="table" id="tablaConsulta">
                <table class="table table-bordered table-striped" id="dtmenuIpsRestringir" style="width:100%!important">
                    <thead>
                        <th>Reg.</th>
                        <th>Usuario</th>
                        <th>Ip</th>
                        <th>Tipo</th>
                        <th>Seccion</th>
                        <th>Acciones</th>
                    </thead>
                    <tbody id="tbobymenu" class="menu-pen">

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="{{ url('js/vue.js') }}"></script>
<script src="{{ url('js/axios.js') }}"></script>
<script src="{{ url('js/modules/admin/administrador_ip/restringir/vue_script.js') }}"></script>

@endsection