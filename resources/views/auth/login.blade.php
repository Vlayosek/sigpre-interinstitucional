<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>SIGPRE INTERINSTITUCIONAL</title>

    <link rel="shortcut icon" href="loginSIGPRE/images/fav.html">
    <link rel="stylesheet" href="loginSIGPRE/css/bootstrap.min.css">
    <link rel="stylesheet" href="loginSIGPRE/css/fontawsom-all.min.css">
    <link rel="stylesheet" type="text/css" href="loginSIGPRE/css/style.css" />
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

    <style>
        #first-name {
            text-transform: lowercase;
        }

        .hidden {
            display: none;
        }

        /* #### Mobile Phones Portrait #### */
        @media screen and (max-device-width: 480px) and (orientation: portrait) {

            /* some CSS here */
            .escritorio {
                display: block;
            }
        }

        /* #### Mobile Phones Landscape #### */
        @media screen and (max-device-width: 640px) and (orientation: landscape) {

            /* some CSS here */
            .escritorio {
                display: none;
            }

            .movil {
                display: block;
            }
        }

        /* #### Mobile Phones Portrait or Landscape #### */
        @media screen and (max-device-width: 640px) {

            /* some CSS here */
            .escritorio {
                display: none;
            }

            .movil {
                display: block;
            }
        }

        /* #### iPhone 4+ Portrait or Landscape #### */
        @media screen and (max-device-width: 480px) and (-webkit-min-device-pixel-ratio: 2) {

            /* some CSS here */
            .escritorio {
                display: none;
            }

            .movil {
                display: block;
            }
        }

        /* #### Tablets Portrait or Landscape #### */
        @media screen and (min-device-width: 768px) and (max-device-width: 1024px) {

            /* some CSS here */
            .escritorio {
                display: none;
            }

            .movil {
                display: block;
            }

        }

        .flex-column-movil {
            flex-direction: unset !important;
        }

        /* #### Desktops #### */
        @media screen and (min-width: 1024px) {
            .escritorio {
                display: block;
            }

            .movil {
                display: none;
            }

            .flex-column-movil {
                flex-direction: column !important;
            }

            /* some CSS here */
        }

        .backgroundGrafica {
                 }
    </style>
</head>

<body>
    <div class="flex">
        <div class="contenido hidden">
            <div class="reloj">
                <span id="horas"></span>
                <span id="minutos"></span>
                <span id="segundos"></span>
            </div>
        </div>

    </div>
    <div class="container-fluid conya">
        <div class="side-left backgroundGrafica">
            <div class="sid-layy">
                <div class="row slid-roo">
                    <div class="data-portion">

                    </div>
                </div>
            </div>
        </div>
        <div class="side-right">
            <div class="contenido escritorio"
                style="position: absolute;/*! background-color: #221e1e6b; *//*! box-shadow: 0px 0px 20px #9d9d9d; *//*! margin: 28px; *//*! padding: 52px; *//*! flex: 4 4; *//*! text-align: center; */font-size: 40px;/*! color: #fff; */border-radius: 20px;top: 12px;right: 80px;">
                <p id="hora_general" style="font-size:55px;line-height:50px;padding-left: 15px;"></p>
                <p style="font-size:10px;"><span style="padding-right: 20px;">HORAS</span><span>MINUTOS</span><span
                        style="padding-left: 20px;">SEGUNDOS</span>
                </p>
            </div>
            <form class="validate-form" role="form" method="POST" action="{{ url('login') }}" id="form-login"
                style="padding-top: 50px;">

                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                @if (array_key_exists('LOGIN LOGO', $graficos))
                    <img class="logo" src="{!! 'data:image/png;base64,' . $graficos['LOGIN LOGO'] !!}" alt="">
                @else
                    <img class="logo" src="/Logo_Presidencia.png" alt="">
                @endif

                <?php
                $ipaddress = '';
                if (getenv('HTTP_CLIENT_IP')) {
                    $ipaddress = getenv('HTTP_CLIENT_IP');
                } elseif (getenv('HTTP_X_FORWARDED_FOR')) {
                    $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
                } elseif (getenv('HTTP_X_FORWARDED')) {
                    $ipaddress = getenv('HTTP_X_FORWARDED');
                } elseif (getenv('HTTP_FORWARDED_FOR')) {
                    $ipaddress = getenv('HTTP_FORWARDED_FOR');
                } elseif (getenv('HTTP_FORWARDED')) {
                    $ipaddress = getenv('HTTP_FORWARDED');
                } elseif (getenv('REMOTE_ADDR')) {
                    $ipaddress = getenv('REMOTE_ADDR');
                } else {
                    $ipaddress = 'UNKNOWN';
                }
                $ip = $ipaddress;
                $objSelect = new App\Http\Controllers\Ajax\SelectController();

                ?>
                @if ($objSelect->verificarEQUIPO($ip)['valida'])
                    <br />
                    <h1 style="font-size:15px"><strong>EQUIPO RESTRINGIDO <br />UNICAMENTE</strong>
                        <p><?php echo $objSelect->verificarEQUIPO($ip)['message']; ?></p>
                    </h1>
                @else
                    <h2></h2>
                @endif
                <div class="form-row">
                    <label for="">Usuario</label>
                    <input type="text" placeholder="perezja" class="form-control form-control-sm" name="name"
                        id="first-name" autocomplete="new-user" required>
                </div>

                <div class="form-row">
                    <label for="">Clave</label>
                    <input type="password" placeholder="Clave" class="form-control form-control-sm" id="campo_clave"
                        autocomplete="new-password" name="password" required>
                </div>



                <div class="form-row dfr">

                    <button class="btn btn-sm btn-success">Inicia Sesión</button>
                </div>

                @if (count($errors) > 0)
                    <div class="alert alert-danger" style="margin-top:10px">
                        <strong></strong> Existe un problema con el dato ingresado:
                        <br><br>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

            </form>
        </div>

    </div>
</body>

<script src="loginSIGPRE/js/jquery-3.2.1.min.js"></script>
<script src="loginSIGPRE/js/popper.min.js"></script>
<script src="loginSIGPRE/js/bootstrap.min.js"></script>
<script src="loginSIGPRE/js/script.js"></script>

<script src="https://www.google.com/recaptcha/api.js?render=6LeJFhAiAAAAAFhXk_TlvSAcQiA32fqQgNT5OpqQ"></script>

<script>
    var base_url = '{{ url('/') }}';
    $("#first-name").on("keyup", function() {
        $(this).val($(this).val().toLowerCase());
    });
    $(document).ready(function() {
        $('#entrar').click(function() {
            grecaptcha.ready(function() {
                grecaptcha.execute('6LeJFhAiAAAAAFhXk_TlvSAcQiA32fqQgNT5OpqQ', {
                    action: 'validarUsuario'
                }).then(function(token) {
                    $('#form-login').prepend(
                        '<input type="hidden" name="token_google" value="' + token +
                        '" >');
                    $('#form-login').prepend(
                        '<input type="hidden" name="action" value="validarUsuario" >'
                    );
                    $('#form-login').submit();
                });
            });
        });
    });
    var relojServidor = "";
    var contadorInicialHora = 0;
    let reloj_general = document.getElementById("hora_general");
    var time = null;
    var fecha_actual_servidor = null;
    muestraReloj();

    setInterval(muestraReloj, 1000);

    function muestraReloj() {

        var currentTime = new Date();

        reloj_general.innerHTML = currentTime.toLocaleTimeString();

    }
</script>
{{-- <script src="{{ url('reloj_virtual/script.js?v=10') }}"></script> --}}

<!-- Mirrored from www.smarteyeapps.com/demo/bootstrap-4-login-form/ by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 16 Jul 2021 12:39:13 GMT -->

</html>
