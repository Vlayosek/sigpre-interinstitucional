$(function () {
    $(".decimal").keyup(function () {
        this.value = this.value.replace(/[^0-9\.]/g, "");
    });
    $(".metafinal").keyup(function () {
        this.value = this.value.replace(/[^0-9NO APLICA\.]/g, "");
    });
    $(".cedula").on({
        keyup: function (event) {
            $(event.target).val(function (index, value) {
                var cedula = value;
                if (cedula.length == 10) {
                    //Obtenemos el digito de la region que sonlos dos primeros digitos
                    var digito_region = cedula.substring(0, 2);
                    //Pregunto si la region existe ecuador se divide en 24 regiones
                    if (digito_region >= 1 && digito_region <= 30) {
                        // Extraigo el ultimo digito
                        var ultimo_digito = cedula.substring(9, 10);
                        //Agrupo todos los pares y los sumo
                        var pares =
                            parseInt(cedula.substring(1, 2)) +
                            parseInt(cedula.substring(3, 4)) +
                            parseInt(cedula.substring(5, 6)) +
                            parseInt(cedula.substring(7, 8));
                        //Agrupo los impares, los multiplico por un factor de 2, si la resultante es > que 9 le restamos el 9 a la resultante
                        var numero1 = cedula.substring(0, 1);
                        var numero1 = numero1 * 2;
                        if (numero1 > 9) {
                            var numero1 = numero1 - 9;
                        }
                        var numero3 = cedula.substring(2, 3);
                        var numero3 = numero3 * 2;
                        if (numero3 > 9) {
                            var numero3 = numero3 - 9;
                        }

                        var numero5 = cedula.substring(4, 5);
                        var numero5 = numero5 * 2;
                        if (numero5 > 9) {
                            var numero5 = numero5 - 9;
                        }

                        var numero7 = cedula.substring(6, 7);
                        var numero7 = numero7 * 2;
                        if (numero7 > 9) {
                            var numero7 = numero7 - 9;
                        }

                        var numero9 = cedula.substring(8, 9);
                        var numero9 = numero9 * 2;
                        if (numero9 > 9) {
                            var numero9 = numero9 - 9;
                        }
                        var impares =
                            numero1 + numero3 + numero5 + numero7 + numero9;

                        //Suma total
                        var suma_total = pares + impares;

                        //extraemos el primero digito
                        var primer_digito_suma = String(suma_total).substring(
                            0,
                            1
                        );

                        //Obtenemos la decena inmediata
                        var decena = (parseInt(primer_digito_suma) + 1) * 10;

                        //Obtenemos la resta de la decena inmediata - la suma_total esto nos da el digito validador
                        var digito_validador = decena - suma_total;

                        //Si el digito validador es = a 10 toma el valor de 0
                        if (digito_validador == 10) var digito_validador = 0;

                        //Validamos que el digito validador sea igual al de la cedula
                        if (digito_validador == ultimo_digito) {
                            $("[name='errorCedula']").text("Cédula válida");
                        } else {
                            $("[name='errorCedula']").text("Cédula no válida");
                        }
                    } else {
                        // imprimimos en consola si la region no pertenece
                        console.log(
                            "Esta cedula no pertenece a ninguna region"
                        );
                        $("[name='errorCedula']").text("Cédula no válida");
                    }
                } else {
                    //imprimimos en consola si la cedula tiene mas o menos de 10 digitos
                    console.log("Esta cedula tiene menos de 10 Digitos");
                    $("[name='errorCedula']").text("Cédula no válida");
                }
                return value;
            });
        },
    });
    $(".moneda_full").on({
        keyup: function () {
            formatCurrency($(this));
        },
        blur: function () {
            formatCurrency($(this), "blur");
        },
    });


    $(".numero").on({
        focus: function (event) {
            $(event.target).select();
        },
        keyup: function (event) {
            $(event.target).val(function (index, value) {
                var vari = value.replace(/\D/g, "");
                return vari;
            });
        },
    });
    $(".correo").on({
        keyup: function (event) {
            $(event.target).val(function (index, value) {
                var texto = value;
                var regex =
                    /^[-\w.%+]{1,64}@(?:[A-Z0-9-]{1,63}\.){1,125}[A-Z]{2,63}$/i;
                if (!regex.test(texto)) {
                    $("[name='errorCorreo']").text("Correo no válido");
                } else {
                    $("[name='errorCorreo']").text("Correo válido");
                }
                return texto;
            });
        },
    });
    ///  cerrar_sesion_post(true);
});

function inactividad() {
  /*  swal({
        title: "Cerrado automático de Sesión",
        text: "Se cerrará la sesión por inactividad de 30 segundos",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Si!",
        cancelButtonText: "No",
        closeOnConfirm: true,
        closeOnCancel: false
    },
        function (isConfirm) {
            if (isConfirm) {*/
                document.getElementById('logout-form').submit();
          /*  } else {
                swal("Cancelado!", "No se ha realizado acciones", "error");
                return false;
            }
        }
    )*/

}
var t = null;
/*
function contadorInactividad() {
    t = setTimeout("inactividad()", tiempo_session_core);
}
window.onblur = window.onmousemove = window.onkeyup=function() {
    if (t) clearTimeout(t);
    contadorInactividad();
}*/
/*
var inFormOrLink = false;
$("a").on("click", function () {
    inFormOrLink = true;
});
$("form").on("submit", function () {
    inFormOrLink = true;
});
$("body").on("click", function (e) {
    if ($(e.target).hasClass("btn")) inFormOrLink = true;
});
$("body").on("keydown", function (e) {
    if (e.which == 116) inFormOrLink = true;
});
$("body").on("keydown", function (e) {
    if (e.keyCode == 82 && e.ctrlKey) inFormOrLink = true;
});

function cerrar_sesion_post(inicial = false) {
    console.log("evento enviado");
    var data = new FormData();
    data.append("inicial", inicial == true ? inicial : inFormOrLink);
    inFormOrLink = false;
    var objApiRest = new AJAXRestFilePOST("/logout_session_post", data);
    objApiRest.extractDataAjaxFile(function (_resultContent) {
        if (_resultContent.status == 200) {
            console.log("ok");
            return true;
        } else {
            console.log("error");
            return false;
        }
    });
}
*/

function renderizarSelect2() {
    $(".select2").each(function () {
        var elemento = $(this).closest(".modal");
        elemento = elemento.id;
        $(this).select2({
            dropdownParent: $("#" + elemento + ""),
        });
    });
}

function retornaModalSelect2(id) {
    return $(`#${id}`);
}

function retornaModalClassSelect2(id) {
    return $(`.${id}`);
}

function buscarErroresInput(clase = null) {
    $(".erroresInput").addClass("hidden");
    var errores = false;
    $(".requerido").each(function () {
        var value = $(this).val();
        let maximo = "";
        var placeholder =
            typeof $(this).attr("placeholder") === "undefined"
                ? typeof $(this).attr("placeholder_") === "undefined"
                    ? $(this).attr("name")
                    : $(this).attr("placeholder_")
                : $(this).attr("placeholder");
        maxlength = $(this).attr("maxLength");
        if ($(this).hasClass("valor_maximo")) {
            if (typeof maxlength === "undefined") {
                $(this).attr("maxLength", 250);
                maxlength = $(this).attr("maxLength");
            }
            maximo = "máximo: " + maxlength + " caracteres";
        }
        var id = $(this).attr("id");
        var texto = "";
        var cargarError = $(this).next();
        if (!cargarError.hasClass("erroresInput")) {
            let li = document.createElement("span");
            li.setAttribute("style", "color:red");
            li.setAttribute("class", "erroresInput hidden");
            //   insertAfter(li,  $(this));
            $(
                '<span style="color:red" class="erroresInput hidden">Error</span>'
            ).insertAfter($(this));
            cargarError = $(this).next();
        }
        if (value == null || value == "") {
            cargarError.removeClass("hidden");
            texto +=
                "\n" +
                "Error: Se requiere llenar el campo: " +
                placeholder +
                " " +
                maximo;
            errores = true;
        }
        if (value != null && value != "") {
            //validaciones

            if ($(this).hasClass("correo")) {
                var regex =
                    /^[-\w.%+]{1,64}@(?:[A-Z0-9-]{1,63}\.){1,125}[A-Z]{2,63}$/i;
                if (!regex.test(value)) {
                    cargarError.removeClass("hidden");
                    texto +=
                        "\n" + cargarError.text() + "\n" + "Correo invalido";
                    errores = true;
                }
            }
        }

        if (errores) cargarError.text(texto);
    });
    if (clase != null) clase = "." + clase;
    else clase = "";
    $(".b-requerido" + clase + "").each(function () {
        var value = $(this).val();
        let maximo = "";
        var placeholder =
            typeof $(this).attr("placeholder") === "undefined"
                ? typeof $(this).attr("placeholder_") === "undefined"
                    ? $(this).attr("name")
                    : $(this).attr("placeholder_")
                : $(this).attr("placeholder");
        maxlength = $(this).attr("maxLength");
        if ($(this).hasClass("valor_maximo")) {
            if (typeof maxlength === "undefined") {
                $(this).attr("maxLength", 250);
                maxlength = $(this).attr("maxLength");
            }
            maximo = "máximo: " + maxlength + " caracteres";
        }
        var id = $(this).attr("id");
        var texto = "";
        var cargarError = $(this).next();
        if (!cargarError.hasClass("erroresInput")) {
            let li = document.createElement("span");
            li.setAttribute("style", "color:red");
            li.setAttribute("class", "erroresInput hidden");
            //   insertAfter(li,  $(this));
            $(
                '<span style="color:red" class="erroresInput hidden">Error</span>'
            ).insertAfter($(this));
            cargarError = $(this).next();
        }
        if (value == null || value == "") {
            cargarError.removeClass("hidden");
            texto +=
                "\n" +
                "Error: Se requiere llenar el campo: " +
                placeholder +
                " " +
                maximo;
            errores = true;
        }
        if (value != null && value != "") {
            //validaciones

            if ($(this).hasClass("b-correo")) {
                var regex =
                    /^[-\w.%+]{1,64}@(?:[A-Z0-9-]{1,63}\.){1,125}[A-Z]{2,63}$/i;
                if (!regex.test(value)) {
                    cargarError.removeClass("hidden");
                    texto +=
                        "\n" + cargarError.text() + "\n" + "Correo invalido";
                    errores = true;
                }
            }
        }

        if (errores) cargarError.text(texto);
    });

    return errores;
}

function transaccionToogle(element, ocultar = null) {
    if (ocultar != null) {
        var padre = $(element).closest("ul").removeClass("show");
        return false;
    }

    if ($(element).next("ul").hasClass("show"))
        $(element).next("ul").removeClass("show");
    else {
        $(".dropdown-menu").removeClass("show");
        $(element).next("ul").addClass("show");
    }
}

function validarFechasEntradas(fecha_inicio, fecha_fin, maximo = 31) {
    if (
        fecha_inicio == "null" ||
        fecha_fin == "null" ||
        fecha_inicio == "0" ||
        fecha_fin == "0" ||
        fecha_inicio == "" ||
        fecha_fin == "" ||
        fecha_inicio == null ||
        fecha_fin == null
    ) {
        alertToast("Debe colocar un rango de fecha", 3500);
        return false;
    }
    var fecha1 = moment(fecha_inicio);
    var fecha2 = moment(fecha_fin);
    var fecha3 = fecha2.diff(fecha1, "days");
   /*  if (fecha3 > maximo) {
        alertToast(
            "Los rangos de fechas no pueden extender a " + maximo + " dias",
            3500
        );
        return false;
    }*/
    if (fecha3 < 0) {
        alertToast(
            "La fecha de inicio no puede ser mayor a la fecha fin",
            3500
        );
        return false;
    }


    if (maximo != null) {
        var fecha1 = moment(fecha_inicio);
        var fecha2 = moment(fecha_fin);
        var fecha3 = fecha2.diff(fecha1, "days");
        if (fecha3 > maximo) {
            alertToast(
                "Los rangos de fechas no pueden extender a " + maximo + " dias",
                3500
            );
            return false;
        }
        return true;
    }else return true;
}

function validarFechaInicio(fecha_inicio, fecha_fin, maximo = 31) {
    if (
        fecha_inicio == "null" ||
        fecha_fin == "null" ||
        fecha_inicio == "0" ||
        fecha_fin == "0" ||
        fecha_inicio == "" ||
        fecha_fin == "" ||
        fecha_inicio == null ||
        fecha_fin == null
    ) {
        alertToast("Debe colocar un rango de fecha", 3500);
        return false;
    }
    var fecha1 = moment(fecha_inicio);
    var fecha2 = moment(fecha_fin);
    var fecha3 = fecha2.diff(fecha1, "days");
    if (fecha3 > maximo) {
        alertToast(
            "La fecha de Inicio no se puede extender mas de " +
                maximo +
                " dias",
            3500
        );
        return false;
    }
    return true;
}

function diaSemana(id, fecha = null) {
    if (fecha == null) {
        var x = document.getElementById(id);
        var date = new Date(x.value.replace(/-+/g, "/"));
    } else {
        var date = new Date(fecha.replace(/-+/g, "/"));
    }
    let options = {
        weekday: "long",
    };
    return removeAccents(date.toLocaleDateString("es-MX", options));
}

function removeAccents(str) {
    return str.normalize("NFD").replace(/[\u0300-\u036f]/g, "");
}

function descargarConectity() {
    downloadURI(
        "https://prusigpre.presidencia.gob.ec/storage/conectity.vbs",
        "conectity"
    );
}
function formatValorMonedaIdText(e) {
    var id_input = $(e).attr('id');
    var valor = $("#" + id_input + "").text() != null ? $("#" + id_input + "").text() : 0;

    if(valor==0) return 0;
    if (valor === "") return 0;

    if (valor.indexOf(".") >= 0) {
        var decimal_pos = valor.indexOf(".");
        var left_side = valor.substring(0, decimal_pos);
        var right_side = valor.substring(decimal_pos);
        left_side = formatNumber(left_side);
        right_side = formatNumber(right_side);
        right_side = right_side.substring(0, 2);
        valor = "$" + left_side + "." + right_side;
    } else {
        valor = formatNumber(valor);
        valor = "$" + valor;

    }
    $("#" + id_input + "").text(valor) ;
}
function formatValorMonedaId(e) {
    var id_input = $(e).attr('id');
    var valor = $("#" + id_input + "").val() != null ? $("#" + id_input + "").val() : 0;

    if(valor==0) return 0;
    if (valor === "") return 0;

    if (valor.indexOf(".") >= 0) {
        var decimal_pos = valor.indexOf(".");
        var left_side = valor.substring(0, decimal_pos);
        var right_side = valor.substring(decimal_pos);
        left_side = formatNumber(left_side);
        right_side = formatNumber(right_side);
        right_side = right_side.substring(0, 2);
        valor = "$" + left_side + "." + right_side;
    } else {
        valor = formatNumber(valor);
        valor = "$" + valor;

    }
    $("#" + id_input + "").val(valor) ;
}
function formatValorMoneda(valor) {
    if(valor==0) return "0.00";
    if (valor === "") return "0.00";
    if (!valor.includes('.')) valor = valor + '.00';
    if (valor.indexOf(".") >= 0) {
        var decimal_pos = valor.indexOf(".");
        var left_side = valor.substring(0, decimal_pos);
        var right_side = valor.substring(decimal_pos);
        left_side = formatNumber(left_side);
        right_side = formatNumber(right_side);
        right_side = right_side.substring(0, 2);
        valor = "$" + left_side + "." + right_side;
    } else {
        valor = formatNumber(valor);
        valor = "$" + valor;

    }
    return valor;
}
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
$("input[data-type='moneda_data']").on({
    keyup: function() {
        formatCurrency($(this));
    },
    blur: function() {
        formatCurrency($(this), "blur");
    }
});
function limpiarDataMoney(data){
    data=data.replaceAll(  ",", "" );
    data =    data.replaceAll(  "$", ""  );
    return data;
}
/*
function inactivityTime() {
    let t;
    window.onload = resetTimer;
    document.onload = resetTimer;
    document.onmousemove = resetTimer;
    document.onmousedown = resetTimer; // touchscreen presses
    document.ontouchstart = resetTimer;
    document.onclick = resetTimer; // touchpad clicks
    document.onkeydown = resetTimer; // onkeypress is deprectaed
    const logout = () => {
        document.getElementById("logout-form").submit();
    };

    function resetTimer() {
        clearTimeout(t);
        t = setTimeout(logout, 600000); // 10 minutos 600000 milisegundos
    }
}

function fullScreen() {
    (document.fullScreenElement && null !== document.fullScreenElement) ||
    (!document.mozFullScreen && !document.webkitIsFullScreen)
        ? document.documentElement.requestFullScreen
            ? document.documentElement.requestFullScreen()
            : document.documentElement.mozRequestFullScreen
            ? document.documentElement.mozRequestFullScreen()
            : document.documentElement.webkitRequestFullScreen &&
              document.documentElement.webkitRequestFullScreen(
                  Element.ALLOW_KEYBOARD_INPUT
              )
        : document.cancelFullScreen
        ? document.cancelFullScreen()
        : document.mozCancelFullScreen
        ? document.mozCancelFullScreen()
        : document.webkitCancelFullScreen && document.webkitCancelFullScreen();
}

function checkSession() {
    $.ajax({
        type: "GET",
        url: "/checksession",
        headers: { "X-CSRF-TOKEN": $("#token").val() },
        cache: false,
        data: getHour(),
        success: function (res) {
            if (!res.login) {
                window.location.href = "/";
            }
        },
    });
}

function getHour() {
    var time = new Date();
    var hour = time.getHours();
    var minute = time.getMinutes();
    var seconds = time.getSeconds();

    var str_hora = new String(hour);
    if (str_hora.length == 1) {
        hour = "0" + hour;
    }
    var str_minuto = new String(minute);
    if (str_minuto.length == 1) {
        minute = "0" + minute;
    }
    var str_segundo = new String(seconds);
    if (str_segundo.length == 1) {
        seconds = "0" + seconds;
    }

    return hour + ":" + minute + ":" + seconds;
}

function timeNow() {
    setTimeout("timeNow()", 1000);

    $("#lbl_time").html("<b>Hora: </b>" + getHour());
}

/*
 Funcion que me permite realizar dependencias entre combos
 */
function selectDependent(father, children, check, multiple) {
    valueFather = $(father).val() == "" ? 0 : $(father).val();

    if (valueFather != "0") {
        var objApiRest = new AJAXRest(
            "/catalog/dataBySelectSingle/" + valueFather,
            {},
            "POST"
        );
        objApiRest.extractDataAjax(function (_resultContent, status) {
            if (status == 200) {
                if (!multiple) {
                    $(children).html('<option value="0">-Seleccione-</option>');
                } else {
                    $(children).html("");
                }

                if (_resultContent.data.length == 0) {
                    alertToast("La solicitud no obtuvo resultados", 3500);
                } else {
                    $.each(_resultContent.data, function (key, value) {
                        var checked = check == key ? "selected" : "";
                        $(children).append(
                            "<option value=" +
                                key +
                                " " +
                                checked +
                                ">" +
                                value +
                                "</option>"
                        );
                    });
                }
                if (!multiple) {
                    $(children).val(check).trigger("change");
                }
            } else {
                alertToast(_resultContent.message, 3500);
            }
        });
    } else {
        if (!multiple) {
            $(children).html('<option value="0">-Seleccione-</option>');
        } else {
            $(children).html("");
        }
    }
}

function verifyKeyPressPattern(e, patron, object, width) {
    var tecla = document.all ? e.keyCode : e.which; // 2
    if (tecla == 8 || tecla == 0) {
        $(object).removeAttr("style");
        return true; // 3
    }
    var te = String.fromCharCode(tecla); // 5
    var result = patron.test(te);
    if (!result) {
        $(object).attr("style", "background-color: #F8E0E6;" + width);
    } else {
        $(object).attr("style", "background-color: #fff;" + width);
    }
    return result;
}

function putAttrInput(elements, flag) {
    if (!flag) {
        $.each(elements, function (index, value) {
            $("#" + value).removeAttr("disabled");
        });
    } else {
        $.each(elements, function (index, value) {
            $("#" + value).attr("disabled", "disabled");
        });
    }
}

function showHideInput(elements, flag) {
    if (!flag) {
        $.each(elements, function (index, value) {
            $("#" + value)
                .parent()
                .parent()
                .show();
        });
    } else {
        $.each(elements, function (index, value) {
            $("#" + value)
                .parent()
                .parent()
                .hide();
        });
    }
}

function clearInputName(elements, pvalue) {
    $.each(elements, function (index, value) {
        $("input[name=" + value + "]").val(pvalue);
    });
}

function clearSelectName(elements, pvalue) {
    $.each(elements, function (index, value) {
        $("select[name=" + value + "]").val(pvalue);
    });
}

function clearInput(elements, pvalue) {
    $.each(elements, function (index, value) {
        $("#" + value).val(pvalue);
    });
}

function clearInputSelect(elements, pvalue) {
    $.each(elements, function (index, value) {
        $("#" + value)
            .val(pvalue)
            .trigger("change");
    });
}

function addOptionSelect(elements, pvalue) {
    $.each(elements, function (index, value) {
        $("#" + value).prepend(pvalue);
    });
}

function fileInputBasicCustom(_maxFileSizeByte, _maxFileSizeMB, _extensions) {
    $(".file-input")
        .fileinput({
            maxFileSize: _maxFileSizeByte,
            showPreview: false,
            showUpload: false,
            browseLabel: "Buscar",

            removeLabel: "",
            language: "en",
            browseIcon: '<i class="icon-file-plus"></i>',
            browseClass: "btn btn-primary  btn-xs",
            removeClass: "btn bg-pink-400 btn-xs",
            previewFileIconClass: "file-icon",
            removeTitle: "Quitar archivo seleccionado",
            layoutTemplates: {
                icon: '<i class="icon-file-check"></i>',
            },
            initialCaption: "",
            allowedFileExtensions: _extensions,
        })
        .on("fileerror", function (event, data) {
            alertToast(
                "Solo se admiten extensiones pdf, con peso m\u00E1ximo de " +
                    _maxFileSizeMB +
                    " MB",
                4000
            );
            $(data).fileinput("clear");
        });
}

function referencePathOriginal(_ref) {
    var namefile = $(_ref).attr("data-namefile");
    var pathdoc = $(_ref).attr("data-path");
    var module = $(_ref).attr("data-module");
    var divurlmodal = $(_ref).attr("data-div");
    $.ajax({
        url: "/global/get-file-ftp",
        type: "post",
        headers: { "X-CSRF-TOKEN": $("input[name='_token']").val() },
        data: { namefile: namefile, pathdoc: pathdoc, module: module },
        dataType: "json",
        success: function (result) {
            if (result.link.trim() != "none") {
                viewModalURL("/" + result.link);
                $("#" + divurlmodal).html(
                    "<span onclick='viewModalURL(\"/" +
                        result.link +
                        "\")' class='label bg-teal'  style='cursor:pointer'>" +
                        namefile +
                        "</span>"
                );
            } else {
                alertToast("NO SE PUEDE OBTENER EL ARCHIVO SOLICITADO", 3500);
            }
        },
        error: function (e) {
            alertToast("NO SE PUEDE OBTENER EL ARCHIVO SOLICITADO", 3500);
        },
        fail: function (result) {
            alertToast("NO SE PUEDE OBTENER EL ARCHIVO SOLICITADO", 3500);
        },
    });
}

function b64toBlob(b64Data, contentType, sliceSize) {
    contentType = contentType || "";
    sliceSize = sliceSize || 512;

    var byteCharacters = atob(b64Data);
    var byteArrays = [];

    for (var offset = 0; offset < byteCharacters.length; offset += sliceSize) {
        var slice = byteCharacters.slice(offset, offset + sliceSize);

        var byteNumbers = new Array(slice.length);
        for (var i = 0; i < slice.length; i++) {
            byteNumbers[i] = slice.charCodeAt(i);
        }

        var byteArray = new Uint8Array(byteNumbers);

        byteArrays.push(byteArray);
    }

    var blob = new Blob(byteArrays, { type: contentType });
    return blob;
}

function putAttrInputCustom(elements, flag, attributes, valueAttr) {
    if (!flag) {
        $.each(elements, function (index, value) {
            $("#" + value).removeAttr(attributes);
        });
    } else {
        $.each(elements, function (index, value) {
            $("#" + value).attr(attributes, valueAttr);
        });
    }
}

function putAttrArray(elements, attributes) {
    $.each(elements, function (index, value) {
        $("#" + value).attr(attributes);
    });
}

function cargarLoading() {
    swal({
        title: "Loading...",
        text: "Please wait",
        icon: "/images/loading.gif",
        button: false,
        closeOnClickOutside: false,
        closeOnEsc: false,
        showConfirmButton: false,
    });
}

function cerrarLoading() {
    $(".confirm").click();
    hideLoading();
}
var AJAXRestFilePOST = function (path, parameters) {
    //alert(path);

    if (
        path.indexOf("buscarMensajes") == -1 &&
        path != "/inventario/mantenimientoProducto"
    ) {
    }

    this._path = path;
    this._parameters = parameters;
    this._resultContent = {};
    this.extractDataAjaxFile = function (callback) {
        $.ajax({
            url: this._path,
            type: "POST",
            dataType: "json",
            data: this._parameters,
            enctype: "multipart/form-data",
            cache: false,
            contentType: false,
            processData: false,
            headers: { "X-CSRF-TOKEN": $("input[name='_token']").val() },
            async: false,

            success: function (msg) {
                this._resultContent = msg;
                callback(this._resultContent, 200);
                if (
                    path.indexOf("buscarMensajes") == -1 &&
                    path != "/inventario/mantenimientoProducto"
                ) {
                    $(".confirm").click();
                }
                hideLoading();
            },
            error: function (xhr, status) {
                if (
                    path.indexOf("buscarMensajes") == -1 &&
                    path != "/inventario/mantenimientoProducto"
                ) {
                    $(".confirm").click();
                }
                hideLoading();
                this._resultContent = {};
                if (xhr.status == 422) {
                    var errores = "";
                    errors = xhr.responseJSON;
                    $.each(errors.errors, function (key, value) {
                        errores += value[0] + "\n";
                    });
                    if (errores.trim() != "") {
                        this._resultContent = { message: errores, code: 422 };
                    }
                } else {
                    console.log(xhr);
                    if (xhr.status == "404") {
                        this._resultContent = {
                            message: "C\u00F3digo o P\u00E1gina no encontrado",
                            code: 404,
                        };
                    } else {
                        this._resultContent = {
                            message:
                                "Error de procesamiento (cod: " +
                                xhr.status +
                                ")\n" +
                                xhr.responseText,
                            code: 500,
                        };
                    }
                }

                callback(this._resultContent, xhr.status);
            },
            beforeSend: function () {
                showLoading();
            },
        });
    };

    function ajaxrequest(rtndata) {}
};
var AJAXRest = function (path, parameters, typeAjax) {
    swal({
        title: "Loading...",
        text: "Please wait",
        icon: "/images/loading.gif",
        button: false,
        closeOnClickOutside: false,
        closeOnEsc: false,
        showConfirmButton: false,
    });
    //  $(".confirm").addClass('hidden')
    this._path = path;
    this._parameters = parameters;
    this._vType = typeAjax.trim();
    this._resultContent = {};
    this.extractDataAjax = function (callback) {
        $.ajax({
            async: false,
            url: this._path,
            data: this._parameters,
            dataType: "json",
            dataType: "json",
            headers: { "X-CSRF-TOKEN": $("input[name='_token']").val() },
            method: this._vType,
            success: function (msg) {
                this._resultContent = msg;
                callback(this._resultContent, 200);
                $(".confirm").click();
                hideLoading();
            },
            error: function (xhr, status) {
                hideLoading();
                this._resultContent = {};
                if (path.indexOf("buscarMensajes") == -1);
                {
                    $(".confirm").click();
                }
                if (xhr.status == 422) {
                    var errores = "";
                    errors = xhr.responseJSON;
                    $.each(errors.errors, function (key, value) {
                        errores += value[0] + "\n";
                    });
                    if (errores.trim() != "") {
                        this._resultContent = { message: errores, code: 422 };
                    }
                } else {
                    if (xhr.status == "404") {
                        this._resultContent = {
                            message: "C\u00F3digo o P\u00E1gina no encontrado",
                            code: 404,
                        };
                    } else {
                        this._resultContent = {
                            message:
                                "Error de procesamiento (cod: " +
                                xhr.status +
                                ")\n" +
                                xhr.responseText,
                            code: 500,
                        };
                    }
                }

                callback(this._resultContent, xhr.status);
            },
            beforeSend: function () {
                showLoading();
            },
        });
    };

    function ajaxrequest(rtndata) {}
};

function validateEmail(email) {
    var re =
        /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(email);
}

function validateNumber(number) {
    var re = /^[0-9]+$/;
    return re.test(number);
}

var n_relogio = 0;
var i_relogio = 0;
var relogio;

function iniciar_modal_espera_dt() {
    swal({
        title: "Loading...",
        text: "Please wait",
        icon: "/images/loading.gif",
        button: false,
        closeOnClickOutside: false,
        closeOnEsc: false,
        showConfirmButton: false,
    });
    i_relogio = 0;
    relogio = setInterval(function () {
        i_relogio++; // equivale a i = i + 1;
        if ($("#dtmenu_processing").css("display") == "none")
            parar_modal_espera_dt();
    }, 1000);
}

function parar_modal_espera_dt() {
    $(".confirm").click();
    clearInterval(relogio);
}

function iniciar_modal_espera() {
    swal({
        title: "Loading...",
        text: "Please wait",
        icon: "/images/loading.gif",
        button: false,
        closeOnClickOutside: false,
        closeOnEsc: false,
        showConfirmButton: false,
    });
}

function iniciar_modal_espera_edificio() {
    swal({
        title: "Cargando Edificios...",
        text: "Por favor espere",
        icon: "/images/loading.gif",
        button: false,
        closeOnClickOutside: false,
        closeOnEsc: false,
        showConfirmButton: false,
    });
}

function parar_modal_espera_edificio() {
    $(".confirm").click();
    //	clearInterval(relogio);
}

function parar_modal_espera() {
    $(".confirm").click();
    //	clearInterval(relogio);
}

function generatePDF(id = null) {
    if (id == null) var texto = $("#target").html();
    else var texto = $("#" + id + "").html();

    impresionHojaPDF(texto);
}

function impresionHojaPDF(html) {
    var mywindow = window.open(
        "academico/imp-blanco",
        "PRINT",
        "height=800,width=800"
    );
    mywindow.onload = function () {
        var isIE = /(MSIE|Trident\/|Edge\/)/i.test(navigator.userAgent);
        if (isIE) {
            mywindow.print();
            setTimeout(function () {
                mywindow.close();
            }, 100);
        } else {
            setTimeout(function () {
                mywindow.print();
                var ival = setInterval(function () {
                    mywindow.close();
                    clearInterval(ival);
                }, 200);
            }, 500);
        }
    };
    mywindow.document.write(
        '<html><head><title>Reporte</title>	<meta charset="utf-8"><style> html{ font-family:  Arial, Helvetica, sans-serif;font-size: 12px; } table,th,td{font-size: 12px; } @media print{.saltoDePagina{display:block;	page-break-before:always;}} </style>'
    );
    mywindow.document.write("</head><body  >");
    mywindow.document.write(html);
    mywindow.document.write("</body></html>");
    mywindow.document.close(); // necessary for IE >= 10
    mywindow.focus(); // necessary for IE >= 10*/
    mywindow.print();
    return true;
}

function exportTo(type) {
    $(".table_export").tableExport({
        filename: "table_%DD%-%MM%-%YY%",
        format: type,
        //cols: '2,3,4'
    });
}

function exportAll(type) {
    $(".table_export").tableExport({
        filename: "table_%DD%-%MM%-%YY%-month(%MM%)",
        format: type,
    });
}

function imprimirDiv(div_imprimir = "div_imprimir") {
    var html = $("#" + div_imprimir + "").html();
    var mywindow = window.open(
        "academico/imp-blanco",
        "PRINT",
        "height=800,width=800"
    );
    mywindow.document.write(
        '<html><head><title>Reporte</title>	<meta charset="utf-8"><style> html{ font-family:  Arial, Helvetica, sans-serif;font-size: 12px; } table,th,td{font-size: 12px; } @media print{.saltoDePagina{display:block;	page-break-before:always;}} </style>'
    );
    mywindow.document.write(
        ' <link href="' + base_url + '/adminlte3/plugins/map/leaflet.css">'
    );
    mywindow.document.write("</head><body  >");
    mywindow.document.write(html);
    mywindow.document.write("</body></html>");
    mywindow.document.close(); // necessary for IE >= 10
    mywindow.focus(); // necessary for IE >= 10*/
    mywindow.print();
    return true;
}

function getBase64(file) {
    return new Promise((resolve, reject) => {
        const reader = new FileReader();
        reader.readAsDataURL(file);
        reader.onload = () => resolve(reader.result);
        reader.onerror = (error) => reject(error);
    });
}

/* BOTONES DE INCREMENTO Y DECREMENTO DE UN INPUT */

$(".qtyminus").on("click", function (e) {
    var input = document.getElementsByName(
        e.target.name.replace("boton_", "")
    )[0];

    var min = Number(input.getAttribute("min"));
    var max = Number(input.getAttribute("max"));
    var step = Number(input.getAttribute("step"));
    var current = Number(input.value);
    var newval = current - step;
    if (newval < min) {
        newval = min;
    } else if (newval > max) {
        newval = max;
    }
    input.value = Number(newval);
    e.preventDefault();
});
$(".qtyplus").on("click", function (e) {
    var input = document.getElementsByName(
        e.target.name.replace("boton_", "")
    )[0];
    var min = Number(input.getAttribute("min"));
    var max = Number(input.getAttribute("max"));
    var step = Number(input.getAttribute("step"));

    var current = Number(input.value);
    var newval = current + step;
    if (newval > max) newval = max;
    input.value = Number(newval);
    e.preventDefault();
});
function window_mouseout(obj, evt, fn) {
    if (obj.addEventListener) {
        obj.addEventListener(evt, fn, false);
    } else if (obj.attachEvent) {
        obj.attachEvent("on" + evt, fn);
    }
}

window_mouseout(document, "mouseout", (event) => {
    event = event ? event : window.event;
    var from = event.relatedTarget || event.toElement;
    if (!(!from || from.nodeName === "HTML")) eventoCerradoSesionGeneral = true;
    else eventoCerradoSesionGeneral = false;
});

function redondear(x) {
    return Number.parseFloat(x).toFixed(2);
}
function descargarURL(URL, name="SIGPRE") {
    var link = document.createElement("a");
    link.download = name;
    link.href = URL;
    link.setAttribute('target', '_blank');
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    delete link;
}
/* BOTONES DE INCREMENTO Y DECREMENTO DE UN INPUT */
/* BOTONES DE INCREMENTO Y DECREMENTO DE UN INPUT */
