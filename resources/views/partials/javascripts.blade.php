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

<script src="{{ url('adminlte3/') }}/popper.min.js"></script>
<script src="{{ url('adminlte3/plugins/bootstrap/js') }}/bootstrap.min.js"></script>
<script src="{{ url('adminlte/js') }}/dataTables.colReorder.min.js"></script>
<script src="{{ url('adminlte//plugins/datatables/') }}/dataTables.responsive.min.js"></script>
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
<script src="{{ url('adminlte3/plugins/inputmask/jquery.inputmask.js') }}"></script>
<script>
    window._token = '{{ csrf_token() }}';
</script>

<script src="{{ url('js/libs/highchart/highcharts.js') }}"></script>
<script src="{{ url('js/libs/highchart/exporting.js') }}"></script>
<script src="{{ url('js/libs/highchart/offline-exporting.js') }}"></script>
<script>
    var tiempo_session_core = "{{ config('app.session_core') }}";
</script>
<script src="{{ asset('js/modules') }}/utils.js?v=57"></script>
<script src="{{ url('js/modules/Core.js') }}"></script>
<script src="{{ url('adminlte3/plugins/chosen/js') }}/chosen.jquery.min.js"></script>
<script>
    var base_url = '{{ url('/') }}';
</script>
<script src="{{ url('reloj_virtual/script.js?v=11') }}"></script>
<script type="text/javascript"></script>

<script>
    function removerShowMensajes() {
        var incluirShow = $('.incluirShow-').hasClass('show')
        if (incluirShow) $('.incluirShow').removeClass('show')
        else $('.incluirShow').addClass('show');

        var showCompromisos = $('.showCompromisos-').hasClass('show')
        if (showCompromisos) $('.showCompromisos').removeClass('show')
        else $('.showCompromisos').addClass('show');
    }


    function removerShowMensajesCompromisos() {
        var showCompromisos = $('.showCompromisos-').hasClass('show')
        if (showCompromisos) $('.showCompromisos').removeClass('show')
        else $('.showCompromisos').addClass('show');
    }

    $(".valida_hoy").attr("min", new Date().toISOString().split("T")[0]);

    function downloadURI(uri, name) {
        var link = document.createElement("a");
        // link.download = name;
        link.href = uri;
        link.setAttribute('target', '_blank');
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        delete link;
    }
    $('.sidebar-mini').on('webkitTransitionEnd transitionend', function(e) {
        if (!e.target.className == "model-open")
            $('body').attr('style', 'overflow-y: hidden!important;');
        else
            $('body').attr('style', 'overflow-y: auto!important;');
        if ($("body").hasClass("modal-open")) {
            $("body").attr("style", "overflow-y: hidden!important;");
        }
    });
    $(".cerrarmodal").on("click", function() {
        $('body').attr('style', 'overflow-y: auto!important;');
    });
    var abreChatTicket = 0;
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

    function limpiarFile() {
        $('#customFile').val(null);
        $('#customfilelabel').text('Archivo');
    }
    $(document).ready(function() {
        $('.chosen').chosen();
        $(".carga_titulos_content").html($(".titulos_content_left").html());
        $('input[type="file"]').on("change", function() {
            let filenames = [];
            let files = this.files;
            if (files.length > 1) {
                filenames.push("Total Files (" + files.length + ")");
            } else {
                for (let i in files) {
                    if (files.hasOwnProperty(i)) {
                        filenames.push(files[i].name);
                    }
                }
            }
            $(this)
                .next(".custom-file-label")
                .html(filenames.join(","));
        });
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
    $(".chosen").chosen({
        search_contains: true, // kwd can be anywhere
        max_selected_options: 1,
        max_shown_results: 5, // show only 5 suggestions at a time
        width: "95%",
        no_results_text: "Oops, nothing found!"
    });

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

        arregloSeguimientoDataTicket = data.seguimiento;
        usuarioSoporteActivo = data['soporte_ticket'];
        var x1 = $.grep(arregloSeguimientoDataTicket, function(element, index) {
            return element.estado == 'ACT';
        });
        var data1 = x1[0];
        $("#idTickectVigenteSeguimiento").val(data1['id']);
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

    var modal = document.getElementById("myModal");
    var modalPrint = document.getElementById("myModalPrint");

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
<script>
    var cargaTicketModalDerecho = 0;

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
    $(".mayuscula").on("keyup", function() {
        var Mayuscula = $(this).val();
        $(this).val(Mayuscula.toUpperCase());
    });

    function formatearFecha(fecha) {
        return ((fecha < 10 ? ('0' + fecha) : fecha) < 10 ? ('0' + fecha) : fecha);
    }

    function addDays(date, days) {
        var result = new Date(date);
        result.setDate(result.getDate() + days);
        return result;
    }

    function abrirModalBootstrap4() {
        document.querySelector('#modal_cargando').click();
    }

    function cerrarModalBootstrap4(mensaje, tipo = false) {
        document.querySelector('#cerrar_modal_cargando').click();

        setTimeout(function() {
            if (!tipo)
                alertToast(mensaje, 3500);
            else
                alertToastSuccess(mensaje, 3500);

        }, 1000);
    }
    const edad = fechaNac => {
        if (!fechaNac || isNaN(new Date(fechaNac))) return;
        const hoy = new Date();
        const dateNac = new Date(fechaNac);
        if (hoy - dateNac < 0) return;
        let dias = hoy.getUTCDate() - dateNac.getUTCDate();
        let meses = hoy.getUTCMonth() - dateNac.getUTCMonth();
        let years = hoy.getUTCFullYear() - dateNac.getUTCFullYear();
        if (dias < 0) {
            meses--;
            dias = 30 + dias;
        }
        if (meses < 0) {
            years--;
            meses = 12 + meses;
        }

        return [years, meses, dias];
    }
</script>
<script>
    // Jquery Dependency

    $("input[data-type='moneda_current']").on({
        keyup: function() {
            formatCurrency($(this));
        },
        blur: function() {
            formatCurrency($(this), "blur");
        }
    });


    function formatNumber(n) {
        // format number 1000000 to 1,234,567
        return n.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",")
    }


    function formatCurrency(input, blur) {
        // appends $ to value, validates decimal side
        // and puts cursor back in right position.

        // get input value
        var input_val = input.val();

        // don't validate empty input
        if (input_val === "") {
            return;
        }

        // original length
        var original_len = input_val.length;

        // initial caret position
        var caret_pos = input.prop("selectionStart");

        // check for decimal
        if (input_val.indexOf(".") >= 0) {

            // get position of first decimal
            // this prevents multiple decimals from
            // being entered
            var decimal_pos = input_val.indexOf(".");

            // split number by decimal point
            var left_side = input_val.substring(0, decimal_pos);
            var right_side = input_val.substring(decimal_pos);

            // add commas to left side of number
            left_side = formatNumber(left_side);

            // validate right side
            right_side = formatNumber(right_side);

            // On blur make sure 2 numbers after decimal
            if (blur === "blur") {
                right_side += "00";
            }

            // Limit decimal to only 2 digits
            right_side = right_side.substring(0, 2);

            // join number by .
            input_val = "$" + left_side + "." + right_side;

        } else {
            // no decimal entered
            // add commas to number
            // remove all non-digits
            input_val = formatNumber(input_val);
            input_val = "$" + input_val;

            // final formatting
            if (blur === "blur") {
                input_val += ".00";
            }
        }

        // send updated string to input
        input.val(input_val);

        // put caret back in the right position
        var updated_len = input_val.length;
        caret_pos = updated_len - original_len + caret_pos;
        input[0].setSelectionRange(caret_pos, caret_pos);
    }
</script>
<script>
    /*  window.onload = function() {
         inactivityTime();
    }
    const inactivityTime = () => {
        let t;
        window.onload = resetTimer;
        document.onmousemove = resetTimer;
        document.onkeypress = resetTimer;
        document.onmousedown = resetTimer; // touchscreen presses
        document.ontouchstart = resetTimer;
        document.onclick = resetTimer; // touchpad clicks
        document.onkeydown = resetTimer;
        const logout_ = () => {
            document.getElementById('logout-form').submit();
        }
        function resetTimer() {
            clearTimeout(t);
            t = setTimeout(logout_, 600000) // 10 minutos 600000 milisegundos
        }
    }*/
</script>
@yield('javascript')
