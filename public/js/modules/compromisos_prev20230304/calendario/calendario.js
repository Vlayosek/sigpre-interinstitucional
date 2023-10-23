var calendar;
var lastClickSchedule = false;

const initCalendario = function() {
  //genera instancia calendario...
  generaInstanciaCalendario();
  // calendario compromisos...
  retornaCalendarioCompromisos();
  // eventos calendario...
  generaEventosCalendario();
}

function limpiaCalendario() {
  // verifica si hay una instancia... limpia el contenedor
  if(calendar) $("#calendar").empty();
}

function generaInstanciaCalendario() {
  // verifica limpia calendario...
  limpiaCalendario();
  // items calendario...
  calendar = new tui.Calendar('#calendar', {
    defaultView: 'month',
    taskView: true,
    month: {
      daynames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
      workweek: true,
      visibleWeeksCount: 2 // visible week count in monthly
    },
    week: {},
    template: templates,
    useCreationPopup: false,
    useDetailPopup: true
  });
}

// calendario compromisos...
function retornaCalendarioCompromisos() {
  $.ajax({
    url: "/compromisos/calendario",
    type: 'GET',
    dataType: 'json',
    success: function(results) {
      // actualizamos el calendario...
      actualizaCalendario(results);
    },
    error: function(error) {
      console.log(error);
    }
  });
}

function actualizaInformacionCalendario(calendarios) {
  // recorre el array object...
  for(let calendario of calendarios) {
    // desestructura el objeto...
    const { id, calendarId, color, bgColor, borderColor, dragBgColor } = calendario;
    // seteando valores de calendario...
    calendar.setCalendarColor(id, {
      color,
      bgColor,
      borderColor,
      dragBgColor
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

function generaEventosCalendario() {
  calendar.setDate(new Date());
  // hoy...
  $(".move-today").on('click', function() {
    calendar.today();
    seteaHtmlFechaCalendario();
  });
  // anterior...
  $(".ic-arrow-line-left").on('click', function() {
    calendar.prev();
    seteaHtmlFechaCalendario();
  });
  // siguiente...
  $(".ic-arrow-line-right").on('click', function() {
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
  calendar.on('clickSchedule', function(event) {
    let schedule = event.schedule;
    // focus the schedule
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
  }); 
}

function currentCalendarDate(format) {
  let currentDate = moment([
    calendar.getDate().getFullYear(),
    calendar.getDate().getMonth(),
    calendar.getDate().getDate()
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
      moment(calendar.getDateRangeStart().getTime()).format(
        "YYYY.MM.DD"
      )
    );
    html.push(" ~ ");
    html.push(
      moment(calendar.getDateRangeEnd().getTime()).format(
        " MM.DD"
      )
    );
  }
  // seteando el calendario html...
  $("#renderRange").html(`<strong>${html.join("")}</strong>`);
}

