$(function () {
    datatableCargar();
    datatableCargarGabinete();
    datatableBuscarCompromisosServerSide();
    var padreSuperior = $(".aprobarMigraciones")
        .closest("a")
        .addClass("hidden");
    $(".aprobarMigraciones").addClass("hidden");
});

var tipoActual = "";
var dtmenuMigracion = $("#dtmenuMigracion").DataTable();
$(".selectAll").on("click", function (e) {
    if ($(this).is(":checked")) {
        var padreSuperior = $(".aprobarMigraciones")
            .closest("a")
            .removeClass("hidden");
        $(".aprobarMigraciones").removeClass("hidden");
        dtmenuMigracion.api().rows().select();
    } else {
        var padreSuperior = $(".aprobarMigraciones")
            .closest("a")
            .addClass("hidden");
        $(".aprobarMigraciones").addClass("hidden");
        dtmenuMigracion.api().rows().deselect();
    }
});

$("#dtmenuMigracion").on("select.dt", function (e, dt, type, indexes) {
    var padreSuperior = $(".aprobarMigraciones")
        .closest("a")
        .removeClass("hidden");
    $(".aprobarMigraciones").removeClass("hidden");
});
$("#dtmenuMigracion").on("deselect.dt", function (e, dt, type, indexes) {
    var padreSuperior = $(".aprobarMigraciones")
        .closest("a")
        .addClass("hidden");
    $(".aprobarMigraciones").addClass("hidden");
});

function resetarModal() {
    $("#institucion_id").html("");
    $("#institucion_id").val(null).change();
}
var tipoActual = "data";
function datatableCargar() {
    $("#dtmenu").dataTable({
        dom: "lfrtip",
        destroy: true,
        serverSide: true,
        ajax: "/compromisos/getDatatableInstitucionServerSide/",
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
                data: "id",
                width: "10%",
                searchable: true,
                //"className": "hidden",
            },
            {
                data: "gabinete",
                width: "30%",
                searchable: true,
                /*"render": function (data, type, row) {
                    return row.instituciones.split(',');
                }*/
            },
            {
                data: "nombres",
                width: "30%",
                searchable: true,
            },
            {
                data: "siglas",
                width: "10%",
                searchable: true,
            },
            {
                data: "ministro",
                width: "10%",
                searchable: true,
            },
            {
                data: "estado",
                width: "10%",
                searchable: true,
                render: function (data, type, row) {
                    return (
                        "<strong>" +
                        (row.estado == "ACT" ? "ACTIVO" : "INACTIVO") +
                        "</strong>"
                    );
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
function datatableCargarGabinete() {
    $("#dtmenuGabinete").dataTable({
        dom: "lfrtip",
        destroy: true,
        serverSide: true,
        ajax: "/compromisos/getDatatableGabineteServerSide/",
        stateSave: true,
        responsive: true,
        processing: true,
        lengthMenu: [
            [5, -1],
            [5, "TODOS"],
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
                data: "id",
                width: "10%",
                searchable: true,
                //"className": "hidden",
            },
            {
                data: "gabinete",
                width: "20%",
                searchable: true,
                /*"render": function (data, type, row) {
                    return row.instituciones.split(',');
                }*/
            },
            {
                data: "nombres",
                width: "30%",
                searchable: true,
            },
            {
                data: "siglas",
                width: "10%",
                searchable: true,
            },

            {
                data: "estado",
                width: "10%",
                searchable: true,
                render: function (data, type, row) {
                    return (
                        "<strong>" +
                        (row.estado == "ACT" ? "ACTIVO" : "INACTIVO") +
                        "</strong>"
                    );
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

function datatableBuscarCompromisosServerSide() {
    var id = app.formCompromiso.id_institucion;
    dtmenuMigracion = $("#dtmenuMigracion").dataTable({
        dom: "lBfrtip",
        destroy: true,
        serverSide: true,
        ajax:
            "/compromisos/getDatatableBuscarCompromisosServerSide/" + id + "/",
        buttons: [
            {
                text: '<button class="btn btn-xs aprobarMigraciones" style="padding:2.6px" data-toggle="modal" data-target="#modal-migracion-compromisos" data-backdrop="static" data-keyboard="false"><i class="far fa-check-circle"width="25px" heigh="25px"></i>&nbsp;Migrar</button>',
                action: function (e, dt, node, config) {
                    app.elegirInstitucion();
                },
            },
        ],
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
        order: [[1, "desc"]],
        columnDefs: [
            {
                orderable: false,
                name: "check_",
                className: "select-checkbox",
                targets: 0,
            },
        ],
        select: {
            style: "os",
            //  className: 'select-os',
            selector: "td:first-child",
        },
        columns: [
            {
                data: "id",
                width: "1%",
                searchable: false,
                render: function (data, type, row) {
                    return "";
                },
            },
            {
                data: "descripcion",
                width: "10%",
                searchable: true,
            },
            {
                data: "codigo",
                width: "30%",
                searchable: true,
            },
            {
                data: "nombre_compromiso",
                width: "30%",
                searchable: true,
            },
        ],
    });
}

$("#filtro_institucion").on("change", function () {
    app.formCrear.institucion_id = $(this).val() != null ? $(this).val() : "";
    //app.formDelegado.institucion_id = $('select[name="filtro_institucion_"] option:selected').text();
});
$("#ministro_usuario_id").on("change", function () {
    app.formCrear.ministro_usuario_id =
        $(this).val() != null ? $(this).val() : "";
    //app.formDelegado.institucion_id = $('select[name="filtro_institucion_"] option:selected').text();
});

$("#filtro_ministro_id").on("change", function () {
    app.formCrear.ministro_usuario_id =
        $(this).val() != null ? $(this).val() : "";
});
$("#filtro_gabinete").on("change", function () {
    app.formCrear.institucion_id = $(this).val() != null ? $(this).val() : "";
    //app.formDelegado.institucion_id = $('select[name="filtro_institucion_"] option:selected').text();
});

function delay(n) {
    return new Promise(function (resolve) {
        setTimeout(resolve, n * 1000);
    });
}

/* Datatable de codigos migrados */
function datatableCodigosMigradosServerSide() {
    $("#dtmenuMigrados").dataTable({
        dom: "lfrtip",
        destroy: true,
        serverSide: true,
        ajax: "/compromisos/getDatatableCodigosMigradosServerSide/",
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
        order: [[1, "desc"]],
        columns: [
            {
                data: "codigo_anterior",
                width: "25%",
                searchable: true,
            },
            {
                data: "codigo_actual",
                width: "25%",
                searchable: true,
            },
            {
                data: "motivo",
                width: "50%",
                searchable: true,
            },
        ],
    });
}
