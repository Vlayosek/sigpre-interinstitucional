$(function() {
    /*ABRIR PANTALLLA , CERRANDO MENU*/
    $("body").addClass("sidebar-collapse");

    datatableCargar();
});

function resetarModal() {
    $("#usuario_id").html('');
    $("#institucion_id").html('');
    $("#usuario_id").val(null).change();
    $("#institucion_id").val(null).change();
}
var tipoActual = 'data';

function datatableCargar() {
    $("#dtmenu").dataTable({
        dom: 'lfrtip',
        'destroy': true,
        serverSide: true,
        "ajax": "/asignacion/getDatatableAsignacionMonitorServerSide/",
        stateSave: true,
        responsive: true,
        "processing": true,
        "lengthMenu": [
            [10, 20, -1],
            [10, 20, "TODOS"]
        ],
        "lengthChange": true,
        "searching": true,
        "language": {
            "search": "Buscar",
            "lengthMenu": "Mostrar _MENU_",
            "zeroRecords": "Lo sentimos, no encontramos lo que estas buscando",
            "info": "Motrar página _PAGE_ de _PAGES_ (_TOTAL_)",
            "infoEmpty": "Registros no encontrados",
            "oPaginate": {
                "sFirst": "Primero",
                "sLast": "Último",
                "sNext": "Siguiente",
                "sPrevious": "Anterior"
            },
            "infoFiltered": "(Filtrado _TOTAL_  de _MAX_ registros totales)",
        },
        "order": [
            [0, "desc"]
        ],
        "columns": [{
                data: 'id',
                "width": "5%",
                "searchable": true,
                "className": "hidden",

            },

            {
                data: 'nombres',
                "width": "40%",
                "searchable": true,

            },
            {
                data: '',
                "width": "40%",
                "searchable": true,
                "render": function(data, type, row) {
                    return row.instituciones.split(',');
                }
            },
            {
                data: '',
                "width": "10%",
                "searchable": true,

            },
        ]

    });
}

/*Funcion para el datatable de Eliminados*/
function datatableBuscarEliminados() {
    let institucion = $("#filtro_institucion_").val() == "" || $("#filtro_institucion_").val() == null ? 0 : $("#filtro_institucion_").val();
    var fecha_inicio = $("#fecha_inicio").val() == "" || $("#fecha_inicio").val() == null ? "0" : $("#fecha_inicio").val();
    var fecha_fin = $("#fecha_fin").val() == "" || $("#fecha_fin").val() == null ? "0" : $("#fecha_fin").val();

    $("#dtmenu_eliminados").dataTable({
        dom: 'lBfrtip',
        'destroy': true,
        serverSide: true,
        "ajax": "/asignacion/getDatatableEliminadosServerSide/" + institucion + "/" + fecha_inicio + "/" + fecha_fin + "/",
        buttons: [{
            extend: "excelHtml5",
            text: '<img src="/images/icons/excel.png" width="25px" heigh="25px">Exportar Excel',
            titleAttr: "Excel",
        }, ],
        stateSave: true,
        responsive: true,
        "processing": true,
        "lengthMenu": [
            [10, 20, -1],
            [10, 20, "TODOS"]
        ],
        "lengthChange": true,
        "searching": true,
        "language": {
            "search": "Buscar",
            "lengthMenu": "Mostrar _MENU_",
            "zeroRecords": "Lo sentimos, no encontramos lo que estas buscando",
            "info": "Motrar página _PAGE_ de _PAGES_ (_TOTAL_)",
            "infoEmpty": "Registros no encontrados",
            "oPaginate": {
                "sFirst": "Primero",
                "sLast": "Último",
                "sNext": "Siguiente",
                "sPrevious": "Anterior"
            },
            "infoFiltered": "(Filtrado _TOTAL_  de _MAX_ registros totales)",
        },
        "order": [
            [0, "desc"]
        ],
        "columns": [{
                data: 'codigo',
                "width": "5%",
                "searchable": true,
            },
            {
                data: 'nombre_compromiso',
                "width": "30%",
                "searchable": true,

            },
            {
                data: 'institucion',
                "width": "30%",
                "searchable": true,
            },
            {
                data: 'fecha_inicio',
                "width": "10%",
                "searchable": true,

            },
            {
                data: 'fecha_fin',
                "width": "10%",
                "searchable": true,

            },
            {
                data: 'eliminado',
                "width": "10%",
                "searchable": true,

            },
            {
                data: 'motivo_eliminado',
                "width": "10%",
                "searchable": true,

            },
        ]

    });
}

/*Funcion para el datatable Notificaciones*/
function datatableBuscarNotificaciones() {
    let institucion = $("#filtro_institucion_notificacion").val() == "" || $("#filtro_institucion_notificacion").val() == null ? 0 : $("#filtro_institucion_notificacion").val();
    let tipo = $("#filtro_tipo_notificacion").val() == "" || $("#filtro_tipo_notificacion").val() == null ? 0 : $("#filtro_tipo_notificacion").val();
    var fecha_inicio = $("#fecha_inicio").val() == "" || $("#fecha_inicio").val() == null ? "0" : $("#fecha_inicio").val();
    var fecha_fin = $("#fecha_fin").val() == "" || $("#fecha_fin").val() == null ? "0" : $("#fecha_fin").val();

    $("#dtmenu_notificaciones").dataTable({
        dom: 'lBfrtip',
        'destroy': true,
        serverSide: true,
        "ajax": "/asignacion/getDatatableNotificacionesServerSide/" + institucion + "/" + tipo + "/" + fecha_inicio + "/" + fecha_fin + "/",
        buttons: [{
            extend: "excelHtml5",
            text: '<img src="/images/icons/excel.png" width="25px" heigh="25px">Exportar Excel',
            titleAttr: "Excel",
        }, ],
        stateSave: true,
        responsive: true,
        "processing": true,
        "lengthMenu": [
            [10, 20, -1],
            [10, 20, "TODOS"]
        ],
        "lengthChange": true,
        "searching": true,
        "language": {
            "search": "Buscar",
            "lengthMenu": "Mostrar _MENU_",
            "zeroRecords": "Lo sentimos, no encontramos lo que estas buscando",
            "info": "Motrar página _PAGE_ de _PAGES_ (_TOTAL_)",
            "infoEmpty": "Registros no encontrados",
            "oPaginate": {
                "sFirst": "Primero",
                "sLast": "Último",
                "sNext": "Siguiente",
                "sPrevious": "Anterior"
            },
            "infoFiltered": "(Filtrado _TOTAL_  de _MAX_ registros totales)",
        },
        "order": [
            [0, "desc"]
        ],
        "columns": [{
                data: 'codigo',
                "width": "10%",
                "searchable": true,

            },
            {
                data: 'descripcion',
                "width": "30%",
                "searchable": true,

            },
            {
                data: 'nombre_compromiso',
                "width": "30%",
                "searchable": true,

            },
            {
                data: 'institucion',
                "width": "30%",
                "searchable": true,
            },
            {
                data: 'fecha_inicio',
                "width": "5%",
                "searchable": true,

            },
            {
                data: 'fecha_fin',
                "width": "5%",
                "searchable": true,
            },
            {
                data: 'tipo_notificacion',
                "width": "10%",
                "searchable": true,

            },
            {
                data: 'estado_gestion',
                "width": "10%",
                "searchable": true,

            }
        ]

    });
}
$("#usuario_id").on("change", function() {
    app.formCrear.usuario_id = $(this).val();
});
$("#institucion_id").on("change", function() {
    app.formCrear.institucion_id = $(this).val() != null ? $(this).val() : [];
})