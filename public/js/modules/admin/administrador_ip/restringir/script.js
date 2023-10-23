$(document).ready(function () {
    $(function () {
        datatableCargarIpsRestringir();
    });
});

var num = 0;
function datatableCargarIpsRestringir() {
    this.tableHistorico = $("#dtmenuIpsRestringir").dataTable({
        dom: 'lfrtip',
        'destroy': true,
        serverSide: true,
        "ajax": "/ips/getDatatableRestringirIpServerSide/",
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
                "width": "1%",
                "searchable": true,
            },
            {
                data: 'usuario_nombres',
                "width": "3%",
                "searchable": true,
            },
            {
                data: 'ip_restringida',
                "width": "8%",
                "searchable": true,

            },
            {
                data: 'tipo',
                "width": "8%",
                "searchable": true,

            },
            {
                data: 'seccion',
                "width": "8%",
                "searchable": true,

            },
            {
                data: '',
                "width": "4%",
                "searchable": true,
            },
        ]
         
    });
}
$("#filtro_usuario").on('change', function() {
    app.formIpRestringido.usuario_id =  $(this).val()!=null?$(this).val():0;
});
$("#filtro_ip_restringidas").on('change', function() {
    app.formIpRestringido.ips =  $(this).val()!=null?$(this).val():0;
});