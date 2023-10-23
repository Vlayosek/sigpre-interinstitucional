$(document).ready(function () {
    $(function () {
        $("#Modalagregar").hide();
        $("#id").val(0);
        changeDatatable();
    });
});
$("#identificacion").on("blur",function(){
    validar($(this));
});
function limpiar() {
    $("#id").val(0);
    $("#identificacion").val('');
    $("#nombres").val('');
    $("#apellidos").val('');
    $('#ciudad_id').val(null).change();
    $("#convencional").val('');
    $("#celular").val('');
    $("#ing_empresa").val('');
    $('#cargo').val(null).change();
    $("#direccion").val('');
    $("#email").val('');
}

function changeDatatable() {
    $('#dtmenu').DataTable().destroy();
    $('#tbobymenu').html('');

    $('#dtmenu').show();
    $.fn.dataTable.ext.errMode = 'throw';
    $('#dtmenu').DataTable(
        {
            dom: 'lBfrtip',
            buttons: [
                'colvis', 'copy', 'csv', 'excel', 'pdf', 'print'
            ],
            responsive: true, "oLanguage":
                {
                    "sUrl": "/js/config/datatablespanish.json"
                },
            "lengthMenu": [[5, -1], [5, "All"]],
            "order": [[1, 'desc']],
            "searching": true,
            "info": false,
            "ordering": false,
            "bPaginate": true,
            "processing": true,
            "serverSide": true,
            "deferRender": true,
            "destroy": true,
            "ajax": "/uath/datatableDirectorio/",
            "columns": [

                {data: 'identificacion', "width": "10%"},
                {data: 'name', "width": "25%"},
                {data: 'ciudad', "width": "10%"},
                {data: 'celular', "width": "10%"},
                {data: 'ing_empresa', "width": "10%"},
                {
                    data: 'estados',
                    "width": "5%",
                    "bSortable": false,
                    "searchable": false,
                    "targets": 0,
                    "render": function (data, type, row) {
                        return $('<div />').html(row.estados).text();
                    }
                },
                {
                    data: 'actions',
                    "width": "5%",
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

function verificaCelular() {
    var numero = $("#celular").val();
    var d10 = numero.substr(0, 2);
    if (d10 != '09') {
        alertToast("Error en numero Celular", 3500);
    }
}

function SaveChanges() {
    var errores = [];
    var listaTribunal = $('select[name^=departamento_id]').val();
    var myJsonString=[];
    if(listaTribunal!=null&&listaTribunal.length>0)
    {
        var myJsonString  = JSON.stringify(listaTribunal);
    }
    var data          = new FormData();

    var id = $("#id").val();
    var identificacion = $("#identificacion").val();
    var nombres = $("#nombres").val();
    var apellidos = $("#apellidos").val();
    var ciudad_id = $('#ciudad_id').val();
    var convencional = $("#convencional").val();
    var celular = $("#celular").val();
    var ing_empresa = $("#ing_empresa").val();
    var cargo = $('#cargo').val();
    var direccion = $("#direccion").val();
    var email = $("#email").val();
    var band = 0;

    data.append('id',   id ? id: '' );
    data.append('identificacion',   identificacion ? identificacion: '' );
    data.append('nombres',   nombres ? nombres: '' );
    data.append('apellidos',   apellidos ? apellidos: '' );
    data.append('ciudad_id',   ciudad_id ? ciudad_id: '' );
    data.append('convencional',   convencional ? convencional: '' );
    data.append('celular',   celular ? celular: '' );
    data.append('ing_empresa',   ing_empresa ? ing_empresa: '' );
    data.append('cargo',   cargo ? cargo: '' );
    data.append('direccion',   direccion ? direccion: '' );
    data.append('email',   email ? email: '' );
    data.append('band',   band ? band: '' );

    var objApiRest = new AJAXRestFilePOST('/uath/SaveDirectorio',  data);
    objApiRest.extractDataAjaxFile(function (_resultContent) {
        if (_resultContent.status == 200) {
            alertToastSuccess(_resultContent.message, 3500);
            limpiar();
            location.reload();


        } else {
            alertToast(_resultContent.message, 3500);
            changeDatatable();

        }
    });
}

function DeleteChanges(id, band) {

    var objApiRest = new AJAXRest('/uath/DirectorioEliminar', {
        id: id, band: band
    }, 'post');
    objApiRest.extractDataAjax(function (_resultContent) {
        if (_resultContent.status == 200) {
            alertToastSuccess(_resultContent.message, 3500);
            limpiar();
            $('body').removeClass('modal-open');
            $('.modal-backdrop').remove();
            changeDatatable();

        } else {
            alertToast(_resultContent.message, 3500);
            changeDatatable();

        }
    });

}

function PedirConfirmacion(id, dato) {
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
                        var band = 1;
                        DeleteChanges(id, band);
                        break;
                    case "activar":
                        var band = 0;
                        DeleteChanges(id, band);
                        break;
                }
            } else {
                swal("¡Cancelado!", "No se registraron cambios...", "error");
            }
        });
}

function EditChanges(id,identificacion, apellidos, nombres, ciudad_id, direccion, convencional, celular, ing_empresa, email, estado, cargo_id) {
    var id = $("#id").val(id);

    var identificacion = $("#identificacion").val(identificacion);
    var nombres = $("#nombres").val(nombres);
    var apellidos = $("#apellidos").val(apellidos);
    var ciudad_id = $('#ciudad_id').val(ciudad_id).change();
    var convencional = $("#convencional").val(convencional);
    var celular = $("#celular").val(celular);
    var ing_empresa = $("#ing_empresa").val(ing_empresa);
    var cargo_id = $('#cargo').val(cargo_id).change();
    var direccion = $("#direccion").val(direccion);
    var email = $("#email").val(email);

    $("#Modalagregar").show();


}

$("#btnGuardar").on('click', function () {

    var errores = [];
    var identificacion = $("#identificacion").val();
    var nombres = $("#nombres").val();
    var apellidos = $("#apellidos").val();
    var ciudad_id = $('#ciudad_id').val();
    var convencional = $("#convencional").val();
    var celular = $("#celular").val();
    var ing_empresa = $("#ing_empresa").val();
    var cargo = $('#cargo').val();
    var direccion = $("#direccion").val();
    var email = $("#email").val();


    if (identificacion.length < 1) {
        errores.push("\nidentitifacion");
    }
    if (nombres.length < 1) {
        errores.push("\nnombres");
    }
    if (apellidos.length < 1) {
        errores.push("\napellidos");
    }
 
    if (cargo.length < 1) {
        errores.push("\ncargo");
    }


    if (errores.length == 0) {
        var save = "save";
        PedirConfirmacion('0', save);

    } else {
        alertToast("Los Siguientes campos son obligatorios:" + errores + "", 3500);
    }


    //  var email = $("#email").val();
    // var correo_institucional = $("#correo_institucional").val();

});
