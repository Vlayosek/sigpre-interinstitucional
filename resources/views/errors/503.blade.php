<!DOCTYPE html>
<html lang="es">

<head>
    @include('partials.head')
</head>
<body class="hold-transition skin-blue sidebar-mini" style="background: #ccc;">
    <div id="wrapper">

        <div class="">
            <!-- Main content -->
            <section class=" content">
                <div class="container">
                    <div class="panel panel-default">


                        @if (isset($siteTitle))
                            <h3 class="page-title">
                                {{ $siteTitle }}
                            </h3>
                        @endif

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
                                <!-- Content Header (Page header) -->
                                <section class="content-header">
                                    <h1>
                                        @yield('contentheader_title')
                                        <small>@yield('contentheader_description')</small>
                                    </h1>
                                </section>
                                <div id="contenidoRegularizacion">
                                    @if (isset($errorRegularizacion))
                                        <div id="contenidoRegularizacion" class="hidden">
                                        @else
                                            <div id="contenidoRegularizacion">
                                    @endif
                                    @yield('content')
                                </div>
                                    <div id="modalRegularizacion" class="card card-body">
                                   
                                <div class="col-md-12" style="text-align:center">
                                    <h2>403</h2>
                                    <h2><strong>No tiene permiso para acceder</strong></h2>
                                </div>
                                <br />
                                <strong style="text-align:center">Espere por favor, será redireccionado en 5
                                    segundos</strong>
                                <span style="text-align:center">&nbsp;o &nbsp;</span>
                                <a href='{{ url('/login') }}' style="text-align:center"> Ir al Inicio</a>
                            </div>
            </section>
        </div>
    </div>

    {!! Form::open(['route' => 'auth.logout', 'style' => 'display:none;', 'id' => 'logout']) !!}
    <button type="submit">Logout</button>
    {!! Form::close() !!}
    {!! Form::open(['route' => 'administracion', 'style' => 'display:none;', 'id' => 'reinicia-brand', 'method' => 'GET']) !!}
    <button type="submit" class="hidden"></button>
    {!! Form::close() !!}
    @include('partials.javascriptsSN')
    @if (isset($errorRegularizacion))
        <script>
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

</body>

</html>
