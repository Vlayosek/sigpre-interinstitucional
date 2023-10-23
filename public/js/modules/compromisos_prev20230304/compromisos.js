var editor = "";

$(function () {
    $("body").addClass("sidebar-collapse");
    datatableCargar();
    $("#dtmenuAntecedentes").dataTable({
        dom: "rtip",
    });
    $("#dtmenuArchivos").dataTable({
        dom: "rtip",
    });
    $("#dtmenuHistorico").dataTable({
        dom: "lfrtip",
    });
    $("#dtmenuMensajes").dataTable({
        dom: "lfrtip",
    });
    $("#dtmenuObjetivos").dataTable({
        dom: "lfrtip",
    });
    $("#dtmenuPeriodos").dataTable({
        dom: "lfrtip",
    });
    $("#dtmenuUbicacion").dataTable({
        dom: "lfrtip",
    });
    $("#dtmenuAvances").dataTable({
        dom: "lfrtip",
    });
});

$("#gabinete_id_exportar").on("change", function () {
    app.onChangeInstitucion();
});

function resetearDatatable() {
    app.formCrear.id = 0;
    var dt = {
        draw: 1,
        recordsFiltered: 0,
        recordsTotal: 0,
        data: [],
    };
    datatableCargarUbicaciones();

    $("#dtmenuAntecedentes").dataTable({
        dom: "lfrtip",
        buttons: [
            {
                extend: "excelHtml5",
                text: '<img src="/images/icons/excel.png" width="25px" heigh="20px">',
                titleAttr: "Excel",
            },
        ],
        lengthMenu: [
            [15, 30, -1],
            [15, 30, "TODOS"],
        ],
        lengthChange: true,
        searching: true,
        ordering: true,
        info: true,
        autoWidth: false,
        draw: dt.draw,
        destroy: true,
        recordsFiltered: dt.recordsFiltered,
        recordsTotal: dt.recordsTotal,
        data: dt.data,
        order: [[0, "desc"]],
        language: {
            search: "Buscar",
            lengthMenu: "Mostrar _MENU_",
            zeroRecords: "Lo sentimos, no encontramos lo que estas buscando",
            info: "Motrar página _PAGE_ de _PAGES_",
            infoEmpty: "Registros no encontrados",
            oPaginate: {
                sFirst: "Primero",
                sLast: "Último",
                sNext: "Siguiente",
                sPrevious: "Anterior",
            },
            infoFiltered: "(Filtrado en MAX registros totales)",
        },
        columnDefs: [{ targets: [0], orderable: true }],
        columns: [
            {
                data: "numero",
                width: "5%",
                searchable: true,
            },
            {
                data: "fecha_antecedente",
                width: "5%",
                searchable: true,
            },
            {
                data: "descripcion",
                width: "12%",
                searchable: true,
            },
            {
                data: "",
                width: "15%",
                searchable: true,
            },
        ],
    });
    $("#dtmenuArchivos").dataTable({
        dom: "lfrtip",
        buttons: [
            {
                extend: "excelHtml5",
                text: '<img src="/images/icons/excel.png" width="25px" heigh="20px">',
                titleAttr: "Excel",
            },
        ],
        lengthMenu: [
            [15, 30, -1],
            [15, 30, "TODOS"],
        ],
        lengthChange: true,
        searching: true,
        ordering: true,
        info: true,
        autoWidth: false,
        draw: dt.draw,
        destroy: true,
        recordsFiltered: dt.recordsFiltered,
        recordsTotal: dt.recordsTotal,
        data: dt.data,
        order: [[0, "desc"]],
        language: {
            search: "Buscar",
            lengthMenu: "Mostrar _MENU_",
            zeroRecords: "Lo sentimos, no encontramos lo que estas buscando",
            info: "Motrar página _PAGE_ de _PAGES_",
            infoEmpty: "Registros no encontrados",
            oPaginate: {
                sFirst: "Primero",
                sLast: "Último",
                sNext: "Siguiente",
                sPrevious: "Anterior",
            },
            infoFiltered: "(Filtrado en MAX registros totales)",
        },
        columnDefs: [{ targets: [0], orderable: true }],
        columns: [
            {
                data: "created_at",
                width: "5%",
                searchable: true,
            },

            {
                data: "nombre",
                width: "12%",
                searchable: true,
            },
            {
                data: "nombres",
                width: "12%",
                searchable: true,
            },
            {
                data: "institucion",
                width: "12%",
                searchable: true,
            },

            {
                data: "fecha_revisa",
                width: "12%",
                searchable: true,
                render: function (data, type, row) {
                    var html = "--";
                    if (row.fecha_revisa != null) html = row.fecha_revisa;
                    return html;
                },
            },
            {
                data: "usuario_leido",
                width: "12%",
                searchable: true,
                render: function (data, type, row) {
                    var html = "--";
                    if (row.usuario_leido != null)
                        html = row.usuario_leido;
                    return html;
                },
            },
            {
                data: "institucion_leida",
                width: "12%",
                searchable: true,
            },
            {
                data: "",
                width: "15%",
                searchable: true,
            },
        ],
    });
    $("#dtmenuHistorico").dataTable({
        dom: "lfrtip",
        buttons: [
            {
                extend: "excelHtml5",
                text: '<img src="/images/icons/excel.png" width="25px" heigh="20px">',
                titleAttr: "Excel",
            },
        ],
        lengthMenu: [
            [15, 30, -1],
            [15, 30, "TODOS"],
        ],
        lengthChange: true,
        searching: true,
        ordering: true,
        info: true,
        autoWidth: false,
        draw: dt.draw,
        destroy: true,
        recordsFiltered: dt.recordsFiltered,
        recordsTotal: dt.recordsTotal,
        data: dt.data,
        order: [[0, "desc"]],
        language: {
            search: "Buscar",
            lengthMenu: "Mostrar _MENU_",
            zeroRecords: "Lo sentimos, no encontramos lo que estas buscando",
            info: "Motrar página _PAGE_ de _PAGES_",
            infoEmpty: "Registros no encontrados",
            oPaginate: {
                sFirst: "Primero",
                sLast: "Último",
                sNext: "Siguiente",
                sPrevious: "Anterior",
            },
            infoFiltered: "(Filtrado en MAX registros totales)",
        },
        columnDefs: [{ targets: [0], orderable: true }],
        columns: [
            {
                data: "fecha",
                name: "sc_compromisos.transacciones.created_at",
                width: "5%",
                searchable: true,
            },
            {
                data: "descripcion",
                name: "sc_compromisos.transacciones.descripcion",

                width: "12%",
                searchable: true,
            },
            {
                data: "usuario",
                name: "core.users.nombres",

                width: "12%",
                searchable: true,
            },
            {
                data: "institucion",
                name: "core.instituciones.descripcion",

                width: "12%",
                searchable: true,
            },
        ],
    });
    $("#dtmenuMensajes").dataTable({
        dom: "lfrtip",
        buttons: [
            {
                extend: "excelHtml5",
                text: '<img src="/images/icons/excel.png" width="25px" heigh="20px">',
                titleAttr: "Excel",
            },
        ],
        lengthMenu: [
            [15, 30, -1],
            [15, 30, "TODOS"],
        ],
        lengthChange: true,
        searching: true,
        ordering: true,
        info: true,
        autoWidth: false,
        draw: dt.draw,
        destroy: true,
        recordsFiltered: dt.recordsFiltered,
        recordsTotal: dt.recordsTotal,
        data: dt.data,
        order: [[0, "desc"]],
        language: {
            search: "Buscar",
            lengthMenu: "Mostrar _MENU_",
            zeroRecords: "Lo sentimos, no encontramos lo que estas buscando",
            info: "Motrar página _PAGE_ de _PAGES_",
            infoEmpty: "Registros no encontrados",
            oPaginate: {
                sFirst: "Primero",
                sLast: "Último",
                sNext: "Siguiente",
                sPrevious: "Anterior",
            },
            infoFiltered: "(Filtrado en MAX registros totales)",
        },
        columnDefs: [{ targets: [0], orderable: true }],
        columns: [
            {
                data: "created_at",
                width: "5%",
                searchable: true,
            },
            {
                data: "descripcion",
                width: "12%",
                searchable: true,
            },
            {
                data: "nombres",
                width: "12%",
                searchable: true,
            },
            {
                data: "institucion",
                width: "12%",
                searchable: true,
            },

            {
                data: "fecha_revisa",
                width: "12%",
                searchable: true,
                render: function (data, type, row) {
                    var html = "--";
                    if (row.fecha_revisa != null) html = row.fecha_revisa;
                    return html;
                },
            },
            {
                data: "usuario_leido",
                width: "12%",
                searchable: true,
                render: function (data, type, row) {
                    var html = "--";
                    if (row.usuario_leido != null)
                        html = row.usuario_leido;
                    return html;
                },
            },
            {
                data: "institucion_leida",
                width: "12%",
                searchable: true,
            },
            {
                data: "",
                width: "12%",
                searchable: true,
            },
        ],
    });
    $("#dtmenuObjetivos").dataTable({
        dom: "lfrtip",
        buttons: [
            {
                extend: "excelHtml5",
                text: '<img src="/images/icons/excel.png" width="25px" heigh="20px">',
                titleAttr: "Excel",
            },
        ],
        lengthMenu: [
            [15, 30, -1],
            [15, 30, "TODOS"],
        ],
        lengthChange: true,
        searching: true,
        ordering: true,
        info: true,
        autoWidth: false,
        draw: dt.draw,
        destroy: true,
        recordsFiltered: dt.recordsFiltered,
        recordsTotal: dt.recordsTotal,
        data: dt.data,
        order: [[0, "desc"]],
        language: {
            search: "Buscar",
            lengthMenu: "Mostrar _MENU_",
            zeroRecords: "Lo sentimos, no encontramos lo que estas buscando",
            info: "Motrar página _PAGE_ de _PAGES_",
            infoEmpty: "Registros no encontrados",
            oPaginate: {
                sFirst: "Primero",
                sLast: "Último",
                sNext: "Siguiente",
                sPrevious: "Anterior",
            },
            infoFiltered: "(Filtrado en MAX registros totales)",
        },
        columnDefs: [{ targets: [0], orderable: true }],
        columns: [
            {
                data: "numero",
                width: "5%",
                searchable: true,
            },
            {
                data: "fecha_inicio",
                width: "5%",
                searchable: true,
            },
            {
                data: "fecha_fin",
                width: "5%",
                searchable: true,
            },
            {
                data: "meta",
                width: "5%",
                searchable: true,
            },
            {
                data: "temporalidad.descripcion",
                width: "7%",
                searchable: true,
            },

            {
                data: "objetivo",
                width: "10%",
                searchable: true,
            },
            {
                data: "descripcion",
                width: "10%",
                searchable: true,
            },
            {
                data: "",
                width: "20%",
                searchable: true,
            },
        ],
    });
    $("#dtmenuAvances").dataTable({
        dom: "lfrtip",
        buttons: [
            {
                extend: "excelHtml5",
                text: '<img src="/images/icons/excel.png" width="25px" heigh="20px">',
                titleAttr: "Excel",
            },
        ],
        lengthMenu: [
            [15, 30, -1],
            [15, 30, "TODOS"],
        ],
        lengthChange: true,
        searching: true,
        ordering: true,
        info: true,
        autoWidth: false,
        draw: dt.draw,
        destroy: true,
        recordsFiltered: dt.recordsFiltered,
        recordsTotal: dt.recordsTotal,
        data: dt.data,
        order: [[0, "desc"]],
        language: {
            search: "Buscar",
            lengthMenu: "Mostrar _MENU_",
            zeroRecords: "Lo sentimos, no encontramos lo que estas buscando",
            info: "Motrar página _PAGE_ de _PAGES_",
            infoEmpty: "Registros no encontrados",
            oPaginate: {
                sFirst: "Primero",
                sLast: "Último",
                sNext: "Siguiente",
                sPrevious: "Anterior",
            },
            infoFiltered: "(Filtrado en MAX registros totales)",
        },
        columnDefs: [{ targets: [0], orderable: true }],
        columns: [
            {
                data: "numero",
                width: "5%",
                searchable: true,
            },
            {
                data: "created_at",
                width: "5%",
                searchable: true,
            },

            {
                data: "descripcion",
                width: "5%",
                searchable: true,
            },
            {
                data: "nombres",
                width: "5%",
                searchable: true,
            },
            {
                data: "institucion",
                width: "5%",
                searchable: true,
            },

            {
                data: "fecha_revisa",
                width: "12%",
                searchable: true,
                render: function (data, type, row) {
                    var html = "--";
                    if (row.fecha_revisa != null) html = row.fecha_revisa;
                    return html;
                },
            },
            {
                data: "usuario_leido",
                width: "12%",
                searchable: true,
                render: function (data, type, row) {
                    var html = "--";
                    if (row.usuario_leido != null)
                        html = row.usuario_leido;
                    return html;
                },
            },
            {
                data: "institucion_leida",
                width: "5%",
                searchable: true,
            },
            {
                data: "motivo",
                width: "5%",
                searchable: true,
                render: function (data, type, row) {
                    html = row.motivo != null ? row.motivo : "--";
                    return html;
                },
            },
            {
                data: "",
                width: "10%",
                searchable: true,
            },
        ],
    });
}

function abrirCantones(e) {
    // $(e).siblings("ul").removeClass("hidden");

    var id = $(e).attr("name");
    if ($("#" + id + "").hasClass("hidden"))
        $("#" + id + "").removeClass("hidden");
    else $("#" + id + "").addClass("hidden");

    if ($("[name=" + id + "]").hasClass("plus")) {
        $("[name=" + id + "]").removeClass("plus");
        $("[name=" + id + "]").addClass("minus");
    } else {
        $("[name=" + id + "]").addClass("plus");
        $("[name=" + id + "]").removeClass("minus");
    }
}

function abrirParroquias(e) {
    var id = $(e).attr("name");
    if ($("#" + id + "").hasClass("hidden"))
        $("#" + id + "").removeClass("hidden");
    else $("#" + id + "").addClass("hidden");

    if ($("[name=" + id + "]").hasClass("plus")) {
        $("[name=" + id + "]").removeClass("plus");
        $("[name=" + id + "]").addClass("minus");
    } else {
        $("[name=" + id + "]").addClass("plus");
        $("[name=" + id + "]").removeClass("minus");
    }
}

var tipoActual = "data";

function datatableCargarAntecedentes() {
    $("#dtmenuAntecedentes").dataTable({
        dom: "lfrtip",
        destroy: true,
        serverSide: true,
        ajax:
            "/compromisos/getDatatableAntecedentesServerSide/" +
            app.formCrear.id,
        //  stateSave:true,
        responsive: true,
        processing: true,
        lengthMenu: [
            [5, 10, 20, -1],
            [5, 10, 20, "TODOS"],
        ],
        lengthChange: true,
        searching: true,
        language: {
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
        order: [[0, "desc"]],
        columns: [
            {
                data: "numero",
                width: "5%",
                searchable: true,
            },
            {
                data: "fecha_antecedente",
                width: "5%",
                searchable: true,
            },
            {
                data: "descripcion",
                width: "12%",
                searchable: true,
            },
            {
                data: "",
                width: "15%",
                searchable: true,
            },
        ],
    });
}

function datatableCargarArchivos() {
    $("#dtmenuArchivos").dataTable({
        dom: "lfrtip",
        destroy: true,
        serverSide: true,
        ajax: "/compromisos/getDatatableArchivosServerSide/" + app.formCrear.id,
        //  stateSave:true,
        responsive: true,
        processing: true,
        lengthMenu: [
            [5, 10, 20, -1],
            [5, 10, 20, "TODOS"],
        ],
        lengthChange: true,
        searching: true,
        language: {
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
        order: [[0, "desc"]],
        columns: [
            {
                data: "created_at",
                width: "5%",
                searchable: true,
            },

            {
                data: "nombre",
                width: "12%",
                searchable: true,
            },
            {
                data: "nombres",
                width: "12%",
                searchable: true,
            },
            {
                data: "institucion",
                width: "12%",
                searchable: true,
            },

            {
                data: "fecha_revisa",
                width: "12%",
                searchable: true,
                render: function (data, type, row) {
                    var html = "--";
                    if (row.fecha_revisa != null) html = row.fecha_revisa;
                    return html;
                },
            },
            {
                data: "usuario_leido",
                width: "12%",
                searchable: true,
                render: function (data, type, row) {
                    var html = "--";
                    if (row.usuario_leido != null)
                        html = row.usuario_leido;
                    return html;
                },
            },
            {
                data: "institucion_leida",
                width: "12%",
                searchable: true,
            },
            {
                data: "",
                width: "15%",
                searchable: true,
            },
        ],
    });
}

function datatableCargarHistorico() {
    $("#dtmenuHistorico").dataTable({
        dom: "lfrtip",
        destroy: true,
        // serverSide: true,
        ajax:
            "/compromisos/getDatatableHistoricoServerSide/" + app.formCrear.id,
        //   stateSave:true,
        responsive: true,
        processing: true,
        lengthMenu: [
            [5, 10, 20, -1],
            [5, 10, 20, "TODOS"],
        ],
        lengthChange: true,
        searching: true,
        language: {
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
        order: [[0, "desc"]],
        columns: [
            {
                data: "fecha",
                name: "sc_compromisos.transacciones.created_at",
                width: "5%",
                searchable: true,
            },
            {
                data: "descripcion",
                name: "sc_compromisos.transacciones.descripcion",

                width: "12%",
                searchable: true,
            },
            {
                data: "usuario",
                name: "core.users.nombres",

                width: "12%",
                searchable: true,
            },
            {
                data: "institucion",
                name: "core.instituciones.descripcion",

                width: "12%",
                searchable: true,
            },
        ],
    });
}

function datatableCargarMensajes(tipo = tipoActual) {
    console.log("llego aca");
    $("#dtmenuMensajes").dataTable({
        dom: "lfrtip",
        destroy: true,
        serverSide: true,
        ajax: "/compromisos/getDatatableMensajeServerSide/" + app.formCrear.id,
        //    stateSave:true,
        responsive: true,
        processing: true,
        lengthMenu: [
            [5, 10, 20, -1],
            [5, 10, 20, "TODOS"],
        ],
        lengthChange: true,
        searching: true,
        language: {
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
        order: [[0, "desc"]],
        columns: [
            {
                data: "created_at",
                width: "5%",
                searchable: true,
            },
            {
                data: "descripcion",
                width: "12%",
                searchable: true,
            },
            {
                data: "nombres",
                width: "12%",
                searchable: true,
            },
            {
                data: "institucion",
                width: "12%",
                searchable: true,
            },

            {
                data: "fecha_revisa",
                width: "12%",
                searchable: true,
                render: function (data, type, row) {
                    var html = "--";
                    if (row.fecha_revisa != null) html = row.fecha_revisa;
                    return html;
                },
            },
            {
                data: "usuario_leido",
                width: "12%",
                searchable: true,
                render: function (data, type, row) {
                    var html = "--";
                    if (row.usuario_leido != null)
                        html = row.usuario_leido;
                    return html;
                },
            },
            {
                data: "institucion_leida",
                width: "12%",
                searchable: true,
            },
            {
                data: "",
                width: "12%",
                searchable: true,
            },
        ],
    });
}

function datatableCargarObjetivos() {
    //  var id=$("#id").val();
    $("#dtmenuObjetivos").dataTable({
        dom: "lfrtip",
        destroy: true,
        serverSide: true,
        ajax:
            "/compromisos/getDatatableObjetivosServerSide/" + app.formCrear.id,
        //   stateSave:true,
        responsive: true,
        processing: true,
        lengthMenu: [
            [5, 10, 20, -1],
            [5, 10, 20, "TODOS"],
        ],
        lengthChange: true,
        searching: true,
        language: {
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
        order: [[0, "desc"]],
        columns: [
            {
                data: "numero",
                width: "5%",
                searchable: true,
            },
            {
                data: "fecha_inicio",
                width: "5%",
                searchable: true,
            },
            {
                data: "fecha_fin",
                width: "5%",
                searchable: true,
            },
            {
                data: "meta",
                width: "5%",
                searchable: true,
            },
            {
                data: "temporalidad.descripcion",
                width: "7%",
                searchable: true,
            },

            {
                data: "objetivo",
                width: "10%",
                searchable: true,
            },
            {
                data: "descripcion",
                width: "10%",
                searchable: true,
            },
            {
                data: "",
                width: "20%",
                searchable: true,
            },
        ],
    });
}

function datatableCargarPeriodos() {
    if (
        app.formCronograma.objetivo_id != null &&
        app.formCronograma.objetivo_id != ""
    ) {
        editor = $("#dtmenuPeriodos").dataTable({
            dom: "lfrtip",
            destroy: true,
            serverSide: true,
            ajax:
                "/compromisos/getDatatablePeriodosServerSide/" +
                app.formCronograma.objetivo_id,
            stateSave: true,
            responsive: true,
            processing: true,
            lengthMenu: [
                [5, 10, 20, 30, 40, 50, -1],
                [5, 10, 20, 30, 40, 50, "TODOS"],
            ],
            lengthChange: true,
            searching: true,
            language: {
                search: "Buscar",
                lengthMenu: "Mostrar _MENU_",
                zeroRecords:
                    "Lo sentimos, no encontramos lo que estas buscando",
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
            order: [[0, "desc"]],
            columns: [
                {
                    data: "",
                    width: "5%",
                    searchable: true,
                },
                {
                    data: "numero",
                    width: "5%",
                    searchable: true,
                },

                {
                    data: "fecha_inicio_periodo",
                    width: "5%",
                    searchable: true,
                },
                {
                    data: "fecha_fin_periodo",
                    width: "5%",
                    searchable: true,
                },
                {
                    data: "temporalidad",
                    width: "5%",
                    searchable: true,
                },
                {
                    data: "descripcion_meta",
                    width: "5%",
                    searchable: true,
                },

                {
                    data: "meta_periodo",
                    width: "5%",
                    searchable: true,
                },
                {
                    data: "cumplimiento_periodo",
                    width: "5%",
                    searchable: true,
                },
                {
                    data: "pendiente_periodo",
                    width: "5%",
                    searchable: true,
                },
                {
                    data: "meta_acumulada",
                    width: "5%",
                    searchable: true,
                    render: function (data, type, row) {
                        if (
                            row.meta_acumulada != null &&
                            row.meta_acumulada != 0
                        ) {
                            html = row.meta_acumulada;
                        } else {
                            html = row.valor_anterior_meta_acumulada;
                        }

                        return html;
                    },
                },
                {
                    data: "cumplimiento_acumulado",
                    width: "5%",
                    searchable: true,
                    render: function (data, type, row) {
                        var cumplimiento_acumulado =
                            row.cumplimiento_acumulado != 0 &&
                            row.meta_acumulada != null
                                ? row.cumplimiento_acumulado
                                : 0;
                        return (cumplimiento_acumulado / 1).toFixed(2);
                    },
                },
                {
                    data: "pendiente_acumulado",
                    width: "5%",
                    searchable: true,
                    render: function (data, type, row) {
                        var cumplido =
                            row.cumplimiento_acumulado != 0 &&
                            row.meta_acumulada != null
                                ? (
                                      (row.cumplimiento_acumulado * 100) /
                                      row.meta_acumulada
                                  ).toFixed(2)
                                : 0;
                        return cumplido + " %";
                    },
                },
                {
                    data: "cumplimiento_acumulado",
                    width: "5%",
                    searchable: true,
                    render: function (data, type, row) {
                        /// var cumplido = ((row.cumplimiento_acumulado * 100) / row.meta_acumulada).toFixed(2);
                        var cumplido = row.cumplimiento_acumulado;
                        var html =
                            (
                                (cumplido / row.suma_meta_acumulada) *
                                100
                            ).toFixed(2) + " %";
                        /*  if (isNaN(html))
                          return 0;*/
                        return html;
                    },
                },
            ],
        });
    } else destroyPeriodos();
}

function datatableCargarAvances() {
    $("#dtmenuAvances").dataTable({
        dom: "lfrtip",
        destroy: true,
        serverSide: true,
        ajax: "/compromisos/getDatatableAvancesServerSide/" + app.formCrear.id,
        //   stateSave:true,
        responsive: true,
        processing: true,
        lengthMenu: [
            [5, 10, 20, -1],
            [5, 10, 20, "TODOS"],
        ],
        lengthChange: true,
        searching: true,
        language: {
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
        order: [[0, "desc"]],
        columns: [
            {
                data: "numero",
                width: "5%",
                searchable: true,
            },
            {
                data: "created_at",
                width: "5%",
                searchable: true,
            },

            {
                data: "descripcion",
                width: "5%",
                searchable: true,
            },
            {
                data: "nombres",
                width: "5%",
                searchable: true,
            },
            {
                data: "institucion",
                width: "5%",
                searchable: true,
            },
            {
                data: "fecha_revisa",
                width: "12%",
                searchable: true,
                render: function (data, type, row) {
                    var html = "--";
                    if (row.fecha_revisa != null) html = row.fecha_revisa;
                    return html;
                },
            },
            {
                data: "usuario_leido",
                width: "12%",
                searchable: true,
                render: function (data, type, row) {
                    var html = "--";
                    if (row.usuario_leido != null)
                        html = row.usuario_leido;
                    return html;
                },
            },
            {
                data: "institucion_leida",
                width: "5%",
                searchable: true,
            },
            {
                data: "motivo",
                width: "5%",
                searchable: true,
                render: function (data, type, row) {
                    html = row.motivo != null ? row.motivo : "--";
                    return html;
                },
            },
            {
                data: "",
                width: "10%",
                searchable: true,
            },
        ],
    });
}

function datatableCargarUbicaciones() {
    var data = new FormData();
    data.append("id", app.formCrear.id);
    var objApiRest = new AJAXRestFilePOST(
        "/compromisos/getDatatableUbicacionesServerSide",
        data
    );
    objApiRest.extractDataAjaxFile(function (_resultContent) {
        if (_resultContent.status == 200) {
            if (_resultContent.status == 200) {
                var data = _resultContent.message;

                $.each(_resultContent.datos, function (_key, _value) {
                    $("#c_bs_" + _value + "").prop("checked", true);
                    $("#c_bs_" + _value + "")
                        .closest(".inner_ul")
                        .removeClass("hidden");
                    $("#c_bs_" + _value + "")
                        .closest(".inner_ul")
                        .closest(".sub_ul")
                        .removeClass("hidden");
                    $("#c_bs_" + _value + "")
                        .closest(".inner_ul")
                        .closest(".sub_ul")
                        .closest(".main_ul")
                        .removeClass("hidden");

                    //     var da=$("#c_bs_"+_value+"").closest('.inner_ul')[0].parent().parent();
                    // $("#c_bs_"+_value+"").closest('.inner_ul').closest('.sub_ul').removeClass('hidden');
                    //$("#c_bs_"+_value+"").closest('.inner_ul').closest('.sub_ul').closest('.main_ul').removeClass('hidden');

                    /*
                                        $("#c_bs_"+_value+"").closest('.inner_ul').closest('.sub_ul').parent().parent().parent().removeClass('plus');
                                        $("#c_bs_"+_value+"").closest('.inner_ul').closest('.sub_ul').parent().parent().parent().removeClass('minus');

                                        $("#c_bs_"+_value+"").closest('.inner_ul').closest('.sub_ul').closest('.main_ul').parent().parent().parent().removeClass('plus');
                                        $("#c_bs_"+_value+"").closest('.inner_ul').closest('.sub_ul').closest('.main_ul').parent().parent().parent().removeClass('minus');*/
                });
                var consulta = 1;
                if (data.length > 0) {
                    var dt = {
                        draw: 1,
                        recordsFiltered: data.length,
                        recordsTotal: data.length,
                        data: data,
                    };
                } else {
                    if (_resultContent.datos_generales.length > 0) {
                        var dt = {
                            draw: 1,
                            recordsFiltered: 0,
                            recordsTotal: 0,
                            data: _resultContent.datos_generales,
                        };
                        consulta = 2;
                    } else {
                        var dt = {
                            draw: 1,
                            recordsFiltered: 0,
                            recordsTotal: 0,
                            data: [],
                        };
                    }
                }
                if (consulta == 1) {
                    $("#tbobymenuUbicacion").show();
                    $.fn.dataTable.ext.errMode = "throw";
                    $("#dtmenuUbicacion").dataTable({
                        dom: "lfrtip",
                        buttons: [
                            {
                                extend: "excelHtml5",
                                text: '<img src="/images/icons/excel.png" width="25px" heigh="20px">',
                                titleAttr: "Excel",
                            },

                            {
                                extend: "pdfHtml5",
                                text: '<img src="/images/icons/pdf.png" width="25px" heigh="20px">',
                                titleAttr: "PDF",
                                orientation: "landscape",
                                title: "UBICACIONES REGISTRADAS",
                                footer: true,
                                pageSize: "A4",
                            },
                        ],
                        lengthMenu: [
                            [15, 30, -1],
                            [15, 30, "TODOS"],
                        ],
                        lengthChange: true,
                        searching: true,
                        ordering: true,
                        info: true,
                        autoWidth: false,
                        draw: dt.draw,
                        destroy: true,
                        recordsFiltered: dt.recordsFiltered,
                        recordsTotal: dt.recordsTotal,
                        data: dt.data,
                        order: [[0, "desc"]],
                        language: {
                            search: "Buscar",
                            lengthMenu: "Mostrar _MENU_",
                            zeroRecords:
                                "Lo sentimos, no encontramos lo que estas buscando",
                            info: "Motrar página _PAGE_ de _PAGES_",
                            infoEmpty: "Registros no encontrados",
                            oPaginate: {
                                sFirst: "Primero",
                                sLast: "Último",
                                sNext: "Siguiente",
                                sPrevious: "Anterior",
                            },
                            infoFiltered: "(Filtrado en MAX registros totales)",
                        },
                        columnDefs: [{ targets: [0], orderable: true }],
                        columns: [
                            {
                                title: "Provinca",
                                data: "fatherpara.fatherpara.descripcion",
                                width: "5%",
                                searchable: true,
                            },

                            {
                                title: "Ciudad",

                                data: "fatherpara.descripcion",
                                width: "12%",
                                searchable: true,
                            },
                            {
                                title: "Parroquia",
                                data: "descripcion",
                                width: "12%",
                                searchable: true,
                            },
                        ],
                    });
                } else {
                    $("#tbobymenuUbicacion").show();
                    $.fn.dataTable.ext.errMode = "throw";
                    $("#dtmenuUbicacion").dataTable({
                        dom: "lfrtip",
                        buttons: [
                            {
                                extend: "excelHtml5",
                                text: '<img src="/images/icons/excel.png" width="25px" heigh="20px">',
                                titleAttr: "Excel",
                            },

                            {
                                extend: "pdfHtml5",
                                text: '<img src="/images/icons/pdf.png" width="25px" heigh="20px">',
                                titleAttr: "PDF",
                                orientation: "landscape",
                                title: "UBICACIONES REGISTRADAS",
                                footer: true,
                                pageSize: "A4",
                            },
                        ],
                        lengthMenu: [
                            [15, 30, -1],
                            [15, 30, "TODOS"],
                        ],
                        lengthChange: true,
                        searching: true,
                        ordering: true,
                        info: true,
                        autoWidth: false,
                        draw: dt.draw,
                        destroy: true,
                        recordsFiltered: dt.recordsFiltered,
                        recordsTotal: dt.recordsTotal,
                        data: dt.data,
                        order: [[0, "desc"]],
                        language: {
                            search: "Buscar",
                            lengthMenu: "Mostrar _MENU_",
                            zeroRecords:
                                "Lo sentimos, no encontramos lo que estas buscando",
                            info: "Motrar página _PAGE_ de _PAGES_",
                            infoEmpty: "Registros no encontrados",
                            oPaginate: {
                                sFirst: "Primero",
                                sLast: "Último",
                                sNext: "Siguiente",
                                sPrevious: "Anterior",
                            },
                            infoFiltered: "(Filtrado en MAX registros totales)",
                        },
                        columnDefs: [{ targets: [0], orderable: true }],
                        columns: [
                            {
                                title: "Provincia",
                                data: "descripcion",
                                width: "12%",
                                searchable: true,
                            },
                            {
                                title: "Canton",
                                data: "descripcion",
                                width: "12%",
                                searchable: true,
                            },
                            {
                                title: "Parroquia",
                                data: "descripcion",
                                width: "12%",
                                searchable: true,
                            },
                        ],
                    });
                }
            }
        }
    });
}

function checkearCanton(e) {
    //alert($(this).attr("id"));
    //var sp = $(this).attr("id");
    //if (sp.substring(0, 4) === "c_bs" || sp.substring(0, 4) === "c_bf") {
    $(e)
        .siblings("ul")
        .find("input[type=checkbox]")
        .prop("checked", $(e).prop("checked"));
    //}
}

$("input[type=checkbox]").change(function () {
    var sp = $(this).attr("id");
    if (sp.substring(0, 4) === "c_io") {
        var ff = $(this).parents("ul[id^=bf_l]").attr("id");
        if (
            $("#" + ff + " > li input[type=checkbox]:checked").length ==
            $("#" + ff + " > li input[type=checkbox]").length
        ) {
            $("#" + ff)
                .siblings("input[type=checkbox]")
                .prop("checked", true);
            check_fst_lvl(ff);
        } else {
            $("#" + ff)
                .siblings("input[type=checkbox]")
                .prop("checked", false);
            check_fst_lvl(ff);
        }
    }

    if (sp.substring(0, 4) === "c_bf") {
        var ss = $(this).parents("ul[id^=bs_l]").attr("id");
        if (
            $("#" + ss + " > li input[type=checkbox]:checked").length ==
            $("#" + ss + " > li input[type=checkbox]").length
        ) {
            $("#" + ss)
                .siblings("input[type=checkbox]")
                .prop("checked", true);
            check_fst_lvl(ss);
        } else {
            $("#" + ss)
                .siblings("input[type=checkbox]")
                .prop("checked", false);
            check_fst_lvl(ss);
        }
    }
});

function check_fst_lvl(dd) {
    //var ss = $('#' + dd).parents("ul[id^=bs_l]").attr("id");
    var ss = $("#" + dd)
        .parent()
        .closest("ul")
        .attr("id");
    if (
        $("#" + ss + " > li input[type=checkbox]:checked").length ==
        $("#" + ss + " > li input[type=checkbox]").length
    ) {
        //$('#' + ss).siblings("input[id^=c_bs]").prop('checked', true);
        $("#" + ss)
            .siblings("input[type=checkbox]")
            .prop("checked", true);
    } else {
        //$('#' + ss).siblings("input[id^=c_bs]").prop('checked', false);
        $("#" + ss)
            .siblings("input[type=checkbox]")
            .prop("checked", false);
    }
}

function arrayEquals(a, b) {
    return (
        Array.isArray(a) &&
        Array.isArray(b) &&
        a.length === b.length &&
        a.every((val, index) => val.toString() === b[index])
    );
}

$("#objetivo_id").on("change", function () {
    app.formCronograma.objetivo_id = $(this).val() == null ? "" : $(this).val();
    app.formCronograma.numero = "--";
    //app.limpiarFormularios();
    datatableCargarPeriodos();
});

function destroyPeriodos() {
    $("#dtmenuPeriodos").DataTable().destroy();
    $("#dtbodyPeriodos").html("");
    $("#dtmenuPeriodos").dataTable({
        dom: "lfrtip",
    });
}
//PESTAÑA BUSQUEDA
function datatableCompromisosBusquedas(tipo = null) {
    app.getBusquedas();
    if (tipo != null) tipoActual = tipo;
    let gabinete_id_busqueda =
        $("#gabinete_id_busqueda").val() == "" ||
        $("#gabinete_id_busqueda").val() == null
            ? 0
            : $("#gabinete_id_busqueda").val();
    let institucion_id_busqueda =
        $("#institucion_id_busqueda").val() == "" ||
        $("#institucion_id_busqueda").val() == null
            ? 0
            : $("#institucion_id_busqueda").val();
    let monitor_busqueda =
        $("#monitor_busqueda").val() == "" ||
        $("#monitor_busqueda").val() == null
            ? 0
            : $("#monitor_busqueda").val();

    this.tableHistorico = $("#dtmenu_busquedas").dataTable({
        dom: "lBfrtip",
        destroy: true,
        serverSide: true,
        ajax:
            "/compromisos/getDatatableBusquedasServerSide/" +
            gabinete_id_busqueda +
            "/" +
            institucion_id_busqueda +
            "/" +
            monitor_busqueda +
            "/" +
            tipoActual +
            "",
        buttons: [
            {
                extend: "excelHtml5",
                text: '<img src="/images/icons/excel.png" width="25px" heigh="25px">Exportar Excel',
                titleAttr: "Excel",
            },
        ],
        stateSave: true,
        responsive: true,
        processing: true,
        lengthMenu: [
            [10, 20, -1],
            [10, 20, "TODOS"],
        ],
        lengthChange: true,
        searching: true,
        language: {
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
        order: [[0, "desc"]],
        columns: [
            {
                data: "codigo",
                width: "10%",
                searchable: true,
                render: function (data, type, row) {
                    html =
                        row.codigo != null && row.codigo != ""
                            ? row.codigo
                            : row.id;
                    return html;
                },
            },
            {
                data: "nombre_compromiso",
                width: "20%",
                searchable: true,
            },
            {
                data: "institucion_responsable",
                width: "10%",
                searchable: true,
            },
            {
                data: "gabinete_",
                width: "10%",
                searchable: true,
            },
            {
                data: "fecha_inicio_",
                width: "10%",
                searchable: true,
            },
            {
                data: "fecha_fin_",
                width: "10%",
                searchable: true,
            },
            {
                data: "estado_porcentaje_",
                width: "7%",
                searchable: true,
            },
            {
                data: "",
                width: "2%",
                searchable: true,
            },
        ],
    });
}

$("#gabinete_id_busqueda").on("change", function () {
    app.gabinete_id_busqueda = $(this).val() != null ? $(this).val() : 0;
    app.institucion_id_busqueda =
        $("#institucion_id_busqueda").val() != null
            ? $("#institucion_id_busqueda").val()
            : 0;
    if (
        (app.gabinete_id_busqueda != 0 || app.gabinete_id_busqueda != "") &&
        (app.institucion_id_busqueda != 0 || app.institucion_id_busqueda == "")
    )
        app.institucionBusqueda();
});

$("#institucion_id_busqueda").on("change", function () {
    if (app.institucion_anterior != null) {
        app.institucion_anterior = null;
        return false;
    }
    app.institucion_id_busqueda = $(this).val() != null ? $(this).val() : 0;
    if (
        (app.institucion_id_busqueda != 0 ||
            app.institucion_id_busqueda != "") &&
        (app.gabinete_id_busqueda == 0 || app.gabinete_id_busqueda == "")
    )
        app.gabineteBusqueda(app.institucion_id_busqueda);
});
