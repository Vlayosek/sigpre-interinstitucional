<script src="{{ url('js/jquery/') }}/jquery2.js"></script>
<script src="{{ url('js/jquery/') }}/jqueryui.js"></script>
<script src="{{ url('js/jquery/') }}/jqueryuitouch.js"></script>
<script src="{{ url('adminlte/plugins/respond/') }}/html5shiv.min.js"></script>
<script src="{{ url('adminlte/plugins/respond/') }}/respond.min.js"></script>

<script src="{{ url('adminlte//plugins/datatables/') }}/jquery.dataTables2.min.js"></script>
<script src="{{ url('adminlte/js') }}/dataTables.buttons.min.js"></script>
<script src="{{ url('adminlte/js') }}/buttons.flash.min.js"></script>
<script src="{{ url('adminlte/js') }}/jszip.min.js"></script>
<script src="{{ url('adminlte/js') }}/pdfmake.min.js"></script>
<script src="{{ url('adminlte/js') }}/vfs_fonts.js"></script>
<script src="{{ url('adminlte/js') }}/buttons.html5.min.js"></script>
<script src="{{ url('adminlte/js') }}/buttons.print.min.js"></script>
<script src="{{ url('adminlte/js') }}/buttons.colVis.min.js"></script>
<script src="{{ url('adminlte/js') }}/dataTables.select.min.js"></script>
<script src="{{ url('adminlte/js') }}/bootstrap.min.js"></script>
<script src="{{ url('adminlte/js') }}/dataTables.colReorder.min.js"></script>
<script src="{{ url('adminlte/plugins/fileinput/fileinput.min.js') }}"></script>
<script src="{{ url('adminlte/plugins/datepicker/') }}/bootstrap-datepicker.js"></script>


<script src="{{ url('adminlte/js') }}/main.js"></script>

<script src="{{ url('adminlte/plugins/notifications/pnotify.min.js') }}"></script>
<script src="{{ url('adminlte/plugins/notifications/sweet_alert.min.js') }}"></script>

<script src="{{ url('adminlte/plugins/slimScroll/jquery.slimscroll.min.js') }}"></script>
<script src="{{ url('adminlte/plugins/fastclick/fastclick.js') }}"></script>
<script src="{{ url('adminlte/js/app.min.js') }}"></script>



<script src="{{ asset('/vendors/ckeditor/ckeditor.js') }}"></script>

<script src="{{ url('adminlte/plugins/data/filesaver.js') }}"></script>
<script src="{{ url('adminlte/plugins/data/html2canvas.js') }}"></script>
<script src="{{ url('adminlte/plugins/dropzone/dropzone.js') }}"></script>
<script type="text/javascript" src="{{ url('adminlte/plugins/daterange/moment.min.js') }}"></script>

<script src="{{ url('adminlte/plugins/datetimepicker/bootstrap-datetimepicker.min.js') }}"></script>
<script type="text/javascript" src="{{ url('adminlte/plugins/daterange/daterangepicker.min.js') }}"></script>

<script>
    window._token = '{{ csrf_token() }}';
  
</script>
<script src="{{ url('adminlte/plugins/charts/highcharts.js') }}"></script>

<script src="{{ asset('js/modules') }}/utils.js?v=2"></script>
<script src="{{ url('js/modules/Core.js') }}"></script>


<script>
    function downloadURI(uri, name) {
        var link = document.createElement("a");
        link.download = name;
        link.href = uri;
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        delete link;
    }
    $('.sidebar-mini').on('webkitTransitionEnd transitionend', function (e) {
        if (e.target.className == "model-open") 
        $('body').attr('style','overflow-y: hidden!important;');
        else
        $('body').attr('style','overflow-y: auto!important;');

    }); 
    $(".cerrarmodal").on("click",function(){
        $('body').attr('style','overflow-y: auto!important;');
    });
    var abreChatTicket = 0;
    $("#chatAplicacionCerradaM").on("click", function() {
        $("#chatAplicacionCerrada").addClass("hidden");
        $("#chatAplicacion").removeClass("hidden");
        $("#abreListaTicket").click();
    });
    $("#abreListaTicket").on("click", function() {
        formarMensajes();
        $("[name='ticketsChatmeDiv']").addClass("hidden");
        $("#ticketIniciados").val(0).change();
    });

    $("#chatAplicacionM").on("click", function() {
        $("#chatAplicacion").removeClass("direct-chat-contacts-open")
        $("#chatAplicacionCerrada").removeClass("hidden");
        $("#chatAplicacion").addClass("hidden");
    });

    var ep = 1;

    var incidente_oficial_seguridad = 0;
    var ccClickAyuda = 0;
    var clickAyuda = 0;
    var cargarTicke = 0;
    //ARCHIVO DE TICKETS
    $(document).on('change', '.btn-file2 :file', function() {
        var input = $(this);
        var men = $("#mensajeChatTexto").val();

        var numFiles = input.get(0).files ? input.get(0).files.length : 1;
        var label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
        input.trigger('fileselect', [numFiles, label]);
        $("#mensajeChatTexto").val(men);

    });
    $(document).ready(function() {
    
        if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
            $(".contenedor223").addClass("hidden");
            $("#mesaAyuda").click();
            $("#mesaAyuda").attr("style", "width:100%!important;");
            $(".navbar-static-top").addClass("hidden");
        } else {
            $("#mesaAyuda").removeAttr("style");
            $(".contenedor223").removeClass("hidden");
        }

        $('.btn-file2 :file').on('fileselect', function(event, numFiles, label) {
            $("#icoUpload").removeClass("fa-upload");
            $("#icoUpload").addClass("fa-paperclip");
            var input = $(this).parents('.input-group').find(':text');
            var log = numFiles > 1 ? numFiles + ' files selected' : label;
            if (input.length) {
                input.val(log);
            } else {
                if (log) alert(log);
            }
        });
    });
    ///FIN DE ARCHIVO DE TICKET 
    $(function() {
        resetImgArchivo();
        $('[name*="documentoTicket"]').change(function() {
            var t = $(this).val();
            var labelText = 'File : ' + t.substr(12, t.length);
            $(this).prev('label').text(labelText.substr(0, 35));
        });

        $('[name*="archivoChatUser"]').change(function() {
            var t = $(this).val();
            var labelText = 'File : ' + t.substr(12, t.length);
            $(this).prev('label').text(labelText.substr(0, 35));
        });

    });
    var arregloTicket = [];
    var arregloDataTicket = [];
    var arregloSeguimientoDataTicket = [];
    var arregloEstadoTickets = [];
    var soporteUsuarioTicket = [];
    var idBienesCargados = 0;
    var fecha_inicio_Busqueda_Ticket = typeof $("#fecha_inicio_Busqueda_Ticket").val() === "undefined" ? true : $(
        "#fecha_inicio_Busqueda_Ticket").val();

    //BuscarTicket();
    SoporteAutorizado();

    function SoporteAutorizado(autorizado = "1") {
        if (URLactualCompleta != 'tickets' && URLactualCompleta != 'ticketsasignados' && URLactualCompleta !=
            'ticketsasignados#')
            $("[name*=tipoTicket2]").val(null).change();
        else
            $("[name*=tipoTicket2]").val(0).change();

        $("[name*=categoriaTicket]").val(0).change();
        $("[name*=descripcionTicket]").val('');
        $("[name*=documentoTicket]").val('');
        $("[name*=documentoTicket]").html('');
        $("[name*=labelFile]").text('');

        var s = $("[name=soporte]").text().trim();
        var s1 = $("[name=director]").text().trim();
        if (autorizado == "c") {
            $("#DivAgregaActividad").addClass("hidden");
            $("#DivAgregaActividad2").addClass("hidden");
            $("[name='btnActualizarDatosTicket']").addClass("hidden");
            $("#DivAgregaActividad3").removeClass("col-lg-9");
            $("#DivAgregaActividad3").addClass("col-lg-12");
            $("#ModalTicketInterno").attr("style", "min-width:92%");

        } else {
            if (s == "1" || s1 == "1") {
                idBienesCargados = 1;
                $("#DivAgregaActividad").removeClass("hidden");
                $("#DivAgregaActividad2").removeClass("hidden");
                $("[name='btnActualizarDatosTicket']").removeClass("hidden");
                $("#DivAgregaActividad3").addClass("col-lg-9");
                $("#DivAgregaActividad3").removeClass("col-lg-12");
                $("#ModalTicketInterno").attr("style", "min-width:92%");
            } else {
                idBienesCargados = 0;

                $("#DivAgregaActividad").addClass("hidden");
                $("#DivAgregaActividad2").addClass("hidden");
                $("[name='btnActualizarDatosTicket']").addClass("hidden");
                $("#DivAgregaActividad3").removeClass("col-lg-9");
                $("#DivAgregaActividad3").addClass("col-lg-12");
                $("#ModalTicketInterno").attr("style", "min-width:92%");
            }
        }

        /* if(fecha_inicio_Busqueda_Ticket==true)
             var a=setInterval(BuscarTicket, time);*/
    }


    var tablaDeActividades = '';
    var arregloActual_tablaDeActividades = '';

    function getBase64FromImageUrl(url) {
        var img = new Image();
        img.crossOrigin = "anonymous";
        img.onload = function() {
            var canvas = document.createElement("canvas");
            canvas.width = this.width;
            canvas.height = this.height;
            var ctx = canvas.getContext("2d");
            ctx.drawImage(this, 0, 0);
            var dataURL = canvas.toDataURL("image/png");
            return dataURL.replace(/^data:image\/(png|jpg);base64,/, "");
        };
        img.src = url;
    }


    function detalleTicketDatatable(e, id) {

        var tr = e.closest('tr');
        var row = tablaDeActividades.api().row(tr);

        if (row.child.isShown()) {
            e.outerHTML =
                "<span class='btn btn-success btn-xs' style='border-radius:100%' onclick='detalleTicketDatatable(this,\"" +
                id + "\")'><i class='fa fa-plus'></i></span>";
            row.child.hide();
            //tr.removeClass('shown');

        } else {
            e.outerHTML =
                "<span class='btn btn-danger btn-xs' style='border-radius:100%' onclick='detalleTicketDatatable(this,\"" +
                id + "\")'><i class='fa fa-minus'></i></span>";
            row.child(formatoDetalleTicket(id)).show();
            // tr.addClass('shown');
        }
    }

  

    function calculoFecha(fecha1, fecha2) {

        return dias;
    }
    var usuarioSoporteActivo = '';

    function cargarModalTickect(id = 0, usuario = '') {

        $("#idTickectVigente").val(id);
        $("#nticket").text(id);

        SoporteAutorizado("0");
        var x = $.grep(arregloDataTicket, function(element, index) {
            return element.id == id;
        });
        var data = x[0];
        var htmlSatisfaccion = '<strong style="font-size:20px;float:left">Califacion:<strong>&nbsp;';
        data['satisfaccion'] = data['satisfaccion'] != null ? data['satisfaccion'] : 0;
        for (i = 0; i < data['satisfaccion']; i++) {
            htmlSatisfaccion += "<i class='fa fa-star' style='font-size:20px;color:#d6d607'></i>";
        }

        $(".satifaccionCargada").html(htmlSatisfaccion);
        if (data['asignacion_director'] == 'ACT')
            $("#CheckTicketDirector").attr("checked", "checked");
        else
            $("#CheckTicketDirector").removeAttr("checked", "checked");

        if (data['notificacion_oficial'] == 'ACT')
            $("#CheckTicketOficial").attr("checked", "checked");
        else
            $("#CheckTicketOficial").removeAttr("checked", "checked");


        var usuarioActual = '{{ Auth::user()->id }}';
        /*   $.each(data.soportecolaborativo, function (_key, _value) {
               if(_value.usuario_id==usuarioActual)
               $("#DivCargaCheckFinalizarTicket").addClass("hidden");
           });
           */
        arregloSeguimientoDataTicket = data.seguimiento;
        usuarioSoporteActivo = data['soporte_ticket'];
        var x1 = $.grep(arregloSeguimientoDataTicket, function(element, index) {
            return element.estado == 'ACT';
        });
        var data1 = x1[0];
        $("#idTickectVigenteSeguimiento").val(data1['id']);
        // $("#soporteUsuarioTicket2").val(data1['soporte_usuario_id']).change();
        $("#actividadSoporteTicket").val('');

        var tt = data['categoria'] != null ? data['categoria']['fatherpara']['id'] : 0;
        var cat = data['categoria'] != null ? data['categoria']['id'] : 0;
        $("#nticketCreacionInicial").text(data['created_at']);
        $("[name*=estadoTicket]").removeAttr('disabled');
        $("[name*=estadoTicket]").val(data['estado']).change();
        $("[name*=estadoTicket]").attr('disabled', 'disabled');
        $("#tipoTicket2").val(tt).change();
        $("#categoriaTicket2").val(cat).change();

        var selectcc = document.getElementById("tipoTicket2"), //El <select>
            valuecc = selectcc.value, //El valor seleccionado
            textcc = selectcc.options[selectcc.selectedIndex].innerText;

        var selectcct = document.getElementById("categoriaTicket2"), //El <select>
            valuett = selectcct.value, //El valor seleccionado
            texttt = selectcct.options[selectcct.selectedIndex].innerText;

        var selectestado2 = document.getElementById("estadoTicket2"), //El <select>
            valueestado2 = selectestado2.value, //El valor seleccionado
            textestado2 = selectestado2.options[selectestado2.selectedIndex].innerText;

        $("#textoEstadoWidget").text(textestado2);

        if (textcc.toUpperCase() == 'INCIDENTE') {
            $("#DivCargaCheckTicketOficial").removeClass("hidden");
        } else {
            $("#DivCargaCheckTicketOficial").addClass("hidden");
        }

        $('#tipoTicket2Texto').text(textcc.toUpperCase());
        $('#usuarioTick').text(usuario.toUpperCase());
        $("[name=descripcionTicket]").val(data['descripcion']);
        if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent))
            $('#categoriaTicket2Texto').html(texttt.toUpperCase().split("-->")[0] + "<br/>" + texttt.toUpperCase()
                .split("-->")[1]);
        else
            $('#categoriaTicket2Texto').text(texttt.toUpperCase());

        /////////AQUI BIENES
        $("#bienesTicket2").html('');
        $("#bienesTicket2").append('<option value="0">--DISPOSITIVOS--</option>');
        var bienesArreglo = data['usuario']['bienes'];
        $.each(bienesArreglo, function(_key, _value) {
            var rayita = '';
            if (_value.marca != null && _value.modelo != null)
                rayita = '-';
            $("#bienesTicket2").append('<option value="' + _value.id + '">' + (_value.producto != null ? _value
                .producto : 'S/I') + ' ' + (_value.marca != null ? _value.marca.descripcion : '') + (
                _value.modelo != null ? (rayita + _value.modelo.descripcion) : '') + '</option>');
        });
        $("#bienesTicket2").val(data['bienes_id']).change();


        var s = $("[name=soporte]").text().trim();

        if (data['asignacion_director'] == 'ACT') {
            idBienesCargados = 1;
            if (valueestado2 != 'FIN') {
                var st = 'PENDIENTE DIRECTOR DTIC';
                $("#textoEstadoWidget").text(st);
            }
            $("#DivDescargarInformedeGestion").addClass("hidden");
            $("#CheckFinalizarTicket").removeAttr("checked");
            $("[name='btnActualizarDatosTicket']").removeClass("hidden");
            $("#DivAgregaActividad").removeClass("hidden");
            $("#DivAgregaActividad2").removeClass("hidden");
            $("#DivAgregaActividad3").addClass("col-lg-9");
            $("#DivAgregaActividad3").removeClass("col-lg-12");
            $("#ModalTicketInterno").attr("style", "min-width:92%");
            $("#tipoTicket2").removeAttr('disabled');
            $("#categoriaTicket2").removeAttr('disabled');
            $("#bienesTicket2").removeAttr('disabled');
        }

        if (data['estado'] == 'FIN' || s == "0" || data1['soporte'] == null) {
            $("#CheckFinalizarTicket").attr("checked", "checked");
            $("#DivDescargarInformedeGestion").removeClass("hidden");
            idBienesCargados = 0;

            $("[name='btnActualizarDatosTicket']").addClass("hidden");
            $("#DivAgregaActividad").addClass("hidden");
            $("#DivAgregaActividad2").addClass("hidden");
            $("#DivAgregaActividad3").removeClass("col-lg-9");
            $("#DivAgregaActividad3").addClass("col-lg-12");
            $("#ModalTicketInterno").attr("style", "min-width:92%");
            $("#tipoTicket2").attr('disabled', 'disabled');
            $("#categoriaTicket2").attr('disabled', 'disabled');
            $("#bienesTicket2").attr('disabled', 'disabled');

        } else {
            idBienesCargados = 1;

            $("#DivDescargarInformedeGestion").addClass("hidden");
            $("#CheckFinalizarTicket").removeAttr("checked");
            $("[name='btnActualizarDatosTicket']").removeClass("hidden");
            $("#DivAgregaActividad").removeClass("hidden");
            $("#DivAgregaActividad2").removeClass("hidden");
            $("#DivAgregaActividad3").addClass("col-lg-9");
            $("#DivAgregaActividad3").removeClass("col-lg-12");
            $("#ModalTicketInterno").attr("style", "min-width:92%");
            $("#tipoTicket2").removeAttr('disabled');
            $("#categoriaTicket2").removeAttr('disabled');
            $("#bienesTicket2").removeAttr('disabled');
        }
        var urlBase = $("#direccionDocumentos").val();
        if (data['documentoTicket'] != null && data['documentoTicket'] != "") {
            $("#documentoDescargaTicket").removeAttr('disabled');
            $("#documentoDescargaTicket").attr('href', urlBase + '/' + 'TICKETS/' + data['documentoTicket']);
            $("#documentoDescargaTicket").attr('target', '_blank');

        } else {
            $("#documentoDescargaTicket").attr('disabled', 'disabled');
            $("#documentoDescargaTicket").attr('href', '#');
            $("#documentoDescargaTicket").removeAttr('target');
        }
        if (data['asignacion_director'] == 'ACT') {
            idBienesCargados = 1;

            $("#DivDescargarInformedeGestion").addClass("hidden");
            $("#CheckFinalizarTicket").removeAttr("checked");
            $("[name='btnActualizarDatosTicket']").removeClass("hidden");
            $("#DivAgregaActividad").removeClass("hidden");
            $("#DivAgregaActividad2").removeClass("hidden");
            $("#DivAgregaActividad3").addClass("col-lg-9");
            $("#DivAgregaActividad3").removeClass("col-lg-12");
            $("#ModalTicketInterno").attr("style", "min-width:92%");
            $("#tipoTicket2").removeAttr('disabled');
            $("#categoriaTicket2").removeAttr('disabled');
            $("#bienesTicket2").removeAttr('disabled');
        }
        consultaActividadesTicket();

    }

    function GeneraInformeTicket() {
        alert("Aqui Genera Informe");
    }

    function resetImgArchivo() {

    }
    var URLactualCompleta = window.location.href;
    URLactualCompleta = URLactualCompleta.split("/");
    URLactualCompleta = URLactualCompleta[URLactualCompleta.length - 1];
    if (URLactualCompleta == "tickets")
        $("#contenidoPaginaBread").text("Mis tickets");

    function urlVerificarMesa() {
        $('#mesaAyuda').click();
    }
    $("#cancelarModalTicket").on("click", function() {
        var tb = $('#tbobymenuActividadesTicket').html().trim();
        if (tb != "") {
            $('#dtmenuActividadesTicket').DataTable().destroy();
            $('#tbobymenuActividadesTicket').html('');
            $('#dtmenuActividadesTicket thead').html('');

        }

    });

    function dataddd() {
        alert("1");
    }

    function BuscarTicket(finalizacion = 0, identificador = 0, inicializador = 0, takeador = 0, CheckTicketTodos = 0,
        chatme = 0, informes = 0, tick = 0) {
        var URLactual = window.location.href;
        URLactual = URLactual.split("/");
        URLactual = URLactual[URLactual.length - 1];
        if (URLactual == "ticketsasignados" || URLactual == "ticketsasignados#") {
            inicializador = 2;
        }
        var data = new FormData()

        var fecha_inicio = "";
        var fecha_fin = "";
        if (fecha_inicio_Busqueda_Ticket != true) {
            fecha_inicio = $("#fecha_inicio_Busqueda_Ticket").val();
            fecha_fin = $("#fecha_fin_Busqueda_Ticket").val();
        }
        data.append('takeador', takeador);
        data.append('inicializador', inicializador);
        data.append('fecha_inicio', fecha_inicio);
        data.append('fecha_fin', fecha_fin);
        data.append('tipo', incidente_oficial_seguridad);
        data.append('CheckTicketTodos', CheckTicketTodos);
        data.append('busqueda', null);

        var objApiRest = new AJAXRestFilePOST('/inventario/BuscarTicket', data);
        objApiRest.extractDataAjaxFile(function(_resultContent) {
            if (_resultContent.status == 200) {

                if (identificador == 0) {
                    $("#cancelarModalTicket").click();
                }
                var media = 'bg-yellow';
                var alta = 'bg-red';
                var baja = 'bg-green';
                var prioridad = baja;
                arregloDataTicket = _resultContent.message;
                arregloMensajesGenerales = _resultContent.message;
                cargarticketIniciados();
                if (informes != 0) {
                    if (InformeAct == 0)
                        consultaReportes(TicketInformeIdCargado, informes);
                    else
                        consultaInformes(TicketInformeIdCargado, informes);
                }

                if (chatme != 0)
                    cargarChatMe(chatme);
                else
                    formarMensajes();
                var s = $("[name=soporte]").text().trim();
                /*if(s!="0"){
                     x = $.grep(arregloDataTicket, function (element, index) {
                           return element.estado != 'PEN';
                     });
                }else{*/
                var x = arregloDataTicket;
                /* }
                 */
                // if(fecha_inicio_Busqueda_Ticket!=true&&clickAyuda==1||finalizacion==1)
                $("#alertasTicket").html('');
                arregloTicket = [];
                var dlength = x.length > 3 ? 3 : x.lenght;
                var acf = 0;
                $.each(x, function(_key, _value) {
                    acf = acf + 1;

                    var d = arregloTicket.indexOf(_value.id);
                    if (d == -1 || (fecha_inicio_Busqueda_Ticket != true && clickAyuda == 1 ||
                            finalizacion == 1)) {
                        arregloTicket.push(_value.id);
                        if (_value.categoria != null) {
                            switch (_value.categoria.nivel.descripcion.split('.')[0]) {
                                case "1":
                                    prioriodad = baja;
                                    break;
                                case "2":
                                    prioridad = media;
                                    break;
                                case "3":
                                    prioridad = alta;
                                    break;
                            }
                        } else {
                            prioridad = baja;
                        }
                        var categoriaTitulo = _value.categoria != null ? _value.categoria.descripcion :
                            'Categoria No definida';
                        var usuarioTitulo = _value.usuario != null ? _value.usuario.nombreCompleto
                            .split(',')[0].replace('CN=', '') : 'Usuario No definido';
                        var categoriaDetalle = _value.categoria != null ? _value.categoria.categoria
                            .descripcion : 'Categoria No Definida';
                        var html = '';
                        html += '<li class="consultaTicketsMovil">';
                        html +=
                            '<a href="#modal-alertas" role="button" data-toggle="modal" onclick="cargarModalTickect(\'' +
                            _value.id + '\',\'' + usuarioTitulo + '\')" >';
                        html += '<i class="menu-icon fas fa-exclamation-triangle ' + prioridad +
                            '"></i>';
                        html += '   <div class="menu-info">';
                        html +=
                            '      <h4 class="control-sidebar-subheading col-lg-8" name="cargaTituloTicket">' +
                            categoriaTitulo + '</h4>';
                        html += '      <span class="col-lg-2"></span>';
                        html += '        <p class="col-lg-12 consultaTicketsMovilLetra">Usuario:' +
                            usuarioTitulo + '</p>';
                        html += '        <p class="col-lg-12 consultaTicketsMovilLetra">Categoria:' +
                            categoriaDetalle + '</p>';
                        html += '    </div>';
                        html += '    </a>';
                        html += '</li>';
                        $("#alertasTicket").append(html);
                        if (identificador != 0) {
                            var x = $.grep(arregloDataTicket, function(element, index) {
                                return element.id == identificador;
                            });
                            var data = x[0];
                            arregloSeguimientoDataTicket = data.seguimiento;
                            var x1 = $.grep(arregloSeguimientoDataTicket, function(element, index) {
                                return element.estado == 'ACT';
                            });
                            var data1 = x1[0];
                            $("#idTickectVigenteSeguimiento").val(data1['id']);
                            consultaActividadesTicket(1);
                        }

                    }
                    if (acf == dlength)
                        return false;
                });
                if (x.length > 3) {
                    var html3 =
                        "<hr/><a style='text-align:center;font-size:14px' class='col-md-6 col-md-offset-3 btn btn-default btn-xs' href='{{ url('/mesaayuda/tickets') }}'>Ver mis tickets</a>";
                    $("#alertasTicket").append(html3);
                }


                if (fecha_inicio_Busqueda_Ticket != true && clickAyuda == 1)
                    consultaDatosAsignadorTicket();
            } else {
                alertToast(_resultContent.message, 3500);
                arregloTicket = [];
                arregloSeguimientoDataTicket = [];
                arregloDataTicket = [];
                $("#alertasTicket").html('');
                if (fecha_inicio_Busqueda_Ticket != true && clickAyuda == 1)
                    consultaDatosAsignadorTicket();
            }
        });
    }
</script>
<script>
    + function(w, d, undefined) {

        /*var id = new Date().getTime().toString();
        if (w.localStorage.appID === undefined) {
            w.localStorage.appID = id;
            w.onbeforeunload = function () {
                w.localStorage.removeItem('appID'); // Removemos la variable en localStorage
            };
        } else if (w.localStorage.appID !== id) {
            guardarDatos();
        }*/
    }(window, document);

    function guardarDatos() {
        /*var objApiRest = new AJAXRest('/admin/sessionAudita', {}, 'post');
        objApiRest.extractDataAjax(function (_resultContent) {
            if (_resultContent.status == 200) {
                $("#cerrar").click();
            }

        });*/
    }
</script>
<script type="text/javascript">
    /* INICIO VALIDACION DE CEDULA*/
    function validar(e) {
        var cad = document.getElementById(e.context.id).value.trim();
        var total = 0;
        var longitud = cad.length;
        var longcheck = longitud - 1;

        if (cad !== "" && longitud === 10) {
            for (i = 0; i < longcheck; i++) {
                if (i % 2 === 0) {
                    var aux = cad.charAt(i) * 2;
                    if (aux > 9) aux -= 9;
                    total += aux;
                } else {
                    total += parseInt(cad.charAt(i)); // parseInt o concatenar� en lugar de sumar
                }
            }

            total = total % 10 ? 10 - total % 10 : 0;

            if (cad.charAt(longitud - 1) == total) {
                // alertToast("Cedula V�lida",3500);
            } else {
                alertToast("Cedula Inv�lida", 3500);
            }
        }
    }
    /* FIN VALIDACION DE CEDULA*/
</script>

<script type="text/javascript">

    $('.select2').attr('style', 'width : 100%');
    $('.modal').removeAttr('tabindex');
    $(".tablinks").on("click", function() {
        var context = $(this).context;
        var id = $(this).val();
        var i, tabcontent, tablinks;

        // Get all elements with class="tabcontent" and hide them
        tabcontent = document.getElementsByClassName("tabcontent");
        for (i = 0; i < tabcontent.length; i++) {
            tabcontent[i].style.display = "none";
        }

        // Get all elements with class="tablinks" and remove the class "active"
        tablinks = document.getElementsByClassName("tablinks");
        for (i = 0; i < tablinks.length; i++) {
            tablinks[i].className = tablinks[i].className.replace(" active", "");
        }

        // Show the current tab, and add an "active" class to the button that opened the tab
        document.getElementById(id).style.display = "block";
        context.className = context.className.search("active") < 0 ? context.className : context.className
            .replace("active", "");
        context.className += " active";
    });
</script>
<script type="text/javascript">
    function soloLetras(e) {
        key = e.keyCode || e.which;
        tecla = String.fromCharCode(key).toLowerCase();
        letras = " áéíóúabcdefghijklmnñopqrstuvwxyz";
        especiales = "8-37-39-46";

        tecla_especial = false
        for (var i in especiales) {
            if (key == especiales[i]) {
                tecla_especial = true;
                break;
            }
        }

        if (letras.indexOf(tecla) == -1 && !tecla_especial) {
            return false;
        }
    }

    function conComas(valor) {
        valor = valor.substring(valor.length - 2, valor.length) > 0 ? valor : valor.substring(0, valor.length - 3);
        var v = valor.length > 3 ? valor.substring(valor.length - 3, valor.length) : 0;
        valor = v != 0 ? valor.substring(0, valor.length - 3) : valor;
        var nums = new Array();
        var simb = ","; //Éste es el separador
        valor = valor.toString();
        valor = valor.replace(/\D/g, ""); //Ésta expresión regular solo permitira ingresar números
        nums = valor.split(""); //Se vacia el valor en un arreglo
        var long = nums.length - 1; // Se saca la longitud del arreglo
        var patron = 3; //Indica cada cuanto se ponen las comas
        var prox = 2; // Indica en que lugar se debe insertar la siguiente coma
        var res = "";

        while (long > prox) {
            nums.splice((long - prox), 0, simb); //Se agrega la coma
            prox += patron; //Se incrementa la posición próxima para colocar la coma
        }

        for (var i = 0.00; i <= nums.length - 1; i++) {
            res += nums[i]; //Se crea la nueva cadena para devolver el valor formateado
        }
        v = v != 0 ? v : '';

        var t = res + v;
        var a = t.indexOf(',');
        var b = v.indexOf(',');
        var c = v.indexOf('.');

        if (t.length > 3 && v.length == 3 && (a == -1 || b == -1) && c == -1) {
            var t = res + ',' + v;
        }
        t = t.indexOf('.') != -1 ? t : t + '.00';

        return t;
    }

    function soloNumeros(e) {
        var key = window.Event ? e.which : e.keyCode
        return (key >= 48 && key <= 57)
    }

    function soloNumeros6(e) {
        var key = window.Event ? e.which : e.keyCode
        return (key >= 50 && key <= 54)
    }

    function soloNumeros0_6(e) {
        var key = window.Event ? e.which : e.keyCode
        return (key >= 48 && key <= 54)
    }

    function soloNumeros1_99(e) {
        var key = window.Event ? e.which : e.keyCode
        return (key >= 48 && key <= 54)
    }
    $(".correo").on({
        "blur": function(event) {
            $(event.target).val(function(index, value) {
                if ($("[name='errorCorreo']").text() != "Correo valido") {
                    alertToast("Corrija el Correo antes de grabar", 3500);
                    return "";
                }
                return value;
            });
        },
        "keyup": function(event) {
            $(event.target).val(function(index, value) {
                var texto = value;
                var regex = /^[-\w.%+]{1,64}@(?:[A-Z0-9-]{1,63}\.){1,125}[A-Z]{2,63}$/i;
                if (!regex.test(texto)) {
                    $("[name='errorCorreo']").text("Correo invalido");
                } else {
                    $("[name='errorCorreo']").text("Correo valido");
                }
                return texto;
            });
        }
    });
    $(".timerhumanos").on({

        "keyup": function(event) {
            $(event.target).val(function(index, value) {
                var texto = value;
                texto = texto.length < 5 ? '00:00' : texto;
                textoi = texto.split(':');
                texto = textoi[1] != '00' ? (textoi[1] != '30' ? (textoi[0] + '00') : texto) :
                texto;
                return texto;
            });
        }
    });


    $(".cedula").on({
        "blur": function(event) {
            $(event.target).val(function(index, value) {
                if ($("[name='errorCedula']").text() != "Cedula válida") {
                    alertToast("Corrija la Cédula antes de grabar", 3500);
                    return "";
                }
                return value;
            });
        },
        "keyup": function(event) {

            $(event.target).val(function(index, value) {
                var cedula = value;
                if (cedula.length == 10) {
                    //Obtenemos el digito de la region que sonlos dos primeros digitos
                    var digito_region = cedula.substring(0, 2);
                    //Pregunto si la region existe ecuador se divide en 24 regiones
                    if (digito_region >= 1 && digito_region <= 30) {
                        // Extraigo el ultimo digito
                        var ultimo_digito = cedula.substring(9, 10);
                        //Agrupo todos los pares y los sumo
                        var pares = parseInt(cedula.substring(1, 2)) + parseInt(cedula.substring(3,
                            4)) + parseInt(cedula.substring(5, 6)) + parseInt(cedula.substring(
                            7, 8));
                        //Agrupo los impares, los multiplico por un factor de 2, si la resultante es > que 9 le restamos el 9 a la resultante
                        var numero1 = cedula.substring(0, 1);
                        var numero1 = (numero1 * 2);
                        if (numero1 > 9) {
                            var numero1 = (numero1 - 9);
                        }
                        var numero3 = cedula.substring(2, 3);
                        var numero3 = (numero3 * 2);
                        if (numero3 > 9) {
                            var numero3 = (numero3 - 9);
                        }

                        var numero5 = cedula.substring(4, 5);
                        var numero5 = (numero5 * 2);
                        if (numero5 > 9) {
                            var numero5 = (numero5 - 9);
                        }

                        var numero7 = cedula.substring(6, 7);
                        var numero7 = (numero7 * 2);
                        if (numero7 > 9) {
                            var numero7 = (numero7 - 9);
                        }

                        var numero9 = cedula.substring(8, 9);
                        var numero9 = (numero9 * 2);
                        if (numero9 > 9) {
                            var numero9 = (numero9 - 9);
                        }
                        var impares = numero1 + numero3 + numero5 + numero7 + numero9;

                        //Suma total
                        var suma_total = (pares + impares);

                        //extraemos el primero digito
                        var primer_digito_suma = String(suma_total).substring(0, 1);

                        //Obtenemos la decena inmediata
                        var decena = (parseInt(primer_digito_suma) + 1) * 10;

                        //Obtenemos la resta de la decena inmediata - la suma_total esto nos da el digito validador
                        var digito_validador = decena - suma_total;

                        //Si el digito validador es = a 10 toma el valor de 0
                        if (digito_validador == 10)
                            var digito_validador = 0;

                        //Validamos que el digito validador sea igual al de la cedula
                        if (digito_validador == ultimo_digito) {
                            $("[name='errorCedula']").text("Cedula válida");
                        } else {
                            $("[name='errorCedula']").text("Cedula no válida");
                        }

                    } else {
                        // imprimimos en consola si la region no pertenece
                        console.log('Esta cedula no pertenece a ninguna region');
                        $("[name='errorCedula']").text("Cedula no válida");
                    }
                } else {
                    //imprimimos en consola si la cedula tiene mas o menos de 10 digitos
                    console.log('Esta cedula tiene menos de 10 Digitos');
                    $("[name='errorCedula']").text("Cedula no válida");
                }
                return value;
            });
        }
    });
    $(".numero").on({
        "focus": function(event) {
            $(event.target).select();
        },
        "keyup": function(event) {

            $(event.target).val(function(index, value) {
                var vari = value.replace(/\D/g, "")
                return vari;
            });
        }
    });
    $(".numero_serie").on({
        "focus": function(event) {
            $(event.target).select();
        },
        "keyup": function(event) {

            $(event.target).val(function(index, value) {
                var vari = value.replace(/[^0-9,.]/g, '').replace(/,/g, '.');
                return vari;
            });
        }
    });
    
    $(".moneda").on({
        "focus": function(event) {
            $(event.target).select();
        },
        "keyup": function(event) {

            $(event.target).val(function(index, value) {
                var vari = value.replace(/\D/g, "")
                    .replace(/([0-9])([0-9]{2})$/, '$1.$2')
                    .replace(/\B(?=(\d{3})+(?!\d)\.?)/g, ",");
                return vari;
            });
        }
    });

    function agregahora(id) {

        var horario_inicio = $("[name*='hora_inicio[" + id + "]']").val();
        var cant_horas = $("[name*='cant_horas[" + id + "]']").val();

        if (cant_horas == null || cant_horas == '' || cant_horas == 1) {
            cant_horas = 0;
            $("[name*='cant_horas[" + id + "]']").val('').change();

        }

        var fin = (parseInt(horario_inicio.split(":")[0]) + parseInt(cant_horas));
        if (fin < 10) {
            fin = "0" + fin;
        }

        // var horario_fin=$("#horario_fin").val(fin+":00").change();
        // var idhf=$("#idhf").val(fin+":00").change();
        $("[name*='hora_fin[" + id + "]']").val(fin + ":00").change();
        $("[name*='hf[" + id + "]']").val(fin + ":00").change();


    }

    function agregahora2() {
        // var horario_inicio=$("[name*='hora_inicio["+id+"]']" ).val();
        // var cant_horas=$("[name*='cant_horas["+id+"]']" ).val();

        var horario_inicio = $("#horario_inicio").val();
        var cant_horas = $("#cant_horas").val();
        if (cant_horas == null || cant_horas == '' || cant_horas == 1) {
            cant_horas = 0;
            $("#cant_horas").val('').change();
        }
        var fin = (parseInt(horario_inicio.split(":")[0]) + parseInt(cant_horas));
        if (fin < 10) {
            fin = "0" + fin;
        }


        var horario_fin = $("#horario_fin").val(fin + ":00").change();
        var idhf = $("#idhf").val(fin + ":00").change();
    }

    function PedirConfirmacion(id, dato) {
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
            function(isConfirm) {
                if (isConfirm) {
                    switch (dato) {
                        case "save":
                            SaveChanges();
                            break;

                        case "eliminar":
                            var band = 1;
                            Eliminar(id);
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
</script>
<script>
    var dropdowMenu = 0;
    $(".dropdown-menu").addClass("hidden");
    // Get the modal
    var modal = document.getElementById("myModal");
    var modalPrint = document.getElementById("myModalPrint");


    $(".dt-dropdown").on("click", function() {
        var dropdownmenuClass = dropdowMenu != 0 ? $(".dt-dropdown-menu").addClass("hidden") : $(
            ".dt-dropdown-menu").removeClass("hidden");
        if (dropdowMenu == 0)
            $(".dt-dropdown-menu").attr("style", "display:initial");

        dropdowMenu = dropdowMenu != 0 ? 0 : 1;
        if (dropdowMenu == 1)
            $("#mayuda").addClass("hidden");

    });
    var diferencia_dia = function(date1, date2) {
        date1 = date1.split('/');
        date1 = date1[1] + '/' + date1[0] + '/' + date1[2];

        date2 = date2.split('/');
        date2 = date2[1] + '/' + date2[0] + '/' + date2[2];

        dt1 = new Date(date1);
        dt2 = new Date(date2);
        return Math.floor((Date.UTC(dt2.getFullYear(), dt2.getMonth(), dt2.getDate()) - Date.UTC(dt1.getFullYear(),
            dt1.getMonth(), dt1.getDate())) / (1000 * 60 * 60 * 24));
    }

    var incluir_almuerzo = function(hora1, hora2) {
        hora1 = hora1.split(':');
        hora2 = hora2.split(':');
        if ((hora1[0] >= 12) && (hora1[0] < 14) && (hora2[0] >= 13))
            return 0;
        else
            return 1;
    }

    $(".mensaje").on({
        "keyup": function(event) {
            if (event.keyCode == 13) {
                enviarMensaje();
            }
        }
    });

    function removeElement(array, element) {
        var index = array.indexOf(element);
        if (index >= -1) {
            // modifies array in place
            array.splice(index, 1);
        }
    }


    function PedirConfirmacion(id, dato) {
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
            function(isConfirm) {
                if (isConfirm) {
                    switch (dato) {
                        case "save":
                            SaveChanges();
                            break;

                        case "eliminar":
                            var band = 1;
                            Eliminar(id);
                            break;
                        case "activar":
                            var band = 0;
                            DeleteChanges(id, band);
                            break;
                    }
                } else {
                    swal("�Cancelado!", "No se registraron cambios...", "error");
                }
            });
    }
</script>
<script src="{{ url('adminlte/plugins/select2/') }}/select21.full.min.js"></script>
<script>
    var cargaTicketModalDerecho = 0;

    $("#mesaAyuda").on("click", function() {
        if (cargaTicketModalDerecho == 0) {
            cargaTicketModalDerecho = 1;
            $("#mayuda").removeClass("hidden");

        } else {
            cargaTicketModalDerecho = 0;
            $("#mayuda").addClass("hidden");
        }
        $(".dropdown-menu").hide();
        dropdowMenu = 0;
    });
    $(".vinculoSolicitud").on("click", function() {
        var html = $("#satisfaccionTicket").html();
        if (html.trim() != "") {
            $("#creacionTicket").addClass("hidden");
        }
    });

    $("#mayuda").addClass("hidden");
    var ocultarMarcadorCont = 1;

    function minMaxTele() {
        if (ocultarMarcadorCont == 0) {
            $(".botonF123").addClass("ocultarMarcador");
            $(".btnminus").removeClass("fa-minus");
            $(".btnminus").addClass("fa-plus");
            ocultarMarcadorCont = 1;
        } else {
            ocultarMarcadorCont = 0;
            $(".botonF123").removeClass("ocultarMarcador");
            $(".btnminus").addClass("fa-minus");
            $(".btnminus").removeClass("fa-plus");
        }
    }
    $(".mayuscula").on("keyup",function(){
                var Mayuscula=$(this).val();
                $(this).val(Mayuscula.toUpperCase());
        });
    $(".form-control").on("keyup",function(){
        var length=$(this).val().length;
        if(length>250){
            var trimmedString = $(this).val().substring(0, 250);
            $(this).val(trimmedString); 
        }
    });

        
</script>
@yield('javascript')

<script src="{{ url('adminlte/plugins/select2/') }}/select21.full.min.js"></script>
