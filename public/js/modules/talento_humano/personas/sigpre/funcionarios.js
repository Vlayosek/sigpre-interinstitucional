$(document).ready(function () {
    $(function () {
   //    $("body").addClass("sidebar-collapse");


    });
});
function composicion(){
    $("#nombreCompleto").val($("#apellidos").val()+' '+$("#nombres").val());
    appPerfil.formPersona.apellidos_nombres=$("#nombreCompleto").val();
}
$("#nombres").on("keyup", function() {
    composicion();
});
$("#apellidos").on("keyup", function() {
    composicion();
});
var urlDefectoMasivo = $("#direccionDocumentos").val() + '/' + 'PRODUCTOS/';
var IniciaFuncionario=0;
var datatableCargarFuncionarios='';
var tipoActual='ACT';
function datatableCargarPersonas(tipo=null) {
    ///$(".menu-pen").addClass('hidden'); // clear table
    /*$.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': '<?= csrf_token() ?>'
        }
    });*/
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    appHistorial.consultaEstados();
    if(tipo!=null)
    tipoActual=tipo;
    var filtro_identificacion=appHistorial.formFiltro.identificacion==''||appHistorial.formFiltro.identificacion==null?0:appHistorial.formFiltro.identificacion;
    var filtro=appHistorial.formFiltro.filtro==''||appHistorial.formFiltro.filtro==null?0:appHistorial.formFiltro.filtro;
    $("#dtmenuPerfil").dataTable({
        dom: 'lBrtip',
        'destroy': true,
        serverSide: true,
        "ajax": {
            "url": "/uath/getDatatablePersonasServerSide",
            "type": "POST",
            "data": {
                "tipo_actual": tipoActual,
                "filtro_identificacion": filtro_identificacion,
                "filtro": filtro
            },
         },
        buttons: [{
                extend: 'excelHtml5',
                text: '<img src="/images/icons/excel.png" width="25px" heigh="25px">Exportar Excel',
                titleAttr: 'Excel'
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
                data: 'id',
                "width": "5%",
                "searchable": true,
                "render": function (data, type, row) {
                    return "PER-"+row.id;
                }
            },
            {
                data: 'tipo_',
                "width": "5%",
                "searchable": true,

            },
            {
                data: 'civil_',
                "width": "5%",
                "searchable": true,

            },
            {
                data: 'celular',
                "width": "5%",
                "searchable": true,

            },
            {
                data: 'identificacion_',
                "width": "5%",
                "searchable": true,

            },
            {
                data: 'apellidos_nombres_',
                "width": "15%",
                "searchable": true,

            },
            {
                data: 'area_',
                "width": "12%",
                "searchable": true,
                "render": function (data, type, row) {
                    return tipoActual=="PEN"?"--":data;
                }
            },
            {
                data: 'edificio_',
                "width": "12%",
                "searchable": true,
                "render": function (data, type, row) {
                    return tipoActual=="PEN"?"--":data;
                }
            },
            /* {
                data: 'horario_',
                "width": "12%",
                "searchable": true,
                "render": function (data, type, row) {
                    return tipoActual=="PEN"?"--":data;
                }
            }, */
            {
                data: 'cargo_',
                "width": "12%",
                "searchable": true,
                "render": function (data, type, row) {
                    return tipoActual=="PEN"?"--":data;
                }
            },
            {
                data: 'denominacion_',
                "width": "12%",
                "searchable": true,
                "render": function (data, type, row) {
                    return tipoActual=="PEN"?"--":data;
                }
            },
            {
                data: 'sueldo_',
                "width": "5%",
                "searchable": true,
                "render": function (data, type, row) {
                    return tipoActual=="PEN"?"--":("$"+data);
                }
            },
            {
                data: 'fecha_ingreso',
                "width": "10%",
                "searchable": true,
                "render": function (data, type, row) {
                    return tipoActual=="PEN"?"--":row.fecha_ingreso;
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

function datatableCargarDeclaracionesJuramentadas() {

    persona_id=appPerfil.formPersona.id;

    $("#dtmenuDeclaracionesJuramentadas").dataTable({
        dom: 'lBfrtip',
        'destroy': true,
        serverSide: true,
        "ajax": "/uath/getDatatableDeclaracionesJuramentadasServerSide/" + persona_id+"/"
        ,buttons: [{
                extend: 'excelHtml5',
                text: '<img src="/images/icons/excel.png" width="25px" heigh="25px">Exportar Excel',
                titleAttr: 'Excel'
            },

        ],
        stateSave:true,
        responsive: true,
        "processing": true,
        "lengthMenu": [
            [3, 10,20],
            [3, 10,20]
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

            },
            {
                data: 'fecha_inserta',
                "width": "5%",
                "searchable": true,

            },
            {
                data: 'fecha_declaracion',
                "width": "5%",
                "searchable": true,

            },
            {
                data: 'fecha_notificacion',
                "width": "5%",
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

function datatableCargarPersonasEstudios() {

    persona_id=appPerfil.formPersona.id;

    $("#dtmenuEstudios").dataTable({
        dom: 'lBfrtip',
        'destroy': true,
        serverSide: true,
        "ajax": "/uath/getDatatableEstudiosServerSide/" + persona_id+"/"
        ,buttons: [{
                extend: 'excelHtml5',
                text: '<img src="/images/icons/excel.png" width="25px" heigh="25px">Exportar Excel',
                titleAttr: 'Excel'
            },

        ],
        stateSave:true,
        responsive: true,
        "processing": true,
        "lengthMenu": [
            [3, 10,20],
            [3, 10,20]
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
            [3, "desc"]
        ],
        "columns": [
            {
                data: 'instruccion',
                "width": "5%",
                "searchable": true,

            },
            {
                data: 'titulo',
                "width": "20%",
                "searchable": true,

            },
            {
                data: 'institucion',
                "width": "40%",
                "searchable": true,

            },

            {
                data: 'numero_referencia',
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

function datatableCargarPersonasCursos() {

    persona_id=appPerfil.formPersona.id;

    $("#dtmenuCursos").dataTable({
        dom: 'lBfrtip',
        'destroy': true,
        serverSide: true,
        "ajax": "/uath/getDatatableCursosServerSide/" + persona_id+"/"
        ,buttons: [{
                extend: 'excelHtml5',
                text: '<img src="/images/icons/excel.png" width="25px" heigh="25px">Exportar Excel',
                titleAttr: 'Excel'
            },

        ],
        stateSave:true,
        responsive: true,
        "processing": true,
        "lengthMenu": [
            [3, 10,20],
            [3, 10,20]
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
            [2, "desc"]
        ],
        "columns": [
            {
                data: 'tipo_capacitacion',
                "width": "5%",
                "searchable": true,

            },
            {
                data: 'nombre',
                "width": "20%",
                "searchable": true,

            },


            {
                data: 'anio',
                "width": "10%",
                "searchable": true,

            },

            {
                data: 'numero_horas',
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
function datatableCargarPersonasDiscapacidad() {

    persona_id=appPerfil.formPersona.id;

    $("#dtmenuPersonasDiscapacidad").dataTable({
        dom: 'lBfrtip',
        'destroy': true,
        serverSide: true,
        "ajax": "/uath/getDatatablePersonasDiscapacidadServerSide/" + persona_id+"/"
        ,buttons: [{
                extend: 'excelHtml5',
                text: '<img src="/images/icons/excel.png" width="25px" heigh="25px">Exportar Excel',
                titleAttr: 'Excel'
            },

        ],
        stateSave:true,
        responsive: true,
        "processing": true,
        "lengthMenu": [
            [3, 10,20],
            [3, 10,20]
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
            [3, "desc"]
        ],
        "columns": [

            {
                data: 'nombre',
                "width": "20%",
                "searchable": true,

            },


            {
                data: 'numero_carnet',
                "width": "10%",
                "searchable": true,

            },

            {
                data: 'porcentaje',
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
function datatableCargarPersonasEnfermedad() {

    persona_id=appPerfil.formPersona.id;

    $("#dtmenuPersonasEnfermedad").dataTable({
        dom: 'lBfrtip',
        'destroy': true,
        serverSide: true,
        "ajax": "/uath/getDatatablePersonasEnfermedadServerSide/" + persona_id+"/"
        ,buttons: [{
                extend: 'excelHtml5',
                text: '<img src="/images/icons/excel.png" width="25px" heigh="25px">Exportar Excel',
                titleAttr: 'Excel'
            },

        ],
        stateSave:true,
        responsive: true,
        "processing": true,
        "lengthMenu": [
            [5, 10,20],
            [5, 10,20]
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
                data: 'nombre',
                "width": "20%",
                "searchable": true,

            },


            {
                data: 'fecha_diagnostico',
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

var editor;


function htmlEditor(id_valor,valor,tipo=null,dataHtml='',valor_id=0,select2=true){
    if(dataHtml==''){
        if(tipo.indexOf("fecha")!=-1)
        var html='<input type="date" class="editar_'+id_valor+' hidden" value="'+valor+'" id="'+tipo+'_'+id_valor+'">';
        else
        var html='<input type="text" class="editar_'+id_valor+' hidden" value="'+valor+'" id="'+tipo+'_'+id_valor+'">';
    }
    else{
        if(select2){
            var html='<div class="editar_'+id_valor+' hidden"><select class="form-control select2 editar_select form-control-sm" onchange="onChangeSelect(this)" id="'+tipo+'_'+id_valor+'">';
            html+=dataHtml
            html+='</select></div>';
            $('#'+tipo+'_'+id_valor+'').val(valor_id).change();
        }else{
            var html='<div class="editar_'+id_valor+' hidden"><select class="form-control form-control-sm" onchange="onChangeSelect(this)" id="'+tipo+'_'+id_valor+'">';
            html+=dataHtml
            html+='</select></div>';
            $('#'+tipo+'_'+id_valor+'').val(valor_id).change();
        }


    }

    html+='<span class="campo_'+id_valor+'" >'+valor+'</span>';
    return html;
}
function editarHistorial(id){
    $(".editar_"+id+"").removeClass("hidden");
    $(".campo_"+id+"").addClass("hidden");
   $('.editar_select').select2();
   appPerfil.editarHistorial(id);
}
function guardarHistorial(id,persona_id){
    cerrarEditarHistorial(id);
    appPerfil.guardarHistorial(id,persona_id);
}
function cerrarEditarHistorial(id){
    $(".editar_"+id+"").addClass("hidden");
    $(".campo_"+id+"").removeClass("hidden");
}
function datatableCargasFamiliares() {

    persona_id=appPerfil.formPersona.id;

    $("#dtmenuCargasFamiliares").dataTable({
        dom: 'lBfrtip',
        'destroy': true,
        serverSide: true,
        "ajax": "/uath/getDatatableCargasFamiliares/" + persona_id+"/"
        ,buttons: [{
                extend: 'excelHtml5',
                text: '<img src="/images/icons/excel.png" width="25px" heigh="25px">Exportar Excel',
                titleAttr: 'Excel'
            },

        ],
        stateSave:true,
        responsive: true,
        "processing": true,
        "lengthMenu": [
            [3, 10,20],
            [3, 10,20]
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
                data: 'parentesco',
                "width": "5%",
                "searchable": true,

            },
            {
                data: 'identificacion',
                "width": "20%",
                "searchable": true,

            },
            {
                data: 'apellidos_nombres',
                "width": "20%",
                "searchable": true,

            },

            {
                data: 'fecha_nacimiento',
                "width": "10%",
                "searchable": true,

            },
            {
                data: 'fecha_nacimiento',
                "width": "10%",
                "searchable": true,
                "render": function (data, type, row) {
                    return calcular_edad_perfil(row.fecha_nacimiento);
                }
            },
            {
                data: 'carnet_conadis',
                "width": "10%",
                "searchable": true,

            },

            {
                data: 'enfermedad_catastrofica',
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
function onChangeSelect(eve){
 //  alert($(eve).val());
}
$("#provincia_id_perfil").on("change",function(){
    appPerfil.formPersona.provincia_id;
    appPerfil.cargarCanton($(this).val());
});
$("#canton_id_perfil").on("change",function(){
    appPerfil.formPersona.canton_id;
});


function changeEnfermedad(){
    appPerfil.formFamiliar.enfermedad_catastrofica=$("#enfermedad_catastrofica").val()==null?'':$("#enfermedad_catastrofica").val();
}
function changeEnfermedadBienestar(){
    appPerfil.formEnfermedad.nombre=$("#formBienestar_catastrofica_nombre").val()==null?'':$("#formBienestar_catastrofica_nombre").val();
}
function agregarComboCanton(id,descripcion,seleccion=false){
    if(seleccion==true)
    $("#canton_id_perfil").append("<option value=''>SELECCIONE UNA OPCION</option>");
    else
    $("#canton_id_perfil").append("<option value='" + id + "'>" + descripcion + "</option>");
}


$("#fecha_nacimiento_perfil").on("change",function(){
    document.querySelector("#edad_perfil").innerHTML=calcular_edad_perfil($(this).val());
});
function calcular_edad_perfil(value){
    var anios='';
    if(value!=null&&value!=''){
        let suEdad = edad(value);

        if(suEdad)
        anios=(`${suEdad[0]} año(s), ${suEdad[1]} mes(es) y ${suEdad[2]} día(s).`);
         else
        anios='';
    }

    return anios;
}
