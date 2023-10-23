function datatableCargar(tipo='data',tabla=1) {
    tipoActual=tipo;
    this.tableHistorico = $("#dtmenu").dataTable({
        dom: 'lBfrtip',
        'destroy': true,
        serverSide: true,
        "ajax": "/agenda_territorial/corresponsable/getDatatableCompromisosServerSide/" + tipoActual+'/'+tabla+'/'+app.asignaciones+'/'+app.btnTemporal+'/'+app.btnPendientes,
        buttons: [{
                extend: 'excelHtml5',
                text: '<img src="/images/icons/excel.png" width="25px" heigh="25px">Exportar Excel',
                titleAttr: 'Excel'
            },

            {
                extend: 'pdfHtml5',
                text: '<img src="/images/icons/pdf.png" width="25px" heigh="25px">Exportar PDF',
                titleAttr: 'PDF',
                orientation: 'landscape',
                title: 'Compromisos',
                footer: true,
                pageSize: 'A4',
                className: "",
                exportOptions: {
                    columns: [0, 1, 2,3,4,5,6,7,8,9,10] //exportar solo la primera y segunda columna
                },
               
            },
        ],
        stateSave:true,
        responsive: true,
        "processing": true,
        "lengthMenu": [
            [5, 10, 20,30,40],
            [5, 10, 20,30,40]
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
                data: 'codigo',
                "width": "10%",
                "searchable": true,
                "render": function (data, type, row) {
                    html=row.codigo!=null&&row.codigo!=''?row.codigo:row.id;
                    return html;
                }
            },
            
            {
                data: 'nombre_compromiso',
                "width": "20%",
                "searchable": true,

            },
            {
                data: 'tipo.descripcion',
                "width": "10%",
                "searchable": true,

            },
            {
                data: 'latest_responsable.institucion.descripcion',
               // name: 'responsables[0].institucion.descripcion',
                "width": "10%",
                "searchable": true,
                className:"hidden",


            },
            {
                data: 'id',
                "width": "10%",
                className:"hidden",
                "searchable": true,
                "render": function (data, type, row) {
                    var html='';
                    $.each(row.corresponsables, function (_key, _value)
                    {
                        html+=(_value.institucion!=null?_value.institucion.descripcion:"")+", ";
                    })
                    return html;
                }

            },
            {
                data: 'latest_responsable.institucion.gabinete.descripcion',
                "width": "10%",
                "searchable": true,
                className:"hidden",

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
                data: 'estado_porcentaje.descripcion',
                "width": "7%",
                "searchable": true,

            },
            {
                data: 'estado.descripcion',
                "width": "7%",
                "searchable": true,

            },
            {
                data: 'avance_compromiso',
                "width": "7%",
                "searchable": true,
                className:"hidden"

            },
            {
                data: '',
                "width": "15%",
                "searchable": true,

            },
        ] 
         
    });
}