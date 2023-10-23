$(document).ready(function () {
    $(function () {
        datatableCargarIpsAdministrar();
    });
});

var num = 0;
function datatableCargarIpsAdministrar() {
    this.tableHistorico = $("#dtmenuIpsAdministrar").dataTable({
        dom: 'lfrtip',
        'destroy': true,
        serverSide: true,
        "ajax": "/ips/getDatatableAdministrarIpServerSide/",
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
                data: 'ips_',
                "width": "3%",
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
$("#tipo_ip").on('change', function() {
    app.formIp.tipo = $('#tipo_ip option:selected').text();
});
$("#seccion_ip").on('change', function() {
    app.formIp.seccion = $('#seccion_ip option:selected').text();
});
function validarIP(ip) {
    var patronIp = new RegExp("^(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$");
    let text_ip = document.getElementById('ip_registrar_');
    var octeto = (ip.split(".")).length;
    if (octeto!=4) text_ip.style.color = 'red';
    else{
        if(ip.search(patronIp) !== 0) text_ip.style.color = 'red';
        else text_ip.style.color = 'green';
    }
}