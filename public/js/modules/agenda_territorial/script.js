var editor='';

   
    $(function () {
        $("body").addClass("sidebar-collapse");
        datatableCargar();
        $("#dtmenuAntecedentes").dataTable({
         dom: 'rtip',
        });
        $("#dtmenuArchivos").dataTable({
            dom: 'rtip',
           });
           $("#dtmenuHistorico").dataTable({
            dom: 'lfrtip',
           });
           $("#dtmenuMensajes").dataTable({
            dom: 'lfrtip',
           });
           $("#dtmenuObjetivos").dataTable({
            dom: 'lfrtip',
           });
           $("#dtmenuPeriodos").dataTable({
            dom: 'lfrtip',
           });
           $("#dtmenuUbicacion").dataTable({
            dom: 'lfrtip',
           });
           $("#dtmenuAvances").dataTable({
            dom: 'lfrtip',
           });
            $('.time').datetimepicker({
                format: 'LT'
            });
    });
$("#tipo_id").on("change",function(){
    var valida_inaguracion_=$("#tipo_id option:selected").attr("data-abv")
    if(valida_inaguracion_=='PRI'){
        app.inauguracion_complementaria=false;
        app.inauguracion_principal=true;
       }
      
       if(valida_inaguracion_=='COM'){
        app.inauguracion_complementaria=true;
        app.inauguracion_principal=false;
       }
    
});
$("#gabinete_id_exportar").on("change",function(){
    app.onChangeInstitucion();
    
});
function resetearDatatable(){
    app.formCrear.id=0;
    var dt = {
        draw: 1,
        recordsFiltered: 0,
        recordsTotal: 0,
        data: []
    };
    datatableCargarUbicaciones();

    $("#dtmenuAntecedentes").dataTable({
        dom: 'lfrtip',
        buttons: [
            {
                extend:    'excelHtml5',
                text:      '<img src="/images/icons/excel.png" width="25px" heigh="20px">',
                titleAttr: 'Excel'
            },
         
        ],
        "lengthMenu": [[15,30, -1], [15,30, "TODOS"]],
        "lengthChange": true,
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": false,
        "draw": dt.draw,
        "destroy":true,
        "recordsFiltered": dt.recordsFiltered,
        "recordsTotal": dt.recordsTotal,
        "data": dt.data,
        "order": [[0, "desc"]],
        "language": {
            "search":"Buscar",
            "lengthMenu": "Mostrar _MENU_",
            "zeroRecords": "Lo sentimos, no encontramos lo que estas buscando",
            "info": "Motrar página _PAGE_ de _PAGES_",
            "infoEmpty": "Registros no encontrados",
            "oPaginate": {
                "sFirst":    "Primero",
                "sLast":     "Último",
                "sNext":     "Siguiente",
                "sPrevious": "Anterior"
            },
            "infoFiltered": "(Filtrado en MAX registros totales)",
            },
        "columnDefs": [
            { "targets": [0], "orderable": true }
        ],
        "columns": [
            {
                data: 'numero',
                "width": "5%",
                "searchable": true,

            },
            {
                data: 'fecha_antecedente',
                "width": "5%",
                "searchable": true,

            },
            {
                data: 'descripcion',
                "width": "12%",
                "searchable": true,

            },
            {
                data: '',
                "width": "15%",
                "searchable": true,

            },
        ] 
    });
    $("#dtmenuArchivos").dataTable({
        dom: 'lfrtip',
        buttons: [
            {
                extend:    'excelHtml5',
                text:      '<img src="/images/icons/excel.png" width="25px" heigh="20px">',
                titleAttr: 'Excel'
            },
         
        ],
        "lengthMenu": [[15,30, -1], [15,30, "TODOS"]],
        "lengthChange": true,
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": false,
        "draw": dt.draw,
        "destroy":true,
        "recordsFiltered": dt.recordsFiltered,
        "recordsTotal": dt.recordsTotal,
        "data": dt.data,
        "order": [[0, "desc"]],
        "language": {
            "search":"Buscar",
            "lengthMenu": "Mostrar _MENU_",
            "zeroRecords": "Lo sentimos, no encontramos lo que estas buscando",
            "info": "Motrar página _PAGE_ de _PAGES_",
            "infoEmpty": "Registros no encontrados",
            "oPaginate": {
                "sFirst":    "Primero",
                "sLast":     "Último",
                "sNext":     "Siguiente",
                "sPrevious": "Anterior"
            },
            "infoFiltered": "(Filtrado en MAX registros totales)",
            },
        "columnDefs": [
            { "targets": [0], "orderable": true }
        ],
        "columns": [
            {
                data: 'created_at',
                "width": "5%",
                "searchable": true,

            },
        
            {
                data: 'nombre',
                "width": "12%",
                "searchable": true,

            },
            {
                data: 'usuario.nombres',
                "width": "12%",
                "searchable": true,
            
            },
            {
                data: 'institucion',
                "width": "12%",
                "searchable": true,

            },
          
            {
                data: 'fecha_revisa',
                "width": "12%",
                "searchable": true,
                "render": function (data, type, row) {

                    var html='--';
                    if(row.usuario_leido!=null)
                    html=row.fecha_revisa;
                    return html;
                }
            },
            {
                data: 'id',
                "width": "12%",
                "searchable": true,
                "render": function (data, type, row) {
                    var html='--';
                    if(row.usuario_leido!=null)
                    html=row.usuario_leido.nombres;
                    return html;
                }
            },
            {
                data: 'institucion_leida',
                "width": "12%",
                "searchable": true,

            },
            {
                data: '',
                "width": "15%",
                "searchable": true,

            },
        ] 
    });
    $("#dtmenuHistorico").dataTable({
        dom: 'lfrtip',
        buttons: [
            {
                extend:    'excelHtml5',
                text:      '<img src="/images/icons/excel.png" width="25px" heigh="20px">&nbps;Excel',
                titleAttr: 'Excel'
            },
        ],
        "lengthMenu": [[15,30, -1], [15,30, "TODOS"]],
        "lengthChange": true,
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": false,
        "draw": dt.draw,
        "destroy":true,
        "recordsFiltered": dt.recordsFiltered,
        "recordsTotal": dt.recordsTotal,
        "data": dt.data,
        "order": [[0, "desc"]],
        "language": {
            "search":"Buscar",
            "lengthMenu": "Mostrar _MENU_",
            "zeroRecords": "Lo sentimos, no encontramos lo que estas buscando",
            "info": "Motrar página _PAGE_ de _PAGES_",
            "infoEmpty": "Registros no encontrados",
            "oPaginate": {
                "sFirst":    "Primero",
                "sLast":     "Último",
                "sNext":     "Siguiente",
                "sPrevious": "Anterior"
            },
            "infoFiltered": "(Filtrado en MAX registros totales)",
            },
        "columnDefs": [
            { "targets": [0], "orderable": true }
        ],
        "columns": [
            {
                data: 'fecha',
                name:'sc_compromisos.transacciones.created_at',
                "width": "5%",
                "searchable": true,

            },
            {
                data: 'descripcion',
                name:'sc_compromisos.transacciones.descripcion',

                "width": "12%",
                "searchable": true,

            },
            {
                data: 'usuario',
                name:'core.users.nombres',

                "width": "12%",
                "searchable": true,

            },
            {
                data: 'institucion',
                name:'core.instituciones.descripcion',

                "width": "12%",
                "searchable": true,

            },
        ] 
    });
    $("#dtmenuMensajes").dataTable({
        dom: 'lfrtip',
        buttons: [
            {
                extend:    'excelHtml5',
                text:      '<img src="/images/icons/excel.png" width="25px" heigh="20px">',
                titleAttr: 'Excel'
            },
         
        ],
        "lengthMenu": [[15,30, -1], [15,30, "TODOS"]],
        "lengthChange": true,
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": false,
        "draw": dt.draw,
        "destroy":true,
        "recordsFiltered": dt.recordsFiltered,
        "recordsTotal": dt.recordsTotal,
        "data": dt.data,
        "order": [[0, "desc"]],
        "language": {
            "search":"Buscar",
            "lengthMenu": "Mostrar _MENU_",
            "zeroRecords": "Lo sentimos, no encontramos lo que estas buscando",
            "info": "Motrar página _PAGE_ de _PAGES_",
            "infoEmpty": "Registros no encontrados",
            "oPaginate": {
                "sFirst":    "Primero",
                "sLast":     "Último",
                "sNext":     "Siguiente",
                "sPrevious": "Anterior"
            },
            "infoFiltered": "(Filtrado en MAX registros totales)",
            },
        "columnDefs": [
            { "targets": [0], "orderable": true }
        ],
        "columns": [
            {
                data: 'created_at',
                "width": "5%",
                "searchable": true,

            },
            {
                data: 'descripcion',
                "width": "12%",
                "searchable": true,

            },
            {
                data: 'usuario.nombres',
                "width": "12%",
                "searchable": true,
            
            },
            {
                data: 'institucion',
                "width": "12%",
                "searchable": true,

            },
          
            {
                data: 'fecha_revisa',
                "width": "12%",
                "searchable": true,
                "render": function (data, type, row) {

                    var html='--';
                    if(row.usuario_leido!=null)
                    html=row.fecha_revisa;
                    return html;
                }
            },
            {
                data: 'id',
                "width": "12%",
                "searchable": true,
                "render": function (data, type, row) {
                    var html='--';
                    if(row.usuario_leido!=null)
                    html=row.usuario_leido.nombres;
                    return html;
                }
            },
            {
                data: 'institucion_leida',
                "width": "12%",
                "searchable": true,
              

            },
            {
                data: '',
                "width": "12%",
                "searchable": true,

            },
        ] 
    });
    $("#dtmenuObjetivos").dataTable({
        dom: 'lfrtip',
        buttons: [
            {
                extend:    'excelHtml5',
                text:      '<img src="/images/icons/excel.png" width="25px" heigh="20px">',
                titleAttr: 'Excel'
            },
         
        ],
        "lengthMenu": [[15,30, -1], [15,30, "TODOS"]],
        "lengthChange": true,
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": false,
        "draw": dt.draw,
        "destroy":true,
        "recordsFiltered": dt.recordsFiltered,
        "recordsTotal": dt.recordsTotal,
        "data": dt.data,
        "order": [[0, "desc"]],
        "language": {
            "search":"Buscar",
            "lengthMenu": "Mostrar _MENU_",
            "zeroRecords": "Lo sentimos, no encontramos lo que estas buscando",
            "info": "Motrar página _PAGE_ de _PAGES_",
            "infoEmpty": "Registros no encontrados",
            "oPaginate": {
                "sFirst":    "Primero",
                "sLast":     "Último",
                "sNext":     "Siguiente",
                "sPrevious": "Anterior"
            },
            "infoFiltered": "(Filtrado en MAX registros totales)",
            },
        "columnDefs": [
            { "targets": [0], "orderable": true }
        ],
        "columns": [
            {
                data: 'numero',
                "width": "5%",
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
                data: 'meta',
                "width": "5%",
                "searchable": true,

            },
            {
                data: 'temporalidad.descripcion',
                "width": "7%",
                "searchable": true,

            },
            
            {
                data: 'objetivo',
                "width": "10%",
                "searchable": true,

            },
            {
                data: 'descripcion',
                "width": "10%",
                "searchable": true,

            },
            {
                data: '',
                "width": "20%",
                "searchable": true,

            },
          
        ] 
    });
    $("#dtmenuAvances").dataTable({
        dom: 'lfrtip',
        buttons: [
            {
                extend:    'excelHtml5',
                text:      '<img src="/images/icons/excel.png" width="25px" heigh="20px">',
                titleAttr: 'Excel'
            },
         
        ],
        "lengthMenu": [[15,30, -1], [15,30, "TODOS"]],
        "lengthChange": true,
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": false,
        "draw": dt.draw,
        "destroy":true,
        "recordsFiltered": dt.recordsFiltered,
        "recordsTotal": dt.recordsTotal,
        "data": dt.data,
        "order": [[0, "desc"]],
        "language": {
            "search":"Buscar",
            "lengthMenu": "Mostrar _MENU_",
            "zeroRecords": "Lo sentimos, no encontramos lo que estas buscando",
            "info": "Motrar página _PAGE_ de _PAGES_",
            "infoEmpty": "Registros no encontrados",
            "oPaginate": {
                "sFirst":    "Primero",
                "sLast":     "Último",
                "sNext":     "Siguiente",
                "sPrevious": "Anterior"
            },
            "infoFiltered": "(Filtrado en MAX registros totales)",
            },
        "columnDefs": [
            { "targets": [0], "orderable": true }
        ],
        "columns": [
            {
                data: 'numero',
                "width": "5%",
                "searchable": true,

            },
            {
                data: 'created_at',
                "width": "5%",
                "searchable": true,

            },
        
            {
                data: 'descripcion',
                "width": "5%",
                "searchable": true,

            },
            {
                data: 'usuario.nombres',
                "width": "5%",
                "searchable": true,
            
            },
            {
                data: 'institucion',
                "width": "5%",
                "searchable": true,

            },
          
            {
                data: 'fecha_revisa',
                "width": "5%",
                "searchable": true,
                "render": function (data, type, row) {

                    var html='--';
                    if(row.usuario_leido!=null)
                    html=row.fecha_revisa;
                    return html;
                }
            },
            {
                data: 'id',
                "width": "5%",
                "searchable": true,
                "render": function (data, type, row) {
                    var html='--';
                    if(row.usuario_leido!=null)
                    html=row.usuario_leido.nombres;
                    return html;
                }
            },
            {
                data: 'institucion_leida',
                "width": "5%",
                "searchable": true,

            },
            {
                data: 'motivo',
                "width": "5%",
                "searchable": true,
                "render": function (data, type, row) {
                    html=row.motivo!=null?row.motivo:'--';
                    return html;
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
function abrirCantones(e){
    // $(e).siblings("ul").removeClass("hidden");
    
    var id=$(e).attr('name');
  if($("#"+id+"").hasClass( "hidden" ))
    $("#"+id+"").removeClass("hidden");
    else
    $("#"+id+"").addClass("hidden");

    if($("[name="+id+"]").hasClass( "plus" )){
        $("[name="+id+"]").removeClass("plus");
        $("[name="+id+"]").addClass("minus");
    }
    else{
        $("[name="+id+"]").addClass("plus");
        $("[name="+id+"]").removeClass("minus");
    }

 }
 function abrirParroquias(e){
    var id=$(e).attr('name');
    if($("#"+id+"").hasClass( "hidden" ))
      $("#"+id+"").removeClass("hidden");
      else
      $("#"+id+"").addClass("hidden");
  
      if($("[name="+id+"]").hasClass( "plus" )){
          $("[name="+id+"]").removeClass("plus");
          $("[name="+id+"]").addClass("minus");
      }
      else{
          $("[name="+id+"]").addClass("plus");
          $("[name="+id+"]").removeClass("minus");
      }
 }

var tipoActual='data';
function datatableCargarObraComplementaria(){
    $("#dtmenuObraComplementaria").dataTable({
        dom: 'lBfrtip',
        'destroy': true,
        serverSide: true,
        "ajax": "/agenda_territorial/getDatatableCargarObraComplementaria/" + app.formCrear.id,
      //  stateSave:true,
      buttons: [
        {
            extend:    'excelHtml5',
            text:      '<img src="/images/icons/excel.png" width="25px" heigh="20px">&nbsp;Excel',
            titleAttr: 'Excel'
        },
    ],
        responsive: true,
        "processing": true,
        "lengthMenu": [
            [5, 10, 20, -1],
            [5, 10, 20, "TODOS"]
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
                "render": function (data, type, row) {
                    return 'OC-'+row.id;
                }
            },
            {
                data: 'descripcion',
                "width": "20%",
                "searchable": true,

            },
            {
                data: 'porcentaje_avance',
                "width": "12%",
                "searchable": true,

            },
            {
                data: 'responsable',
                "width": "20%",
                "searchable": true,

            },
            {
                data: '',
                "width": "5%",
                "searchable": true,

            },
        ] 
         
    });
}
function datatableCargarOrdenDia(){
  var t=  $("#dtmenuOrden").dataTable({
        dom: 'lBfrtip',
        'destroy': true,
        serverSide: true,
        "ajax": "/agenda_territorial/getDatatableCargarOrdenDia/" + app.formCrear.id,
      //  stateSave:true,
        buttons: [
            {
                extend:    'excelHtml5',
                text:      '<img src="/images/icons/excel.png" width="25px" heigh="20px">&nbsp;Excel',
                titleAttr: 'Excel'
            },
        ],
        responsive: true,
        "processing": true,
        "lengthMenu": [
            [5, 10, 20, -1],
            [5, 10, 20, "TODOS"]
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
                data: 'increment',
                "width": "12%",
                "searchable": true,
              
            },
            {
                data: 'tema',
                "width": "12%",
                "searchable": true,

            },
            {
                data: 'expositor',
                "width": "12%",
                "searchable": true,

            },
            {
                data: 'cargo',
                "width": "12%",
                "searchable": true,

            },
            {
                data: 'entidad',
                "width": "12%",
                "searchable": true,

            },
            {
                data: 'tiempo',
                "width": "12%",
                "searchable": true,

            },
            {
                data: 'informacion_complementaria',
                "width": "12%",
                "searchable": true,

            },
         

            {
                data: '',
                "width": "15%",
                "searchable": true,

            },
        ] 
         
    });
    var i = 0;

    t.api().on( 'order.dt search.dt', function () {
      
        t.api().cells(null, 0, {search:'applied', order:'applied'}).every( function (cell) {
            i=i+1;
            this.data(i);
        } );
    } ).draw();
}
function datatableCargarAntecedentes() {
    
     $("#dtmenuAntecedentes").dataTable({
        dom: 'lfrtip',
        'destroy': true,
        serverSide: true,
        "ajax": "/agenda_territorial/getDatatableAntecedentesServerSide/" + app.formCrear.id,
      //  stateSave:true,
        responsive: true,
        "processing": true,
        "lengthMenu": [
            [5, 10, 20, -1],
            [5, 10, 20, "TODOS"]
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
                data: 'numero',
                "width": "5%",
                "searchable": true,

            },
            {
                data: 'fecha_antecedente',
                "width": "5%",
                "searchable": true,

            },
            {
                data: 'descripcion',
                "width": "12%",
                "searchable": true,

            },
            {
                data: '',
                "width": "15%",
                "searchable": true,

            },
        ] 
         
    });
}
function datatableCargarArchivos() {
    $("#dtmenuArchivos").dataTable({
        dom: 'lfrtip',
        'destroy': true,
        serverSide: true,
        "ajax": "/agenda_territorial/getDatatableArchivosServerSide/" + app.formCrear.id,
      //  stateSave:true,
        responsive: true,
        "processing": true,
        "lengthMenu": [
            [5, 10, 20, -1],
            [5, 10, 20, "TODOS"]
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
                data: 'created_at',
                "width": "5%",
                "searchable": true,

            },
        
            {
                data: 'nombre',
                "width": "12%",
                "searchable": true,

            },
            {
                data: 'usuario.nombres',
                "width": "12%",
                "searchable": true,
            
            },
            {
                data: 'institucion',
                "width": "12%",
                "searchable": true,

            },
          
            {
                data: 'fecha_revisa',
                "width": "12%",
                "searchable": true,
                "render": function (data, type, row) {

                    var html='--';
                    if(row.usuario_leido!=null)
                    html=row.fecha_revisa;
                    return html;
                }
            },
            {
                data: 'id',
                "width": "12%",
                "searchable": true,
                "render": function (data, type, row) {
                    var html='--';
                    if(row.usuario_leido!=null)
                    html=row.usuario_leido.nombres;
                    return html;
                }
            },
            {
                data: 'institucion_leida',
                "width": "12%",
                "searchable": true,

            },
            {
                data: '',
                "width": "15%",
                "searchable": true,

            },
        ] 
         
    });
}

function datatableCargarHistorico() {
    $("#dtmenuHistorico").dataTable({
        dom: 'lBfrtip',
        'destroy': true,
       // serverSide: true,
        "ajax": "/agenda_territorial/getDatatableHistoricoServerSide/" + app.formCrear.id,
     //   stateSave:true,
        buttons: [
            {
                extend:    'excelHtml5',
                text:      '<img src="/images/icons/excel.png" width="25px" heigh="20px">&nbsp;Excel',
                titleAttr: 'Excel'
            },
        ],
        responsive: true,
        "processing": true,
        "lengthMenu": [
            [5, 10, 20, -1],
            [5, 10, 20, "TODOS"]
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
                data: 'fecha',
                name:'sc_compromisos.transacciones.created_at',
                "width": "5%",
                "searchable": true,

            },
            {
                data: 'descripcion',
                name:'sc_compromisos.transacciones.descripcion',

                "width": "12%",
                "searchable": true,

            },
            {
                data: 'usuario',
                name:'core.users.nombres',

                "width": "12%",
                "searchable": true,

            },
            {
                data: 'institucion',
                name:'core.instituciones.descripcion',

                "width": "12%",
                "searchable": true,

            },
          
        ] 
         
    });
}
function datatableCargarMensajes(tipo=tipoActual) {
  
     $("#dtmenuMensajes").dataTable({
        dom: 'lfrtip',
        'destroy': true,
        serverSide: true,
        "ajax": "/agenda_territorial/getDatatableMensajeServerSide/" + app.formCrear.id,
    //    stateSave:true,
        responsive: true,
        "processing": true,
        "lengthMenu": [
            [5, 10, 20, -1],
            [5, 10, 20, "TODOS"]
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
                data: 'created_at',
                "width": "5%",
                "searchable": true,

            },
            {
                data: 'descripcion',
                "width": "12%",
                "searchable": true,

            },
            {
                data: 'usuario.nombres',
                "width": "12%",
                "searchable": true,
            
            },
            {
                data: 'institucion',
                "width": "12%",
                "searchable": true,

            },
          
            {
                data: 'fecha_revisa',
                "width": "12%",
                "searchable": true,
                "render": function (data, type, row) {

                    var html='--';
                    if(row.usuario_leido!=null)
                    html=row.fecha_revisa;
                    return html;
                }
            },
            {
                data: 'id',
                "width": "12%",
                "searchable": true,
                "render": function (data, type, row) {
                    var html='--';
                    if(row.usuario_leido!=null)
                    html=row.usuario_leido.nombres;
                    return html;
                }
            },
            {
                data: 'institucion_leida',
                "width": "12%",
                "searchable": true,
              

            },
            {
                data: '',
                "width": "12%",
                "searchable": true,

            },
          
        ] 
         
    });
}
function datatableCargarObjetivos() {
  //  var id=$("#id").val();
  $("#dtmenuObjetivos").dataTable({
        dom: 'lfrtip',
        'destroy': true,
        serverSide: true,
        "ajax": "/agenda_territorial/getDatatableObjetivosServerSide/" + app.formCrear.id,
     //   stateSave:true,
        responsive: true,
        "processing": true,
        "lengthMenu": [
            [5, 10, 20, -1],
            [5, 10, 20, "TODOS"]
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
                data: 'numero',
                "width": "5%",
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
                data: 'meta',
                "width": "5%",
                "searchable": true,

            },
            {
                data: 'temporalidad.descripcion',
                "width": "7%",
                "searchable": true,

            },
            
            {
                data: 'objetivo',
                "width": "10%",
                "searchable": true,

            },
            {
                data: 'descripcion',
                "width": "10%",
                "searchable": true,

            },
            {
                data: '',
                "width": "20%",
                "searchable": true,

            },
          
        ] 
         
    });
}
function datatableCargarPeriodos(){ 
if(app.formCronograma.objetivo_id!=null&&app.formCronograma.objetivo_id!=''){
    editor= $("#dtmenuPeriodos").dataTable({
        dom: 'lfrtip',
        'destroy': true,
        serverSide: true,
        "ajax": "/agenda_territorial/getDatatablePeriodosServerSide/" + app.formCronograma.objetivo_id,
        stateSave:true,
        responsive: true,
        "processing": true,
        "lengthMenu": [
            [5,10,20,30,40,50,-1],
            [5,10,20,30,40,50,"TODOS"]
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
                data: '',
                "width": "5%",
                "searchable": true,

            },
            {
                data: 'numero',
                "width": "5%",
                "searchable": true,

            },
          
            {
                data: 'fecha_inicio_periodo',
                "width": "5%",
                "searchable": true,

            },
            {
                data: 'fecha_fin_periodo',
                "width": "5%",
                "searchable": true,

            },
            {
                data: 'temporalidad',
                "width": "5%",
                "searchable": true,

            },
            {
                data: 'descripcion_meta',
                "width": "5%",
                "searchable": true,

            },
            {
                data: 'caracterizacion',
                "width": "5%",
                "searchable": true,

            },
            {
                data: 'meta_periodo',
                "width": "5%",
                "searchable": true,

            },
            {
                data: 'cumplimiento_periodo',
                "width": "5%",
                "searchable": true,
              
            },
            {
                data: 'pendiente_periodo',
                "width": "5%",
                "searchable": true,

            },
            {
                data: 'meta_acumulada',
                "width": "5%",
                "searchable": true,
                "render": function (data, type, row) {
                    if(row.meta_acumulada!=null&&row.meta_acumulada!=0){
                        html=row.meta_acumulada;
                    }else{
                        html=row.valor_anterior_meta_acumulada;
                    }
 
                    return html;
                }
            },
            {
                data: 'cumplimiento_acumulado',
                "width": "5%",
                "searchable": true,
                "render": function (data, type, row) {
                    if(row.cumplimiento_acumulado!=null&&row.cumplimiento_acumulado!=0){
                        var cumplido=((row.cumplimiento_acumulado*100)/row.meta_acumulada).toFixed(2);
                        html='<div class="progress-group"><strong>';
                        html+=row.cumplimiento_acumulado;
                        html+='</strong>  <span class="float-right">'+cumplido+'%</span>';
                        html+='  <div class="progress progress-sm">';
                        html+='    <div class="progress-bar bg-primary" style="width: '+cumplido+'%"></div>';
                        html+='  </div>';
                        html+='</div>';
                    }else{
                        html=row.valor_anterior_cumplimiento_acumulado;
                    }
 
                    return html;
                }
            },
            {
                data: 'pendiente_acumulado',
                "width": "5%",
                "searchable": true,

            },
            
        ] 
         
    });
}else
        destroyPeriodos();
}
function datatableCargarAvances() {
    $("#dtmenuAvances").dataTable({
        dom: 'lfrtip',
        'destroy': true,
        serverSide: true,
        "ajax": "/agenda_territorial/getDatatableAvancesServerSide/" + app.formCrear.id,
     //   stateSave:true,
        responsive: true,
        "processing": true,
        "lengthMenu": [
            [ 5, 10, 20, -1],
            [  5, 10, 20, "TODOS"]
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
                data: 'numero',
                "width": "5%",
                "searchable": true,

            },
            {
                data: 'created_at',
                "width": "5%",
                "searchable": true,

            },
        
            {
                data: 'descripcion',
                "width": "5%",
                "searchable": true,

            },
            {
                data: 'usuario.nombres',
                "width": "5%",
                "searchable": true,
            
            },
            {
                data: 'institucion',
                "width": "5%",
                "searchable": true,

            },
          
            {
                data: 'fecha_revisa',
                "width": "5%",
                "searchable": true,
                "render": function (data, type, row) {

                    var html='--';
                    if(row.usuario_leido!=null)
                    html=row.fecha_revisa;
                    return html;
                }
            },
            {
                data: 'id',
                "width": "5%",
                "searchable": true,
                "render": function (data, type, row) {
                    var html='--';
                    if(row.usuario_leido!=null)
                    html=row.usuario_leido.nombres;
                    return html;
                }
            },
            {
                data: 'institucion_leida',
                "width": "5%",
                "searchable": true,

            },
            {
                data: 'motivo',
                "width": "5%",
                "searchable": true,
                "render": function (data, type, row) {
                    html=row.motivo!=null?row.motivo:'--';
                    return html;
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

function datatableCargarUbicaciones() {
    var data= new FormData();
    data.append('id',app.formCrear.id);
    var objApiRest = new AJAXRestFilePOST('/agenda_territorial/getDatatableUbicacionesServerSide',  data);
    objApiRest.extractDataAjaxFile(function (_resultContent) {
        if (_resultContent.status == 200) {
            if (_resultContent.status == 200) {
                var data=_resultContent.message;  

                $.each(_resultContent.datos, function (_key, _value)
                {
                    $("#c_bs_"+_value+"").prop("checked",true);
                    $("#c_bs_"+_value+"").closest('.inner_ul').removeClass('hidden');
                    $("#c_bs_"+_value+"").closest('.inner_ul').closest('.sub_ul').removeClass('hidden');
                    $("#c_bs_"+_value+"").closest('.inner_ul').closest('.sub_ul').closest('.main_ul').removeClass('hidden');

               //     var da=$("#c_bs_"+_value+"").closest('.inner_ul')[0].parent().parent();
                   // $("#c_bs_"+_value+"").closest('.inner_ul').closest('.sub_ul').removeClass('hidden');
                    //$("#c_bs_"+_value+"").closest('.inner_ul').closest('.sub_ul').closest('.main_ul').removeClass('hidden');

/*
                    $("#c_bs_"+_value+"").closest('.inner_ul').closest('.sub_ul').parent().parent().parent().removeClass('plus');
                    $("#c_bs_"+_value+"").closest('.inner_ul').closest('.sub_ul').parent().parent().parent().removeClass('minus');

                    $("#c_bs_"+_value+"").closest('.inner_ul').closest('.sub_ul').closest('.main_ul').parent().parent().parent().removeClass('plus');
                    $("#c_bs_"+_value+"").closest('.inner_ul').closest('.sub_ul').closest('.main_ul').parent().parent().parent().removeClass('minus');*/

                });
                var consulta=1;
                if (data.length>0) {
                    var dt = {
                        draw: 1,
                        recordsFiltered: data.length,
                        recordsTotal: data.length,
                        data: data
                    };
                } else {
                    if(_resultContent.datos_generales.length>0){
                        var dt = {
                            draw: 1,
                            recordsFiltered: 0,
                            recordsTotal: 0,
                            data: _resultContent.datos_generales
                        };
                        consulta=2;
                    }else{
                        var dt = {
                            draw: 1,
                            recordsFiltered: 0,
                            recordsTotal: 0,
                            data: []
                        };
                    }
                }   
                    if(consulta==1){
                        $('#tbobymenuUbicacion').show();
                        $.fn.dataTable.ext.errMode = 'throw';
                        $("#dtmenuUbicacion").dataTable({
                            dom: 'lBfrtip',
                            buttons: [
                                {
                                    extend:    'excelHtml5',
                                    text:      '<img src="/images/icons/excel.png" width="25px" heigh="20px">&nbsp;Excel',
                                    titleAttr: 'Excel'
                                },
                          
                            ],
                            "lengthMenu": [[15,30, -1], [15,30, "TODOS"]],
                            "lengthChange": true,
                            "searching": true,
                            "ordering": true,
                            "info": true,
                            "autoWidth": false,
                            "draw": dt.draw,
                            "destroy":true,
                            "recordsFiltered": dt.recordsFiltered,
                            "recordsTotal": dt.recordsTotal,
                            "data": dt.data,
                            "order": [[0, "desc"]],
                            "language": {
                                "search":"Buscar",
                                "lengthMenu": "Mostrar _MENU_",
                                "zeroRecords": "Lo sentimos, no encontramos lo que estas buscando",
                                "info": "Motrar página _PAGE_ de _PAGES_",
                                "infoEmpty": "Registros no encontrados",
                                "oPaginate": {
                                    "sFirst":    "Primero",
                                    "sLast":     "Último",
                                    "sNext":     "Siguiente",
                                    "sPrevious": "Anterior"
                                },
                                "infoFiltered": "(Filtrado en MAX registros totales)",
                                },
                            "columnDefs": [
                                { "targets": [0], "orderable": true }
                            ],
                            "columns": [
                                {
                                    title:"Provinca",
                                    data: 'fatherpara.fatherpara.descripcion',
                                    "width": "5%",
                                    "searchable": true,
                                },
                            
                                {
                                    title:"Ciudad",

                                    data: 'fatherpara.descripcion',
                                    "width": "12%",
                                    "searchable": true,
                    
                                },
                                {
                                    title:"Parroquia",
                                    data: 'descripcion',
                                    "width": "12%",
                                    "searchable": true,
                                   
                                },
                            ],
                        });
                    }else{
                        $('#tbobymenuUbicacion').show();
                        $.fn.dataTable.ext.errMode = 'throw';
                        $("#dtmenuUbicacion").dataTable({
                            dom: 'lBfrtip',
                            buttons: [
                                {
                                    extend:    'excelHtml5',
                                    text:      '<img src="/images/icons/excel.png" width="25px" heigh="20px">&nbps;Excel',
                                    titleAttr: 'Excel'
                                },
                                  
                                
                            ],
                            "lengthMenu": [[15,30, -1], [15,30, "TODOS"]],
                            "lengthChange": true,
                            "searching": true,
                            "ordering": true,
                            "info": true,
                            "autoWidth": false,
                            "draw": dt.draw,
                            "destroy":true,
                            "recordsFiltered": dt.recordsFiltered,
                            "recordsTotal": dt.recordsTotal,
                            "data": dt.data,
                            "order": [[0, "desc"]],
                            "language": {
                                "search":"Buscar",
                                "lengthMenu": "Mostrar _MENU_",
                                "zeroRecords": "Lo sentimos, no encontramos lo que estas buscando",
                                "info": "Motrar página _PAGE_ de _PAGES_",
                                "infoEmpty": "Registros no encontrados",
                                "oPaginate": {
                                    "sFirst":    "Primero",
                                    "sLast":     "Último",
                                    "sNext":     "Siguiente",
                                    "sPrevious": "Anterior"
                                },
                                "infoFiltered": "(Filtrado en MAX registros totales)",
                                },
                            "columnDefs": [
                                { "targets": [0], "orderable": true }
                            ],
                            "columns": [
                           
                            
                                {
                                    title:"Provincia",
                                    data: 'descripcion',
                                    "width": "12%",
                                    "searchable": true,
                    
                                },
                                {
                                    title:"Canton",
                                    data: 'descripcion',
                                    "width": "12%",
                                    "searchable": true,
                    
                                },
                                {
                                    title:"Parroquia",
                                    data: 'descripcion',
                                    "width": "12%",
                                    "searchable": true,
                    
                                },
                            ],
                        });
                    }
                 
            
                    
            }      
        } 
    });
}

function checkearCanton(e){
    //alert($(this).attr("id"));
    //var sp = $(this).attr("id");
    //if (sp.substring(0, 4) === "c_bs" || sp.substring(0, 4) === "c_bf") {
        $(e).siblings("ul").find("input[type=checkbox]").prop('checked', $(e).prop('checked'));
    //}
}

$("input[type=checkbox]").change(function () {
    var sp = $(this).attr("id");
    if (sp.substring(0, 4) === "c_io") {
        var ff = $(this).parents("ul[id^=bf_l]").attr("id");
        if ($('#' + ff + ' > li input[type=checkbox]:checked').length == $('#' + ff + ' > li input[type=checkbox]').length) {
            $('#' + ff).siblings("input[type=checkbox]").prop('checked', true);
            check_fst_lvl(ff);
        }
        else {
            $('#' + ff).siblings("input[type=checkbox]").prop('checked', false);
            check_fst_lvl(ff);
        }
    }

    if (sp.substring(0, 4) === "c_bf") {
        var ss = $(this).parents("ul[id^=bs_l]").attr("id");
        if ($('#' + ss + ' > li input[type=checkbox]:checked').length == $('#' + ss + ' > li input[type=checkbox]').length) {
            $('#' + ss).siblings("input[type=checkbox]").prop('checked', true);
            check_fst_lvl(ss);
        }
        else {
            $('#' + ss).siblings("input[type=checkbox]").prop('checked', false);
            check_fst_lvl(ss);
        }
    }
});

function check_fst_lvl(dd) {
    //var ss = $('#' + dd).parents("ul[id^=bs_l]").attr("id");
    var ss = $('#' + dd).parent().closest("ul").attr("id");
    if ($('#' + ss + ' > li input[type=checkbox]:checked').length == $('#' + ss + ' > li input[type=checkbox]').length) {
        //$('#' + ss).siblings("input[id^=c_bs]").prop('checked', true);
        $('#' + ss).siblings("input[type=checkbox]").prop('checked', true);
    }
    else {
        //$('#' + ss).siblings("input[id^=c_bs]").prop('checked', false);
        $('#' + ss).siblings("input[type=checkbox]").prop('checked', false);
    }

}
function arrayEquals(a, b) {
    return Array.isArray(a) &&
      Array.isArray(b) &&
      a.length === b.length &&
      a.every((val, index) => val.toString() === b[index]);
  }

  
$("#objetivo_id").on("change",function(){
    app.formCronograma.objetivo_id=$(this).val()==null?'':$(this).val();
    app.formCronograma.numero='--';
    //app.limpiarFormularios();
    datatableCargarPeriodos();
});
function destroyPeriodos(){
    $('#dtmenuPeriodos').DataTable().destroy();
    $('#dtbodyPeriodos').html('');
    $("#dtmenuPeriodos").dataTable({
        dom: 'lfrtip',
       });
}
function datatableCargar(tipo='data',tabla=1) {
  /*  tipoActual=tipo;
    app.tipoActual=tipoActual;
    app.tabla=tabla;
    app.getDatatableAgendaServerSide();
 */
    datatableCargarAgenda(tipo);
}
function limpiarProvinciaCiudadCanton(){
    $("#ciudad_id_exportar").val(null).change();
    $("#canton_id_exportar").val(null).change();
}
function limpiarCiudadCanton(){
    $("#canton_id_exportar").val(null).change();
}
$("#provincia_id_exportar").on("change",function(){
    limpiarProvinciaCiudadCanton();
    var x = $.grep(app.arregloProvincias, function (element, index) {
        return element.id == $("#provincia_id_exportar").val();
    });
    if(x!=null&&x!=[]&&x.length>0)
    app.arregloCanton_=x[0].lista_detalle;
    else
    app.arregloCanton_=[];

});
$("#ciudad_id_exportar").on("change",function(){
    limpiarCiudadCanton();
    var x = $.grep(app.arregloCanton_, function (element, index) {
        return element.id == $("#ciudad_id_exportar").val();
    });
    if(x!=null&&x!=[]&&x.length>0)
    app.arregloParroquia_=x[0].lista_detalle;
    else
    app.arregloParroquia_=[];

});
function datatableCargarAgenda(tipo=null){
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    if(tipo!=null)
    tipoActual=tipo;
   
    var institucion_id_exportar=$("#institucion_id_exportar").val()==""||$("#institucion_id_exportar").val()==null?"null":$("#institucion_id_exportar").val();
    var estado_id_exportar=$("#estado_id_exportar").val()==""||$("#estado_id_exportar").val()==null?"null":$("#estado_id_exportar").val();
    var provincia_id_exportar=$("#provincia_id_exportar").val()==""||$("#provincia_id_exportar").val()==null?"null":$("#provincia_id_exportar").val();
    var ciudad_id_exportar=$("#ciudad_id_exportar").val()==""||$("#ciudad_id_exportar").val()==null?"null":$("#ciudad_id_exportar").val();
    var canton_id_exportar=$("#canton_id_exportar").val()==""||$("#canton_id_exportar").val()==null?"null":$("#canton_id_exportar").val();
    var buscarAgenda=$("#buscarAgenda").val()==""||$("#buscarAgenda").val()==null?"null":$("#buscarAgenda").val();
    var fecha_inicio=$("#fecha_inicio_exportar").val()==""||$("#fecha_inicio_exportar").val()==null?"null":$("#fecha_inicio_exportar").val();
    var fecha_fin=$("#fecha_fin_exportar").val()==""||$("#fecha_fin_exportar").val()==null?"null":$("#fecha_fin_exportar").val();
        if(fecha_inicio=="null"||fecha_fin=="null"){
            alertToast("Debe colocar un rango de fecha",3500);
            return false;
        }
        var fecha1 = moment(fecha_inicio);
        var fecha2 = moment(fecha_fin);
        var fecha3=fecha2.diff(fecha1, 'days');
        if(fecha3<0&this.filtro){
            alertToast("Las fechas fin no puede ser menor a la fecha de inicio",3500);
            return false;
        }
    if(app.filtro==true){
        app.currentTab=0;
        tipoActual='data';
        $("#buscarAgenda").val(null);
    }
    $("#dtmenuAgenda").dataTable({
        dom: 'lBrtip',
        'destroy': true,
        serverSide: true,
        "ajax": {
            "url": "/agenda_territorial/getDatatableAgendaServerSidePOST",
            "type": "POST",
            "data": {
                "estado": tipoActual,
                "asignaciones": app.asignaciones,
                "filtro": app.filtro,
                "fecha_inicio": fecha_inicio,
                "fecha_fin": fecha_fin,
                "institucion_id_exportar": institucion_id_exportar,
                "estado_id_exportar":estado_id_exportar,
                "provincia_id_exportar":provincia_id_exportar,
                "ciudad_id_exportar":ciudad_id_exportar,
                "canton_id_exportar":canton_id_exportar,
                "buscar":buscarAgenda
            },
         },
        buttons: [
          {
              extend: 'excelHtml5',
              text: '<img src="/images/icons/excel.png" width="25px" heigh="25px">Exportar Excel',
              titleAttr: 'Excel',
              exportOptions: {
                columns: [0,1,2,3,4,5,6,7,8,9,10,11,13,14,15] //exportar solo la primera y segunda columna
              }
            },
            {
                extend:    'pdfHtml5',
                text:      '<img src="/images/icons/pdf.png" width="25px" heigh="20px">Exportar PDF',
                titleAttr: 'PDF',
                orientation: 'landscape',
                title:'AGENDA REGISTRADA',
                footer: true,
                pageSize: 'A4',
                exportOptions: {
                    columns: [0, 1, 2,3,4,5,6,7,8,9,10,11,13,14,15] //exportar solo la primera y segunda columna
                },
                customize: function (doc) {
                 
                    doc['styles'] = {
                        userTable: {
                            margin: [10, 40, 10, 15]
                        },
                        tableHeader: {
                            bold: !0,
                            fontSize: 11,
                            color: 'black',
                            fillColor: '#17a2b8',
                            alignment: 'center'
                        }
                    };
                    doc.content[1].layout = "Borders";

                    doc.content.splice(1, 0, {
                        margin: [10, 10, 10, 2],
                        alignment: 'center',
                        image: 'data:image/png;base64,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'
                    });
                    // Set page margins [left,top,right,bottom] or [horizontal,vertical]
                    doc.pageMargins = [20, 10, 40, 80];
                    // Set the font size fot the entire document
                    doc.defaultStyle.fontSize = 5;
                    // Set the fontsize for the table header
                    doc.styles.tableHeader.fontSize = 5;
                    // Create a header object with 3 columns
                    doc['message']='Arc';
                    var today = new Date();
                    var dd = today.getDate();
                    var mm = today.getMonth() + 1; //January is 0!
                    var yyyy = today.getFullYear();
                    if (dd < 10) {
                        dd = '0' + dd
                    }
                    if (mm < 10) {
                        mm = '0' + mm
                    }
                    var today = mm + '/' + dd + '/' + yyyy;

                    var objFooter = {};
                    objFooter['alignment'] = 'center';
                    var objLayout = {};
                    objLayout['hLineWidth'] = function (i) {
                        return .5;
                    };
                    objLayout['vLineWidth'] = function (i) {
                        return .5;
                    };
                    objLayout['hLineColor'] = function (i) {
                        return '#aaa';
                    };
                    objLayout['vLineColor'] = function (i) {
                        return '#aaa';
                    };
                    objLayout['paddingLeft'] = function (i) {
                        return 4;
                    };
                    objLayout['paddingRight'] = function (i) {
                        return 4;
                    };
                    doc.content[0].layout = objLayout;
                    doc["footer"] = function (currentPage, pageCount) {
                        var footer = [
                           
                            {
                                alignment: 'left',
                                stack: [
                                 
                                    {
                                        text: today,
                                        color: 'grey',
                                        fontSize: 8,
                                        alignment: 'left',
                                        margin: [20, 2, 40, 40]
                                    }

                                ]
                            },
                            {
                                stack: [
                                 
                                    {
                                        text: 'Page ' + currentPage + " of " + pageCount,
                                        alignment: 'right',
                                        color: 'grey',
                                        fontSize: 8,
                                        margin: [0, 2, 20, 0]
                                    }
                                ]
                            },

                        ];
                        objFooter['columns'] = footer;
                        return objFooter;

                    };
                }
            },
        ],
        //stateSave:true,
        responsive: true,
        "processing": true,
        "lengthMenu": [
            [8,20,40,-1],
            [8,20,40,"TODOS"]
        ],
        "lengthChange": true,
        "searching": true,
        "language": {
            "processing": "<i class='fa fa-spinner fa-spin fa-stack-2x fa-fw'></i>\n\
             ",
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
            [4, "desc"]
        ],
        "columns": [
          {
              data: 'reg_',
              "width": "5%",
              "searchable": true,
              "render": function (data, type, row) {
                  return row.reg_.trim();
              }
          },
          {
              data: 'fecha_inicio_',
              "width": "5%",
              "searchable": true,
          },
          {
              data: 'tipo_',
              "width": "5%",
              "searchable": true,
          },
          {
              data: 'institucion_',
              "width": "5%",
              "searchable": true, 
          },
          {
              data: 'gabinete_',
              "width": "5%",
              "searchable": true,
          },
          {
              data: 'nombre_',
              "width": "5%",
              "searchable": true            
          },
          {
              data: 'descripcion_',
              "width": "5%",
              "searchable": true,
              className:"hidden"
          },
          {
              data: 'costo_',
              "width": "5%",
              "searchable": true,
              className:"hidden",
          },
          {
              data: 'beneficiario_',
              "width": "5%",
              "searchable": true,
              className:"hidden",
          },
          {
              data: 'provincias',
              "width": "5%",
              "searchable": true,
          
          },
          {
              data: 'ciudades',
              "width": "5%",
              "searchable": true
          },
          {
              data: 'parroquias',
              "width": "5%",
              "searchable": true,
              className:"hidden"
          },
          {
            data: 'estado_porcentaje_',
            "width": "5%",
            "searchable": true
          },
          {
            data: 'coyuntura_',
            defaultContent: '',
            width: "5%",
            visible: false
          },
          {
            data: 'impacto_',
            defaultContent: '',
            width: "5%",
            visible: false
          },
          {
            data: 'observacion_',
            defaultContent: '',
            visible: false
          },
          {
            data: '',
            "width": "5%",
            "searchable": true 
          }
      ]
  });
}