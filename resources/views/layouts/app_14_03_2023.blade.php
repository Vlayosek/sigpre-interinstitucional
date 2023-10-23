<!DOCTYPE html>
<html lang="es">

<head>
    @include('partials.head')
    @laravelPWA

    <link href="/images/icons/splash-640x1136.png"
        media="(device-width: 320px) and (device-height: 568px) and (-webkit-device-pixel-ratio: 2)"
        rel="apple-touch-startup-image" />
    <link href="/images/icons/splash-750x1334.png"
        media="(device-width: 375px) and (device-height: 667px) and (-webkit-device-pixel-ratio: 2)"
        rel="apple-touch-startup-image" />
    <link href="/images/icons/splash-1242x2208.png"
        media="(device-width: 621px) and (device-height: 1104px) and (-webkit-device-pixel-ratio: 3)"
        rel="apple-touch-startup-image" />
    <link href="/images/icons/splash-1125x2436.png"
        media="(device-width: 375px) and (device-height: 812px) and (-webkit-device-pixel-ratio: 3)"
        rel="apple-touch-startup-image" />
    <link href="/images/icons/splash-828x1792.png"
        media="(device-width: 414px) and (device-height: 896px) and (-webkit-device-pixel-ratio: 2)"
        rel="apple-touch-startup-image" />
    <link href="/images/icons/splash-1242x2688.png"
        media="(device-width: 414px) and (device-height: 896px) and (-webkit-device-pixel-ratio: 3)"
        rel="apple-touch-startup-image" />
    <link href="/images/icons/splash-1536x2048.png"
        media="(device-width: 768px) and (device-height: 1024px) and (-webkit-device-pixel-ratio: 2)"
        rel="apple-touch-startup-image" />
    <link href="/images/icons/splash-1668x2224.png"
        media="(device-width: 834px) and (device-height: 1112px) and (-webkit-device-pixel-ratio: 2)"
        rel="apple-touch-startup-image" />
    <link href="/images/icons/splash-1668x2388.png"
        media="(device-width: 834px) and (device-height: 1194px) and (-webkit-device-pixel-ratio: 2)"
        rel="apple-touch-startup-image" />
    <link href="/images/icons/splash-2048x2732.png"
        media="(device-width: 1024px) and (device-height: 1366px) and (-webkit-device-pixel-ratio: 2)"
        rel="apple-touch-startup-image" />

    <link href="{{ url('adminlte/plugins/notifications/sweetalert.css') }}" rel="stylesheet">
    <link rel="stylesheet"
        href="{{ url('adminlte/plugins/fullcalendar/bower_components/fullcalendar/dist/fullcalendar.min.css') }}">
    <link rel="stylesheet"
        href="{{ url('adminlte/plugins/fullcalendar/bower_components/fullcalendar/dist/fullcalendar.print.min.css') }}"
        media="print">
    <link href="{{ url('adminlte/plugins/datepicker/datepicker3.css') }}" rel="stylesheet">

    <script src="{{ url('adminlte3/plugins/jquery/jquery36.min.js?v=3') }}"></script>
    <script src="{{ url('adminlte/plugins/select21/dist/js/select2.js?v=3') }}"></script>

    <style>
        .paginador {
            border: 1px solid #ccc;
            padding: 5px;
            padding-left: 10px;
            padding-right: 10px;
        }

        .active_page {
            background: #3a7ee8;
            color: #ffffff !important;
        }
    </style>

</head>

<body class="hold-transition sidebar-mini">

    <?php $rolConsultaUATH = Auth::user() != null ? Auth::user()->evaluarole(['CONSULTA DE UATH']) : false; ?>

    <input type="hidden" id="inicializacion" value="{{ url('/') }}">
    <input type="hidden" id="direccionDocumentos" name="direccionDocumentos" value="{{ url('storage/') }}">
    <input type="hidden" id="direccionDocumentosPortal" name="direccionDocumentosPortal"
        value="{!! env('DIRECCION_PORTAL') !!}/nucleo/src/anexos/permisos/">
    <input type="hidden" id="direccionDocumentosSIGAP" name="direccionDocumentosSIGAP"
        value="{!! env('DIRECCION_SIGAP') !!}/fotos/">

    <div class="wrapper">
        <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
            {{ csrf_field() }}
        </form>
        @if ($retornarLogin == null)
            <?php $URLCerrar = url('/logout'); ?>
            <?php header($URLCerrar); ?>
            {!! die() !!}
        @endif
        @include('partials.topbar')
        @include('partials.sidebar')


        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6 titulos_content titulos_content_left hidden">
                            <ol class="breadcrumb float-sm-left">
                                <li class="breadcrumb-item"><a href="#">@yield('contentheader_title')</a></li>
                                <li class="breadcrumb-item active">@yield('contentheader_description')</li>
                            </ol>
                        </div><!-- /.col -->
                        <div class="col-sm-6 titulos_content">
                            <h1 class="m-0"></h1>
                        </div><!-- /.col -->
                        <div class="col-sm-6 titulos_content titulos_content_right">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">@yield('contentheader_title')</a></li>
                                <li class="breadcrumb-item active">@yield('contentheader_description')</li>
                            </ol>
                        </div><!-- /.col -->
                        <div class="col-sm-12">
                            <div class="row">
                                <div class="col-md-12">

                                    @if (Session::has('message'))
                                        <div class="note note-info">
                                            <p>{{ Session::get('message') }}</p>
                                        </div>
                                    @endif
                                    @if ($errors->count() > 0)
                                        <div class="note note-danger alert alert-danger">
                                            <ul class="list-unstyled">
                                                @foreach ($errors->all() as $error)
                                                    <li>- {{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif
                                </div>
                            </div><!-- /.row -->
                        </div><!-- /.container-fluid -->
                    </div>
                    <!-- /.content-header -->

                    <!-- Main content -->
                    <div class="content">
                        <input type="hidden" id="cedula_logueada" value="{{ Auth::user()->identificacion }}">
                        <span id="texto_usuario_nombreCompleto" class="hidden">{!! str_replace('CN=', '', explode(',', Auth::user()->nombreCompleto)[0]) !!}</span>
                        <div class="">
                            @yield('content')
                            <div id="main2">
                               
                            </div>
                            <script src="{{ url('js/vue.js') }}"></script>
                            <script src="{{ url('js/axios.js') }}"></script>
                            @yield('vue_js')
                        </div>
                    </div>
                </div>

                <aside class="control-sidebar control-sidebar-dark ">
                    <div class="p-3 control-sidebar-content">
                        <center> <img src="{{ url('/img/avatar_plusis.png') }}" width="130px" /></center>
                        <br />
                        <button class="btn btn-default btn-block btn-xs"
                            onclick="appPerfil.editarPerfil();document.getElementById('perfil-content').click()"
                            data-toggle="modal" data-target="#modal-persona" data-backdrop="static"
                            data-keyboard="false">Mi perfil</button>
                    </div>
                </aside>

            </div>
            @include('partials.javascripts')
            <script src="{{ url('adminlte3/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
            <script src="{{ url('adminlte3/dist/js/adminlte.js') }}"></script>
            <script src="{{ url('adminlte3/dist/js/demo.js') }}"></script>
            <script src="{{ url('js/modules/talento_humano/personas/sigpre/funcionarios.js?v=24') }}"></script>
            <script>
                $('.pickadate').datepicker({
                    formatSubmit: 'yyyy-mm-dd',
                    format: 'yyyy-mm-dd',
                    selectYears: true,
                    editable: true,
                    autoclose: true,
                    todayHighlight: true,
                    orientation: 'top'
                }).datepicker('update', new Date());
            </script>
            <script src="{{ url('adminlte/plugins/fileinput/fileinput.min.js') }}"></script>
            <script src="{{ url('serviceworker.js') }}"></script>
            <script>
                var base_url = '{{ url('/') }}';
            </script>
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            {!! Form::open([
                'route' => 'administracion',
                'style' => 'display:none;',
                'id' => 'reinicia-brand',
                'method' => 'GET',
            ]) !!}
            <button type="submit" class="hidden"></button>
            {!! Form::close() !!}
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        @if (isset($errorRegularizacion))
                            <script>
                                $(function() {
                                    $("#modalRegularizacion").click();
                                });

                                function redireccionar() {
                                    document.getElementById("reinicia-brand").submit();
                                }
                                const inactivityTimeReset = () => {
                                    let t;
                                    resetTimerReset();

                                    function resetTimerReset() {
                                        clearTimeout(t);
                                        t = setTimeout(redireccionar, 5000) // 10 minutos 600000 milisegundos
                                    }

                                }
                                inactivityTimeReset();
                            </script>
                        @endif

                        <a href="#modal-regularizacion" role="button" class="hidden" data-backdrop="static"
                            data-keyboard="false" data-toggle="modal" id="modalRegularizacion"></a>

                        <div class="modal fade" id="modal-regularizacion" role="dialog"
                            aria-labelledby="myModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="myModalLabel">
                                            Errores
                                        </h5>

                                    </div>
                                    <div class="modal-body">
                                        <div class="col-md-12">
                                            <div class="row">
                                                <div class="col-md-12" style="text-align:center">
                                                    @if (isset($errorRegularizacion))
                                                        {{ $errorRegularizacion }}
                                                    @endif
                                                </div>
                                                <br />
                                                <strong>Espere por favor, será redireccionado en 5 segundos</strong>
                                                &nbsp;o &nbsp;
                                                <a href='{{ url('/') }}'> Ir al Inicio</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">

                                    </div>
                                </div>

                            </div>

                        </div>

                    </div>
                </div>
            </div>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <a href="#cargando_modal" id="modal_cargando" role="button" class="hidden"
                            data-toggle="modal" data-backdrop="static" data-keyboard="false">Modal Cargando</a>

                        <div class="modal fade" id="cargando_modal" tabindex="-1" role="dialog"
                            aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <br />
                                    <h2 style="text-align:center">Cargando....</h2>
                                    <center><img src="{{ url('/spinner2.gif') }}" width="80%"></center>
                                    <button type="button" class="btn btn-secondary hidden"
                                        id="cerrar_modal_cargando" data-dismiss="modal">Close</button>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
</body>

</html>
