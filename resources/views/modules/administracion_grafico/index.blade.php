@extends('layouts.app')

@section('contentheader_title')
    PANEL DE CONTROL
@endsection

@section('contentheader_description')
    ADMINISTRADOR GR&Aacute;FICO
@endsection

@section('css')
    <link href="{{ url('adminlte/plugins/notifications/sweetalert.css') }}" rel="stylesheet">
    {!! Html::style('adminlte/plugins/fileinput/fileinput.min.css') !!}
    <link href="{{ url('adminlte/style_moderno2.css') }}" rel="stylesheet">
    <style>
        table.dataTable tr th.select-checkbox.selected::after {
            content: "✔";
            margin-top: -11px;
            margin-left: -4px;
            text-align: center;
            text-shadow: rgb(176, 190, 217) 1px 1px, rgb(176, 190, 217) -1px -1px, rgb(176, 190, 217) 1px -1px, rgb(176, 190, 217) -1px 1px;
        }

        .h6 {
            font-size: 10px;
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

        .float {
            position: fixed;
            width: 60px;
            height: 60px;
            bottom: 40px;
            right: 40px;
            background-color: #0C9;
            color: #FFF;
            border-radius: 50px;
            text-align: center;
            box-shadow: 1px 1px 2px #999;
        }

        .my-float {
            margin-top: 22px;
        }

        button.capture:disabled {
            opacity: 0.25;
        }

        button.capture:active {
            opacity: 0.9;
        }

        th {
            text-align: center;
        }
    </style>
@endsection
@section('javascript')
    {!! Html::script('adminlte/plugins/fileinput/fileinput.min.js') !!}
    <script src="{{ url('adminlte/plugins/datepicker/') }}/bootstrap-datepicker.js"></script>
    <script src="{{ url('js/modules/administracion_grafico/script.js?v=6') }}"></script>
@endsection
@section('content')
    <div id="main">
        <div class="card">
            <div class="card-heading">
                <div class="col-md-12 btnTop">
                    <!-- MENU DE OPCIONES -->
                    <div class="row" style="padding:1.25rem">
                        <div class="hidden">
                            <div class="col-sm-4" style="text-align:center">
                                <button type="button" class="btn  btnTop btn-sm" v-on:click="filtroLogin();"
                                    v-show="!habilitaLogin">
                                    <i class="fas fa-chalkboard-teacher"></i><br>LOGIN
                                </button>
                                <button style="color:blue" type="button" class="btn btnTop btn-sm"
                                    v-on:click="desactivarFiltro();" v-show="habilitaLogin">
                                    <i class="fas fa-chalkboard-teacher"></i><br>LOGIN
                                </button>
                            </div>
                            <div class="col-sm-4" style="text-align:center">
                                <button type="button" class="btn  btnTop btn-sm" v-on:click="filtroMenu();"
                                    v-show="!habilitaMenu">
                                    <i class="fab fa-elementor"></i><br>MENU LATERAL
                                </button>
                                <button style="color:blue" type="button" class="btn  btnTop btn-sm"
                                    v-on:click="desactivarFiltro();" v-show="habilitaMenu">
                                    <i class="fab fa-elementor"></i><br>MENU LATERAL
                                </button>
                            </div>
                            <div class="col-sm-4" style="text-align:center">
                                <button type="button" class="btn  btnTop btn-sm" v-on:click="filtroBiometrico();"
                                    v-show="!habilitaBiometrico">
                                    <i class="fas fa-fingerprint"></i><br>BIOMETRICO
                                </button>
                                <button style="color:blue" type="button" class="btn  btnTop btn-sm"
                                    v-on:click="desactivarFiltro();" v-show="habilitaBiometrico">
                                    <i class="fas fa-fingerprint"></i><br>BIOMETRICO
                                </button>
                            </div>
                        </div>

                        <div class="col-sm-12"><br>
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <form id="form-archivos">
                                            <div class="row">
                                             
                                                    <div class="col-sm-6">
                                                        <div class="col-sm-4">
                                                            <label>SUBIR PORTADA:</label>
                                                            <button type="button" class="btn btn-sm"
                                                                v-on:click="verPlantilla('LOGIN PORTADA');">
                                                                <i style="color:red" class="fa fa-eye"></i>
                                                            </button>
                                                        </div>
                                                        <div class="col-sm-12 input-group">
                                                            <input type="file" name="file"
                                                                class="custom-file-input form-control"
                                                                id="file_login_portada" name="customFileLogin"
                                                                accept="image/png">
                                                            <label
                                                                class="custom-file-label form-control form-control-sm label_archivos"
                                                                id="label_login_portada" for="file_login_portada">Seleccione
                                                                el archivo</label>
                                                            &nbsp;&nbsp;
                                                            <button type="button" class="btn btn-primary btn-xs"
                                                                v-on:click="guardarAdministracionGraficaLogin('LOGIN PORTADA','file_login_portada');">
                                                                <i class="fa fa-save"></i>&nbsp;Guardar
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="col-sm-12">
                                                            <label>SUBIR LOGO:</label>
                                                            <button type="button" class="btn btn-sm"
                                                                v-on:click="verPlantilla('LOGIN LOGO');">
                                                                <i style="color:red" class="fa fa-eye"></i>
                                                            </button>
                                                        </div>
                                                        <div class="col-sm-12 input-group">
                                                            <input type="file" name="file"
                                                                class="custom-file-input form-control " id="file_login_logo"
                                                                name="customFileLogin" accept="image/png">
                                                            <label
                                                                class="custom-file-label form-control form-control-sm label_archivos"
                                                                id="label_login_logo" for="file_login_logo">Seleccione el
                                                                archivo</label>
                                                            &nbsp;&nbsp;
                                                            <button type="button" class="btn btn-primary btn-xs"
                                                                v-on:click="guardarAdministracionGraficaLogin('LOGIN LOGO','file_login_logo');">
                                                                <i class="fa fa-save"></i>&nbsp;Guardar
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="col-sm-12">
                                                            <label>CABECERA DE DOCUMENTOS:</label>
                                                            <button type="button" class="btn btn-sm"
                                                                v-on:click="verPlantilla('CABECERA DOCUMENTO');">
                                                                <i style="color:red" class="fa fa-eye"></i>
                                                            </button>
                                                        </div>
                                                        <div class="col-sm-12 input-group">
                                                            <input type="file" name="file"
                                                                class="custom-file-input form-control "
                                                                id="file_cabecera_doc" name="customFileLogin"
                                                                accept="image/png">
                                                            <label
                                                                class="custom-file-label form-control form-control-sm label_archivos"
                                                                id="label_cabecera_doc" for="file_cabecera_doc">Seleccione
                                                                el archivo</label>
                                                            &nbsp;&nbsp;
                                                            <button type="button" class="btn btn-primary btn-xs"
                                                                v-on:click="guardarAdministracionGraficaLogin('CABECERA DOCUMENTO','file_cabecera_doc');">
                                                                <i class="fa fa-save"></i>&nbsp;Guardar
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="col-sm-12">
                                                            <label>PIE DE DOCUMENTOS:</label>
                                                            <button type="button" class="btn btn-sm"
                                                                v-on:click="verPlantilla('PIE DOCUMENTO');">
                                                                <i style="color:red" class="fa fa-eye"></i>
                                                            </button>
                                                        </div>
                                                        <div class="col-sm-12 input-group">
                                                            <input type="file" name="file"
                                                                class="custom-file-input form-control "
                                                                id="file_pie_documento" name="customFileLogin"
                                                                accept="image/png">
                                                            <label
                                                                class="custom-file-label form-control form-control-sm label_archivos"
                                                                id="label_pie_doc" for="file_pie_documento">Seleccione el
                                                                archivo</label>
                                                            &nbsp;&nbsp;
                                                            <button type="button" class="btn btn-primary btn-xs"
                                                                v-on:click="guardarAdministracionGraficaLogin('PIE DOCUMENTO','file_pie_documento');">
                                                                <i class="fa fa-save"></i>&nbsp;Guardar
                                                            </button>
                                                        </div>
                                                    </div>

                                              
                                            </div>
                                        </form>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col-sm-12" style="text-align:center"
                                                    v-show="plantillaLogin!=null"><br>
                                                    <img style="width:250px;height:110xpx" :src="plantillaLogin" />
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                </div>
                                <div class="card-body">
                                    <button id="BotonDatatable" onclick="datatableCargar()" class="hidden"></button>
                                    <div class="table table-responsive" id="tablaConsulta">
                                        <table class="table table-bordered table-striped" id="dtmenu"
                                            style="width:100%!important">
                                            <thead>
                                                <th>Reg</th>
                                                <th>fecha de Registro</th>
                                                <th>Tipo</th>
                                                <th>Imagen</th>
                                                <th>estado</th>
                                                <th></th>

                                            </thead>
                                            <tbody id="tbobymenu" class="menu-pen">

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">

            </div>
        </div>
    </div>
    <script src="{{ url('js/vue.js') }}"></script>
    <script src="{{ url('js/axios.js') }}"></script>
    <script src="{{ url('js/modules/administracion_grafico/vue_script.js?v=6') }}"></script>
@endsection
