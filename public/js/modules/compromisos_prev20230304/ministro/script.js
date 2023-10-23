$(function () {
   
    $(function () {
        /*ABRIR PANTALLLA , CERRANDO MENU*/
     //   $("body").addClass("sidebar-collapse");

        datatableCargar();
       // datatableCargarGabinete();
    });
});
function resetarModal(){
    $("#institucion_id").html('');
    $("#institucion_id").val(null).change();
}
var tipoActual='data';
function datatableCargar() {
    $("#dtmenu").dataTable({
        dom: 'lfrtip',
        'destroy': true,
        serverSide: true,
        "ajax": "/comrpromisos/getDatatableUsuariosMinistrosServerSide/" ,
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
                "width": "10%",
                "searchable": true,
                //"className": "hidden",

            },
         
            {
                data: 'nombres',
                "width": "30%",
                "searchable": true,
            
            },
            {
           
                data: 'email',
                "width": "10%",
                "searchable": true,
            },
            {
           
                data: 'cargo',
                "width": "10%",
                "searchable": true,
            },
            {
           
                data: 'extension',
                "width": "10%",
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
    app.formCrear.institucion_id = $(this).val()!=null?$(this).val():'';
    //app.formDelegado.institucion_id = $('select[name="filtro_institucion_"] option:selected').text();
});
$("#ministro_usuario_id").on("change", function() {
    app.formCrear.ministro_usuario_id = $(this).val()!=null?$(this).val():'';
    //app.formDelegado.institucion_id = $('select[name="filtro_institucion_"] option:selected').text();
});