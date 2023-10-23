var templates = {
    popupIsAllDay: function () {
        return "All Day";
    },
    popupStateFree: function () {
        return "Free";
    },
    popupStateBusy: function () {
        return "Busy";
    },
    titlePlaceholder: function () {
        return "Subject";
    },
    locationPlaceholder: function () {
        return "Location";
    },
    startDatePlaceholder: function () {
        return "Start date";
    },
    endDatePlaceholder: function () {
        return "End date";
    },
    popupSave: function () {
        return "Save";
    },
    popupUpdate: function () {
        return "Update";
    },
    popupDetailDate: function (isAllDay, start, end) {
        let formatDate = "DD-MM-YYYY";
        let isSameDate = moment(start).isSame(end);
        let startFormat = isSameDate ? "" : formatDate;
        let endFormat = isSameDate ? "" : formatDate;

        if (isAllDay) {
            return (
                moment(start).format(startFormat) +
                (isSameDate ? "" : " - " + moment(end).format(formatDate))
            );
        }

        return `
          <strong>Inicio</strong>: ${moment(start).format(startFormat)} </br>
          <strong>Final</strong>: ${moment(end).format(endFormat)} </br>`;
    },
    popupDetailLocation: function (schedule) {
        return "Location : " + schedule.location;
    },
    popupDetailUser: function (schedule) {
        return "User : " + (schedule.attendees || []).join(", ");
    },
    popupDetailState: function (schedule) {
        return "State : " + schedule.state || "Busy";
    },
    popupDetailRepeat: function (schedule) {
        return "Repeat : " + schedule.recurrenceRule;
    },
    popupDetailBody: function (schedule) {
        // console.log(schedule);
        return `
        </br>
          <strong>Detalle</strong>: ${schedule.body}
        `;
    },
    popupEdit: function () {
        return "Edit";
    },
    popupDelete: function () {
        return "Delete";
    },
};

var templatesFinalizacion = {
    popupIsAllDay: function () {
        return "All Day";
    },
    popupStateFree: function () {
        return "Free";
    },
    popupStateBusy: function () {
        return "Busy";
    },
    titlePlaceholder: function () {
        return "Subject";
    },
    locationPlaceholder: function () {
        return "Location";
    },
    startDatePlaceholder: function () {
        return "Start date";
    },
    endDatePlaceholder: function () {
        return "End date";
    },
    popupSave: function () {
        return "Save";
    },
    popupUpdate: function () {
        return "Update";
    },
    popupDetailDate: function (isAllDay, start, end) {
        let formatDate = "DD-MM-YYYY";
        let isSameDate = moment(start).isSame(end);
        let startFormat = isSameDate ? "" : formatDate;
        let endFormat = isSameDate ? "" : formatDate;

        if (isAllDay) {
            return (
                moment(start).format(startFormat) +
                (isSameDate ? "" : " - " + moment(end).format(formatDate))
            );
        }

        return `
          <strong>Inicio</strong>: ${moment(start).format(startFormat)} </br>
          <strong>Final</strong>: ${moment(end).format(endFormat)} </br>`;
    },
    popupDetailLocation: function (schedule) {
        return "Location : " + schedule.location;
    },
    popupDetailUser: function (schedule) {
        return "User : " + (schedule.attendees || []).join(", ");
    },
    popupDetailState: function (schedule) {
        return "State : " + schedule.state || "Busy";
    },
    popupDetailRepeat: function (schedule) {
        return "Repeat : " + schedule.recurrenceRule;
    },
    popupDetailBody: function (schedule) {
        // console.log(schedule);
        return `
        </br>
          <strong>Detalle</strong>: ${schedule.body}
        `;
    },
    popupEdit: function () {
        return "Edit";
    },
    popupDelete: function () {
        return "Delete";
    },
    /* taskTitle: function () {
        return "hola";
    }, */
};
