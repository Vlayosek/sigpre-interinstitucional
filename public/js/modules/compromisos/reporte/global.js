const language = {
    search: "Buscar",
    lengthMenu: "Mostrar _MENU_",
    zeroRecords: "Lo sentimos, no encontramos lo que estas buscando",
    info: "Motrar página _PAGE_ de _PAGES_ (_TOTAL_)",
    infoEmpty: "Registros no encontrados",
    oPaginate: {
        sFirst: "Primero",
        sLast: "Último",
        sNext: "Siguiente",
        sPrevious: "Anterior",
    },
    infoFiltered: "(Filtrado _TOTAL_  de _MAX_ registros totales)",
};
const dom = "lBfrtip";
const destroy = true;
const responsive = true;
const processing = true;
let arregloUbcicacionesMapas = [];
let todas_ubicacciones = "imagen_mapa_svg";

function generaDataTableMapa(data) {
    // init datatable...
    $(".mapa").dataTable({
        destroy: true,
        lengthChange: true,
        searching: true,
        ordering: true,
        info: true,
        autoWidth: true,
        language,
        data,
        lengthMenu: [
            [-1],
            ["TODOS"]
        ],
        footerCallback: function(row, data, start, end, display) {
            var api = this.api(),
                data;

            // Remove the formatting to get integer data for summation
            var intVal = function(i) {
                return typeof i === "string" ?
                    i.replace(/[\$,]/g, "") * 1 :
                    typeof i === "number" ?
                    i :
                    0;
            };

            // Total over all pages
            var total = api
                .column(1)
                .data()
                .reduce(function(a, b) {
                    return intVal(a) + intVal(b);
                }, 0);

            // Total over this page
            var pageTotal = api
                .column(1, { page: "current" })
                .data()
                .reduce(function(a, b) {
                    return intVal(a) + intVal(b);
                }, 0);

            // Update footer
            var colCount = api.rows().count();
            $(api.column(1).footer()).html(pageTotal);
            var pageTotal_2 = api
                .column(2, { page: "current" })
                .data()
                .reduce(function(a, b) {
                    return intVal(a) + intVal(b);
                }, 0);

            // Update footer
            $(api.column(2).footer()).html(pageTotal_2);

            var pageTotal_3 = api
                .column(3, { page: "current" })
                .data()
                .reduce(function(a, b) {
                    return intVal(a) + intVal(b);
                }, 0);

            // Update footer
            $(api.column(3).footer()).html(pageTotal_3);
            $("#total_provincias").html(pageTotal_2 - $("#total_nacional").html());
            $("#total_cantones").html(pageTotal);
            $("#total_compromisos").html(parseInt($("#total_nacional").html()) + parseInt($("#total_provincias").html()) + parseInt($("#total_cantones").html()));

            var avance = pageTotal_3 / (pageTotal_2 + pageTotal);
            console.log(pageTotal_3);

            $("#total_avances").html(avance);
            calculate(isNaN(avance) ? 0 : avance);
        },

        columns: [{
                title: "Ubicación",
                data: "ubicacion",
                "render": function(data, type, row) {

                    var html = '--';
                    if (row.ubicacion != null) {
                        html = '<a href="#" onclick="generarDatatableCompromisosDetalle(\'' + row.ubicacion + '\')"';
                        html += ' data-toggle="modal" data-target="#modal-COMPROMISOS_DETALLE" data-backdrop="static"';
                        html += ' data-keyboard="false">' + row.ubicacion + '</a>';
                    }
                    return html;
                }
            },
            {
                title: "Cantonal",
                data: "contador_cantones",
            },
            {
                title: "Provincial",
                data: "contador",
            },
            {
                title: "Avances",
                data: "avances",
                className: "hidden",
            },
        ],
    });
    let tamanio_mapa = $(".mapa").height();
    let style = "width: 100%;min-height: 580px;";
    style = style + "height:" + (tamanio_mapa + 70) + "px;"
    $("#mapa_svg").attr('style', style);
    //  alert("tamano:"+);
}
async function generarImagenCanvas() {
    /*  const { Canvg, Document, Parser, presets } = canvg;

      let v = null,
          v2 = null,
          v3 = null;

      const canvas = document.getElementById("canvas");
      const canvas2 = document.getElementById("canvas2");

      const ctx = canvas.getContext("2d");
      const ctx2 = canvas2.getContext("2d");

      v = Canvg.fromString(ctx, app.grafico1.getSVG());
      // Start drawing the SVG on the canvas
      v.start();
      // Convert the Canvas to an image
      var img = canvas.toDataURL("img/png");
      // Write the image on the screen
      $("#grafico1").attr("src", img);

      v2 = Canvg.fromString(ctx2, app.grafico2.getSVG());
      // Start drawing the SVG on the canvas
      v2.start();
      // Convert the Canvas to an image
      var img2 = canvas2.toDataURL("img/png");
      // Write the image on the screen
      $("#grafico2").attr("src", img2);

      const imagenMapa = await simpleMapScreenshoter.takeScreen("image", {});
      //console.log(imagenMapa);
      $("#grafico3").attr("src", imagenMapa);*/
}

function generarDatatableCompromisosDetalle(ubicacion) {
    if (ubicacion.indexOf(" - ") != -1) {
        ubicacion = ubicacion.split(" - ");
        ubicacion = ubicacion[1];
    }
    let data = app.arregloDatosCompromisosBasico;
    data = filtrarDatosCompromisos(ubicacion);

    // init datatable...
    $("#dtUbicacionCompromisosDetalle").dataTable({
        dom: 'lBfrtip',
        buttons: [{
                extend: 'excelHtml5',
                text: '<img src="/images/icons/excel.png" width="25px" heigh="25px">Exportar Excel',
                titleAttr: 'Excel'
            },

        ],
        destroy: true,
        lengthChange: true,
        searching: true,
        ordering: true,
        info: true,
        autoWidth: true,
        language,
        data,
        responsive: true,
        lengthMenu: [
            [3, 10, 20],
            [3, 10, 20],
        ],
        "order": [
            [1, "desc"]
        ],

        columns: arregloDatosCompromisosBasico,
    });
}

function cargarUbicacionesMapaSVG(provincias = null) {

    arregloUbcicacionesMapas = [];
    let nacional = false;
    $.grep(provincias, function(element, index) {
        if (element.total_compromisos > 0) {
            //    $("." + element.clase + "").attr("style", "display:block!important");
            arregloUbcicacionesMapas.push(element.clase)
            if ("imagen_mapa_svg" == element.clase) nacional = true;
        }
    });
    if (nacional) {
        arregloUbcicacionesMapas = [];
        arregloUbcicacionesMapas.push(todas_ubicacciones)
    }
    habilitarUbicacionesMapa();
}

function generarDatatableCompromisos(data) {
    // init datatable...
    $("#dtUbicacionCompromisos").dataTable({

        dom: 'lBfrtip',
        buttons: [{
                extend: 'excelHtml5',
                text: '<img src="/images/icons/excel.png" width="25px" heigh="25px">Exportar Excel',
                titleAttr: 'Excel'
            },

        ],
        destroy: true,
        lengthChange: true,
        searching: true,
        ordering: true,
        info: true,
        autoWidth: true,
        language,
        data,
        responsive: true,
        lengthMenu: [
            [3, 10, -1],
            [3, 10, "TODOS"]
        ],
        "order": [
            [1, "desc"]
        ],

        columns: arregloDatosCompromisosBasico,
    });
}
let arregloDataUbicacionesCantonales = [];

function filtrarDatosCompromisos(ubicacion) {
    let data = app.arregloDatosCompromisosBasico;
    arregloDataUbicacionesCantonales = [];
    data = $.grep(data, function(element, index) {
        if (filtro_ubicacion_seleccionado) {
            if (element.cantones != null) {
                ubicacion = ubicacion.indexOf('_') != -1 ? ubicacion.replaceAll('_', ' ') : ubicacion;
                var match = element.cantones.includes(ubicacion);
                if (match) {
                    arregloDataUbicacionesCantonales.push(element.union_cantones_clase)
                    return true;
                }
            }
        } else {
            if (element.provincias != null) {
                ubicacion = ubicacion.indexOf('_') != -1 ? ubicacion.replaceAll('_', ' ') : ubicacion;
                var match = element.provincias.includes(ubicacion);
                if (match) {
                    arregloDataUbicacionesCantonales.push(element.union_cantones_clase)
                    return true;
                }
            }
        }
    });
    return data;
}

function cargarMapaCantonal(data) {
    //  let id="#capa_"+data+"";
    //let html=$(id).html();
    //$("#mapa_svg").html(html);
    app.cargarHtmlLugares(data);
}

function habilitarUbicacionesMapa() {

    $("." + todas_ubicacciones + "").attr("style", "display:none!important");
    let data = arregloUbcicacionesMapas;
    if (app.botonRetornoHabilitado != 'provincias') {
        filtrarDatosCompromisos(app.botonRetornoHabilitado.toUpperCase());
        data = arregloDataUbicacionesCantonales;
    }
    $.each(data, function(_key, _value) {
        if (_value != "")
            $("." + _value + "").attr("style", "display:block!important");
    });


    /* if(app.botonRetornoHabilitado=='provincias'){
       $("." + todas_ubicacciones + "").attr("style", "display:none!important");
       $.each(arregloUbcicacionesMapas, function (_key, _value) {
         $("." + _value+ "").attr("style", "display:block!important");
       });
     }else{
        filtrarDatosCompromisos(app.botonRetornoHabilitado.toUpperCase());
        arregloDataUbicacionesCantonales
        console.log(arregloDataUbicacionesCantonales);

     }*/

}

function generarDatatableVisualizacion(tipo) {
    console.log("AQUI");
    if (tipo != null)
        tipoActual = tipo;
    let fecha_inicio = $("#fecha_inicio_ejecutivo").val() == "" || $("#fecha_inicio_ejecutivo").val() == null ? "null" : $("#fecha_inicio_ejecutivo").val();
    let fecha_fin = $("#fecha_fin_ejecutivo").val() == "" || $("#fecha_fin_ejecutivo").val() == null ? "null" : $("#fecha_fin_ejecutivo").val();
    let institucion_filtro = $("#filtro_institucion_ejecutivo").val() == "" || $("#filtro_institucion_ejecutivo").val() == null ? "--" : $("#filtro_institucion_ejecutivo").val();
    $("#dtReportesVisualizacion").dataTable({
        ajax: "/compromisos/getDatatableReportesVisualizacionServerSide / " + institucion_filtro + " / " +
            fecha_inicio + "/" +
            fecha_fin + "",
        dom: 'lBfrtip',
        buttons: [{
                extend: 'excelHtml5',
                text: '<img src="/images/icons/excel.png" width="25px" heigh="25px">Exportar Excel',
                titleAttr: 'Excel'
            },

        ],
        destroy: true,
        lengthChange: true,
        searching: true,
        ordering: true,
        info: true,
        autoWidth: true,
        language,
        //data,
        responsive: true,
        lengthMenu: [
            [3, 10, -1],
            [3, 10, "TODOS"]
        ],
        "order": [
            [1, "desc"]
        ],

        columns: arregloReportesVisualizacion,
    });
}