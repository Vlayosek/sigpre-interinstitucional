var calendar;
var lastClickSchedule = false;

$(() => {
    initCalendario();
});

function initCalendario() {
    // genera instancia calendario...
    generaInstanciaCalendarioReporte();
    // calendario compromisos...
    retornaCalendarioCompromisosReportes();
    // eventos calendario...
    generaEventosCalendarioReportes();
};

function limpiaCalendario() {
    // verifica si hay una instancia... limpia el contenedor
    if (calendar) $("#calendar").empty();
}

function generaInstanciaCalendarioReporte() {
    // verifica limpia calendario...
    limpiaCalendario();
    // items calendario...
    calendar = new tui.Calendar("#calendar", {
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
                "Sábado",
            ],
            //workweek: true,
            //visibleWeeksCount: 4, // visible week count in monthly
        },
        week: {},
        template: templates,
        useCreationPopup: false,
        useDetailPopup: false,
    });
}

// calendario compromisos...
function retornaCalendarioCompromisosReportes() {
    $.ajax({
        url: "/compromisos/calendarioReporte",
        type: "GET",
        dataType: "json",
        success: function(results) {
            // actualizamos el calendario...
            actualizaCalendario(results);
        },
        error: function(error) {
            console.log(error);
        },
    });
}

function actualizaInformacionCalendario(calendarios) {
    // recorre el array object...
    for (let calendario of calendarios) {
        // desestructura el objeto...
        const { id, calendarId, color, bgColor, borderColor, dragBgColor } =
        calendario;
        // seteando valores de calendario...
        calendar.setCalendarColor(id, {
            color,
            bgColor,
            borderColor,
            dragBgColor,
        });
    }
}

// renderiza datos del calendario...
function actualizaCalendario(results) {
    // actualizando datos...
    calendar.clear();
    calendar.createSchedules(results);
    calendar.render(true);
    // actuaizando datos calendario...
    actualizaInformacionCalendario(results);
}

function generaEventosCalendarioReportes() {
    calendar.setDate(new Date());
    // hoy...
    $(".move-today").on("click", function() {
        calendar.today();
        seteaHtmlFechaCalendario();
    });
    // anterior...
    $(".ic-arrow-line-left").on("click", function() {
        calendar.prev();
        seteaHtmlFechaCalendario();
    });
    // siguiente...
    $(".ic-arrow-line-right").on("click", function() {
        calendar.next();
        seteaHtmlFechaCalendario();
    });
    // click en evento calendario...
    generaClickCalendario();
    // setea fecha calendario...
    seteaHtmlFechaCalendario();
}

function generaClickCalendario() {
    // evento click...
    calendar.on("clickSchedule", function(event) {
        let schedule = event.schedule;
        // focus the schedule
        /*
        if (lastClickSchedule) {
            calendar.updateSchedule(lastClickSchedule.id, lastClickSchedule.calendarId, {
                isFocused: false
            });
        }
        calendar.updateSchedule(schedule.id, schedule.calendarId, {
            isFocused: true
        });
        lastClickSchedule = schedule;
        // open detail view
        */
        let formatDate = "DD-MM-YYYY";
        let isSameDate = moment(schedule.start._date).isSame(
            schedule.end._date
        );
        let startFormat = isSameDate ? "" : formatDate;
        let endFormat = isSameDate ? "" : formatDate;
        /* $("#codigo_compromiso").html(
             `<a href="#" onclick="compromisoDetalleCalendario(\'` +
             schedule.id +
             `\')" data-toggle="modal" data-target="#modal-COMPROMISO_DETALLE_CALENDARIO" data-backdrop="static">` +
             schedule.title +
             `</a>`
         );
         $("#inicio_compromiso").html(
             moment(schedule.start._date).format(startFormat)
         );
         $("#final_compromiso").html(
             moment(schedule.end._date).format(endFormat)
         );
         $("#detalle_compromiso").html(schedule.body);
         $("#modal-compromisoCalendario").modal("show");*/
        compromisoDetalleCalendario(schedule.id);
        $("#modal-COMPROMISO_DETALLE_CALENDARIO").modal("show");
    });
}

function currentCalendarDate(format) {
    let currentDate = moment([
        calendar.getDate().getFullYear(),
        calendar.getDate().getMonth(),
        calendar.getDate().getDate(),
    ]);

    return currentDate.format(format);
}

function seteaHtmlFechaCalendario() {
    var options = calendar.getOptions();
    var viewName = calendar.getViewName();

    var html = [];
    if (viewName === "day") {
        html.push(currentCalendarDate("YYYY.MM.DD"));
    } else if (
        viewName === "month" &&
        (!options.month.visibleWeeksCount ||
            options.month.visibleWeeksCount > 4)
    ) {
        html.push(currentCalendarDate("YYYY.MM"));
    } else {
        html.push(
            moment(calendar.getDateRangeStart().getTime()).format("YYYY.MM.DD")
        );
        html.push(" ~ ");
        html.push(
            moment(calendar.getDateRangeEnd().getTime()).format(" MM.DD")
        );
    }
    // seteando el calendario html...
    $("#renderRange").html(`<strong> ${html.join("")} </strong>`);
}

function compromisoDetalleCalendario(id) {
    $("#modal-compromisoCalendario").modal("hide");
    let data = arregloDatosCompromisosBasico;
    //this.id = id;
    // init datatable...
    $("#dtCompromisoDetalleCalendario").dataTable({
        dom: "lfrtip",
        destroy: true,
        serverSide: true,
        ajax: " /compromisos/getDatatableCompromisoDetalleCalendario/" + id,
        buttons: [{
            extend: "excelHtml5",
            text: '<img src="/images/icons/excel.png" width="25px" heigh="25px">Exportar Excel',
            titleAttr: "Excel",
        }, ],
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
        responsive: true,
        lengthMenu: [
            [3, 10, 20],
            [3, 10, 20],
        ],
        order: [
            [1, "desc"]
        ],
        columns: data,
    });
}