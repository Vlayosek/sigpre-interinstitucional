$(document).ready(function() {
    $(function() {
        //datatableCargarReporteMinisterio()
    });
});
var IniciaFuncionario = 0;
var tipoActual = '';

/*
function datatableCargarReporteMinisterio(tipo = null) {

    if (tipo != null)
        tipoActual = tipo;
    let fecha_inicio = $("#fecha_inicio").val() == "" || $("#fecha_inicio").val() == null ? "null" : $("#fecha_inicio").val();
    let fecha_fin = $("#fecha_fin").val() == "" || $("#fecha_fin").val() == null ? "null" : $("#fecha_fin").val();
    let institucion_filtro = $("#filtro_institucion").val() == "" || $("#filtro_institucion").val() == null ? "--" : $("#filtro_institucion").val();
    this.tableHistorico = $("#dtmenuReporteMinisterio").dataTable({
        dom: 'lBfrtip',
        'destroy': true,
        serverSide: true,
        "ajax": "/compromisos/getDatatableReporteMinisterioServerSide/" + institucion_filtro + "",
        buttons: [{
                extend: 'excelHtml5',
                text: '<img src="/images/icons/excel.png" width="25px" heigh="25px">Reporte Excel',
                titleAttr: 'Excel'
            },
            {
                extend: 'pdfHtml5',
                text: '<img src="/images/icons/pdf.png" width="25px" heigh="20px">Reporte pdf',
                titleAttr: 'PDF',
                orientation: 'landscape',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5]
                }
            },

        ],
        responsive: true,
        "processing": true,
        "lengthMenu": [
            [10, 20, 30, 40],
            [10, 20, 30, 40]
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
                data: 'codigo_compromiso',
                "width": "5%",
                "searchable": true,

            },
            {
                data: 'nombre_compromiso',
                "width": "20%",
                "searchable": true,

            },
            {
                data: 'estado_gestion',
                "width": "10%",
                "searchable": true,

            },
            {
                data: 'estado_compromiso',
                "width": "10%",
                "searchable": true,
            },
            {
                data: 'porcentaje_avance',
                "width": "5%",
                "searchable": true,
            },
            {
                data: 'avance_compromiso',
                "width": "20%",
                "searchable": true,
            },
        ]

    });
}*/