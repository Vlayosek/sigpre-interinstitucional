$(document).ready(function () {
    $(function () {
        $("body").addClass("sidebar-collapse");
        datatableCargar();
          
    });
});
var urlDefectoMasivo = $("#direccionDocumentos").val() + '/' + 'PRODUCTOS/';
var IniciaFuncionario=0;
var tipoActual='';
function datatableCargar() {
    var fecha_inicio=$("#fecha_inicio_consulta").val()==""||$("#fecha_inicio_consulta").val()==null?"null":$("#fecha_inicio_consulta").val();
    var fecha_fin=$("#fecha_fin_consulta").val()==""||$("#fecha_fin_consulta").val()==null?"null":$("#fecha_fin_consulta").val();
    this.tableHistorico = $("#dtmenu").dataTable({
        dom: 'lBfrtip',
        'destroy': true,
        serverSide: true,
        "ajax": "/personas/getDatatablePersonasServerSide/",
        buttons: [{
                extend: 'excelHtml5',
                text: '<img src="/images/icons/excel.png" width="25px" heigh="25px">Exportar Excel',
                titleAttr: 'Excel'
            },
        ],
     //   stateSave:true,
        responsive: true,
        "processing": true,
        "lengthMenu": [
            [4, 10, 20],
            [4, 10, 20]
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
                data: 'per_id',
                "width": "10%",
                "searchable": true,
                "render": function (data, type, row)
                {
                    return 'PERSONA-'+row.per_id;
                }
              
            },
            {
                data: 'per_cedula',
                "width": "10%",
                "searchable": true,
              
            },
            {
                data: 'nombre_completo',
                "width": "10%",
                "searchable": true,
              
            },
            {
                data: 'area_actual',
                "width": "10%",
                "searchable": true,
              
            },
            
            {
                data: 'estado_subusuario',
                "width": "10%",
                "searchable": true,
              
            },
            {
                data: 'estado_usuario',
                "width": "10%",
                "searchable": true,
              
            },
            {
                data: 'jefe_inmediato_',
                "width": "10%",
                "className":"",
                "searchable": false,
              
            },
            {
                data: '',
                "width": "10%",
                "searchable": true,
               
            },
        ]
         
    });
}
function datatableHistorial() {
    $("#dtmenuHistorial").dataTable({
        dom: 'lBfrtip',
        'destroy': true,
        serverSide: true,
        "ajax": "/personas/getDatatableHistorialServerSide/"+app.formCrear.per_id+"",
        buttons: [{
                extend: 'excelHtml5',
                text: '<img src="/images/icons/excel.png" width="25px" heigh="25px">Exportar Excel',
                titleAttr: 'Excel'
            },
        ],
     //   stateSave:true,
        responsive: true,
        "processing": true,
        "lengthMenu": [
            [4, 10, 20],
            [4, 10, 20]
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
                data: 'sus_id',
                "width": "10%",
                "searchable": true,
              
            },
            {
                data: 'periodo',
                "width": "10%",
                "searchable": true,
              
            },
        
            {
                data: 'area.are_nombre',
                "width": "10%",
                "searchable": true,
              
            },
            
            {
                data: 'cargo.car_nombre',
                "width": "10%",
                "searchable": true,
              
            },
            {
                data: 'fecha_desde',
                "width": "10%",
                "searchable": true,
              
            },
            {
                data: 'fecha_hasta',
                "width": "10%",
                "searchable": true,
              
            },
            
        ]
         
    });
}



