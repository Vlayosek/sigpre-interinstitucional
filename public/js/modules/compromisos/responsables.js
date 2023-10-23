var cargaVariables=0;
var cargaVariablesCo=0;

var arregloResponsables=[];
var arregloCoResponsables=[];

$("#institucion_id").on("change",function(){
    var tipo="institucion";
    if(cargaVariables==0)
    getCargaDatosInstitucion(tipo,$(this).val());
});

$("#responsable_id").on("change",function(){
    var tipo="responsable";
    if(cargaVariables==0)
    getCargaDatosInstitucion(tipo,$(this).val());
});
$("#instituciones_corresponsables").on("change",function(){
        app.formCrear.instituciones_corresponsables=$(this).val()==null?[]:$(this).val();
});
$("#gabinete_id").on("change",function(){
    app.formCrear.gabinete_id=$(this).val()==null?'':$(this).val();
});

function resetear(){
    $("[name='ubicacion_']").each(function () {  this.checked = false;   });
    $("[name='ul_ubicaciones']").addClass("hidden");
    $(".abrirplus").removeClass("minus");
    $(".abrirplus").addClass("plus");
    cargaVariables=1;
    cargaVariablesCo=1;
    $(".selector_gestion").val(null).change();
    cargaVariables=0;
    cargaVariablesCo=0;
    app.limpiarForm();
    $("#estado_porcentaje_id").val(1).change();
    $("#estado_id").val(1).change();
    app.linkNav = 0;
    $("#link_inicial").click();
    destroyPeriodos();
}
function resetCombo(){
    cargaVariables=1;
    cargaVariablesCo=1;
    $("#responsable_id").val(null).change();
    $("#delegado_id").val(null).change();
    $("#institucion_id").val(null).change();
    $("#monitor_id").val(null).change();
    $("#gabinete_id").val(null).change();
    app.formCrear.responsable_id='';
    app.formCrear.delegado_id='';
    app.formCrear.institucion_id='';
    app.formCrear.monitor_id='';
    app.formCrear.gabinete_id='';
    $("#instituciones_corresponsables").val(null).change();
    app.formCrear.instituciones_corresponsables=[];
    cargaVariables=0;
    cargaVariablesCo=0;
}
function getCargaDatosInstitucion(tipo,id,monitor_id=null){
    var objApiRest = new AJAXRest('/compromisos/getCargaDatosInstitucion', {
        id: id,
        tipo:tipo
    }, 'post');
    objApiRest.extractDataAjax(function (_resultContent) {
        if (_resultContent.status == 200) {
            arregloResponsables=_resultContent.datos;

            cargaVariables=1;
            limpiarCombos();
            if(arregloResponsables!=null){
                var identifica="institucion_id";
                var id=arregloResponsables.id;
                var value=arregloResponsables.nombre.toUpperCase();
                $("#"+identifica+"").html('');
                getResponsable(identifica,id,value);

                $("#"+identifica+"").val(id).change();
                app.formCrear.institucion_id=id;

                var identifica="gabinete_id";
                var gabinete_id__=arregloResponsables.gabinete!=null?arregloResponsables.gabinete.id:null
                $("#"+identifica+"").val(gabinete_id__).change();
                app.formCrear.gabinete_id=gabinete_id__!=null?gabinete_id__:'';

                $.each(arregloResponsables.usuarios_ministro, function (_key, _value)
                {
                    var identifica="responsable_id";
                    var id=_value.id;
                    var value=_value.nombres.toUpperCase();
                    $("#"+identifica+"").html('');
                         getResponsable(identifica,id,value);
                   app.formCrear.responsable_id=id;
                    $("#"+identifica+"").val(id).change();

                });
                var identifica="monitor_id";
                if(monitor_id==null){
                    $.each(arregloResponsables.usuarios_monitor, function (_key, _value)
                    {
                            app.formCrear.monitor_id=_value.usuario==null?'':_value.usuario.id;
                            $("#"+identifica+"").val(app.formCrear.monitor_id).change();
                    });
                }
                else{
                    app.formCrear.monitor_id=monitor_id;
                    $("#"+identifica+"").val(monitor_id).change();
                }
                $.each(arregloResponsables.delegado, function (_key, _value)
                {
                    var identifica="delegado_id";
                    $("#"+identifica+"").val(_value.id).change();
                    app.formCrear.delegado_id=_value.id;
                });
            }

            cargaVariables=0;
        } else {
            alertToast(_resultContent.message, 3500);
        }
    });

}
function limpiarCombos(){
    $("#responsable_id").val(null).change();
    $("#delegado_id").val(null).change();
    $("#institucion_id").val(null).change();
    $("#monitor_id").val(null).change();
    $("#gabinete_id").val(null).change();
    app.formCrear.responsable_id=null;
    app.formCrear.delegado_id=null;
    app.formCrear.institucion_id=null;
    app.formCrear.monitor_id=null;
    app.formCrear.gabinete_id=null;
}
function limpiarCombosCorresponsables(){
    $("#instituciones_corresponsables").val(null).change();
    app.formCrear.instituciones_corresponsables=[];

}

function getResponsable(identifica,id,value){
            $("#"+identifica+"").append("<option value='" + id + "'>" + value + "</option>")
}

/*
$("#instituciones_corresponsables").on("change",function(){
    var tipo="institucion";
    if(cargaVariablesCo==0)
    getCargaDatosInstitucionCorresponsables(tipo,$(this).val());
});

$("#corresponsables_id").on("change",function(){
    var tipo="responsable";
    if(cargaVariablesCo==0)
    getCargaDatosInstitucionCorresponsables(tipo,$(this).val());
});*/
function getCargaDatosInstitucionCorresponsables(tipo,id){
    id=id==null?[]:id;
    var objApiRest = new AJAXRest('/compromisos/getCargaDatosInstitucionCorresponsables', {
        id: id,
        tipo:tipo
    }, 'post');
    objApiRest.extractDataAjax(function (_resultContent) {
        if (_resultContent.status == 200) {
            arregloCoResponsables=_resultContent.datos;
            cargaVariablesCo=1;
            limpiarCombosCorresponsables();
            $("#instituciones_corresponsables").html('');
            if(arregloCoResponsables!=null){
                var arregloChangeInstitucion=[];
                $.each(arregloCoResponsables, function (_key, _value){
                    var identifica="instituciones_corresponsables";
                    var id=_value.id;
                    var value=_value.nombredata.toUpperCase();
                    arregloChangeInstitucion.push(_value.id);
                    getResponsable(identifica,id,value);
                });
                $("#instituciones_corresponsables").val(arregloChangeInstitucion).change();

            }
           cargaVariablesCo=0;
        } else {

            alertToast(_resultContent.message, 3500);
        }
    });

}
