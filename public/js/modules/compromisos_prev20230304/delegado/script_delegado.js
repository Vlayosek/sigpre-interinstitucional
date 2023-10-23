$(document).ready(function() {

    $(function() {
        /*ABRIR PANTALLLA , CERRANDO MENU*/
        $("body").addClass("sidebar-collapse");

        datatableCargarDelegado();
    });
});

function resetarModal() {
    $("#usuario_id").html('');
    $("#institucion_id").html('');
    $("#usuario_id").val(null).change();
    $("#institucion_id").val(null).change();
}
var tipoActual = 'data';

function datatableCargarDelegado() {
    $("#dtmenuDelegadosInstitucion").dataTable({
        dom: 'lfrtip',
        'destroy': true,
        serverSide: true,
        "ajax": "/compromisos/getDatatableDelegadoInstitucionServerSide/",
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
            },
            {
                data: 'identificacion',
                "width": "10%",
                "searchable": true,

            },
            {
                data: 'nombres',
                "width": "10%",
                "searchable": true,

            },
            {
                data: 'institucion',
                "width": "40%",
                "searchable": true,
            },
            {
                data: 'cargo',
                "width": "40%",
                "searchable": true,
            },
            {
                data: 'telefono',
                "width": "40%",
                "searchable": true,
            },
            {
                data: 'celular',
                "width": "40%",
                "searchable": true,
            },
            {
                data: '',
                "width": "10%",
                "searchable": true,

            },
        ]

    });
}
$("#filtro_institucion").on("change", function() {
    app.formDelegado.institucion_id = $(this).val() != null ? $(this).val() : '';
});