function datatableCargarBusquedas(id, tipo) {
    app.tipo_busqueda = tipo;
    let arregloDatosIndicado = arregloDatosMensajes;
    tipoConsulta = "getDatatableBusquedaPorTipo";
    let dt = "dtListadoBusquedasMensajes";
    $(".datatablesSelectores").addClass("hidden");

    if (tipo == "ARCHIVOS") {
        arregloDatosIndicado = arregloDatosArchivos;
        dt = "dtListadoBusquedasArchivos";
    }
    if (tipo == "AVANCES") {
        arregloDatosIndicado = arregloDatosAvances;
        dt = "dtListadoBusquedasAvances";
    }

    if (tipo == "OBJETIVOS") {
        arregloDatosIndicado = arregloDatosObjetivos;
        dt = "dtListadoBusquedasObjetivos";
    }

    $("." + dt + "").removeClass("hidden");

    $("#" + dt + "").dataTable({
        dom: "lfrtip",
        destroy: true,
        serverSide: true,
        ajax: "/compromisos/" + tipoConsulta + "/" + id + "/" + tipo,
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
        order: [
            [0, "desc"]
        ],
        columns: arregloDatosIndicado,
    });
}

function cargarExportaciones() {
    app.exportaciones = true;
    app.gestiones = false;
    app.calendario = false;
    app.calendario_finalizacion = false;
    app.busquedas = false;
    $("#dtExportacion").dataTable({
        dom: "lfrtip",
        destroy: true,
        serverSide: true,
        ajax: "/compromisos/getDatatableExportaciones",
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
        order: [
            [0, "desc"]
        ],
        columns: arregloDatosExportaciones,
    });
}