@extends('layouts.app')

@section('contentheader_title')
Ips
@endsection

@section('contentheader_description')
Administrar
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

<script src="{{ url('adminlte/plugins/datepicker/bootstrap-datepicker.js') }}"></script>
<script src="{{ url('js/modules/admin/administrador_ip/administrar/script.js?v=2') }}"></script>

@endsection
@section('content')
<div id="main">
    <div class="card">
        <div class="card-heading">
            <div class="col-md-12 btnTop">
                <div class="row">
                    <div class="col-md-12 btnTop" style="padding-bottom:0px!important;padding-top:0px!important" v-show="!habilitaEditar">
                        <label>&nbsp;&nbsp;INGRESAR IP</label>
                    </div>
                    <div class="col-md-12 btnTop" style="padding-bottom:0px!important;padding-top:0px!important;color:blue" v-show="habilitaEditar">
                        <label>&nbsp;&nbsp;EDITAR IP</label>
                    </div>
                    <div class="col-sm-2">
                        <label>&nbsp;&nbsp;IP:</label>
                        <input type="text" class="form-control form-control-sm" id="ip_registrar_" onkeyup="validarIP( this.value )" v-model="ip_registrar"
                                placeholder="ej. 192.168.10.5" maxlength="15">
                    </div>
                    <div class="col-sm-2">
                        <label>Tipo:</label>
                        <select id="tipo_ip" class="form-control form-control-sm">
                            <option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>
                            <option value="RESTRINGIDO">RESTRINGIDO</option>
                            <option value="LIBRE">LIBRE</option>
                        </select>
                    </div>
                    <div class="col-sm-2">
                        <label>Sección:</label>
                        <select id="seccion_ip" class="form-control form-control-sm">
                            <option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>
                            <option value="TODOS">TODOS</option>
                            <option value="CÓDIGO DE TRABAJO">CÓDIGO DE TRABAJO</option>
                            <option value="PERSONAL">PERSONAL</option>
                            <option value="LOSEP">LOSEP</option>
                        </select>
                    </div>
                    <div class="col-sm-4">
                        <label style="color:#FFFFFF">.</label><br>
                        <button class="btn btn-primary" disabled v-show="cargando"><img
                        src="{{ url('/spinner.gif') }}">&nbsp;Cargando</button>
                        <button type="button" class="btn btn-info btnTop btn-sm" v-on:click="agregarIp()" data-backdrop="static" v-show="!habilitaEditar"
                            data-keyboard="false">
                            <i class="fa fa-plus"></i>&nbsp;AGREGAR
                        </button>
                        <button type="button" class="btn btn-default btnTop btn-sm" v-on:click="limpiarFormulario()" data-backdrop="static" v-show="!habilitaEditar"
                            data-keyboard="false">
                            <i class="fa fa-eraser"></i>&nbsp;LIMPIAR
                        </button>
                        <button type="button" class="btn btn-info btnTop btn-sm" v-on:click="agregarIp()" data-backdrop="static" v-show="habilitaEditar"
                            data-keyboard="false">
                            <i class="fa fa-edit"></i>&nbsp;ACTUALIZAR
                        </button>
                        <button type="button" class="btn btn-default btnTop btn-sm" v-on:click="limpiarFormulario()" data-backdrop="static" v-show="habilitaEditar"
                            data-keyboard="false">
                            <i class="fa fa-times"></i>&nbsp;CANCELAR
                        </button>
                    </div>

                </div>
            </div>
        </div>
        <br><br>
        <div class="card-body">
            <button id="BotonDatatable" onclick="datatableCargarIpsAdministrar()" class="hidden"></button>
            <div class="table" id="tablaConsulta">
                <table class="table table-bordered table-striped" id="dtmenuIpsAdministrar" style="width:100%!important">
                    <thead>
                        <th>Reg.</th>
                        <th>Dirección Ip</th>
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
<script src="{{ url('js/modules/admin/administrador_ip/administrar/vue_script.js') }}"></script>

@endsection
