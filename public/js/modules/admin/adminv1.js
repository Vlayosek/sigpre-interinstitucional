$(document).ready(function(){
    $(function () {
        $("#Modalagregar").hide();
        changeDatatable();
    });
});



function ChangeListTrabTitulacion(idcarrera,idplectivo) {
    $("#cmbEstudiante").html('');
    if (this.value != '')
    {
        var objApiRest = new AJAXRest('/titulacion/complexivo/data-student/'+idcarrera+'/' + idplectivo, {}, 'POST');
        objApiRest.extractDataAjax(function (_resultContent, status)
        {
            if (status == 200)
            {
                $("#cmbEstudiante").append("<option value='' selected='selected'> * SELECCIONE ESTUDIANTE *</option>");
                $.each(_resultContent.data, function (_key, _value)
                {
                    $("#cmbEstudiante").append("<option value='" + _value.CODIGO + "'>" + _value.DESCRIPCION + "</option>")
                });
            }
            else
            {
                alertToast(_resultContent.message, 3000);
            }
        })
    }
}



$("#btnGuardar").on('click', function () {


        var save="save";
        PedirConfirmacion('0',save);


});



function PedirConfirmacion(id,dato)
{
    swal({ title:                "�Estás seguro de realizar esta accion?",
            text:                "Al confirmar se grabaran los datos exitosamente",
            type:                "warning",
            showCancelButton:    true,
            confirmButtonColor:  "#DD6B55",
            confirmButtonText:   "Si!",
            cancelButtonText:    "No",
            closeOnConfirm:      true,
            closeOnCancel:       false },
        function(isConfirm)
        {
            if (isConfirm)
            {
                switch(dato)
                {
                    case "save":
                        SaveChanges();
                        break;

                    case "delete":
                        DeleteChanges(id);
                        break;
                }
            } else {
                swal("�Cancelado!","No se registraron cambios...","error");
            }
        });
}
function EditChanges(id,name,slug,parent,order)
{
    modal.style.display = "block";
        $("#var").val(id);
        $("#optionid").val(parent).change();
        $("#name").val(name);
        $("#url").val(slug);
        $("#prefix").val(order);
}

function DeleteChanges(id)
{
    var objApiRest = new AJAXRest('/admin/MenuEliminar', {id:id
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
function SaveChanges() {
    var objApiRest = new AJAXRest('/admin/SaveOpcion', {
        optionid:    $("#optionid").val(),
        name:    $("#name").val(),
        prefix:    $("#prefix").val(),
        url:    $("#url").val(),
        var:    $("#var").val()
    }, 'post');
    objApiRest.extractDataAjax(function (_resultContent) {
        if (_resultContent.status == 200) {
            alertToastSuccess(_resultContent.message, 3500);
            limpiar();
            $("#Modalagregar").hide();
            $('body').removeClass('modal-open');
            $('.modal-backdrop').remove();
            changeDatatable();


        } else {
            alertToast(_resultContent.message, 3500);
            changeDatatable();

        }
    });
}
function limpiar() {
    modal.style.display = "block";
    $('#optionid').val(0).change();
    $("#name").val('');
    $("#prefix").val(0);
    $("#url").val('');
    $("#var").val(0);

}
function changeDatatable()
{
    $('#dtmenu').DataTable().destroy();
    $('#tbobymenu').html('');

        $('#dtmenu').show();
        $.fn.dataTable.ext.errMode = 'throw';
        $('#dtmenu').DataTable(
            {
                responsive: true,"oLanguage":
                    {
                        "sUrl": "/js/config/datatablespanish.json"
                    },
                "lengthMenu": [[5,10,20 -1], [5,10,20, "All"]],
                "order": [[ 1, 'desc' ]],
                "searching": true,
                "info":  false,
                "ordering": false,
                "bPaginate": true,
                "processing": true,
                "serverSide": true,
                "deferRender": true,
                "destroy": true,
                "ajax": "/admin/datatable-menu/",
                "columns":[

                    {data: 'name', "width": "20%"},
                    {data: 'slug',   "width": "60%"},
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
