function datatableCargarNotCompromisos(tipo = null) {
    if (tipo != null) tipoActual = tipo;

    $("#dtmenuNotCompromisos").dataTable({
        dom: "lBfrtip",
        destroy: true,
        serverSide: true,
        ajax:
            "/notificaciones/getDatatableNotificacionesCompromisosServerSide/" +
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
            [10, 20, 30, 40],
            [10, 20, 30, 40],
        ],
        lengthChange: true,
        searching: true,
        language: languaje,
        order: [[0, "desc"]],
        columns: arregloDatableNotComp,
    });
}

var languaje = {
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
};

const arregloDatableNotComp = [
    {
        data: "codigo",
        width: "5%",
        searchable: true,
    },
    {
        data: "descripcion",
        width: "30%",
        searchable: true,
    },
    {
        data: "institucion",
        width: "30%",
        searchable: true,
    },
    {
        data: "compromiso",
        width: "30%",
        searchable: true,
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
];
