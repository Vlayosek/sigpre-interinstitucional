<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link pushmenu" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
        <div class="contenido hidden">
            <div class="reloj">
                <span id="horas"></span>
                <span id="minutos"></span>
                <span id="segundos"></span>
            </div>
        </div>

        <div class="contenido escritorio"><strong style="font-size: 20px;">
                ¡Hora actual!: <span id="hora_general" style="margin: 6px;font-size: 20px;"></span></strong>
        </div>

    </ul>

    <!-- Right navbar links -->

    <ul class="navbar-nav ml-auto ">



        @if (config('app.NOTIFICACIONES') == 'COMPROMISO')
            {{-- NOTIFICACIONES DE COMPROMISOS --}}
            <li class="nav-item dropdown showCompromisos- showCompromisos">
                <div id="notificacionMain">
                    {{-- <a class="nav-link" alt="NOTIFICACIONES" data-toggle="dropdown" href="#"
                    onclick="removerShowMensajesCompromisos(); appNotificacion.consultarDTNotificaciones()"> --}}
                    <a class="nav-link" alt="NOTIFICACIONES" data-toggle="dropdown" href="#">
                        <i class="fa fa-solid fa-bell" style="font-size: 30px;"></i>
                        <span class="badge badge-warning navbar-badge"><span
                                v-text="total_notificaciones_compromisos"></span></span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right" style="font-size: 14px;">
                        <span class="dropdown-item dropdown-header"><span
                                v-text="total_notificaciones_compromisos"></span>
                            Notificaciones</span>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item" data-toggle="modal" data-target="#modal-notificaciones"
                            v-on:click="consultarDTNotificaciones('AVANCE'); tipo='AVANCE' " data-backdrop="static"
                            data-keyboard="false">
                            <i class="fa fa-forward mr-2"></i><span v-text="cantidad_avance">2</span>
                            Avances
                        </a>
                        <a href="#" class="dropdown-item" data-toggle="modal" data-target="#modal-notificaciones"
                            v-on:click="consultarDTNotificaciones('ARCHIVO');  tipo='ARCHIVO'" data-backdrop="static"
                            data-keyboard="false">
                            <i class="fas fa-file mr-2"></i><span v-text="cantidad_archivo">2</span>
                            Archivos
                        </a>
                        <a href="#" class="dropdown-item" data-toggle="modal" data-target="#modal-notificaciones"
                            v-on:click="consultarDTNotificaciones('MENSAJE');  tipo='MENSAJE'" data-backdrop="static"
                            data-keyboard="false">
                            <i class="fas fa-envelope mr-2"></i><span v-text="cantidad_mensaje">2</span>
                            Mensajes
                        </a>
                        <a href="#" class="dropdown-item" data-toggle="modal" data-target="#modal-notificaciones"
                            v-on:click="consultarDTNotificaciones('OBJETIO');  tipo='OBJETIO'" data-backdrop="static"
                            data-keyboard="false">
                            <i class="fas fa-bullseye mr-2"></i><span v-text="cantidad_objetivo">2</span>
                            Objetivos
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item dropdown-footer" data-toggle="modal"
                            data-target="#modal-notificaciones" data-backdrop="static" data-keyboard="false"
                            v-on:click="consultarDTNotificaciones('TODOS');">Ver todas las
                            Notificaciones</a>

                    </div>

                </div>
            </li>
            {{-- FIN DE NOTIFICACIONES DE COMPROMISOS --}}
        @else
            <li class="nav-item
                        dropdown incluirShow- incluirShow">
                <div id="notificacionMain">
                    <a class="nav-link" href="#" data-toggle="dropdown" onclick="removerShowMensajes()">
                        <i class="far fa-bell" style="font-size: 30px;"></i>
                        <span class="badge badge-warning navbar-badge" style="font-size: 12px;"
                            v-text="total_notificaciones">2</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right incluirShow"
                        style="left: inherit; right: 0px;">
                        <span class="dropdown-item dropdown-header"><span v-text="total_notificaciones">2</span>
                            Notificación</span>
                        <div v-show="actividades_pendientes_registrar>0">
                            <div class="dropdown-divider"></div>
                            <p href="#" class="dropdown-item" style="font-size: 14px;">
                                <i class="fas fa-envelope mr-2"></i><span v-text="actividades_pendientes_registrar"
                                    style="font-size: 14px;"> </span>
                                <span v-text="actividades_pendientes_registrar>1?'Actividades':'Actividad'"
                                    style="font-size: 14px;">Actividades</span> de teletrabajo <span
                                    v-text="actividades_pendientes_registrar>1?'pendientes':'pendiente'"
                                    style="font-size: 14px;">pendientes</span> de
                                Registrar
                            </p>
                        </div>
                        <div v-show="actividades_pendientes_aprobar>0">
                            <div class="dropdown-divider"></div>
                            <p href="#" class="dropdown-item" style="font-size: 14px;">
                                <i class="fas fa-envelope mr-2"></i><span
                                    v-text="actividades_pendientes_aprobar"style="font-size: 14px;"> </span>
                                <span v-text="actividades_pendientes_aprobar>1?'Actividades':'Actividad'"
                                    style="font-size: 14px;">Actividades</span> de teletrabajo <span
                                    v-text="actividades_pendientes_aprobar>1?'pendientes':'pendiente'"
                                    style="font-size: 14px;">pendientes</span> de
                                Aprobar
                            </p>
                        </div>

                    </div>
                </div>

            </li>
        @endif





        <li class="" style="font-size:14px;padding-top:8px">
            @auth

                @if (Auth::user()->evaluarole(['SERVIDOR']))
                    <a href="#" data-widget="control-sidebar" data-slide="true"role="button"
                        id="perfil-content">
                        Bienvenido, {!! Auth::user()->nombres !!}
            </li>
            </a>
        @else
            <a href="#" role="button">
                Bienvenido, {!! Auth::user()->nombres !!}</li>
            </a>
            @endif

            <li class="nav-link" style="font-size:16px;padding-top:5px">|</li>
            @if (session('impersonated_by'))
                <li class="navbar-nav  ">
                    <a href="{{ url('/impersonate_leave') }}" class="nav-link">
                        <p>
                            REGRESAR A MI USUARIO
                        </p>
                    </a>
                </li>
            @endif
        @endauth
        <li class="navbar-nav  ">
            <a href="{{ url('/logout') }}"
                onclick="event.preventDefault();
        document.getElementById('logout-form').submit();"
                class="nav-link">
                <p>
                    CERRAR SESIÓN
                </p>
            </a>
        </li>

        <li class="nav-item escritorio">
            <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                <i class="fas fa-expand-arrows-alt"></i>
            </a>
        </li>

        {{-- <li class="nav-item">
            <a class="nav-link" data-widget="control-sidebar" data-controlsidebar-slide="true" href="#"
                role="button">
                <i class="fas fa-th-large"></i>
            </a>
        </li> --}}
    </ul>

</nav>
@include('partials.modal')
