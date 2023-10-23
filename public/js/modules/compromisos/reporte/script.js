import { language } from "../../dt.utils.js";

$(function() {
    // inicia al cargar la pagina el datata bles de reporte de mapa datos para mapa de calor...
    generaDataTableMapa([]);
    //console.log(language);
});

//var app.institucion_anterior = null;

var arregloCompromisosMapa = [];

window.generarReporteMapa = function() {
    arregloCompromisosMapa = app.filtro_compromiso_individual;
    // cerranod el modal...
    $('.cerrarmodal').trigger('click');
    app.consultaDatosUbicacion();
    // retorna datos...
    // generaDatosDT();
}


function retornaUrl(tipo_detalle = false) {
    let filtro_compromiso = 0,
        filtro_ubicacion = 0,
        filtro_gestion = 0;

    if ($('#filtro_compromiso_individual').val()) filtro_compromiso = $('#filtro_compromiso_individual').val();
    if ($('#filtro_ubicacion').val()) filtro_ubicacion = $('#filtro_ubicacion').val();
    let filtro_gabinete = $("#filtro_gabinete_ind").val() == "" || $("#filtro_gabinete_ind").val() == null ? 0 : $("#filtro_gabinete_ind").val();
    let filtro_institucion = $("#filtro_institucion_ind").val() == "" || $("#filtro_institucion_ind").val() == null ? 0 : $("#filtro_institucion_ind").val();

    //institucion_filtro
    if ($('#filtro_gestion').val()) filtro_gestion = $('#filtro_gestion').val();
    //  var arregloCompromisos=$('#filtro_compromiso_individual').val().join(',');

    // retorna url...

    filtro_compromiso = arregloCompromisosMapa;

    if (arregloCompromisosMapa.length == 0) filtro_compromiso = 0

    return `/compromisos/getDatatableReporteCompromisoIndividualServerSide/${filtro_gestion}/${filtro_compromiso}/${filtro_ubicacion}/${filtro_gabinete}/${filtro_institucion}/${tipo_detalle}`;

}

async function generarImagenCanvas() {
    /*   const {
           Canvg,
           Document,
           Parser,
           presets
       } = canvg;

       let v = null,
           v2 = null,
           v3 = null;

       const canvas = document.getElementById('canvas');
       const canvas2 = document.getElementById('canvas2');

       const ctx = canvas.getContext('2d');
       const ctx2 = canvas2.getContext('2d');

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

       const imagenMapa = await simpleMapScreenshoter.takeScreen('image', {});
       //console.log(imagenMapa);
       $("#grafico3").attr("src", imagenMapa);*/
}

function html_xml(html) {
    var doc = document.implementation.createHTMLDocument('');
    doc.write(html);

    // You must manually set the xmlns if you intend to immediately serialize
    // the HTML document to a string as opposed to appending it to a
    // <foreignObject> in the DOM
    doc.documentElement.setAttribute('xmlns', doc.documentElement.namespaceURI);

    // Get well-formed markup
    html = (new XMLSerializer).serializeToString(doc.body);
    return html;
}

function generaDatosMapa() {
    // app.consultaDatosUbicacion();
    // regresa url...
    const url = retornaUrl(true);
    // retorna datos...
    $.ajax({
        url,
        type: 'GET',
        dataType: 'json',
        success: function(results) {
            // carga el datatables de reporte...
            agregaDatosMapa(results);
        },
        complete: async function() {
            // generamos imagen..
            await generarImagenCanvas();
        }
    });
}

const generaDatosDT = function() {
    // regresa url...
    const url = retornaUrl();

    // retorna datos...
    $.ajax({
        url,
        type: 'GET',
        dataType: 'json',
        success: function(results) {
            // desestructura objeto...
            const { data } = results;
            console.log('- > ', data);
            // carga el datatables de reporte...
            generaDataTableMapa(data);
        },
        complete: function() {
            // llama a la funcion de mapa...
            generaDatosMapa();
        }
    });

}



function generaDataTableMapa(data) {
    // init datatable...
    $(".mapa").dataTable({
        destroy: true,
        "lengthChange": true,
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": true,
        language,
        data,
        "lengthMenu": [
            [-1],
            ["TODOS"]
        ],
        "footerCallback": function(row, data, start, end, display) {
            var api = this.api(),
                data;

            // Remove the formatting to get integer data for summation
            var intVal = function(i) {
                return typeof i === 'string' ?
                    i.replace(/[\$,]/g, '') * 1 :
                    typeof i === 'number' ?
                    i : 0;
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
                .column(1, { page: 'current' })
                .data()
                .reduce(function(a, b) {
                    return intVal(a) + intVal(b);
                }, 0);

            // Update footer
            var colCount = api.rows().count();
            $(api.column(1).footer()).html(pageTotal);
            var pageTotal_2 = api
                .column(2, { page: 'current' })
                .data()
                .reduce(function(a, b) {
                    return intVal(a) + intVal(b);
                }, 0);

            // Update footer
            $(api.column(2).footer()).html(
                pageTotal_2
            );

            var pageTotal_3 = api
                .column(3, { page: 'current' })
                .data()
                .reduce(function(a, b) {
                    return intVal(a) + intVal(b);
                }, 0);
            // Update footer
            $(api.column(3).footer()).html(
                pageTotal_3
            );
            $("#total_provincias").html(pageTotal_2 - $("#total_nacional").html());
            $("#total_cantones").html(pageTotal);
            $("#total_compromisos").html(parseInt($("#total_nacional").html()) + parseInt($("#total_provincias").html()) + parseInt($("#total_cantones").html()));

            var avance = pageTotal_3 / (pageTotal_2 + pageTotal);
            console.log(pageTotal_3, );

            $("#total_avances").html(avance);
            calculate(isNaN(avance) ? 0 : avance);
        },

        "columns": [{
                title: 'Ubicación',
                data: 'ubicacion'
            },
            {
                title: 'Cantonal',
                data: 'contador_cantones'
            },
            {
                title: 'Provincial',
                data: 'contador'
            },
            {
                title: 'Avances',
                data: 'avances',
                className: "hidden"
            }
        ]
    });
}

$("#filtro_gabinete").on('change', function() {
    app.gabinete_id = $(this).val() != null ? $(this).val() : 0;
    app.institucion_id = $("#filtro_institucion").val() != null ? $("#filtro_institucion").val() : 0;
    if ((app.gabinete_id != 0 || app.gabinete_id != "") && (app.institucion_id != 0 || app.institucion_id == ""))
        app.filtro_institucion_cc();
});

$("#filtro_institucion").on('change', function() {
    if (app.institucion_anterior != null) {
        app.institucion_anterior = null;
        return false;
    }
    app.institucion_id = $(this).val() != null ? $(this).val() : 0;
    //app.gabinete_id = $("#filtro_gabinete").val() != null ? $("#filtro_gabinete").val() : 0;
    if ((app.institucion_id != 0 || app.institucion_id != "") && (app.gabinete_id == 0 || app.gabinete_id == ""))
        app.filtro_gabinete_cc(app.institucion_id);
});

$("#filtro_periodos").on('change', function() {
    app.formConsultar.id = $(this).val() != null ? $(this).val() : 0;
});

$("#filtro_compromisos").on('change', function() {
    app.formConsultar.id = $(this).val() != null ? $(this).val() : 0;
});

$("#filtro_compromisos_detallado").on('change', function() {
    app.formConsultar.id = $(this).val() != null ? $(this).val() : 0;
});

//MAPA CALOR 1 - por compromisos de manera individual
$("#filtro_gestion").on('change', function() {
    app.gestion_filtro = $(this).val() != null ? $(this).val() : 0;
    if (app.gestion_filtro != 0)
        app.filtro_compromiso_cc();
});
$("#filtro_gabinete_ind").on('change', function() {
    app.gabinete_id = $(this).val() != null ? $(this).val() : 0;
    app.institucion_id = $("#filtro_institucion_ind").val() != null ? $("#filtro_institucion_ind").val() : 0;
    if ((app.gabinete_id != 0 || app.gabinete_id != "") && (app.institucion_id != 0 || app.institucion_id == ""))
        app.filtro_institucion_cc();
});

$("#filtro_institucion_ind").on('change', function() {
    if (app.institucion_anterior != null) {
        app.institucion_anterior = null;
        return false;
    }
    app.institucion_id = $(this).val() != null ? $(this).val() : 0;
    if ((app.institucion_id != 0 || app.institucion_id != "") && (app.gabinete_id == 0 || app.gabinete_id == ""))
        app.filtro_gabinete_cc(app.institucion_id);
});
$("#filtro_institucion_ejecutivo").on('change', function() {
    app.filtroCompromisosporInstitucion();
});