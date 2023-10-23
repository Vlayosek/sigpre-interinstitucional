@extends('layouts.app')

@section('contentheader_title')
    Panel de Control
@endsection

@section('contentheader_description')
    @auth
        Compromisos
    @endauth
@endsection

@section('content')
@section('css')
    <link href="{{ url('adminlte/plugins/notifications/sweetalert.css') }}" rel="stylesheet">
    <link href="{{ url('adminlte/style_moderno.css') }}" rel="stylesheet">
    <link href="{{ url('adminlte3/plugins/leaflet/dist/leaflet.css') }}" rel="stylesheet">
    <link href="{{ url('web-fonts-with-css/css/fontawesome-all.min.css') }}" rel="stylesheet">
    <link href="{{ url('mapas_compromisos/css/estilo.css') }}" rel="stylesheet">

    <style>
        .span {
            word-wrap: break-word;
            font-size: 0.8px;
        }
    </style>
    <style>
        #span_canar {
            position: absolute;
            top: 100px;
            left: 150px;
        }

        /*
                .imagen_imbabura {
                    display: block;

                }*/
    </style>
    <style>
        .titulos_content {
            display: none;
        }

        #heatmap {
            height: 400px;
            width: 600px;
            max-width: 100%;
            max-height: 100%;
        }
    </style>
    <style type="text/css">
        * {
            margin: 0;
            padding: 0;
        }

        .bg {
            width: 200px;
            height: 200px;
            border-radius: 100%;
            background: #ccc;
            position: relative;
            margin: 20px auto;
        }

        .circle-right,
        .circle-left,
        .mask-right,
        .mask-left {
            width: 200px;
            height: 200px;
            border-radius: 100%;
            position: absolute;
            top: 0;
            left: 0;
        }

        .circle-right,
        .circle-left {
            background: skyblue;
        }

        .mask-right,
        .mask-left {
            background: #ccc;
        }

        .circle-right,
        .mask-right {
            clip: rect(0, 200px, 200px, 100px);
        }

        .circle-left,
        .mask-left {
            clip: rect(0, 100px, 200px, 0);
        }

        .text {
            width: 160px;
            height: 160px;
            line-height: 160px;
            text-align: center;
            font-size: 34px;
            color: deepskyblue;
            border-radius: 100%;
            background: #fff;
            position: absolute;
            top: 20px;
            left: 20px;
        }
    </style>
@endsection
@section('javascript')
    <script src="{{ url('js/modules/compromisos/reporte/script.js?v=9') }}" type="module"></script>
    <script src="{{ url('js/modules/compromisos/reporte/highcarts/config.hightchart.js') }}"></script>

    <script src="{{ url('adminlte3/plugins/leaflet/dist/leaflet.js') }}"></script>
    <script src="{{ url('js/libs/heatmap/heatmap.min.js') }}"></script>
    <script src="{{ url('js/libs/heatmap/leaflet-heatmap.js') }}"></script>
    <script src="{{ url('js/libs/heatmap/leaflet-simple-map-screenshoter.js') }}" defer></script>

    <script src="{{ url('js/modules/compromisos/datatableCompromisos.js?v=14') }}"></script>
    <script src="{{ url('js/modules/compromisos/global.js?v=8') }}"></script>
    <script src="{{ url('js/modules/compromisos/reporte/global.js?v=8') }}"></script>
    <script src="{{ url('js/modules/compromisos/reporte/mapa/mapa.js') }}"></script>
    <script src="{{ url('js/modules/dt.utils.js') }}" type="module"></script>
    {!! Html::script('adminlte3/plugins/jspdf/jspdf.min.js') !!}
    <script src="{{ url('adminlte3/plugins/canvg') }}/canvg.min.js"></script>

    <!-- <script src="{{ url('adminlte3/plugins/canvg') }}/canvg.js" defer></script>-->
    <!-- <script src="{{ url('adminlte3/plugins/canvg') }}/umd.js" defer></script>-->
    <script>
        var element = document.getElementById("mapa_svg");
        element.scrollIntoView({
            inline: "end"
        });
    </script>
    <script>
        $(function() {
            iniciaMapaDeCalor();
        });
        $("#filtro_compromiso_individual").on("change", function() {
            app.filtro_compromiso_individual = $(this).val();
        });

        /*   $('#cmd').click(function() {
               //  await generarImagenCanvas();
               var doc = new jsPDF();
               doc.addHTML($("#contentReporteIndividual"), function() {
                   var imgData3 = $("#grafico3").attr("src");
                   doc.addImage(imgData3, 'PNG', 140, 8, 72, 90);
                   var imgData = $("#grafico1").attr("src");
                   doc.addImage(imgData, 'PNG', 15, 150, 70, 60);
                   var imgData2 = $("#grafico2").attr("src");
                   doc.addImage(imgData2, 'PNG', 120, 150, 70, 60);
                   doc.save('compromisosUbicacion.pdf');
               });
           });*/

        function genPDF() {

            html2canvas(document.getElementById('canvas_mapa'), {
                useCORS: true,
                onrendered: (canvas) => {
                    let doc = new jsPDF("l", "mm", "a4");

                    //Obtengo la dimensión en pixeles en base a la documentación
                    // https://github.com/MrRio/jsPDF/blob/ddbfc0f0250ca908f8061a72fa057116b7613e78/jspdf.js#L59
                    let a4Size = {
                        w: convertPointsToUnit(595.28, 'px'),
                        h: convertPointsToUnit(841.89, 'px')
                    }

                    /*Nuevo Canvas donde generare mis imágenes separadas*/
                    let canvastoPrint = document.createElement('canvas');
                    let ctx = canvastoPrint.getContext("2d");

                    /* Medidas de mi hoja*/
                    canvastoPrint.width = a4Size.w;
                    canvastoPrint.height = a4Size.h;

                    /* Tomo cuanto corresponde esos los 700 pixeles restantes de el total de mi imagen*/
                    let aspectRatioA4 = a4Size.w / a4Size.h;
                    let rezised = canvas.width / aspectRatioA4;

                    let printed = 0,
                        page = 0;
                    while (printed < canvas.height) {
                        //Tomo la imagen en proporcion a el ancho y alto.
                        ctx.drawImage(canvas, 0, printed, canvas.width, rezised, 0, 0, a4Size.w, a4Size.h);
                        var imgtoPdf = canvastoPrint.toDataURL("image/png");
                        let width = doc.internal.pageSize.width;
                        let height = doc.internal.pageSize.height;
                        if (page == 0) { // si es la primera pagina, va directo a doc
                            doc.addImage(imgtoPdf, 'JPEG', 0, 0, height, width);
                        } else { // Si no ya tengo que agregar nueva hoja.
                            let page = doc.addPage();
                            page.addImage(imgtoPdf, 'JPEG', 0, 0, height, width);
                        }
                        ctx.clearRect(0, 0, canvastoPrint.height, canvastoPrint.width); // Borro el canvas
                        printed += rezised; //actualizo lo que ya imprimi
                        page++; // actualizo mi pagina
                    }
                    let date = new Date();
                    doc.save('compromisos' + date.toLocaleDateString() + '.pdf');
                    //   doc.save('test.pdf');

                }
            });

            function convertPointsToUnit(points, unit) {
                // Unit table from https://github.com/MrRio/jsPDF/blob/ddbfc0f0250ca908f8061a72fa057116b7613e78/jspdf.js#L791
                var multiplier;
                switch (unit) {
                    case 'pt':
                        multiplier = 1;
                        break;
                    case 'mm':
                        multiplier = 72 / 25.4;
                        break;
                    case 'cm':
                        multiplier = 72 / 2.54;
                        break;
                    case 'in':
                        multiplier = 72;
                        break;
                    case 'px':
                        multiplier = 96 / 72;
                        break;
                    case 'pc':
                        multiplier = 12;
                        break;
                    case 'em':
                        multiplier = 12;
                        break;
                    case 'ex':
                        multiplier = 6;
                    default:
                        throw ('Invalid unit: ' + unit);
                }
                return points * multiplier;
            }
            /* html2canvas(div, {
                 canvas:canvas,
                 onrendered: function (canvas) {
                     theCanvas = canvas;
                     document.body.appendChild(canvas);

                     Canvas2Image.saveAsPNG(canvas);
                     $(body).append(canvas);
                 }
             });*/
        }
    </script>
    <script type="text/javascript">
        $(function() {
            // Obtener el valor porcentual
            var num = parseInt($('.text').html());

            // Muestra el porcentaje de progreso de la transición a través de un temporizador
            var temp = 0;
            var timer = setInterval(function() {
                calculate(temp);
                // Limpia el temporizador para finalizar la llamada al método
                if (temp == num) {
                    clearInterval(timer);
                }
                temp++;
            }, 30)

            // Cambiar el porcentaje de visualización de la página

        })

        function calculate(value) {
            // Cambiar el valor mostrado en la página
            $('.text').html(value + '%');

            // Limpia el efecto residual de la última llamada a este método
            $('.circle-left').remove();
            $('.mask-right').remove();

            // Cuando el porcentaje es menor o igual a 50
            if (value <= 50) {
                var html = '';

                html += '<div class="mask-right" style="transform:rotate(' + (value * 3.6) + 'deg)"></div>';

                // Agrega elementos secundarios al elemento
                $('.circle-right').append(html);
            } else {
                value -= 50;
                var html = '';

                html += '<div class="circle-left">';
                html += '<div class="mask-left" style="transform:rotate(' + (value * 3.6) + 'deg)"></div>';
                html += '</div>';

                // Agregar elemento tras elemento
                $('.circle-right').after(html);
            }
        }
    </script>
    <script src="{{ url('js/modules/compromisos/reporte/script_grafico.js?v=5') }}"></script>
@endsection

<div id="main">
    <a href="#" class="hidden" id="botonImprimir" target="_blank" download>Boton Imprimir</a>
    <div class="card">
        <div class="card-heading">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-10">
                        <div class="btn-group dropdown" style="float:left">
                            <button class="btn btn-default dropdown-toggle" aria-haspopup="true" aria-expanded="false"
                                onclick="transaccionToogle(this)">Filtros de Datos<span class="caret"></span></button>
                            <ul class="dropdown-menu">
                                <li class="hidden">
                                    <a href="#" class="dropdown-item" data-toggle="modal"
                                        data-target="#modal-DETALLADO"
                                        v-on:click="limpiarReporteEjecutivo();consulta_compromiso();"
                                        data-backdrop="static" data-keyboard="false"
                                        onclick="transaccionToogle(this)">Reporte Detallado</a>
                                </li>
                                <li>
                                    <a href="#" class="dropdown-item" data-toggle="modal"
                                        data-target="#modal-EJECUTIVO" v-on:click="limpiarReporteEjecutivo()"
                                        data-backdrop="static" data-keyboard="false"
                                        onclick="transaccionToogle(this)">Reporte Ejecutivo</a>
                                </li>
                                <li>
                                    <a href="#" class="dropdown-item" data-toggle="modal"
                                        data-target="#modal-COMPROMISOS_CUMPLIDOS" v-on:click="consultasCC()"
                                        data-backdrop="static" data-keyboard="false"
                                        onclick="transaccionToogle(this)">Compromisos Cumplidos</a>
                                </li>
                                <li>
                                    <a href="#" class="dropdown-item" data-toggle="modal"
                                        data-target="#modal-COMPROMISOS_MINISTERIO"
                                        v-on:click="limpiarCompromisoMinisterio()" data-backdrop="static"
                                        data-keyboard="false" onclick="transaccionToogle(this)">Compromisos por
                                        Ministerio</a>
                                </li>
                                <li>
                                    <a href="#" class="dropdown-item" data-toggle="modal"
                                        data-target="#modal-COMPROMISOS_GABINETE"
                                        v-on:click="limpiarCompromisoGabinete()" data-backdrop="static"
                                        data-keyboard="false" onclick="transaccionToogle(this)">Compromisos por
                                        Gabinete</a>
                                </li>
                                <li>
                                    <a href="#" class="dropdown-item" data-toggle="modal"
                                        data-target="#modal-MAPA_CALOR1"
                                        v-on:click="limpiarMapaCompromisoIndividual();consultasCC();"
                                        data-backdrop="static" data-keyboard="false"
                                        onclick="$('#filtro_gestion').val(null).change();$('#filtro_ubicacion').val(null).change();transaccionToogle(this)">Compromisos
                                        por Ubicaci&oacute;n
                                    </a>
                                </li>
                                <li class="hidden">
                                    <a href="#" class="dropdown-item" data-toggle="modal"
                                        data-target="#modal-MAPA_CALOR2" v-on:click="limpiarMapaTodosCompromisos()"
                                        data-backdrop="static" data-keyboard="false"
                                        onclick="transaccionToogle(this)">Todos los
                                        Compromisos por Ubicaci&oacute;n</a>
                                </li>

                                <li>
                                    <a href="#" class="dropdown-item" data-toggle="modal"
                                        data-target="#modal-RESUMEN_GABINETE"
                                        v-on:click="limpiarCompromisoGabineteResumen('gestion')" data-backdrop="static"
                                        data-keyboard="false" onclick="transaccionToogle(this)">Gabinete Estado de Gestión</a>
                                </li>
                                <li>
                                  <a href="#" class="dropdown-item" data-toggle="modal"
                                      data-target="#modal-RESUMEN_GABINETE"
                                      v-on:click="limpiarCompromisoGabineteResumen('compromiso')" data-backdrop="static"
                                      data-keyboard="false" onclick="transaccionToogle(this)">Gabinete Estados de Compromiso</a>
                              </li>
                            </ul>
                        </div>
                        <button onclick="imprimirDiv()" class="btn btn-default hidden">&nbsp;EXPORTAR PDF</button>
                        <button id="cmd" class="btn btn-default"
                            v-show="arregloDatosUbicacionGenerados!=null&&!cargando" v-on:click="generarPdf()"><img
                                src="/images/icons/pdf.png" width="15px" heigh="10px">&nbsp;IMPRIMIR DATOS
                            GENERADOS</button>
                        <button class="btn btn-default" disabled v-show="cargando"><img
                                src="{{ url('/spinner.gif') }}">&nbsp;Cargando..</button>
                        <!--  <button id="cmd" class="btn btn-default" v-show="habilitaImprimir"><img
                                src="/images/icons/pdf.png" width="15px" heigh="10px">&nbsp;IMPRIMIR</button>-->
                        <div id="elementH"></div>
                        <br />
                        <button class="btn btn-default btn-block hidden" type="button"
                            v-on:click="exportarReporteIndividual()">
                            <span class="fa fa-search">&nbsp;Exportar PDF</span>
                        </button>
                        <br />
                    </div>
                    <div class="col-md-2 carga_titulos_content btnTop">

                    </div>
                </div>
            </div>


            <div class="col-md-12 btnTop">
                <div class="row">
                    <div class="col-md-12 hidden"
                        style="padding-bottom:0px!important;padding-top:0px!important;text-align:center"><br>
                        <div class="row justify-content-center align-items-center">
                            <!--FILTRO PASTEL Y PERZONALIZADO-->&nbsp;&nbsp;&nbsp;
                            <div class="col-md-2" style="float:right;padding-right: 0px; padding-left: 0px;width:60%">
                                <button class="btn btn-info btn-block btn-sm" data-toggle="modal"
                                    data-target="#modal-DETALLADO"
                                    v-on:click="limpiarReporteEjecutivo();consulta_compromiso();"
                                    data-backdrop="static" data-keyboard="false"><i
                                        class="fa fa-filter"></i>&nbsp;Reporte Detallado</button>
                            </div>&nbsp;&nbsp;&nbsp;
                            <div class="col-md-2" style="float:right;padding-right: 0px; padding-left: 0px;width:60%">
                                <button class="btn btn-info btn-block btn-sm" data-toggle="modal"
                                    data-target="#modal-EJECUTIVO" v-on:click="limpiarReporteEjecutivo()"
                                    data-backdrop="static" data-keyboard="false"><i
                                        class="fa fa-filter"></i>&nbsp;Reporte Ejecutivo</button>
                            </div>&nbsp;&nbsp;&nbsp;
                            <div class="col-md-2" style="float:right;padding-right: 0px; padding-left: 0px;width:60%">
                                <button class="btn btn-info btn-block btn-sm" data-toggle="modal"
                                    data-target="#modal-COMPROMISOS_CUMPLIDOS" v-on:click="consultasCC()"
                                    data-backdrop="static" data-keyboard="false"><i
                                        class="fa fa-filter"></i>Compromisos
                                    Cumplidos</button>
                            </div>&nbsp;&nbsp;&nbsp;
                            <!--FIN FILTRO PASTEL Y PERZONALIZADO-->
                            <div class="col-md-2" style="float:right;padding-right: 0px; padding-left: 0px;width:60%">
                                <button class="btn btn-info btn-block btn-sm" data-toggle="modal"
                                    data-target="#modal-COMPROMISOS_MINISTERIO"
                                    v-on:click="limpiarCompromisoMinisterio()" data-backdrop="static"
                                    data-keyboard="false"><i class="fa fa-filter"></i>Compromisos por
                                    Ministerio</button>
                            </div>&nbsp;&nbsp;&nbsp;
                            <div class="col-md-2" style="float:right;padding-right: 0px; padding-left: 0px;width:60%">
                                <button class="btn btn-info btn-block btn-sm" data-toggle="modal"
                                    data-target="#modal-COMPROMISOS_GABINETE" v-on:click="limpiarCompromisoGabinete()"
                                    data-backdrop="static" data-keyboard="false"><i
                                        class="fa fa-filter"></i>Compromisos
                                    por Gabinete</button>
                            </div>&nbsp;&nbsp;&nbsp;
                        </div><br>
                        <div class="row justify-content-center align-items-center">
                            &nbsp;&nbsp;&nbsp;
                            <div class="col-md-2" style="float:right;padding-right: 0px; padding-left: 0px;width:60%">
                                <button class="btn btn-info btn-block btn-sm" data-toggle="modal"
                                    data-target="#modal-MAPA_CALOR1" v-on:click="limpiarMapaCompromisoIndividual()"
                                    data-backdrop="static" data-keyboard="false"
                                    onclick="$('#filtro_gestion').val(null).change();$('#filtro_ubicacion').val(null).change();"><i
                                        class="fa fa-filter"></i>Compromiso
                                    Individual</button>
                            </div>&nbsp;&nbsp;&nbsp;<br>
                            <div class="col-md-2" style="float:right;padding-right: 0px; padding-left: 0px;width:60%">
                                <button class="btn btn-info btn-block btn-sm" data-toggle="modal"
                                    data-target="#modal-MAPA_CALOR2" v-on:click="limpiarMapaTodosCompromisos()"
                                    data-backdrop="static" data-keyboard="false"><i class="fa fa-filter"></i>Todos
                                    los
                                    Compromisos</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="page-pdf">
            <div class="card-body" id="contentReporteIndividual" style="background-color: #ffffff">
                <button id="BotonDatatable" onclick="datatableCompromisosCumplidosRep()" class="hidden"></button>
                <div class="row">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="card card-info" style="min-height: 60px;">
                                            <div class="card-header">
                                                <h3 class="card-title">Total /Casos</h3>
                                            </div>
                                            <h4 class="card-body" style="text-align:center" id="total_compromisos">
                                                0
                                            </h4>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="card card-info" style="min-height: 60px;">
                                            <div class="card-header">
                                                <h3 class="card-title">Nacional</h3>
                                            </div>
                                            <h4 class="card-body" style="text-align:center" id="total_nacional">
                                                0
                                            </h4>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="card card-info" style="min-height: 60px;">
                                            <div class="card-header">
                                                <h3 class="card-title">Provincial</h3>
                                            </div>
                                            <h4 class="card-body" style="text-align:center" id="total_provincias">
                                                0
                                            </h4>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="card card-info" style="min-height: 60px;">
                                            <div class="card-header">
                                                <h3 class="card-title">Cantonal</h3>
                                            </div>
                                            <h4 class="card-body" style="text-align:center" id="total_cantones">
                                                0
                                            </h4>
                                        </div>
                                    </div>
                                    <div class="col-md-12 hidden">
                                        <div class="card card-info" style="min-height: 60px;">
                                            <div class="card-header">
                                                <h3 class="card-title">Avances</h3>
                                            </div>
                                            <h4 class="card-body" style="text-align:center;padding:0px!important">
                                                <div class="bg">
                                                    <div class="circle-right"></div>
                                                    <div class="text" id="total_avances">0%</div>
                                                </div>
                                            </h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-sm-6 ">
                                        <div class="card card-info" style="min-height: 665px;" id="tablaUbicacion">
                                            <div class="card-header">
                                                <h3 class="card-title">Detalle de Ubicaciones</h3>
                                            </div>
                                            <div class="card-body">
                                                <div class=" table table-responsive">

                                                    <table class="table mapa table-bordered table-striped"
                                                        id="dtmapa" style="width:100%!important">
                                                        <tfoot>
                                                            <tr>
                                                                <th style="text-align:right">Totales:</th>
                                                                <th></th>
                                                                <th></th>
                                                            </tr>
                                                        </tfoot>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 hidden">
                                        <div class="card card-info">
                                            <div class="card-header">
                                                <h3 class="card-title">Mapa de los Compromisos</h3>
                                            </div>
                                            <div class="card-body">
                                                <div class=" table table-responsive">

                                                    <div id="heatmap"
                                                        style="width: 100%; height: 600px; position: relative;">
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="card card-info">
                                            <div class="card-header">
                                                <h3 class="card-title">Mapa de los Compromisos</h3>
                                                <button class="btn btn-default btn-sm" style="float:right"
                                                    v-show="cargando" disabled>Cargando..</button>
                                                <button class="btn btn-default btn-sm" style="float:right"
                                                    v-show="botonRetornoHabilitado!='provincias'&&!cargando"
                                                    v-on:click="cargarHtmlLugares('provincias')">Regresar</button>

                                            </div>
                                            <div class="card-body">
                                                <div class=" table table-responsive" style="background:#67bdee;">
                                                    @include('modules.compromisos.controlPanel.mapas.index')
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="col-sm-12">
                            <div class="card card-info" id="tablaUbicacionCompromisos">
                                <div class="card-header">
                                    <h3 class="card-title">Detalle de Ubicaciones de Compromisos</h3>
                                </div>
                                <div class="card-body">
                                    <div class=" table ">

                                        <table class="table table-sm table-striped table-hover compact hover stripe"
                                            id="dtUbicacionCompromisos" style="width:100%!important">
                                            <thead>

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
        </div>
        <!--Grafico Todos los compromisos-->
        <div class="card-body" v-show="graficos_estados" id="contentGraficoEstados">
            <button id="BotonDatatable" onclick="datatableCompromisosCumplidosRep()" class="hidden"></button>
            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="card card-info">
                                <div class="card-header">
                                    <h3 class="card-title">Estados de los compromisos</h3>
                                </div>
                                <div class="card-body  table table-responsive">
                                    <div class="" id="grafico_estado"
                                        style="min-width: 500px;height: 400px; max-width: 500px; margin: 0 auto">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="card card-info">
                                <div class="card-header">
                                    <h3 class="card-title">Ambito del Compromiso</h3>
                                </div>
                                <div class="card-body">
                                    <div class=" table table-responsive" id="tablaAmbito">
                                        <div id="containerAmbito"
                                            style="width: 100%; height: 400px; position: relative;">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div id="content-pdf" style="background-color: #f6f4f4">
                <div id="graficos-pdf" class="row hidden">
                    <div class="col-6 col-xs-4">
                        <img id="grafico3" class="img-fluid img-thumbnail" />
                    </div>
                    <div class="col-6 col-xs-4">
                        <div class="card card-info">
                            <div class="card-body">
                                <img id="grafico1" class="img-fluid img-thumbnail" />
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-xs-4">
                        <img id="grafico2" class="img-fluid img-thumbnail" />
                    </div>
                </div>
            </div>
            <canvas id="canvas" class="hidden"></canvas>
            <canvas id="canvas2" class="hidden"></canvas>
            <canvas id="canvas_av" class="hidden"></canvas>
        </div>
    </div>
    <!--
            espacio de imagen...
        -->
    <div class="col-md-12 hidden" id="screens"></div>
    @include('modules.compromisos.controlPanel.modal_ejecutivo')
    @include('modules.compromisos.controlPanel.modal_ministerio')
    @include('modules.compromisos.controlPanel.modal_gabinete')
    @include('modules.compromisos.controlPanel.modal_resumen_gabinete')
    @include('modules.compromisos.controlPanel.modal_cumplidos')
    @include('modules.compromisos.controlPanel.modal_ubicacion')
    @include('modules.compromisos.controlPanel.modal_detalle_compromiso')
</div>
</div>
<script src="{{ url('js/vue.js') }}"></script>
<script src="{{ url('js/axios.js') }}"></script>
<script src="{{ url('js/modules/compromisos/reporte/vue_script.js?v=14') }}"></script>

@endsection
