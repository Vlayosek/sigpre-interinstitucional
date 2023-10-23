<html>

<head>
    <style>
        /**
                Set the margins of the pdf page to 0, so the footer and the header
                can be of the full height and width !
             **/
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
        }

        th {
            padding: 5px;
            font-size: 9px;
            text-align: center;
        }

        td {
            padding: 5px;
            font-size: 9px;
            word-break: break-word;
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

        .div-table {
            display: table;
            width: auto;
            background-color: #eee;
            border: 1px solid #666666;
            border-spacing: 5px;
            /* cellspacing:poor IE support for  this */
        }

        .div-table-row {
            display: table-row;
            width: auto;
            clear: both;
        }

        .div-table-col {
            float: left;
            /* fix for  buggy browsers */
            display: table-column;
            width: 200px;
            background-color: #ccc;
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
        <table class="border" width="100%" cellspacing="0" cellpadding="10" border="0">
            <tbody>
                <tr>
                    <td class="td_actividad td_cabecera">
                        <h3>Total /Casos</h3>
                    </td>
                    <td></td>
                    <td class="td_actividad td_cabecera">
                        <h3>Nacional</h3>

                    </td>
                    <td></td>
                    <td class="td_actividad td_cabecera">
                        <h3>Provincial</h3>

                    </td>
                    <td></td>
                    <td class="td_actividad td_cabecera">
                        <h3>Cantonal</h3>

                    </td>
                    <td></td>


                </tr>
                <tr>
                    <td class="td_actividad">
                        <h3>39</h3>
                    </td>
                    <td></td>
                    <td class="td_actividad">
                        <h3>24</h3>

                    </td>
                    <td></td>
                    <td class="td_actividad">
                        <h3>23</h3>

                    </td>
                    <td></td>
                    <td class="td_actividad">
                        <h3>5</h3>

                    </td>
                    <td></td>


                </tr>
            </tbody>
        </table>
        <br />
        <table class="border" width="100%" cellspacing="0" cellpadding="10" border="0">
            <tbody>
                <tr>
                    <td class="td_actividad td_cabecera" width="50%">
                        <h3>Detalle de las Ubicaciones</h3>
                    </td>
                    <td>
                    </td>
                    <td class="td_actividad td_cabecera" width="50%">
                        <h3>Mapa de los compromisos</h3>
                    </td>
                </tr>
                <tr>
                    <td class="td_actividad">
                        <table class="border" width="100%" cellspacing="0" cellpadding="0" border="0">
                            <tr>
                                <td class="td_actividad " style="border-top:0px" width="33.33%">
                                    <h3>Ubicación</h3>
                                </td>
                                <td class="td_actividad " style="border-top:0px" width="33.33%">
                                    <h3>Cantonal</h3>
                                </td>
                                <td class="td_actividad " style="border-top:0px" width="33.33%">
                                    <h3>Provincial</h3>
                                </td>
                            </tr>
                            @foreach ($array['datatable'] as $value)
                            <tr>
                                <td class="td_actividad" style="text-align: justify">{{$value->ubicacion}}</td>
                                <td class="td_actividad" style="text-align: center">{{$value->contador_cantones}}</td>
                                <td class="td_actividad" style="text-align: center">{{$value->contador}}</td>
                            </tr>
                            @endforeach

                        </table>
                    </td>
                    <td>
                    </td>
                    <td class="td_actividad">
                        MAPA
                    </td>
                </tr>
            </tbody>
        </table>
        <br />
        {!!$html_compromisos!!}

        <table class="border" width="100%" cellspacing="0" cellpadding="10" border="0">
            <tbody>
                <tr>
                    <td class="td_actividad td_cabecera">
                        <h3>Estado del Compromiso</h3>
                    </td>
                    <td>
                    </td>
                    <td class="td_actividad td_cabecera">
                        <h3>Ámbito compromisos</h3>
                    </td>
                </tr>
                <tr>
                    <td class="td_actividad">
                        BARRAS
                    </td>
                    <td>
                    </td>
                    <td class="td_actividad">
                        PIE
                    </td>
                </tr>
            </tbody>
        </table>
    </main>
</body>

</html>
