$(function () {
    $("body").addClass("sidebar-collapse");
});
$("#fecha_inicio_grupo").on("change", function () {
    appHistorial.formGrupo.fecha_inicio = $(this).val();
});
$("#fecha_fin_grupo").on("change", function () {
    appHistorial.formGrupo.fecha_fin = $(this).val();
});

function datatablehistorialPersona(id) {
    persona_id = id;
    appHistorial.limpiarHistorial();
    appHistorial.consultaPersonaHistorial(id);
    //  iniciar_modal_espera_dt();
    var tipo = $("#tipo_estado").val();
    $("#dtmenuHistoria").dataTable({
        dom: "lBfrtip",
        destroy: true,
        serverSide: true,
        ajax:
            "/uath/datatablehistorialPersona_/" + persona_id + "/" + tipo + "/",
        buttons: [
            {
                extend: "excelHtml5",
                text: '<img src="/images/icons/excel.png" width="25px" heigh="25px">Exportar Excel',
                titleAttr: "Excel",
                exportOptions: {
                    columns: [2, 3, 4, 5, 6, 7, 8, 9, 10], //exportar solo la primera y segunda columna
                },
            },
        ],
        // stateSave:true,
        responsive: true,
        processing: true,
        lengthMenu: [[-1], ["TODOS"]],
        lengthChange: true,
        searching: true,
        language: {
            processing:
                "<i class='fa fa-spinner fa-spin fa-stack-2x fa-fw'></i>\n\
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
        order: [
            [0, "asc"],
            [9, "desc"],
            [3, "desc"],
        ],
        rowCallback: function (row, data, index) {
            if (data["eliminado_por_reingreso"]) {
                $node = this.api().row(row).nodes().to$();
                $node.addClass("colorCeldaEliminado");
            } else {
                if (data["estado"] == "ACT") {
                    if (data["es_principal"]) {
                        $node = this.api().row(row).nodes().to$();
                        $node.addClass("colorCelda");
                    }
                }
            }
        },
        columns: [
            {
                data: "eliminado_por_reingreso",
                width: "5%",
                searchable: true,
                className: "hidden",
            },
            {
                data: "sistema",
                width: "5%",
                searchable: true,
                className: "hidden",
            },
            {
                data: "fecha_inserta",
                width: "5%",
                searchable: true,
                className: "hidden",
            },
            {
                data: "id_",
                width: "5%",
                searchable: true,
                render: function (data, type, row) {
                    return $("<div />").html(row.id_).text();

                    //    return row.id_;
                },
            },
            {
                data: "tipo_contrato_",
                width: "10%",
                searchable: true,
                className: "hidden",
            },
            {
                data: "area_",
                width: "15%",
                searchable: true,
                className: "hidden",
            },
            {
                data: "cargo_",
                width: "15%",
                searchable: true,
                className: "hidden",
            },
            {
                data: "denominacion_",
                width: "15%",
                searchable: true,
                className: "hidden",
            },
            {
                data: "sueldo_",
                width: "7%",
                searchable: true,
                className: "hidden",
            },
            {
                data: "fecha_ingreso",
                width: "7%",
                searchable: true,
                className: "hidden",
            },
            {
                data: "fecha_salida",
                width: "7%",
                searchable: true,
                className: "hidden",
            },
            {
                data: "movimiento_",
                width: "7%",
                searchable: true,
                /* "render": function (data, type, row) {
                    var dataHtml = $("#motivo_id").html();
                    return htmlEditor(row.id, row.movimiento_, "motivo_id", dataHtml, row.motivo_id, false);
                } */
            },
            {
                data: "tipo_contrato_",
                width: "5%",
                searchable: true,
                /* "render": function (data, type, row) {
                    var dataHtml = $("#tipo_contrato_id").html();
                    return htmlEditor(row.id, row.tipo_contrato_, "tipo_contrato_id", dataHtml, row.tipo_contrato_id, false);
                } */
            },
            {
                data: "area_",
                width: "15%",
                searchable: true,
                /* "render": function (data, type, row) {
                    var dataHtml = $("#area_id").html();
                    return htmlEditor(row.id, row.area_, "area", dataHtml, row.area_id);
                } */
            },
            {
                data: "edificio_",
                width: "15%",
                searchable: true,
                /* "render": function (data, type, row) {
                    var dataHtml = $("#edificio_id").html();
                    return htmlEditor(row.id, row.edificio_, "edificio", dataHtml, row.edificio_id);
                } */
            },
            /*{
                data: 'horario_',
                "width": "15%",
                "searchable": true,
                 "render": function (data, type, row) {
                    var dataHtml = $("#edificio_id").html();
                    return htmlEditor(row.id, row.edificio_, "edificio", dataHtml, row.edificio_id);
                }
            },*/
            {
                data: "cargo_",
                width: "15%",
                searchable: true,
                /* "render": function (data, type, row) {
                    var dataHtml = $("#cargo_id").html();
                    return htmlEditor(row.id, row.cargo_, "cargo", dataHtml, row.cargo_id);
                } */
            },
            {
                data: "denominacion_",
                width: "15%",
                searchable: true,
                /* "render": function (data, type, row) {
                    var dataHtml = $("#denominacion_id").html();
                    return htmlEditor(row.id, row.denominacion_, "denominacion", dataHtml, row.denominacion_id);
                } */
            },
            {
                data: "sueldo_",
                width: "5%",
                searchable: true,
            },
            {
                data: "fecha_ingreso",
                width: "7%",
                searchable: true,
                /* "render": function (data, type, row) {
                    let valor = row.fecha_ingreso == null ? '' : row.fecha_ingreso;
                    let id_valor = row.id;
                    return htmlEditor(id_valor, valor, "fecha_ingreso");
                } */
            },
            {
                data: "fecha_salida",
                width: "7%",
                searchable: true,
                /* "render": function (data, type, row) {
                    let valor = row.fecha_salida == null ? '' : row.fecha_salida;
                    let id_valor = row.id;
                    return htmlEditor(id_valor, valor, "fecha_salida");
                } */
            },

            {
                data: "",
                width: "20%",
                searchable: true,
            },
        ],
    });
}

function datatablehistorialHorario(id) {
    persona_id = id;
    $("#dtmenuHorarios").dataTable({
        dom: "lfrtip",
        destroy: true,
        serverSide: true,
        ajax: "/uath/datatablehistorialHorario/" + persona_id + "/",
        // stateSave:true,
        responsive: true,
        processing: true,
        lengthMenu: [[-1], ["TODOS"]],
        lengthChange: true,
        searching: true,
        language: {
            processing:
                "<i class='fa fa-spinner fa-spin fa-stack-2x fa-fw'></i>\n\
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
        order: [[0, "asc"]],
        columns: [
            {
                data: "descripcion",
                width: "25%",
                searchable: true,
            },
            {
                data: "fecha_inicio",
                width: "15%",
                searchable: true,
                render: function (data, type, row) {
                    return row.fecha_inicio != null ? row.fecha_inicio : "--";
                },
            },
            {
                data: "fecha_fin",
                width: "15%",
                searchable: true,
                render: function (data, type, row) {
                    return row.fecha_fin != null ? row.fecha_fin : "--";
                },
            },
            {
                data: "lunes",
                width: "15%",
                searchable: true,
                render: function (data, type, row) {
                    return row.lunes != null || row.lunes != " "
                        ? row.lunes
                        : "--";
                },
            },
            {
                data: "martes",
                width: "15%",
                searchable: true,
                render: function (data, type, row) {
                    return row.martes != null || row.martes != " "
                        ? row.martes
                        : "--";
                },
            },
            {
                data: "miercoles",
                width: "15%",
                searchable: true,
                render: function (data, type, row) {
                    return row.miercoles != null || row.miercoles != " "
                        ? row.miercoles
                        : "--";
                },
            },
            {
                data: "jueves",
                width: "15%",
                searchable: true,
                render: function (data, type, row) {
                    return row.jueves != null || row.jueves != " "
                        ? row.jueves
                        : "--";
                },
            },
            {
                data: "viernes",
                width: "15%",
                searchable: true,
                render: function (data, type, row) {
                    return row.viernes != null || row.viernes != " "
                        ? row.viernes
                        : "--";
                },
            },
            {
                data: "sabado",
                width: "15%",
                searchable: true,
                render: function (data, type, row) {
                    return row.sabado != null || row.sabado != " "
                        ? row.sabado
                        : "--";
                },
            },
            {
                data: "domingo",
                width: "15%",
                searchable: true,
                render: function (data, type, row) {
                    return row.domingo != null || row.domingo != " "
                        ? row.domingo
                        : "--";
                },
            },
            {
                data: "estado",
                width: "5%",
                searchable: true,
                render: function (data, type, row) {
                    return row.estado == "ACT"
                        ? '<span class="badge bg-primary">ACTIVO</span>'
                        : '<span class="badge bg-danger">INACTIVO</span>';
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
