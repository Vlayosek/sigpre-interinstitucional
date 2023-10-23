


    $(function () {
        /*ABRIR PANTALLLA , CERRANDO MENU*/
        $("body").addClass("sidebar-collapse");

        datatableCargar();
    });
function resetarModal(){
    $("#usuario_id").html('');
    $("#institucion_id").html('');
    $("#usuario_id").val(null).change();
    $("#institucion_id").val(null).change();
}
var tipoActual='data';
function datatableCargar() {
    $("#dtmenu").dataTable({
        dom: 'lfrtip',
        'destroy': true,
        serverSide: true,
        "ajax": "/agenda_territorial/getDatatableAsignacionMonitorServerSide/" ,
        stateSave:true,
        responsive: true,
        "processing": true,
        "lengthMenu": [
            [ 10, 20, -1],
            [ 10, 20, "TODOS"]
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
        "columns": [
            {
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
                "render": function (data, type, row) {
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
$("#usuario_id").on("change",function() {
    app.formCrear.usuario_id=$(this).val();
});
$("#institucion_id").on("change",function() {
    app.formCrear.institucion_id=$(this).val()!=null?$(this).val():[];
});
