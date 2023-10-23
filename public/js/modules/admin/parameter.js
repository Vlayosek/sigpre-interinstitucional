$(document).ready(function () {
    $(function () {
        $("#Modalagregar").hide();
        changeDatatable();
    });
});


$("#btnGuardar").on('click', function () {

    var save = "save";
    PedirConfirmacion('0', '0', save);
});


function PedirConfirmacion(id, parameter, dato) {
    swal({
            title: "¿Estás seguro de realizar esta accion?",
            text: "Al confirmar se grabaran los datos exitosamente",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Si!",
            cancelButtonText: "No",
            closeOnConfirm: true,
            closeOnCancel: false
        },
        function (isConfirm) {
            if (isConfirm) {
                switch (dato) {
                    case "save":
                        SaveChanges();
                        break;

                    case "delete":
                        DeleteChanges(id, parameter);
                        break;
                }
            } else {
                swal("¡Cancelado!", "No se registraron cambios...", "error");
            }
        });
}

function EditChanges(id, name, estado, parameter, verificacion, nivel_id, area_id, categoria_id, principal_listado_id, tipo_lista_primaria, secundario_listado_id, tipo_lista_secundaria) {
    $("#var").val(id);
    $("#father").val(parameter).change();
    $("#optionid").val(estado).change();
    $("#verificacion").val(verificacion).change();
    $("#name").val(name);
    $("#niveles").val(nivel_id).change();
    $("#categorias").val(categoria_id).change();
    $("#areas").val(area_id).change();
    $("#principal_listado_id").val(principal_listado_id).change();
    $("#tipo_lista_primaria").val(tipo_lista_primaria).change();
    $("#tipo_lista_secundaria").val(tipo_lista_secundaria).change();
    $("#secundario_listado_id").val(secundario_listado_id).change();
}

function DeleteChanges(id, parameter) {
    var objApiRest = new AJAXRest('/admin/ParameterEliminar', {
        id: id,
        parameter: parameter
    }, 'post');
    objApiRest.extractDataAjax(function (_resultContent) {
        if (_resultContent.status == 200) {
            alertToastSuccess(_resultContent.message, 3500);
            limpiar();
            changeDatatable();
        } else {
            alertToast(_resultContent.message, 3500);
        }
    });

}

function SaveChanges() {
    var objApiRest = new AJAXRest('/admin/SaveParameter', {
        optionid: $("#optionid").val(),
        name: $("#name").val(),
        father: $("#father").val(),
        var: $("#var").val(),
        verificacion: $("#verificacion").val(),
        nivel_id: $("#niveles").val(),
        area_id: $("#areas").val(),
        categoria_id: $("#categorias").val(),
        principal_listado_id: $("#principal_listado_id").val(),
        secundario_listado_id: $("#secundario_listado_id").val(),
        tipo_lista_primaria: $("#tipo_lista_primaria").val(),
        tipo_lista_secundaria: $("#tipo_lista_secundaria").val()
    }, 'post');
    objApiRest.extractDataAjax(function (_resultContent) {
        if (_resultContent.status == 200) {
            alertToastSuccess(_resultContent.message, 3500);
            limpiar();
            changeDatatable();

        } else {
            alertToast(_resultContent.message, 3500);
        }
    });
}

function limpiar() {
    $('#optionid').val(0).change();
    $("#name").val('');
    $("#father").val(0).change();
    $("#var").val(0);
    $("#niveles").val('').change();
    $("#areas").val('').change();
    $("#categorias").val('').change();
    $("#verificacion").val('').change();
    $("#principal_listado_id").val('').change();
    $("#tipo_lista_primaria").val('SIMPLE').change();
    $("#tipo_lista_secundaria").val('SIMPLE').change();
    $("#secundario_listado_id").val('').change();

}

function changeDatatable() {
    $('#dtmenu').DataTable().destroy();
    $('#tbobymenu').html('');

    $('#dtmenu').show();
    $.fn.dataTable.ext.errMode = 'throw';
    $('#dtmenu').DataTable({
        dom: 'lfrtip',
      
        responsive: true,
        "oLanguage": {
            "sUrl": "/js/config/datatablespanish.json"
        },
        "lengthMenu": [
            [5, -1],
            [5, "All"]
        ],
        "order": [
            [1, 'desc']
        ],
        "searching": true,
        "info": false,
        "ordering": false,
        "bPaginate": true,
        "processing": true,
        "serverSide": true,
        "deferRender": true,
        "destroy": true,
        "ajax": "/admin/datatable-parameter/",
        "columns": [

            {
                data: 'name',
                "width": "50%"
            },
            {
                data: 'padre',
                "width": "50%"
            },
            {
                data: 'estado',
                "width": "20%",
                "bSortable": false,
                "searchable": false,
                "targets": 0,
                "render": function (data, type, row) {
                    return $('<div />').html(row.estado).text();
                }
            },
            {
                data: 'actions',
                "width": "20%",
                "bSortable": false,
                "searchable": false,
                "targets": 0,
                "render": function (data, type, row) {
                    return $('<div />').html(row.actions).text();
                }
            }
        ],

    }).ajax.reload();


}
