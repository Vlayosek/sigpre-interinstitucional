$(function() {
    app.consultaEstadoTab();
});

function datatableCargar(tipo = 'data', tabla = null) {
    app.linkNav = 0;

    if (app.filtro) app.resetearBotones();

    if (tipo != 'data')
        tipoActual = tipo;
    app.tipoActual = tipoActual;
    if (tabla != null) app.tabla = tabla;

    var fecha_inicio = $("#fecha_inicio_exportar").val() == "" || $("#fecha_inicio_exportar").val() == null ? "null" : $("#fecha_inicio_exportar").val();
    var fecha_fin = $("#fecha_fin_exportar").val() == "" || $("#fecha_fin_exportar").val() == null ? "null" : $("#fecha_fin_exportar").val();
    var institucion_id_exportar = $("#institucion_id_exportar_monitor").val() == "" || $("#institucion_id_exportar_monitor").val() == null ? "null" : $("#institucion_id_exportar_monitor").val();
    var gabinete_id_exportar = $("#gabinete_id_exportar_monitor").val() == "" || $("#gabinete_id_exportar_monitor").val() == null ? "null" : $("#gabinete_id_exportar_monitor").val();
    if (fecha_inicio == "null" || fecha_fin == "null") {
        alertToast("Debe colocar un rango de fecha", 3500);
        return false;
    }
    var fecha1 = moment(fecha_inicio);
    var fecha2 = moment(fecha_fin);
    var fecha3 = fecha2.diff(fecha1, 'days');
    if (fecha3 < 0 & app.filtro) {
        alertToast("Las fechas fin no puede ser menor a la fecha de inicio", 3500);
        return false;
    }
    let corresponsable = location.href.includes('corresponsable') ? "true" : "false";

    if (app.tipoActual == 'data') this.currentTab = 0;
    let fill = {
        'estado': app.tipoActual,
        'tabla': app.tabla,
        'asignaciones': app.asignaciones,
        'pendientes': app.btnPendientes,
        'temporales': app.btnTemporal,
        'filtro': app.filtro,
        'fecha_inicio': fecha_inicio,
        'fecha_fin': fecha_fin,
        'institucion_id_exportar': institucion_id_exportar,
        'gabinete_id_exportar': gabinete_id_exportar,
        'corresponsable': corresponsable,

    }
    if (app.rolMinistro == 0 || app.rolMinistro == '0') fill = app.llenarDatosEnviar(true);
    app.getKeeps();
    let dataConsulta = arregloDatosCompromisos;
    crearDatatable(dataConsulta, "dtCompromisos", fill);

    //app.getDatatableCompromisosGETServerSide();
}

function crearDatatable(
    dataConsulta,
    id,
    parametros,
    ruta = '/compromisos/getDatatableCompromisosPOSTServerSide') {
    $('#' + id + "").DataTable({
        dom: 'lfrtip',
        buttons: [{
            extend: "excelHtml5",
            text: '<img src="/images/icons/excel.png" width="25px" heigh="20px">',
            titleAttr: "Excel",
        }, ],
        processing: true,
        serverSide: true,
        serversSide: true,
        destroy: true,
        responsive: true,
        processing: true,
        order: [],
        ajax: {
            url: ruta,
            type: 'POST',
            data: parametros,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
        },
        language: {
            "ajax": {
                "error": "Lo sentimos, ha ocurrido un comuniquese con la DTIC"
            },
            processing: "<i class='fa fa-spinner fa-spin fa-stack-2x fa-fw'></i>\n\
           ",
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
        },
        columns: dataConsulta
    });
}