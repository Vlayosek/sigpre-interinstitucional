<html>

<head>
    <style>
        /* ESTRUCTURA DE PAGINA */

        @page {
            margin: 0cm 0cm;
        }

        /** Define now the real margins of every pdf page in the PDF **/
        body {
            margin-top: 3cm;
            margin-left: 2cm;
            margin-right: 2cm;
            margin-bottom: 2cm;
        }

        /** Define the header rules **/
        header {
            padding: 40px;
            position: fixed;
            top: 0cm;
            left: 0cm;
            right: 0cm;
            height: 3cm;
        }

        /** Define the footer rules **/
        footer {
            position: fixed;
            bottom: 0cm;
            left: 0cm;
            right: 0cm;
            height: 2cm;
        }

        table {
            width: 100%;
            page-break-inside: avoid;
            text-align: center;

        }

        th {
            padding: 5px;
            font-size: 9px;
            text-align: center;
            text-align: center;

        }

        td {
            padding: 5px;
            font-size: 9px;
            word-break: break-word;
            text-align: center;

        }

        span {
            font-family: "Calibri, sans-serif";
            font-size: 9px;
        }

        p {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 9px;

        }

        .td_actividad {
            padding: 0px;
            font-size: 10px;
            border: 1px solid #000;
            text-align: center;
        }

        .td_cabecera {
            background: #17a2b8;
            color: #fff;
            /*  border-top-left-radius: 10px;
            border-top-right-radius: 10px;*/

        }
    </style>

    <style type="text/css">
        /* CREAR TABLA CON DIV */

        .Table {
            display: table;
            width: 500px;
            padding-right: 20px;
        }

        .Title {
            display: table-caption;
            text-align: center;
            font-weight: bold;
            font-size: larger;
        }

        .Heading {
            display: table-row;
            font-weight: bold;
            text-align: center;
        }

        .Row {
            display: table-row;
        }

        .Cell {
            display: table-cell;
            border: solid;
            border-width: thin;
            padding-left: 2px;
            padding-right: 2px;

        }

        .Heading_merge {
            border: 1px solid #000;
            border-top-width: 1px;
            border-right-width: 1px;
            border-bottom-width: 1px;
            border-left-width: 1px;
            display: table-caption;
            background: #17a2b8;
            color: #fff;
            width: 104%;
        }
    </style>
    <style>
        /*GRAFICO DE BARRAS*/
        #principal {
            width: 100%;
            margin-left: 0px;
            font-family: Verdana, Helvetica, sans-serif;
            font-size: 10px;
        }

        #barra {
            margin: 0 2px;
            vertical-align: bottom;
            display: inline-block;
        }

        .cor0,
        .cor1,
        .cor2,
        .cor3,
        .cor4,
        .cor5 {
            color: #FFF;
            padding: 15px;
        }

        .cor0 {
            background-color: rgb(95, 182, 242);
        }

        .cor1 {
            background-color: rgb(95, 182, 242);
        }

        .cor2 {
            background-color: rgb(95, 182, 242);
        }

        .cor3 {
            background-color: rgb(95, 182, 242);
        }

        .cor4 {
            background-color: rgb(95, 182, 242);
        }

        .cor5 {
            background-color: rgb(95, 182, 242);
        }
    </style>

</head>

<body>
    <!-- Define header and footer blocks before your subject matter content -->
    <header>
        <img style="width:320px;height:50px" src="data:image/png;base64,{{ $img_encabezado }}" />
    </header>
    <footer>
        <img style="width:100%;height:70px" src="data:image/png;base64,{{ $img_pie }}" />
    </footer>
    <!-- Wrap the subject matter content of your PDF inside a main tag -->
    <main>
        <div class="Heading Heading_merge" style="width:100%">
            <div>
                <h4>Detalle de Ubicaciones de los Compromisos Presidenciales</h4>
            </div>
        </div>
        <br />
        <table class="border" width="104%" cellspacing="0" cellpadding="10" border="0">
            <tbody>
                <tr>
                    <td class="td_actividad td_cabecera" width="20%">
                        <h3>Total /Casos</h3>
                    </td>
                    <td></td>
                    <td class="td_actividad td_cabecera"width="20%">
                        <h3>Nacional</h3>

                    </td>
                    <td></td>
                    <td class="td_actividad td_cabecera"width="20%">
                        <h3>Provincial</h3>

                    </td>
                    <td></td>
                    <td class="td_actividad td_cabecera"width="20%">
                        <h3>Cantonal</h3>

                    </td>



                </tr>
                <tr>
                    <td class="td_actividad">
                        <h3>{!! $array_contadores['total'] !!}</h3>
                    </td>
                    <td></td>
                    <td class="td_actividad">
                        <h3>{!! $array_contadores['nacional'] !!}</h3>

                    </td>
                    <td></td>
                    <td class="td_actividad">
                        <h3>{!! $array_contadores['provincia'] !!}</h3>

                    </td>
                    <td></td>
                    <td class="td_actividad">
                        <h3>{!! $array_contadores['canton'] !!}</h3>

                    </td>


                </tr>
            </tbody>
        </table>
        <br />

        <table class="border" width="50%" cellspacing="0" cellpadding="10" border="0">
            <tbody>
                <tr>
                    <td class="td_actividad td_cabecera" width="52%">
                        <h3>Mapa de los compromisos</h3>
                    </td>
                </tr>
                <tr>
                    <td class="td_actividad">
                        {!! $grafico_mapa !!}

                    </td>
                </tr>
            </tbody>
        </table>
        <div style="page-break-before:always;">

            <table class="border" width="50%" cellspacing="0" cellpadding="10" border="0">
                <tbody>
                    <tr>
                        <td class="td_actividad td_cabecera" width="52%">
                            <h3>Detalle de las Ubicaciones</h3>
                        </td>

                    </tr>
                    <tr>
                        <td class="td_actividad"
                            style="border-right:0px;border-left:0px;border-bottom:0px;border-top:0px;">
                            <table class="border" width="100%" cellspacing="0" cellpadding="0" border="0">
                                <tr>
                                    <td class="td_actividad " width="33.33%">
                                        <h3>Ubicación</h3>
                                    </td>
                                    <td class="td_actividad " width="33.33%">
                                        <h3>Cantonal</h3>
                                    </td>
                                    <td class="td_actividad " width="33.33%">
                                        <h3>Provincial</h3>
                                    </td>
                                </tr>
                                @foreach ($array['datatable'] as $value)
                                    <tr>
                                        <td class="td_actividad">
                                            {{ $value->ubicacion }}</td>
                                        <td class="td_actividad"style="text-align: center">
                                            {{ $value->contador_cantones }}
                                        </td>
                                        <td class="td_actividad">
                                            {{ $value->contador }}</td>
                                    </tr>
                                @endforeach

                            </table>
                        </td>

                    </tr>
                </tbody>
            </table>
        </div>

        <div style="page-break-before:always;" >
            <br />
            <div class="Heading Heading_merge">
                <div>
                    <h4>Detalle de Compromiso</h4>
                </div>
            </div>
            <br />
            {!! $html_compromisos !!}
        </div>
        <div style="page-break-before:always;">
            <table class="border" width="104%" cellspacing="0" cellpadding="10" border="0">
                <tbody>
                    <tr>
                        <td class="td_actividad td_cabecera" width="40%">
                            <h3>Estado del Compromiso</h3>
                        </td>

                        <td class="td_actividad td_cabecera" width="40%">
                            <h3>Ambito de los compromisos</h3>
                        </td>
                    </tr>
                    <tr>
                        <td class="td_actividad" style="height:70%">
                            {!! $grafico_estados !!}

                        </td>
                        <td class="td_actividad">
                            {!! $grafico_ambito !!}

                        </td>
                    </tr>
                </tbody>
            </table>




        </div>


    </main>
</body>

</html>
