var tipoSolicitud='';
var bienesActivos='';
var bienesActivosSoporte='';
var categoriaTicket='';
var arregloEstadoTickets='';
var tipoTicket2='';
var soporteUsuarioTicket='';
var IDSolicitud=0;
var solicitudParaMovil=0;
var categoriaParaMovil=0;
var activadoInicioSoporte=1;
var combo1='';
var urlLocalGeneral=location.href;

  if(urlLocalGeneral.indexOf("archivo")==-1&&urlLocalGeneral.indexOf("file")==-1)
  {
    bienesActivos=document.querySelector('#Div_bienesActivos').dataset.arreglo;
    bienesActivos = JSON.parse(bienesActivos);

    tipoTicket2=document.querySelector('#Div_tipoTicket2').dataset.arreglo;
    tipoTicket2 = JSON.parse(tipoTicket2);

    arregloEstadoTickets=document.querySelector('#Div_arregloEstadoTickets').dataset.arreglo;
    arregloEstadoTickets = JSON.parse(arregloEstadoTickets);

    categoriaTicket=document.querySelector('#Div_categoriaTicket').dataset.arreglo;
    categoriaTicket = JSON.parse(categoriaTicket);

    soporteUsuarioTicket=document.querySelector('#Div_soporteUsuarioTicket').dataset.arreglo;
    soporteUsuarioTicket = JSON.parse(soporteUsuarioTicket);
    cargarCategoriaTicket();
    cargarBienesTicket();
    cargarTipoTicket();
    cargarEstadoTicket();
    cargaUsuarioTicket();
    satisfaccionPendienteModal();
}else{
  $("#mesaAyuda").addClass("hidden");
   
}

  
  $(function () {
  
    var urlLocal=location.href;
    $(".contenedor22").addClass("hidden");

    if(urlLocal.indexOf("mesaayuda")!=-1)
    $(".contenedor22").removeClass("hidden");

    var fechaFinaliza_data=new Date();
    $('.pickadate-inicio-auditoria').datepicker({
      formatSubmit: 'yyyy-mm-dd',
      format: 'yyyy-mm-dd',
      selectYears: true,
      editable: true,
      autoclose: true,
      todayHighlight: true,
      orientation: 'top'
  }).datepicker('update', new Date(fechaFinaliza_data.getFullYear(),fechaFinaliza_data.getMonth(),(fechaFinaliza_data.getDate()-fechaFinaliza_data.getDate())+1));
    $('.pickadate-fin-auditoria').datepicker({
          formatSubmit: 'yyyy-mm-dd',
          format: 'yyyy-mm-dd',
          selectYears: true,
          editable: true,
          autoclose: true,
          todayHighlight: true,
          orientation: 'top'
      }).datepicker('update', new Date());
    $('.fechaDTP').datetimepicker({
      locale: 'es',
      format: 'DD/MM/YYYY',
      maxDate: Date.now(),
      showClose: true,
      allowInputToggle: true,
      keepInvalid: true,
      ignoreReadonly: true,
      widgetPositioning: {
        horizontal: 'auto',
        vertical: 'top'
      }
      });
    $("#dtUser").dataTable({
                destroy: true,
                dom: 'lBfrtip',
                fixedHeader: true,
                scrollY: 300,
                "lengthMenu": [[-1], ["TODOS"]],
                buttons: [
                  {
                      extend:    'excelHtml5',
                      text:      '<img src="/images/icons/excel.png" width="15px" heigh="10px">Descargar',
                      titleAttr: 'Excel'
                  },
              ]

      });
      var urlLocalGeneral=urlLocal;
      if(urlLocalGeneral.indexOf("archivo")==-1&&urlLocalGeneral.indexOf("file")==-1)
      agregarAuditoria();
    });

    $('.sidebar-mini').on('webkitTransitionEnd transitionend', function (e) {
      if(e.target.className=="main-sidebar"){
            $('.table-striped tr th').each( function (i) {
              var title = $(this).text();
              if (title==""||title=="No.")
              $(this).click();
          });
      }
    }); 
   

var fechaDiariaf=new Date();
var indefinidoSolicitud=true;
$("#ffinSolicitud").attr("disabled","disabled");
$("#hfinSolicitud").attr("disabled","disabled");
var fechaDiaria = moment(fechaDiariaf).format('L');

function calcularDiasAusencia(fechaIni, fechaFin)
{

var fecha1 = new   Date(fechaIni.substring(0,4),fechaIni.substring(5,7)-1,fechaIni.substring(8,10));
var fecha2 = new Date(fechaFin.substring(0,4),fechaFin.substring(5,7)-1,fechaFin.substring(8,10));

var diasDif = fecha2.getTime() - fecha1.getTime();
var dias = Math.round(diasDif/(1000 * 60 * 60 * 24));
  if(dias>0){
    $("#cerrar").click();
  }
}
var fechalast='{{Auth::user()->last_login}}';
var today = moment().format('YYYY-MM-DD HH:mm');

calcularDiasAusencia(fechalast,today)

$('#indefinidoSolicitud').click(function() {
  indefinidoSolicitud=$("#indefinidoSolicitud").is(':checked');
  if(indefinidoSolicitud==true){
    $("#ffinSolicitud").attr("disabled","disabled");
    $("#hfinSolicitud").attr("disabled","disabled");
    $("#ffinSolicitud").val('');
    $("#hfinSolicitud").val('');
  }
  else{
    $("#ffinSolicitud").removeAttr("disabled");
    $("#hfinSolicitud").removeAttr("disabled");
  }

});
function limpiaSolicitud(){
      $('#otrosSolicitud').val('');
      $('#otrosSolicitud').val('');
      $("#ffinSolicitud").val('');
       $("#hfinSolicitud").val('');
       $("#finicioSolicitud").val('');
       $("#hinicioSolicitud").val('');
       $("#descripcionTicketSolicitud1").html('');
       $("#anydesk").val('');
      $("[name*=descripcionBien]").html('');
      $('#bienesActivosUsuarios').val('').change();
      $("[name*=comboSolicitud]").val('').change();
      $("[name*=comboSolicitud]").prop('disabled', false);
}  
inicioCreacionTicke();
function inicioCreacionTicke(){
  /////CARGAR DATOS SOLICITUD
  solicitudActiva=0;
  formularioCiudadano=0;
  $(".control-sidebar").removeClass("tamanoSolicitud");
  $("[name*='solicitudcampos']").attr('style','color:#ffffff');
  $("[name*='fechadia']").text(fechaDiaria);
  $(".control-sidebar-dark").removeClass("solicitudColor");
  $(".vinculoSolicitud").removeClass("solicitudColor");

  $("#solicitudAdicionaldiv").addClass("hidden");
  $("#creacionTicket").removeClass("hidden");
  $("#crearSolicitud").addClass("hidden");

   var blancoTipo=document.getElementById("tipoTicket1").value;
   if(blancoTipo!=""){
       var select2 = document.getElementById("tipoTicket1"), //El <select>
       value2 = select2.value, //El valor seleccionado
       text2 = select2.options[select2.selectedIndex].innerText; 
       if(text2.indexOf("SOLICITUD")!=-1)
       $("#crearSolicitud").removeClass("hidden");
       else
       $("#crearSolicitud").addClass("hidden");
   }else{
       var text2="";
   }

}
$("#categoriaTicket1").on("change",function(){
  var select2 = document.getElementById("categoriaTicket1"), //El <select>
              value2 = select2.value, //El valor seleccionado
              text2 = select2.options[select2.selectedIndex].innerText; 
  $("#categoriaTexto").html(text2);
})
/*
$("[name*='comboSolicitud']").on("change",function(){
  var data=$(this).attr('id');
  if(data!='cargarComboCategoriaSESIMPLE'&&data!='cargarComboCategoriaSEMULTIPLE'){
    var valor=$(this).val();
    var id=$(this).attr('id');
    var lista = $(this).val();
    var activa=0;
  if(tipoSolicitud=='SIMPLE'){
    var x = $.grep(combo1, function (element, index) {
                      return element.id == lista&&element.descripcion.indexOf('REDES SOCIALES')!=-1;
                  });
                  x=x.length>0?$(PendientedeRemover).removeClass("hidden"):0;
  }else{
    $.each(lista, function (_key, _value)
      {
                var x = $.grep(combo1, function (element, index) {
                      return element.id == _value&&element.descripcion.toUpperCase().indexOf('REDES SOCIALES')!=-1;
                  });
                  if(x.length>0)
                  activa=1;
                });

                if(activa==1){
                  $(PendientedeRemover).removeClass("hidden");
                  $(PendientedeRemover).val(null).change();
                }
                else
                $(PendientedeRemover).addClass("hidden");
     }
  }
 
 

/*
  if(tipoSolicitud=='SIMPLE'){
    var select2 = document.getElementById(id), //El <select>
              value2 = select2.value, //El valor seleccionado
              text2 = select2.options[select2.selectedIndex].innerText; 
  alert(text2);

  }
  if(tipoSolicitud=='MULTIPLE'){
    var lista = $(this).val();
    var json  = JSON.stringify(lista);
  alert(json);

  }

});*/

$("[name=bienesTicketSolicitud]").on("change",function(){
  var id=$(this).val()
  var x = $.grep(bienesActivos, function (element, index) {
                  return element.id == id;
           });
           var html='';
           if(x.length>0){
            x=x[0];
            html+='Nombre/Producto:'+(x.producto==null?'--':x.producto);
            html+='\n\nCaracteristicas:'+(x.descripcion==null?'--':x.descripcion);
            html+='\n\nMarca:'+(x.marca==null?'--':x.marca.descripcion);
            html+='\n\nModelo:'+(x.modelo==null?'--':x.modelo.descripcion);
            html+='\n\nIp:'+(x.direccion_ip==null?'--':x.direccion_ip);
            html+='\n\nSerie:'+(x.serie==null?'--':x.serie);
           }
           
  $("[name*=descripcionBien]").html(html); 
});
var formularioCiudadano=0;
var solicitudActiva=0;
function crearSolicitudadicional(){
  limpiaSolicitud();
  PendientedeRemover='';
  formularioCiudadano=0;
  var text=$("#categoriaTicket1").val();
  if(text=="0")
  {
    alertToast("Debe llenar el campo Categoria",3500);
    return false;
  }
  solicitudActiva=1;
              $("[name=bienesTicketSolicitud]").html('');
              $("[name=bienesTicketSolicitud]").append('<option value="0">--DISPOSITIVOS--</option>');   
              $.each(bienesActivos, function (_key, _value) {
                var rayita='';
                if(_value.marca!=null&&_value.modelo!=null)
                rayita='-';
                $("[name=bienesTicketSolicitud]").append('<option value="'+_value.id+ '">'+_value.producto+' '+(_value.marca!=null?_value.marca.descripcion:'')+(_value.modelo!=null?(rayita+_value.modelo.descripcion):'')+'</option>');
              });
              $("[name=bienesTicketSolicitud]").val(0).change();
  $(".control-sidebar-dark").addClass("solicitudColor");
  $(".vinculoSolicitud").addClass("solicitudColor");
  $(".control-sidebar").addClass("tamanoSolicitud");

  $("#solicitudAdicionaldiv").removeClass("hidden");
  $("#creacionTicket").addClass("hidden");
  $("[name*='DivcargarComboCategoria']").addClass("hidden");
  var html='';
  tipoSolicitud='';
  var x = $.grep(categoriaTicket, function (element, index) {
                      return element.id == text;
                  });
                  x=x[0];
                  if(x.listado_primario!=null)
                  {
                    $("#accionesConfiuradas").removeClass("hidden");

                    var tipo=x.tipo_lista_primaria;
                    tipoSolicitud=tipo;
                    if(x.listado_primario.lista_detalle!=null){
                      if(tipo!="FORMULARIO"){
                        
                            var placeholder2=x.listado_primario.descripcion.toUpperCase();;
                            combo1=x.listado_primario.lista_detalle;
                            if(x.listado_primario.descripcion.toUpperCase()=='USUARIOS'){
                              var usuario='{!! \App\User::where("estado","A")->get()!!}';
                                  user = JSON.parse(usuario);
                                  console.log(user);
                                  $.each(user, function (_key, _value)
                                  {
                                    html+='<option value="'+_value.id+'">';
                                    html+=(_value.nombreCompleto.split(',')[0]).replace('CN=','').toUpperCase();
                                    html+='</option>';
                                   });
                            }else{
                              $.each(x.listado_primario.lista_detalle, function (_key, _value)
                              {
                                html+='<option value="'+_value.id+'">';
                                html+=_value.descripcion.toUpperCase();
                                html+='</option>';
                              });
                            }
                           
                            $("#cargarComboCategoria"+tipo+"").html(html);
                              $("#cargarComboCategoria"+tipo+"").select2({
                                  placeholder: placeholder2
                              });
                            $("#cargarComboCategoria"+tipo+"").val("").change();
                            $("#DivcargarComboCategoria"+tipo+"").removeClass("hidden");
                            $("#otros").removeClass("hidden");
                      }
                      if(tipo=="FORMULARIO"){
                        $("#formularioSolicitud").removeClass("hidden");
                        formularioCiudadano=1;
                        $.each(x.listado_primario.lista_detalle, function (_key, _value)
                        {
                            html+='<div class="col-lg-12">';
                            html+='<br/>';                  
                            html+='</div>'; 
                            html+='<div class="col-lg-12">';
                            html+='<div class="col-lg-6">';
                            html+='<label style="color:#ffffff">'+_value.descripcion.toUpperCase()+'</label>';
                            html+=':';
                            html+='</div>';                    
                            html+='<div class="col-lg-6">';
                            html+='<input type="text" class="form-control" placeholder="'+_value.descripcion.toUpperCase()+'"name="InputformularioCiudadano">';
                            html+='</div>';      
                            html+='</div>';      
                                             
                         });
                        $("#formularioSolicitud").html(html);
                      }
                      
                    }
                    if(x.listado_secundario!=null){ 
                      var tipose=x.tipo_lista_secundaria;
                      if(x.listado_secundario.lista_detalle!=null){
                        html='';
                        if(tipose!="FORMULARIO"){
                          var placeholder2=x.listado_secundario.descripcion.toUpperCase();
                          $.each(x.listado_secundario.lista_detalle, function (_key, _value)
                          {
                            html+='<option value="'+_value.id+'">';
                            html+=_value.descripcion.toUpperCase();
                            html+='</option>';
                          });
                             $("#cargarComboCategoriaSE"+tipose+"").html(html);
                             $("#cargarComboCategoriaSE"+tipose+"").select2({
                              placeholder: placeholder2
                          });
                             $("#cargarComboCategoriaSE"+tipose+"").val("").change();
                             PendientedeRemover="DivcargarComboCategoriaSE"+tipose+"";
                            $("#"+PendientedeRemover+"").removeClass("hidden");

                        }
                        if(tipo=="FORMULARIO"){
                        $("#formularioSolicitud").removeClass("hidden");
                           formularioCiudadano=1;
                          $.each(x.listado_secundario.lista_detalle, function (_key, _value)
                          {
                            html+='<div class="col-lg-12">';
                            html+='<br/>';                  
                            html+='</div>'; 
                            html+='<div class="col-lg-12">';
                            html+='<div class="col-lg-6">';
                            html+='<label style="color:#ffffff">'+_value.descripcion.toUpperCase()+'</label>';
                            html+=':';
                            html+='</div>';                    
                            html+='<div class="col-lg-6">';
                            html+='<input type="text" class="form-control" placeholder="'+_value.descripcion.toUpperCase()+'"name="formulario'+_value.id+'">';
                            html+='</div>';      
                            html+='</div>';      
                          });
                          $("#formularioSolicitud").html(html);

                        }

                      }
                    }
                  }else{
                    $("#accionesConfiuradas").addClass("hidden");
                  }
                  
}
var PendientedeRemover='';
var idticketIniciados=0;
var arregloMensajesGenerales='';
//buscarMensajes();
$("#tipoTicket1").on("change",function(){
  $("#categoriaTicket1").val("0").change();
  var valor=$(this).val();
  if(valor!=null){
    var select2 = document.getElementById("tipoTicket1"), //El <select>
              value2 = select2.value, //El valor seleccionado
              text2 = select2.options[select2.selectedIndex].innerText; 
                
  if(text2.indexOf("SOLICITUD")!=-1 )
    $("#crearSolicitud").removeClass("hidden");
    else
    $("#crearSolicitud").addClass("hidden");
  }else
  $("#crearSolicitud").addClass("hidden");


  

});
function enviarMensaje(){
var mensaje=$("#mensajeChatTexto").val();
var ticketIniciados=$("#ticketIniciados").val();
if(mensaje==null || mensaje=='')
{
  alertToast("Porfavor llene la casilla de descripcion del mensaje para continuar",3500);
  return false;
}
  var data=new FormData()
  data.append('mensaje',mensaje);
  data.append('ticket_id',ticketIniciados);
  data.append('usuario_dirigido',idticketIniciados);

         $('#archivoTicketAnexo').each(function(a, array)
          {
            if(array.files.length==0)
            {
              data.append('archivoTicket', null);
              return false;
            }
              $.each(array.files, function (k, file)
              {
                data.append('archivoTicket[' + k + ']', file);
              })
          });
    
  var objApiRest = new AJAXRestFilePOST('/mesaayuda/GuardaMensaje',  data);
              objApiRest.extractDataAjaxFile(function (_resultContent) {
                  if (_resultContent.status == 200) {
                    $("[name='banner_captura']").val('');
                    $("#mensajeChatTexto").val('');
                    idticketIniciados=0;
                    BuscarTicket(0,0,0,0,0,ticketIniciados);
                  } 
              });
}
buscarmen=0;
function buscarMensajes(){
  $("#ticketIniciados").val(0).change();
  BuscarTicket();
}
function dataMensajeRespuesta(id,nombre){
  idticketIniciados=id;
  $("#mensajeChatTexto").val(nombre+',');

}
function precargarModalTickect(){
  var id=$("#ticketIniciados").val();
  if(id==0||id=="0")
  {
    return false;
  }
  $("#precargarModalTickectDivi").click();
  var nombre=$("#ticketIniciados").val();
  var nombre='{{Auth::user()->nombreCompleto}}';
  nombre=nombre.split(',')[0].replace('CN=','');

  cargarModalTickect(id,nombre);
}
function formarMensajes(){
  var x=arregloMensajesGenerales;
  html2='';
  $(".box-body").removeClass("chatBuild2");
  $(".direct-chat-messages").removeClass("chatBuildDirect2");

  $(".box-body").addClass("chatBuild");
  $(".direct-chat-messages").addClass("chatBuildDirect");
  
  $("#chatMe").html(html2);
  $("[name='ticketsChatmeDiv']").addClass("hidden");

  html2+='<div style="background: #f9f9f9;">';
  html2+='                          <ul class="contacts-list" id="cargaContenidoTicketsInicial">';
  $.each(x, function (_key, _value) {
    if(_value.mensajes.length>0){
      if(_value.mensajes[_value.mensajes.length-1].visto==0){
        var mensajeUltimo=_value.mensajes[_value.mensajes.length-1].descripcion;
          mensajeUltimo=mensajeUltimo.length>10?(mensajeUltimo.substring(0,9)+'...'):mensajeUltimo;
          html2+='      <li>';
          html2+='      <span onclick="cargarChatMe1(\'' + _value.id + '\')">';
          html2+='        <img class="contacts-list-img" src="/img/avatar_plusis.png" alt="Contact Avatar" style="width: 30px;">';
          html2+='        <div class="contacts-list-info">';
          html2+='          <span class="contacts-list-name" style="color:#000">';
          html2+='Ticket # '+_value.id;
          html2+='            <small class="contacts-list-date pull-right">';
          html2+=_value.created_at;
          html2+='</small>';
          html2+='          </span>';
          html2+='          <span class="contacts-list-msg">'+mensajeUltimo+'</span>';
          html2+='        </div><!-- /.contacts-list-info -->';
          html2+='      </span>';
          html2+='    </li><!-- End Contact Item -->';
      }
    }

  }); 

  html2+='                           </ul>';
  html2+='                         </div>';
  $("#chatMe").html(html2);
  $("#abreListaTicket").addClass("hidden");

}
  $("#ticketIniciados").on("change",function(){
    var id=$("#ticketIniciados").val();
    if(id!=0&&id!="0"&&id!=null&&id!="null"){
      llenadoTicketChatme(id);
      $("[name='ticketsChatmeDiv']").removeClass("hidden");
    }
 
  });
function cargarChatMe1(id){
  var s1=$("[name=director]").text().trim();
  var s2=$("[name=soporte]").text().trim();
  var s3=$("[name=asignadorTickets]").text().trim();
  var s4=$("[name=oficialSeguridad]").text().trim();

  var data=new FormData()
  data.append('id',id);
  var objApiRest = new AJAXRestFilePOST('/mesaayuda/VistoTicket',  data);
  objApiRest.extractDataAjaxFile(function (_resultContent) {
                  if (_resultContent.status == 200) {
                      if(URLactualCompleta!='ticketsasignados'&&URLactualCompleta!='ticketsasignados#')
                      BuscarTicket(0,0,0,0,0,id);
                      else
                      buscarFechaTicketEp(id);
                  }
            });
}
function cargarChatMe(id){
                        $("#ticketIniciados").val(id).change();
}
function llenadoTicketChatme(id){
  $(".box-body").removeClass("chatBuild");
  $(".direct-chat-messages").removeClass("chatBuildDirect");
 // $("[name='ticketsChatmeDiv']").removeClass("hidden");
  $("#abreListaTicket").removeClass("hidden");
  $(".box-body").addClass("chatBuild2");
  $(".direct-chat-messages").addClass("chatBuildDirect2");

  var html='';
  $("#chatMe").html(html);
if(arregloMensajesGenerales.length>0){
  var x = $.grep(arregloMensajesGenerales, function (element, index) {
                      return element.id == id;
                  });
                  x=x[0].mensajes;
                  if(x.length>0){

                  
  $.each(x, function (_key, _value) {
    var urls='<a href="'+$("#direccionDocumentos").val()+'/TICKETS/'+_value.archivo+'" target="_blank" style="float:right;color:#a94442"><i class="fa fa-paperclip"></i></a>';
    var colorDerecha='#2fa9d8';
    var colorIzquierda='#ffffff';

    var checkPalomaD='<i class="fa fa-check" style="font-size:8px;color:'+colorDerecha+'"></i>';
    var palomas1D='<p><span style="float:right">'+checkPalomaD+'</span></p>';
    var palomas2D='<p><span style="float:right">'+checkPalomaD+checkPalomaD+'</span></p>';
    
    var checkPalomaI='<i class="fa fa-check" style="font-size:8px;color:'+colorIzquierda+'"></i>';
    var palomas1I='<p><span style="float:right">'+checkPalomaI+'</span></p>';
    var palomas2I='<p><span style="float:right">'+checkPalomaI+checkPalomaI+'</span></p>';

    var urlTicketB=_value.archivo!=null?(_value.archivo==''?'':urls):'';
    var mensaje=_value.descripcion;
    var fecha=_value.created_at;
    var usuario=_value.usuario!=null?_value.usuario.nombreCompleto.split(',')[0].replace('CN=',''):'--';
    var ids='{{Auth::user()->id}}';

    /////VALIDA PALOMA

    

    //FIN VALIDA PALOMA

    var ticketCarga= 'TICKET #'+_value.ticket_id+'/';
          palomaD=palomas2D;
          palomaI=palomas2I;
        if(ids==_value.usuario_ingresa){
                if(_value.visto==0)
                  palomaD=palomas1D;

                    html+='        <div class="direct-chat-msg">';
                    html+='            <div class="direct-chat-info clearfix">';
                    html+='                <span class="direct-chat-name pull-left" style="font-size:11px">'+usuario+'</span>';
                    html+='                <span class="direct-chat-timestamp pull-right">'+fecha+'</span>';
                    html+='            </div><!-- /.direct-chat-info -->';
                    html+='            <!-- /.direct-chat-img -->';
                    html+='            <div class="direct-chat-text" style="font-size:11px">';
                    html+=ticketCarga+mensaje;
                    html+=urlTicketB;
                    html+=palomaD;
                    html+='            </div><!-- /.direct-chat-text -->';
              
                    html+='        </div><!-- /.direct-chat-msg -->';
                  }else {
                    if(_value.visto==0)
                     palomaI=palomas1I;
                    html+='        <div class="direct-chat-msg right">';
                    html+='            <div class="direct-chat-info clearfix">';
                    html+='                <span class="direct-chat-name pull-left" style="font-size:11px">'+usuario+'&nbsp;</span>';
                    html+='                <span class="direct-chat-timestamp pull-right">'+fecha+'</span>';
                    html+='            </div><!-- /.direct-chat-info -->';
                    html+='            <div class="direct-chat-text" style="font-size:11px" >';
                    html+=mensaje;
                    html+=urlTicketB;
                    html+=palomaI;

                    html+='            </div><!-- /.direct-chat-text -->';
                    html+='        </div><!-- /.direct-chat-msg -->';}
  }); }
}
  
   $("#chatMe").html(html);  
}
// on first focus (bubbles up to document), open the menu
$(document).on('focus', '.select2-selection.select2-selection--single', function (e) {
  $(this).closest(".select2-container").siblings('select:enabled').select2('open');
});

// steal focus during close - only capture once and stop propogation
$('select.select2').on('select2:closing', function (e) {
  $(e.target).data("select2").$selection.one('focus focusin', function (e) {
    e.stopPropagation();
  });
});


function votacionTicket(voto,ticket_id){
  var data=new FormData()
  data.append('voto',voto);
  data.append('ticketPendiente',ticket_id);
  var objApiRest = new AJAXRestFilePOST('/inventario/GuardaTicket',  data);
              objApiRest.extractDataAjaxFile(function (_resultContent) {
                  if (_resultContent.status == 200) {
                      alertToastSuccess(_resultContent.message,3500);
                        $("#satisfaccionTicket").addClass('hidden');
                        $("#creacionTicket").removeClass('hidden');
                        $("#CerrarModalSatisfaccion").click();
                  } else {
                        if (_resultContent.status == 100) {
                                  $("#satisfaccionTicket").removeClass('hidden');
                                  $("#creacionTicket").addClass('hidden');
                                  var html='';
                                  var x=_resultContent.message;
                                  $.each(x, function (_key, _value) {
                                    html+=' <h4 style="color:#ffffff;text-align:center"># de Ticket : '+_value.id+'</h4>';
                                    html+='   <h4 style="color:#ffffff;text-align:center">'+_value.categoria.descripcion+'</h4>';
                                    html+='   <h4 style="color:#ffffff;text-align:center">'+_value.categoria.categoria.descripcion+'</h4>';
                                    html+='   <hr/>';
                                    html+='     <h5 style="color:#ffffff;text-align:center">Para poder crear el siguiente Ticket debe calificar la atenci&oacute;n anterior:</h5>';
                                    html+='     <center><div class="ec-stars-wrapper">';
                                    html+='       <a href="#" data-value="1" title="Votar con 1 estrellas" onclick="votacionTicket(1,\'' + _value.id + '\')">&#9733;</a>';
                                    html+='       <a href="#" data-value="2" title="Votar con 2 estrellas" onclick="votacionTicket(2,\'' + _value.id + '\')">&#9733;</a>';
                                    html+='       <a href="#" data-value="3" title="Votar con 3 estrellas" onclick="votacionTicket(3,\'' + _value.id + '\')">&#9733;</a>';
                                    html+='       <a href="#" data-value="4" title="Votar con 4 estrellas" onclick="votacionTicket(4,\'' + _value.id + '\')">&#9733;</a>';
                                    html+='       <a href="#" data-value="5" title="Votar con 5 estrellas" onclick="votacionTicket(5,\'' + _value.id + '\')">&#9733;</a>';
                                    html+='     </div></center>';
                                  }); 
                                  $("#satisfaccionTicket").html(html);
                                  $(".satisfaccionTicket").html(html);

                                }else{
                                  alertToast(_resultContent.message,3500);
                                  $("#satisfaccionTicket").removeClass('hidden');
                                  $("#creacionTicket").addClass('hidden');
                                }
                  }
              });
}
var urlTicket=$("#direccionDocumentos").val()+'/'+'pdf.png';
var abiertocontrolsidebar=0;
$("#mesaAyuda").on("click",function(){
  if(abiertocontrolsidebar==0){
    abiertocontrolsidebar=1;
    $(".control-sidebar-dark").removeClass("hidden");
  }
  else{
    abiertocontrolsidebar=0;
    $(".control-sidebar-dark").addClass("hidden");
  }

})
$("[name=mesaAyuda]").on("click",function(){
  $("[name=bienesTicket]").val(0).change();
  $("[name=categoriaTicket]").html('<option value="0"> SELECCIONE UNA CATEGORIA</option>');  
  $(".kv-file-content,.file-preview-image,.kv-preview-data").attr('style','height: 80px!important;');

});

$("#descripcionTicketSolicitud1").on("keyup",function(){
  $("#descripcionTicket1").val($(this).val())
});
$('#otrosSolicitud').on("keyup",function(){
    var valor=$(this).val();
    if(valor.length>0){
      $("[name*=comboSolicitud]").prop('disabled', true);
    }
    else{
      $("[name*=comboSolicitud]").val('').change();
      $("[name*=comboSolicitud]").prop('disabled', false);
    }

});
        
$("[name=guardaTicket]").on("click",function(){
  var error=0;
  var d=$('#bienesActivosUsuarios').val();
  var f2=new Date($('#finicioSolicitud').val());
                var f1=new Date( $('#ffinSolicitud').val());
                var fecha2 = moment(f1);
                var fecha1 = moment(f2);

                var dias =fecha2.diff(fecha1, 'hours');
              
  if(solicitudActiva==1&&(dias<0&&indefinidoSolicitud!="true")){
                  alertToast("La fecha de fin debe ser posterior a la fecha de inicio",3500);
                  return false;
  }
  /*if(solicitudActiva==1&&(d=="0")){
                  alertToast("Debe seleccionar un dispositivo",3500);
                  return false;
  }*/
        var data=new FormData()
        var tipoTicket2=$("#tipoTicket1").val();
        var categoriaTicket=$("#categoriaTicket1").val();
        var descripcionTicket= $("#descripcionTicket1").val();

        if(tipoTicket2==null||tipoTicket2.length<2){
          alertToast("Debe seleccionar un tipo de Ticket",3500);
          return false;
        }
        if(categoriaTicket==null||categoriaTicket.length<2){
          alertToast("Debe seleccionar una Categoria del Ticket",3500);
          return false;
        }
        if(descripcionTicket==null|descripcionTicket==''){
           alertToast("Debe describir el ticket",3500);
          return false;
        }
        var blancoTipo=document.getElementById("tipoTicket1").value;
            if(blancoTipo!=""){
                var select2 = document.getElementById("tipoTicket1"), //El <select>
                value2 = select2.value, //El valor seleccionado
                text2 = select2.options[select2.selectedIndex].innerText; 
            }else{
                var text2="";
            }
 

        data.append('tipoTicket2',tipoTicket2);
        data.append('categoria_id',categoriaTicket);
        data.append('descripcion',descripcionTicket);
        data.append('autorizacion',text2.indexOf('ADICIONAL')!=-1?'P':'S');
        data.append('voto',0);
        ////////////ASOLICITUD DE RECURSOS ADICIONALES
        data.append('solicitudActiva',solicitudActiva);
        data.append('indefinidoSolicitud',indefinidoSolicitud);
        data.append('formularioCiudadano',formularioCiudadano);
        data.append('comboSolicitudSIMPLE', $('#otrosSolicitud').val().length>0?"null":$('#cargarComboCategoriaSIMPLE').val());
        data.append('comboSolicitudSESIMPLE',$('#otrosSolicitud').val().length>0?"null":$('#cargarComboCategoriaSESIMPLE').val());
        data.append('dispositivo', $('#bienesActivosUsuarios').val());
        data.append('otrosSolicitud', $('#otrosSolicitud').val());
        data.append('anydesk', $('#anydesk').val());
        data.append('fecha_inicio', $('#finicioSolicitud').val());
        data.append('hora_inicio', $('#hinicioSolicitud').val());
        data.append('fecha_fin', $('#ffinSolicitud').val());
        data.append('hora_fin', $('#hfinSolicitud').val());
        data.append('descripcion_bien', $('[name="descripcionBien"]').html());
        
        var k=0;
        var valornulo=$("#cargarComboCategoriaMULTIPLE").val();
        if($('#otrosSolicitud').val().length>0)
        valornulo="";
        if(valornulo!=null&&valornulo!=""&&valornulo!=[]){
          $("#cargarComboCategoriaMULTIPLE option:selected").each(function(){
            var textCombo=$(this).text();
            data.append('comboSolicitudMULTIPLE[' + k + ']', textCombo);
            k=k+1;
          });
          k=0;
        }else{
          data.append('comboSolicitudMULTIPLE', "null");
        }
        var valornulo=$("#cargarComboCategoriaSEMULTIPLE").val();
        if($('#otrosSolicitud').val().length>0)
        valornulo="";
        if(valornulo!=null&&valornulo!=""&&valornulo!=[]){
          $("#cargarComboCategoriaSEMULTIPLE option:selected").each(function(){
            var textCombo=$(this).text();
            data.append('comboSolicitudSEMULTIPLE[' + k + ']', textCombo);
            k=k+1;
          });
          k=0;
        }else{
          data.append('comboSolicitudSEMULTIPLE', "null");
        }
          $("[name='InputformularioCiudadano']").each(function(){
            var valor=$(this).val();
            data.append('InputformularioCiudadano[' + k + ']', valor);
            k=k+1;
          });
          
         ////////////ASOLICITUD DE RECURSOS ADICIONALES

          $('[name*=documentoTicket]').each(function(a, array)
          {
              $.each(array.files, function (k, file)
              {
                  data.append('documentoTicket[' + k + ']', file);
              })
          });
    

         if(text2.indexOf('ADICIONAL')!=-1&&solicitudActiva==0)
          {
              alertToast("Para Crear una Solicitud debe subir el documento de recursos adicionales",3500);
          }else{
            inicioCreacionTicke();
            $("[name=guardaTicket]").attr('disabled','disabled');

                                 $("#satisfaccionTicket").addClass('hidden');
                                  $("#creacionTicket").removeClass('hidden');
            var objApiRest = new AJAXRestFilePOST('/inventario/GuardaTicket',  data);
              objApiRest.extractDataAjaxFile(function (_resultContent) {
                  if (_resultContent.status == 200) {
                    $("[name=guardaTicket]").removeAttr('disabled');
                      limpiaSolicitud();
                      alertToastSuccess(_resultContent.message,3500);
                      var t=$(".ui-pnotify-text").text();
                        var a=t.split("#");
                        $(".ui-pnotify-text").html(a[0]+'<strong style="font-size:16px">#'+a[1]+'</strong>');
                      BuscarTicket();
                      SoporteAutorizado(5);
                  } else {
                    $("[name=guardaTicket]").removeAttr('disabled');
                    if (_resultContent.status == 100) {

                                  $("#satisfaccionTicket").removeClass('hidden');
                                  $("#creacionTicket").addClass('hidden');
                                  var html='';
                                  var x=_resultContent.message;
                                  $.each(x, function (_key, _value) {
                                    html+=' <h4 style="color:#ffffff;text-align:center"># de Ticket : '+_value.id+'</h4>';
                                    html+='   <h4 style="color:#ffffff;text-align:center">'+_value.categoria.descripcion+'</h4>';
                                    html+='   <h4 style="color:#ffffff;text-align:center">'+_value.categoria.categoria.descripcion+'</h4>';
                                    html+='   <hr/>';
                                    html+='     <h5 style="color:#ffffff;text-align:center">Para poder crear el siguiente Ticket debe calificar la atenci&oacute;n anterior:</h5>';
                                    html+='     <center><div class="ec-stars-wrapper">';
                                    html+='       <a href="#" data-value="1" title="Votar con 1 estrellas" onclick="votacionTicket(1,\'' + _value.id + '\')">&#9733;</a>';
                                    html+='       <a href="#" data-value="2" title="Votar con 2 estrellas" onclick="votacionTicket(2,\'' + _value.id + '\')">&#9733;</a>';
                                    html+='       <a href="#" data-value="3" title="Votar con 3 estrellas" onclick="votacionTicket(3,\'' + _value.id + '\')">&#9733;</a>';
                                    html+='       <a href="#" data-value="4" title="Votar con 4 estrellas" onclick="votacionTicket(4,\'' + _value.id + '\')">&#9733;</a>';
                                    html+='       <a href="#" data-value="5" title="Votar con 5 estrellas" onclick="votacionTicket(5,\'' + _value.id + '\')">&#9733;</a>';
                                    html+='     </div></center>';
                                  }); 
                                  $("#satisfaccionTicket").html(html);

                                  $(".satisfaccionTicket").html(html);
                          
                    }else{

                      alertToast(_resultContent.message,3500);
                    }
                  }
              });
          }
 
      
});
$("#cancelarModalTicket").on("click",function(){
    SoporteAutorizado("c");
});

$("[name=limpiaTicket]").on("click",function(){
    SoporteAutorizado();
});
$("[name=tipoTicketUsuario]").on("change",function(){
 $("[name=tipoTicket2]").val(0).change();
 $("#categoriaTicket1").val(0).change();
 $("[name=categoriaTicketUsuario]").val(0).change();

            $("[name=categoriaTicketUsuario]").html('');
            $("[name=categoriaTicketUsuario]").append('<option value="0"> SELECCIONE UNA CATEGORIA</option>');  
              var valorx=$(this).val();
              if(valorx!='SOPORTE')
              var x = $.grep(categoriaTicket, function (element, index) { return element.parametro_id == IDSolicitud; });
              else
              var x = $.grep(categoriaTicket, function (element, index) { return element.parametro_id != IDSolicitud; });

                  var titulos=[];
                  $.each(x, function (_key, _value) {
                    if(titulos.indexOf(_value.categoria.descripcion)==-1){
                      titulos.push(_value.categoria.descripcion);
                      if(titulos.length>1)
                      $("[name=categoriaTicketUsuario]").append('</optgroup>');
                      $("[name=categoriaTicketUsuario]").append('<optgroup label="'+_value.categoria.descripcion+'">');
                    }
                    if(_value.descripcion=='SOPORTE GENERAL')
                     categoriaParaMovil=_value.id;
                    $("[name=categoriaTicketUsuario]").append('<option value="'+_value.id+ '" tipoTicket="'+_value.parametro_id+'"><span style="font-size:8px"!important;font-weight:bold>'+_value.categoria.descripcion+'--></span>'+_value.descripcion+'</option>')
                }); 
                $("[name=categoriaTicketUsuario]").append('</optgroup>');
                
                if(activadoInicioSoporte==1){
                   activadoInicioSoporte=0;
                   $("[name=categoriaTicket]").change(categoriaParaMovil).change();
                }
               
          
});

$("[name=categoriaTicketUsuario]").on("change",function(){
  var tipoTicket = $('option:selected', this).attr('tipoTicket');
 $("[name=tipoTicket2]").val(tipoTicket).change();
 $("#categoriaTicket1").val($(this).val()).change();

});

$("[name=tipoTicket2]").on("change",function(){
  var da=$(this).val();
       $("[name=categoriaTicket]").html('<option value="0"> SELECCIONE UNA CATEGORIA</option>');  
        
          if(da!=0&&da!=null){
            $("[name=categoriaTicket]").html('');
            $("[name=categoriaTicket]").append('<option value="0"> SELECCIONE UNA CATEGORIA</option>');  
                var x = $.grep(categoriaTicket, function (element, index) {
                      return element.parametro_id == da;
                  });
                  var titulos=[];
                  $.each(x, function (_key, _value) {
                    if(titulos.indexOf(_value.categoria.descripcion)==-1){
                      titulos.push(_value.categoria.descripcion);
                      if(titulos.length>1)
                      $("[name=categoriaTicket]").append('</optgroup>');
                      $("[name=categoriaTicket]").append('<optgroup label="'+_value.categoria.descripcion+'">');
                    }
                   
                    $("[name=categoriaTicket]").append('<option value="'+_value.id+ '"><span style="font-size:8px"!important;font-weight:bold>'+_value.categoria.descripcion+'--></span>'+_value.descripcion+'</option>')
                }); 
                $("[name=categoriaTicket]").append('</optgroup>');
               // $("[name=categoriaTicket]").val(0).change();
           }
         
});
$('#CheckTicketDirector').click(function() {
  var che=$("#CheckTicketDirector").is(':checked');

});


function guardarActividadTicket(c=0){

  var bienesTicket2=$("#bienesTicket2").val();
  var categoriaTicket2=$("#categoriaTicket2").val();
  var tipoTicket2=$("#tipoTicket2").val();
  var actividadSoporteTicket=$("#actividadSoporteTicket").val();
  var idTickectVigente=$("#idTickectVigente").val();
  var idTickectVigenteSeguimiento=$("#idTickectVigenteSeguimiento").val();
  var CheckFinalizarTicket=$("#CheckFinalizarTicket").is(':checked');
  var CheckTicketDirector=$("#CheckTicketDirector").is(':checked');
  var CheckTicketOficial=$("#CheckTicketOficial").is(':checked');
  
                                          
  var archivoSoporteTicket="";
  errorer=0;



  $('#archivoSoporteTicket').each(function(a, array)
    {
    if(array.files==null)
    {
      archivoSoporteTicket=null;
    }else{
      if(array.files.length>0)
        {
            $.each(array.files, function (k, file)
            {
              archivoSoporteTicket=file;
            });
        }else{
              archivoSoporteTicket=null;
        }
    }
	
	});
  if(c==1 && (actividadSoporteTicket==""||actividadSoporteTicket==null))
  {
    alertToast("Debe llenar el campo de actividad",3500);
    return false;
  }
  
  if(CheckFinalizarTicket==true&&(tipoTicket2=="0"||tipoTicket2==""||tipoTicket2==null))
  {
    alertToast("Para Finalizar debe de tener un tipo de Ticket escogido",3500);
    return false;

  }
  if(CheckFinalizarTicket==true&&(categoriaTicket2=="0"||categoriaTicket2==""||categoriaTicket2==null))
  {
    alertToast("Para Finalizar debe de tener una Categoria escogida",3500);
    return false;

  }
  if(CheckFinalizarTicket==true&&(actividadSoporteTicket==""||actividadSoporteTicket==null))
  {
    alertToast("Para Finalizar debe de ingresar una actividad",3500);
    return false;

  }
  if(CheckTicketDirector==true&&(actividadSoporteTicket==""||actividadSoporteTicket==null))
  {
    alertToast("Para Reasignar al Director debe llenar la actividad con el motivo de la reasignacion",3500);
    return false;

  }
  var select = document.getElementById("categoriaTicket2"), //El <select>
              value = select.value, //El valor seleccionado
              text = select.options[select.selectedIndex].innerText; 

   if(CheckFinalizarTicket==true)
  {
    swal({
            title: "Estas seguro de realizar esta accion",
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
              var data= new FormData();
              data.append('bienesTicket2',bienesTicket2);
              data.append('categoriaTicket2',categoriaTicket2);
              data.append('tipoTicket2',tipoTicket2);
              data.append('actividadSoporteTicket',actividadSoporteTicket);
              data.append('idTickectVigente',idTickectVigente);
              data.append('CheckFinalizarTicket',CheckFinalizarTicket);
              data.append('asignacion_director',CheckTicketDirector);
              data.append('notificacion_oficial',CheckTicketOficial);
              data.append('idTickectVigenteSeguimiento',idTickectVigenteSeguimiento);
              data.append('cat',text);
              data.append('archivoSoporteTicket',archivoSoporteTicket);
              if(CheckFinalizarTicket==true)
              idTickectVigente=0;
              else
              idTickectVigente=idTickectVigente;
              var objApiRest = new AJAXRestFilePOST('/inventario/guardaSoporteTicket',  data);
                  objApiRest.extractDataAjaxFile(function (_resultContent) {
                    if (_resultContent.status == 200) {
                      alertToastSuccess("Se ha grabado exitosamente",3500);
                      $("[name*=estadoTicket]").val('CUR').change();
                      var s1=$("[name=director]").text().trim();
                      var CheckTicketTodos=$("#CheckTicketTodos").is(':checked');

                      if(CheckTicketDirector==true&&s1=="0")
                      BuscarTicket(1,0,0,ep,CheckTicketTodos);
                      else
                      BuscarTicket(1,idTickectVigente,0,ep,CheckTicketTodos);
                      $("[name='actividadSoporteTicket']").val('');
                      $("[name='banner_captura']").val('');
                    } else{
                      alertToast(_resultContent.message,3500);
                    }
              }); 
            } else {
                swal("Cancelado!", "No se registraron cambios...", "error");
                return false;
            }
        });
    
  }else{
    var data= new FormData();
              data.append('bienesTicket2',bienesTicket2);
              data.append('categoriaTicket2',categoriaTicket2);
              data.append('tipoTicket2',tipoTicket2);
              data.append('actividadSoporteTicket',actividadSoporteTicket);
              data.append('idTickectVigente',idTickectVigente);
              data.append('CheckFinalizarTicket',CheckFinalizarTicket);
              data.append('asignacion_director',CheckTicketDirector);
              data.append('notificacion_oficial',CheckTicketOficial);
              data.append('idTickectVigenteSeguimiento',idTickectVigenteSeguimiento);
              data.append('cat',text);
              data.append('archivoSoporteTicket',archivoSoporteTicket);
              if(CheckFinalizarTicket==true)
              idTickectVigente=0;
              else
              idTickectVigente=idTickectVigente;
              var objApiRest = new AJAXRestFilePOST('/inventario/guardaSoporteTicket',  data);
                  objApiRest.extractDataAjaxFile(function (_resultContent) {
                    if (_resultContent.status == 200) {
                      alertToastSuccess("Se ha grabado exitosamente",3500);
                      var s1=$("[name=director]").text().trim();
                      var s=$("[name=soporte]").text().trim();
                      var CheckTicketTodos=$("#CheckTicketTodos").is(':checked');

                      if(CheckTicketDirector==true){
                        if(s=="1")
                          BuscarTicket(1,0,0,ep,CheckTicketTodos);
                        if(s1=="1")
                          BuscarTicket(1,idTickectVigente,0,ep,CheckTicketTodos);

                      } 
                      else{
                          if(s=="1")
                            BuscarTicket(1,idTickectVigente,0,ep);
                          if(s1=="1")
                            BuscarTicket(1,0,0,ep);
                      } 


                      $("[name='actividadSoporteTicket']").val('');
                      $("[name='banner_captura']").val('');
                      $("[name*=estadoTicket]").val('CUR').change();

                    } else{
                      alertToast(_resultContent.message,3500);
                    }
              }); 
  }
 
}

function cargaUsuarioTicket(){
/*
  var id='{{ Auth::user()->id }}';
  var objApiRest = new AJAXRest('/inventario/usuariosSoporteTicket',
    {
        id:id,
    }, 'post');
    objApiRest.extractDataAjax(function (_resultContent) {
        if (_resultContent.status == 200) 
          soporteUsuarioTicket=_resultContent.message;
        else{
          alertToast("No cargaron todos los elementos de loa p gina porfavor ,Recargue la p gina nuevamente",3500);
          soporteUsuarioTicket=[];
        }
        
    }); */
    console.log("optimizado");
}

function cargarticketIniciados(){
          $("[name=ticketIniciados]").html('');
          $("[name=ticketIniciados]").append('<option value="0"> SELECCIONE UN TICKET</option>');   
          $("#mensajesAsignadosUsuario").addClass("hidden");
          if(arregloMensajesGenerales.length>0)
          $("#mensajesAsignadosUsuario").removeClass("hidden");
            $.each(arregloMensajesGenerales, function (_key, _value) {
              $("[name=ticketIniciados]").append('<option value="'+_value.id+'">Ticket # '+_value.id+'</option>');
            }); 
  }
function cargarEstadoTicket(){/*
  var id='{{ Auth::user()->id }}';
  var objApiRest = new AJAXRest('/inventario/estadoTicket',
    {
        id:id,
    }, 'post');
    objApiRest.extractDataAjax(function (_resultContent) {
        if (_resultContent.status == 200) {
          $("[name=estadoTicket]").html('');
          $("[name=estadoTicket]").append('<option value="0"> SELECCIONE UN ESTADO</option>');   
          arregloEstadoTickets=_resultContent.message;
            $.each(_resultContent.message, function (_key, _value) {
              $("[name=estadoTicket]").append('<option value="'+_value.abv+'">'+_value.descripcion+'</option>');
            }); 
        }else
        {
          alertToast("No cargaron todos los elementos de loa p gina porfavor ,Recargue la p gina nuevamente",3500);
          arregloEstadoTickets=[];
        } 
    }); */
    console.log("optimizado");
    $("[name=estadoTicket]").html('');
    $("[name=estadoTicket]").append('<option value="0"> SELECCIONE UN ESTADO</option>');   
      $.each(arregloEstadoTickets, function (_key, _value) {
        $("[name=estadoTicket]").append('<option value="'+_value.abv+'">'+_value.descripcion+'</option>');
      });
}
function cargarTipoTicket(){
  /*
  var id='{{ Auth::user()->id }}';
  var objApiRest = new AJAXRest('/inventario/TipoTicket',
      {
          id:id,
      }, 'post');
      objApiRest.extractDataAjax(function (_resultContent) {
          if (_resultContent.status == 200) {
              tipoTicket2=_resultContent.message;
              $("[name=tipoTicket2]").html('');
              $("[name=tipoTicket2]").append('<option value="0"> SELECCIONE UN TIPO TICKET  </option>');   
              $.each(_resultContent.message, function (_key, _value) {
                  if(_value.indexOf("SOLICITUD")!=-1)
                  IDSolicitud=_key;
                  $("[name=tipoTicket2]").append('<option value="'+_key+ '">'+_value+'</option>');
              }); 
          } else{
          alertToast("No cargaron todos los elementos de loa p gina porfavor ,Recargue la p gina nuevamente",3500);
        } 
      }); 
*/

  console.log("optimizado");
  $("[name=tipoTicket2]").html('');
  $("[name=tipoTicket2]").append('<option value="0"> SELECCIONE UN TIPO TICKET  </option>');   
  $.each(tipoTicket2, function (_key, _value) {
      if(_value.indexOf("SOLICITUD")!=-1){
        IDSolicitud=_key;
      }
      $("[name=tipoTicket2]").append('<option value="'+_key+ '">'+_value+'</option>');
  }); 
 


}
function cargarCategoriaTicket(){
  /*
  var id='{{ Auth::user()->id }}';

  var objApiRest = new AJAXRest('/inventario/CategoriaTicket',
    {
        id:id,
    }, 'post');
    objApiRest.extractDataAjax(function (_resultContent) {
        if (_resultContent.status == 200) {
            categoriaTicket=_resultContent.message;
        } else{
          alertToast("No cargaron todos los elementos de loa p gina porfavor ,Recargue la p gina nuevamente",3500);
        } 
    }); */
    console.log('optimizado');
}
function cargarBienesTicket(){
 /*
   var id='{{ Auth::user()->id }}';

    var objApiRest = new AJAXRest('/inventario/BienesTicket',
      {
          id:id,
      }, 'post');
      objApiRest.extractDataAjax(function (_resultContent) {
          if (_resultContent.status == 200) {
                 bienesActivos=_resultContent.message;
              $("[name=bienesTicket]").html('');
              $("[name=bienesTicket]").append('<option value="0"> SELECCIONE UN BIEN  </option>');   
              $.each(_resultContent.message, function (_key, _value) {
                var rayita='';
                if(_value.marca!=null&&_value.modelo!=null)
                rayita='-';
                $("[name=bienesTicket]").append('<option value="'+_value.id+ '">'+(_value.producto!=null?_value.producto:'S/I')+' '+(_value.marca!=null?_value.marca.descripcion:'')+(_value.modelo!=null?(rayita+_value.modelo.descripcion):'')+'</option>');
              }); 
          } else{
            alertToast("Recargue la pagina porfavor",3500);
        } 
      }); */
     console.log('optimizado');
     $("[name=bienesTicket]").html('');
     $("[name=bienesTicket]").append('<option value="0"> SELECCIONE UN BIEN  </option>');   
     $.each(bienesActivos, function (_key, _value) {
       var rayita='';
       if(_value.marca!=null&&_value.modelo!=null)
       rayita='-';
       $("[name=bienesTicket]").append('<option value="'+_value.id+ '">'+(_value.producto!=null?_value.producto:'S/I')+' '+(_value.marca!=null?_value.marca.descripcion:'')+(_value.modelo!=null?(rayita+_value.modelo.descripcion):'')+'</option>');
     }); 
}

resetImgTicket(urlTicket);
  $(".sidebar-toggle").on("click",function(e){
   var a=$(".sidebar-mini").attr("Class");
   if(a.indexOf("sidebar-collapse")==-1)
        $(".contenedor223").addClass("conte223");
        else
        $(".contenedor223").removeClass("conte223");

  });
  $("body").on("change",function(e){
   var a=$(".sidebar-mini").attr("Class");

    if(a.indexOf("sidebar-collapse")!=-1)
    $(".contenedor223").addClass("conte223");
    else
    $(".contenedor223").removeClass("conte223");
   return false;
  });

function resetImgTicket(urlTicket){
      /*  $('.file-input-new2').fileinput('refresh',{
            initialPreview: [urlTicket],
            initialPreviewConfig: [
                {downloadUrl: urlTicket, width: "120px", key: 1},
            ],
            overwriteInitial: true,
            initialPreviewAsData: true,
            showUpload: false,
            showPreview: true,
            browseLabel: "Buscar",
            removeLabel: "Quitar",
         //   allowedFileExtensions: ['pdf'],
            maxFileCount: 1,
            maxFileSize: 5120
        }).on('fileerror', function (event, data) {
            alertToast("Solo se admiten m ximo 1 archivo y las extensiones deben ser jpeg con un peso para cada uno de 5mb", 2000);
        });
       
        
        $(".fileinput-remove-button").addClass('hidden');
        $(".fileinput-remove").addClass('hidden');
        $(".btn-file,.fileinput-remove-button").attr('style','padding: 0px 12px;!important');
        $(".file-footer-caption,.file-upload-indicator").addClass('hidden');
        var d=$(".file-caption-name")[0].innerHTML;
        d=d.replace('seleccionado(s)','');
        $(".file-caption-name")[0].innerHTML=d;*/
}

var nSegSes = 0;
var nminitos = 00;
var nSegSes2=0;
var segundosSesion = document.getElementById("segundosSesion");

window.setInterval(function(){
  nSegSes2++;
  if(nSegSes2>59){
    nSegSes2=0;
    nSegSes2++;
  }
  segundosSesion.innerHTML = 'Inactividad: '+(nminitos<10?'0'+nminitos:nminitos)+':'+(nSegSes2<10?'0'+nSegSes2:nSegSes2)+' seg';
  nSegSes++;
  nminitos=nSegSes/60;
  nminitos=parseInt(nminitos);
  if(nSegSes>28800)
  {
    $("#cerrar").click();
    return false;
  }
},1000);
window.addEventListener('click', function(event) {
  nSegSes=0;
  nSegSes2=0;
});

var dataAuditoria=[];
function agregarAuditoria(tipo='CONSULTA',ep=null){
  var  data=new FormData();
  data.append('tipo',tipo);
 data.append('id',idObjetoAuditoriaGeneral);
  data.append('descripcion','');
 data.append('fecha',fechaVigenteAUDITA);
 var inicio=$("#inicio_auditoria").val();
 var fin=$("#fin_auditoria").val();
 data.append('fecha_inicio',inicio);
 data.append('fecha_fin',fin);
  var objApiRest = new AJAXRestFilePOST('/admin/auditoria',  data);
              objApiRest.extractDataAjaxFile(function (_resultContent) {
                  if (_resultContent.status == 200) {
                    if(tipo!='CONSULTA')
                    alertToastSuccess(_resultContent.message,3500);
                    dataAuditoria=_resultContent.data;
                    cargarConsultaAuditoria(ep);
                  } else
                    alertToast(_resultContent.message,3500);
                  
              });
}

var objetoAuditoria=[];
var tablaDeActividadesAuditoria='';
function crearelemento(){
  objetoAuditoria.push({
    fecha: arregloObjeto[0],
    inicio: arregloObjeto[1],
    inicio_parada: arregloObjeto[2],
    fin_parada: arregloObjeto[3],
    fin: arregloObjeto[4]
  });
}
var arregloObjeto=[];
function cargarConsultaAuditoria(ep=null){
  if(ep==null){
    if(dataAuditoria.length>0){
      data=dataAuditoria[0].auditoria;
      let date = new Date()
      let day = date.getDate()<10?('0'+date.getDate()):date.getDate();
      var month = (date.getMonth()+1);
       var   mes = month<10?('0'+month):month;

       month=mes;
      let year = date.getFullYear();
      var diaAuditoria=year+'-'+month+'-'+day;
      var x = $.grep(data, function (element, index) {
          return element.created_at.indexOf(diaAuditoria)!=-1;
      });
       var ipa = $.grep(x, function (element, index) {
            return element.tipo=='IPA';
        });
        var fpa = $.grep(x, function (element, index) {
          return element.tipo=='FPA';
      });
        var ini = $.grep(x, function (element, index) {
          return element.tipo=='INI';
      });
      var fin = $.grep(x, function (element, index) {
        return element.tipo=='FIN';
      });
      $('#inicioAu').html(ini.length>0?ini[0].created_at.split(' ')[1].substr(0,ini[0].created_at.split(' ')[1].length-3):'00:00');
      $('#inicioAlAu').html(ipa.length>0?ipa[0].created_at.split(' ')[1].substr(0,ipa[0].created_at.split(' ')[1].length-3):'00:00');
      $('#finAlAu').html(fpa.length>0?fpa[0].created_at.split(' ')[1].substr(0,fpa[0].created_at.split(' ')[1].length-3):'00:00');
      $('#finAu').html(fin.length>0?fin[0].created_at.split(' ')[1].substr(0,fin[0].created_at.split(' ')[1].length-3):'00:00');
  
    }else{
      $('#inicioAu').html('00:00');
      $('#inicioAlAu').html('00:00');
      $('#finAlAu').html('00:00');
      $('#finAu').html('00:00');
    }

    
  }else{
    cargarConsultaDatatableAuditoria();
  }
 
}
var idObjetoAuditoriaGeneral=0;
function cargarConsultaDatatableAuditoria(){
  objetoAuditoria=[];
    if(dataAuditoria.length>0){
    data=dataAuditoria[0].auditoria;
    var inis='00:00';
    var inisia='00:00';
    var inisfa='00:00';
    var inisf='00:00';
    var last=null;
        $.each(data, function (_key, _value)
        {
            if(last!=_value.fecha_creada){
              if(last==null)
              {
                inis='00:00';
                inisia='00:00';
                inisfa='00:00';
                inisf='00:00';
                arregloObjeto=[];
                arregloObjeto.push(_value.created_at.split(' ')[0]);
                arregloObjeto.push(inis);
                arregloObjeto.push(inisia);
                arregloObjeto.push(inisfa);
                arregloObjeto.push(inisf);
                last=_value.fecha_creada;
                if(_value.tipo=='INI')
                arregloObjeto[1]=_value.created_at.split(' ')[1].substr(0,_value.created_at.split(' ')[1].length-3);
                if(_value.tipo=='IPA')
                arregloObjeto[2]=_value.created_at.split(' ')[1].substr(0,_value.created_at.split(' ')[1].length-3);
                if(_value.tipo=='FPA')
                arregloObjeto[3]=_value.created_at.split(' ')[1].substr(0,_value.created_at.split(' ')[1].length-3);
                if(_value.tipo=='FIN')
                arregloObjeto[4]=_value.created_at.split(' ')[1].substr(0,_value.created_at.split(' ')[1].length-3);
           
              }else{
                   crearelemento();
                    inis='00:00';
                    inisia='00:00';
                    inisfa='00:00';
                    inisf='00:00';
                    arregloObjeto=[];
                    arregloObjeto.push(_value.created_at.split(' ')[0]);
                    arregloObjeto.push(inis);
                    arregloObjeto.push(inisia);
                    arregloObjeto.push(inisfa);
                    arregloObjeto.push(inisf);
                    last=_value.fecha_creada;
                    if(_value.tipo=='INI')
                    arregloObjeto[1]=_value.created_at.split(' ')[1].substr(0,_value.created_at.split(' ')[1].length-3);
                    if(_value.tipo=='IPA')
                    arregloObjeto[2]=_value.created_at.split(' ')[1].substr(0,_value.created_at.split(' ')[1].length-3);
                    if(_value.tipo=='FPA')
                    arregloObjeto[3]=_value.created_at.split(' ')[1].substr(0,_value.created_at.split(' ')[1].length-3);
                    if(_value.tipo=='FIN')
                    arregloObjeto[4]=_value.created_at.split(' ')[1].substr(0,_value.created_at.split(' ')[1].length-3);
                
               }
             
            }else{
              if(_value.tipo=='INI')
              arregloObjeto[1]=_value.created_at.split(' ')[1].substr(0,_value.created_at.split(' ')[1].length-3);
              if(_value.tipo=='IPA')
              arregloObjeto[2]=_value.created_at.split(' ')[1].substr(0,_value.created_at.split(' ')[1].length-3);
              if(_value.tipo=='FPA')
              arregloObjeto[3]=_value.created_at.split(' ')[1].substr(0,_value.created_at.split(' ')[1].length-3);
              if(_value.tipo=='FIN')
              arregloObjeto[4]=_value.created_at.split(' ')[1].substr(0,_value.created_at.split(' ')[1].length-3);
            }
  
            if((_key+1)==data.length){
                crearelemento();
            }
        });
      }
        cargaConsultaAuditoriaActividades();
  


}
function cargaConsultaAuditoriaActividades(){
  var dt = {
    draw: 1,
    recordsFiltered: objetoAuditoria.length,
    recordsTotal: objetoAuditoria.length,
    data: objetoAuditoria 
};
$("#tablaConsultaAuditoria").attr('style','margin-top:10px');
$('#tbobymenuAuditoria').show();
$.fn.dataTable.ext.errMode = 'throw';
tablaDeActividadesAuditoria='';
tablaDeActividadesAuditoria=$("#dtmenuAuditoria").dataTable({
    dom: 'lBfrtip',
    buttons: [
      
        {
            extend:    'excelHtml5',
            text:      '<img src="/images/icons/excel.png" width="15px" heigh="10px">',
            titleAttr: 'Excel'
        },
        {
          text: '<img src="/images/icons/pdf.png" width="15px" heigh="10px">&nbsp;Informe de Actividades ',
          action: function ( e, dt, node, config ) {
              DescargarInformeActividades();
          }
       },
    ],
    "lengthMenu": [[-1], ["TODOS"]],
    "lengthChange": true,
    "searching": true,
    "ordering": true,
    "info": true,
    "autoWidth": true,
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
      
        {title:'Fecha',data: 'fecha', "width": "15%"},
        {title:'Inicio',data: 'inicio', "width": "15%"},
        {title:'Inicio/Parada',data: 'inicio_parada', "width": "15%"},
        {title:'Fin/Parada',data: 'fin_parada', "width": "15%"},
        {title:'Fin',data: 'fin', "width": "15%"},
        {
            "class":          "details-control",
            "orderable":      false,
            "data":           null,
            "visible": false
            ,"render": function (data, type, row) {
              if(idObjetoAuditoriaGeneral==0)
                return  '<span class="btn btn-success btn-xs" style="border-radius:100%;font-size:10px" onclick="detallesActividades(this,\'' + row.fecha+ '\')"><i class="fa fa-plus"></i></span>';
                else
                return  '';
            }
        },
       
    ],
});
}
var fechaVigenteAUDITA='';
function detallesActividades(e,fecha) {
   
  var tr = e.closest('tr');
  var row = tablaDeActividadesAuditoria.api().row( tr );
   
  if ( row.child.isShown() ) {
      fechaVigenteAUDITA='';
      e.outerHTML="<span class='btn btn-success btn-xs' style='border-radius:100%' onclick='detallesActividades(this,\"" + fecha + "\")'><i class='fa fa-plus'></i></span>";
      row.child.hide();
      //tr.removeClass('shown');
           
  }
  else {
      fechaVigenteAUDITA=fecha;
      e.outerHTML="<span class='btn btn-danger btn-xs' style='border-radius:100%' onclick='detallesActividades(this,\"" + fecha + "\")'><i class='fa fa-minus'></i></span>";
      row.child(formatoDetalleAUDITA(fecha)).show();
     // tr.addClass('shown');
   }
}
function formatoDetalleAUDITA (fecha,carga=1) {
  var data=dataAuditoria[0].actividades;
  var x = $.grep(data, function (element, index) {
      return element.created_at.indexOf(fecha)!=-1;
  });

  var Descripcion='';
  var html='';
   
  html+='   <table class="" width="100%" id="DivfechaVigenteAUDITA_'+fecha+'">';
  html+='    <tbody>';
  html+='      <tr>';
  html+='      <td width="40%">';
  html+='   <table class="" width="100%" >';
  html+='    <tbody>';
  html+='      <tr>';
  html+='        <td><button class="btn btn-primary col-lg-12 btn-xs" onclick="AgregarActividadesAUDITA(\'' + fecha + '\')"><i class="fa fa-plus"></i>&nbsp;Agregar Actividad</button></td>';
  html+='      </tr>';
  html+='      <tr>';
  html+='        <td><input type="text" class="form-control" maxlength="200" placeholder="Escribe tu actividad" id="actividadesAUDITA'+fecha+'"></td>';
  html+='      </tr>';
  html+='    </tbody>';
  html+='  </table>';
  html+='      </td>';
  html+='      <td width="60%" style="padding: 0px;">';
        html+='   <table width="100%" border="1">';
        html+='    <tbody>';
          html+='      <tr>';
          html+='        <td width="75%" style="text-align:center;font-weight:bold;background: #1e4e6a;color: #fff;padding: 5px;">ACTIVIDADES DIARIAS</td>';
          html+='      </tr>';
          if(x.length>0){
            $.each(x, function (_key, _value)
            {
              html+='      <tr>';
              html+='        <td width="100%" style="text-align:center;padding: 0px;" >';
              html+='   <table cellspacing="0" width="100%" >';
              html+='    <tbody>';
              html+='      <tr>';
              html+='        <td width="80%"style="text-align:center;padding: 0px;padding-left: 8px;" >';
              html+=_value.descripcion+'</td>';
              html+='        <td width="20%" style="text-align:right" class="celda_sin">';
              html+='<a href="#" class="fa fa-trash" onclick="EliminarActividad(\'' + _value.id + '\',\'' + fecha + '\')" style="color:red"></a></td>';
              html+='      </tr>';
              html+='    </tbody>';
              html+='  </table>';
              html+='      </td>';
             
              html+='      </tr>';
            });
          }else{
            html+='      <tr>';
            html+='        <td colspan="2" style="text-align:center">No existen registros de actividades</td>';
            html+='      </tr>';
          }
      
          html+='    </tbody>';
        html+='  </table>';
  html+='      </td>';
  html+='      </tr>';
  html+='    </tbody>';
  html+='  </table>';
  if(carga==1){
      return html;
  }else{
    $("#DivfechaVigenteAUDITA_"+fecha+"").html(html);
  }

 
}

function EliminarActividad(id,fecha){
 var  data=new FormData();
 data.append('id',id);
 data.append('tipo','ELIMINAR');
 data.append('descripcion','');
 data.append('fecha','');
 var inicio=$("#inicio_auditoria").val();
 var fin=$("#fin_auditoria").val();
 data.append('fecha_inicio',inicio);
 data.append('fecha_fin',fin);
  var objApiRest = new AJAXRestFilePOST('/admin/auditoria',  data);
  objApiRest.extractDataAjaxFile(function (_resultContent) {
      if (_resultContent.status == 200) {
        alertToastSuccess(_resultContent.message,3500);
        dataAuditoria=_resultContent.data;
        formatoDetalleAUDITA(fecha,2)
      } else
        alertToast(_resultContent.message,3500);
  });
}

function AgregarActividadesAUDITA(fecha){
 var actividadAgregar=$("#actividadesAUDITA"+fecha+"").val();
 var  data=new FormData();
 data.append('tipo','AGREGAR');
 data.append('id',0);
 data.append('descripcion',actividadAgregar);
 data.append('fecha',fecha);
 var inicio=$("#inicio_auditoria").val();
 var fin=$("#fin_auditoria").val();
 data.append('fecha_inicio',inicio);
 data.append('fecha_fin',fin);
 var objApiRest = new AJAXRestFilePOST('/admin/auditoria',  data);
             objApiRest.extractDataAjaxFile(function (_resultContent) {
                 if (_resultContent.status == 200) {
                   alertToastSuccess(_resultContent.message,3500);
                   dataAuditoria=_resultContent.data;
                   $("#actividadesAUDITA").val(null);
                   formatoDetalleAUDITA(fecha,2)
                 } else
                   alertToast(_resultContent.message,3500);
             });

}
function DescargarInformeActividades(){
   DescargarChat("auditoria.DescargarInforme",1);
}
function cargarConsultaAuditoriaCompleta(id=0){
  //cargarCalendar;
  idObjetoAuditoriaGeneral=id;
  agregarAuditoria('CONSULTA',1);
  $("#dataConsultoria").click();
}
$("#ListadoChat").on("change",function(){
  var id=$(this).find(':selected').attr('data-id');
  var name=$(this).find(':selected').attr('data-user');
  $("#DivChatToggle").attr('data-id',id);
  $("#DivChatToggle").attr('data-user',name.replace("."," ").toUpperCase());
  $("#DivChatToggle").click();
});

function satisfaccionPendienteModal(){
  let arregloDataSatisfaccion = document.querySelector('#dh_data_satisfaccion').dataset.data;
  arregloDataSatisfaccion = JSON.parse(arregloDataSatisfaccion);
    var x=arregloDataSatisfaccion;
    if(x.length==0){
      $("#satisfaccionTicket").addClass('hidden');
      $("#creacionTicket").removeClass('hidden');
    }else{
      $("#modalSatisfaccion").click();
      $("#satisfaccionTicket").removeClass('hidden');
      $("#creacionTicket").addClass('hidden');
      var html='';
      $.each(x, function (_key, _value) {
        html+=' <h4 style="color:#ffffff;text-align:center"># de Ticket : '+_value.id+'</h4>';
        html+='   <h4 style="color:#ffffff;text-align:center">'+_value.categoria.descripcion+'</h4>';
        html+='   <h4 style="color:#ffffff;text-align:center">'+_value.categoria.categoria.descripcion+'</h4>';
        html+='   <hr/>';
        html+='     <h5 style="color:#ffffff;text-align:center">Para poder crear el siguiente Ticket debe calificar la atenci&oacute;n anterior:</h5>';
        html+='     <center><div class="ec-stars-wrapper">';
        html+='       <a href="#" data-value="1" title="Votar con 1 estrellas" onclick="votacionTicket(1,\'' + _value.id + '\')">&#9733;</a>';
        html+='       <a href="#" data-value="2" title="Votar con 2 estrellas" onclick="votacionTicket(2,\'' + _value.id + '\')">&#9733;</a>';
        html+='       <a href="#" data-value="3" title="Votar con 3 estrellas" onclick="votacionTicket(3,\'' + _value.id + '\')">&#9733;</a>';
        html+='       <a href="#" data-value="4" title="Votar con 4 estrellas" onclick="votacionTicket(4,\'' + _value.id + '\')">&#9733;</a>';
        html+='       <a href="#" data-value="5" title="Votar con 5 estrellas" onclick="votacionTicket(5,\'' + _value.id + '\')">&#9733;</a>';
        html+='     </div></center>';
      }); 
      
      $("#satisfaccionTicket").html(html);
      $(".satisfaccionTicket").html(html);
    }
 
}