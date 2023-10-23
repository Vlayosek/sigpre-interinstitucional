$(document).ready(function(){
    $(function () {
        $("#Modalagregar").hide();
        changeDatatable();
    });
});




$("#btnGuardar").on('click', function () {


        var save="save";
        PedirConfirmacion('0',save);


});



function PedirConfirmacion(id,dato)
{
    swal({ title:                "¿Estás seguro de realizar esta accion?",
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
                swal("¡Cancelado!","No se registraron cambios...","error");
            }
        });
}
function EditChanges(id,name,slug,parent,order,descripcion)
{
        $("#var").val(id);
        $("#optionid").val(parent).change();
        $("#descripcion").val(descripcion);
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
        descripcion:    $("#descripcion").val(),
        url:    $("#url").val(),
        var:    $("#var").val()
    }, 'post');
    objApiRest.extractDataAjax(function (_resultContent) {
        if (_resultContent.status == 200) {
            alertToastSuccess(_resultContent.message, 3500);
            $("#btnCancelar").click();
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
  //  modal.style.display = "block";
    $('#optionid').val(0).change();
    $("#descripcion").html(null);
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
            dom: 'lBfrtip',

                buttons: [
                {
                    extend:    'excelHtml5',
                    text:      '<img src="/images/icons/excel.png" width="25px" heigh="20px">',
                    titleAttr: 'Excel'
                },
             
                {
                    extend:    'pdfHtml5',
                    text:      '<img src="/images/icons/pdf.png" width="25px" heigh="20px">',
                    titleAttr: 'PDF',
                    orientation: 'landscape',
                    exportOptions: {
                        columns: [0, 1,2,3] //exportar solo la primera y segunda columna
                    },
                },
            ],
                responsive: true,"oLanguage":
                    {
                        "sUrl": "/js/config/datatablespanish.json"
                    },
                "lengthMenu": [[5,10,20, -1], [5,10,20, "All"]],
                "order": [[ 1, 'desc' ]],
                "searching": true,
                "info":  false,
                "ordering": true,
                "bPaginate": true,
                "processing": true,
                "serverSide": true,
                "deferRender": true,
                "destroy": true,
                stateSave:true,
                "ajax": "/admin/datatable-menu/",
                "columns":[

                    {
                        data: 'modulo',
                        "width": "10%",
                        "bSortable": true,
                        "searchable": true,
                        "targets": 0,
                        "render": function (data, type, row) {
                            return row.modulo!=null?row.modulo.toUpperCase():'--';
                        }
                    },
                    {
                        data: 'name',
                        "width": "10%",
                        "bSortable": true,
                        "searchable": true,
                        "targets": 0,
                        "render": function (data, type, row) {
                            return row.name.toUpperCase();
                        }
                    },
                    {data: 'slug',   "width": "20%"},
                    {data: 'descripcion',   "width": "50%"},
                    {
                        data: 'actions',
                        "width": "10%",
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
