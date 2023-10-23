/**
 * Created by blacksato on 19/5/2017.
 */

$(document).ready(function(){
    $("form").on("submit", function(){
        $("#pageLoader").fadeIn();

    });//submit



    /*var idleState = false;
    var idleTimer = null;
    $('*').bind('mousemove click mouseup mousedown keydown keypress keyup submit change mouseenter scroll resize dblclick', function () {
        clearTimeout(idleTimer);
        idleState = false;
        idleTimer = setTimeout(function () {
            $('#logout-form').submit();
            idleState = true; }, (60000*15));
    });
    $("body").trigger("mousemove");
    timeNow();*/



});//document ready

function alertConfirmDelete(ptext, url) {
    swal({
            title: "Confirmaci\u00F3n de Eliminaci\u00F3n",
            text: "Realmente desea eliminar " + ptext+"?",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
           confirmButtonText: "Aceptar",
            cancelButtonText: "Cancelar",
            closeOnConfirm: true,
            closeOnCancel: true
        },
        function (isConfirm) {
            if (isConfirm) {
                alertToastSuccess("Ejecutando proceso de eliminaci\xf3n",3500);
                location.href = url;
            } else {
                alertToast("Acci\xf3n cancelada",3500);
            }
        });
}


function alertConfirmAction(ptext, url) {
    swal({
            title: "Confirmaci\u00F3n de Acciones",
            text: "Realmente desea realizar el proceso de " + ptext,
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Aceptar",
            cancelButtonText: "Cancelar",
            closeOnConfirm: false,
            closeOnCancel: true
        },
        function (isConfirm) {
            if (isConfirm) {
                location.href = url;
            }
        });
}


function showLoading() {
    $("#pageLoader").fadeIn();
}

function hideLoading() {
    $("#pageLoader").fadeOut();
}

function alertToast(description,time){
    new PNotify({
        icon:'icon-notification2',
        title: 'Notificaci\u00F3n',
        text: description,
        addclass: 'alert alert-warning alert-styled-right',
        type: 'error',
        delay:time
    });

    // Materialize.toast(description,time);
}
function alertToastSuccess(description,time){
    new PNotify({
        icon:'icon-notification2',
        title: 'Notificaci\u00F3n',
        text: description,
        addclass: 'alert alert-primary alert-styled-right',
        type: 'info',
        delay:time
    });

}

function valueSelect(_name,_value){
    $("#"+_name).select2("destroy");
    $("#"+_name).val(_value);
    $("#"+_name).select2({
        language: "es",
        width: '100%'
    });
}

