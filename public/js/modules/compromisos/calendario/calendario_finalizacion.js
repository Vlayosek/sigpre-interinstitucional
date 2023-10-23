var calendarEnding;
var lastClickSchedule_ = false;

$(() => {
    initCalendarioFinalizacion();
});

function initCalendarioFinalizacion() {
    //genera instancia calendario...
    generaInstanciaCalendarioFinalizacion();
    // calendario compromisos...
    retornaCalendarioCompromisosFinalizacion();
    // eventos calendario...
    generaEventosCalendarioFinalizacion();
};

function limpiaCalendarioEnding() {
    if (calendarEnding) $("#calendar_ending").empty();
}

function generaInstanciaCalendarioFinalizacion() {
    // verifica limpia calendario...
    limpiaCalendarioEnding();
    // items calendario...
    calendarEnding = new tui.Calendar("#calendar_ending", {
        defaultView: "month",
        taskView: true,
        month: {
            daynames: [
                "Domingo",
                "Lunes",
                "Martes",
                "Miércoles",
                "Jueves",
                "Viernes",
                "Sabado",
            ],
            //workweek: true,
            //narrowWeekend: true,
            //startDayOfWeek: 1,
            //visibleWeeksCount: 4,
        },
        // template: templatesFinalizacion,
        useCreationPopup: false,
        useDetailPopup: false,
    });
}

function retornaCalendarioCompromisosFinalizacion() {
    $.ajax({
        url: "/compromisos/calendario",
        type: "GET",
        dataType: "json",
        success: function(results) {
            // actualizamos el calendario...
            actualizaCalendarioFinalizacion(results);
        },
        error: function(error) {
            console.log(error);
        },
    });
}

function actualizaInformacionCalendarioFinalizacion(calendarios) {
    // recorre el array object...
    //console.log(calendarios);
    for (let calendarioF of calendarios) {
        // desestructura el objeto...
        const { id, calendarId, color, bgColor, borderColor, dragBgColor } =
        calendarioF;
        // seteando valores de calendario...
        calendarEnding.setCalendarColor(id, {
            color,
            bgColor,
            borderColor,
            dragBgColor,
        });
    }
}

function actualizaCalendarioFinalizacion(results) {
    // actualizando datos...
    calendarEnding.clear();
    calendarEnding.createSchedules(results);
    calendarEnding.render(true);
    // actuaizando datos calendario...
    actualizaInformacionCalendarioFinalizacion(results);
}

function generaEventosCalendarioFinalizacion() {
    calendarEnding.setDate(new Date());
    // hoy...
    $(".move-today_").on("click", function() {
        calendarEnding.today();
        seteaHtmlFechaCalendarioFinalizacion();
    });
    // anterior...
    $(".ic-arrow-line-left_").on("click", function() {
        calendarEnding.prev();
        seteaHtmlFechaCalendarioFinalizacion();
    });
    // siguiente...
    $(".ic-arrow-line-right_").on("click", function() {
        calendarEnding.next();
        seteaHtmlFechaCalendarioFinalizacion();
    });
    // click en evento calendario...
    generaClickCalendarioFinalizacion();
    // setea fecha calendario...
    seteaHtmlFechaCalendarioFinalizacion();
}

function generaClickCalendarioFinalizacion() {
    // evento click...
    calendarEnding.on("clickSchedule", function(event) {
        let schedule_ = event.schedule;

        let formatDate = "DD-MM-YYYY";
        let isSameDate = moment(schedule_.start._date).isSame(
            schedule_.end._date
        );
        let startFormat = isSameDate ? "" : formatDate;
        let endFormat = isSameDate ? "" : formatDate;
        /* $("#codigo_compromiso_f").html(
             `<a href="#" onclick="compromisoCalendarioFinalizacion(\'` +
             schedule_.id +
             `\')" data-toggle="modal" data-target="#modal-calendario-compromiso-finalizacion" data-backdrop="static">` +
             schedule_.title +
             `</a>`
         );
         $("#inicio_compromiso_f").html(
             moment(schedule_.start._date).format(startFormat)
         );
         $("#final_compromiso_f").html(
             moment(schedule_.end._date).format(endFormat)
         );
         $("#detalle_compromiso_f").html(schedule_.body);
         $("#modal-compromiso-calendario-finalizacion").modal("show");*/
        compromisoCalendarioFinalizacion(schedule_.id);
        $("#modal-calendario-compromiso-finalizacion").modal("show");
    });
}

function currentCalendarDateFinalizacion(format) {
    let currentDateFinalizacion = moment([
        calendarEnding.getDate().getFullYear(),
        calendarEnding.getDate().getMonth(),
        calendarEnding.getDate().getDate(),
    ]);

    return currentDateFinalizacion.format(format);
}

function seteaHtmlFechaCalendarioFinalizacion() {
    var options_ = calendarEnding.getOptions();
    var viewName_ = calendarEnding.getViewName();

    var html_ = [];
    if (viewName_ === "day") {
        html_.push(currentCalendarDateFinalizacion("YYYY.MM.DD"));
    } else if (
        viewName_ === "month" &&
        (!options_.month.visibleWeeksCount || options_.month.visibleWeeksCount > 4)
    ) {
        html_.push(currentCalendarDateFinalizacion("YYYY.MM"));
    } else {
        html_.push(
            moment(calendarEnding.getDateRangeStart().getTime()).format("YYYY.MM.DD")
        );
        html_.push(" ~ ");
        html_.push(
            moment(calendarEnding.getDateRangeEnd().getTime()).format("MM.DD")
        );
    }
    // seteando el calendario html...
    $("#renderRange_f").html(`<strong>${html_.join("")}</strong>`);
}

function compromisoCalendarioFinalizacion(id) {
    $("#modal-compromiso-calendario-finalizacion").modal("hide");
    $("#dtCompromisoCalendarioFinalizacion").dataTable({
        dom: "lfrtip",
        destroy: true,
        serverSide: true,
        ajax: " /compromisos/getDatatableCompromisoCalendarioFinalizacion/" + id,
        lengthChange: true,
        searching: true,
        ordering: true,
        info: true,
        autoWidth: true,
        language: {
            search: "Buscar",
            lengthMenu: "Mostrar _MENU_",
            zeroRecords: "Lo sentimos, no encontramos lo que estas buscando",
            info: "Motrar página _PAGE_ de _PAGES_ (_TOTAL_)",
            infoEmpty: "Registros no encontrados",
            oPaginate: {
                sFirst: "Primero",
                sLast: "Último",
                sNext: "Siguiente",
                sPrevious: "Anterior",
            },
            infoFiltered: "(Filtrado _TOTAL_  de _MAX_ registros totales)",
        },
        // data,
        responsive: true,
        lengthMenu: [
            [3, 10, 20],
            [3, 10, 20],
        ],
        order: [
            [1, "desc"]
        ],

        //    columns: arregloDatosCompromisosBasico,
        columns: arregloDatosCompromisosBasico,
    });
}