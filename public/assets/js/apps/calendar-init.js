/*========Calender Js=========*/
/*==========================*/

document.addEventListener("DOMContentLoaded", function () {
    /*=================*/
    //  Calender Date variable
    /*=================*/
    var newDate = new Date();
    function getDynamicMonth() {
        getMonthValue = newDate.getMonth();
        _getUpdatedMonthValue = getMonthValue + 1;
        if (_getUpdatedMonthValue < 10) {
            return `0${_getUpdatedMonthValue}`;
        } else {
            return `${_getUpdatedMonthValue}`;
        }
    }
    /*=================*/
    // Calender Modal Elements
    /*=================*/
    var getModalTitleEl = document.querySelector("#event-title");
    var getModalTitleDisplayEl = document.querySelector("#event-title-display");
    var getModalStartDateEl = document.querySelector("#event-start-date");
    var getModalStartDateDisplayEl = document.querySelector("#event-start-date-display");
    var getModalEndDateEl = document.querySelector("#event-end-date");
    var getModalEndDateDisplayEl = document.querySelector("#event-end-date-display");
    var getModalAddBtnEl = document.querySelector(".btn-add-event");
    var getModalUpdateBtnEl = document.querySelector(".btn-update-event");
    var calendarsEvents = {
        Danger: "danger",
        Success: "success",
        Primary: "primary",
        Warning: "warning",
    };
    /*=====================*/
    // Calendar Elements and options
    /*=====================*/
    var calendarEl = document.querySelector("#calendar");
    var checkWidowWidth = function () {
        if (window.innerWidth <= 1199) {
            return true;
        } else {
            return false;
        }
    };
    var calendarHeaderToolbar = {
        left: "prev next",
        center: "title",
        right: "dayGridMonth,timeGridWeek,timeGridDay",
    };
    var calendarEventsList = window.calendarAppointmentsData || [];
    /*=====================*/
    // Calendar Select fn. (Disabled for this page)
    /*=====================*/
    var calendarSelect = function (info) {
        // Disabled
    };
    /*=====================*/
    // Calendar AddEvent fn. (Disabled for this page)
    /*=====================*/
    var calendarAddEvent = function () {
        // Disabled
    };

    /*=====================*/
    // Calender Event Function
    /*=====================*/
    var calendarEventClick = function (info) {
        var eventObj = info.event;

        if (eventObj.url) {
            window.open(eventObj.url);

            info.jsEvent.preventDefault();
        } else {
            // Hide input fields and show display fields
            document.getElementById("event-title-group").style.display = "none";
            document.getElementById("event-title-display-group").style.display = "block";
            document.getElementById("event-color-group").style.display = "none";
            document.getElementById("event-start-date-group").style.display = "none";
            document.getElementById("event-start-date-display-group").style.display = "block";
            document.getElementById("event-end-date-group").style.display = "none";

            // Set values for display fields
            getModalTitleDisplayEl.textContent = eventObj.title;
            getModalStartDateDisplayEl.textContent = eventObj.startStr.slice(0, 10);
            getModalEndDateDisplayEl.textContent = eventObj.endStr ? eventObj.endStr.slice(0, 10) : eventObj.startStr.slice(0, 10);

            // Hide Add/Update buttons
            getModalAddBtnEl.style.display = "none";
            getModalUpdateBtnEl.style.display = "none";

            myModal.show();
        }
    };

    /*=====================*/
    // Active Calender
    /*=====================*/
    var calendar = new FullCalendar.Calendar(calendarEl, {
        selectable: false, // Disable selection
        height: checkWidowWidth() ? 900 : 1052,
        initialView: checkWidowWidth() ? "listWeek" : "dayGridMonth",
        initialDate: `${newDate.getFullYear()}-${getDynamicMonth()}-07`,
        headerToolbar: calendarHeaderToolbar,
        events: calendarEventsList,
        select: calendarSelect,
        unselect: function () {
            console.log("unselected");
        },
        customButtons: {
            // Removed addEventButton
        },
        eventClassNames: function ({ event: calendarEvent }) {
            const getColorValue =
                calendarsEvents[calendarEvent._def.extendedProps.calendar];
            return ["event-fc-color fc-bg-" + getColorValue];
        },

        eventClick: calendarEventClick,
        windowResize: function (arg) {
            if (checkWidowWidth()) {
                calendar.changeView("listWeek");
                calendar.setOption("height", 900);
            } else {
                calendar.changeView("dayGridMonth");
                calendar.setOption("height", 1052);
            }
        },
    });

    /*=====================*/
    // Update Calender Event (Removed)
    /*=====================*/
    // getModalUpdateBtnEl.addEventListener("click", function () {
    //     // Removed
    // });
    /*=====================*/
    // Add Calender Event (Removed)
    /*=====================*/
    // getModalAddBtnEl.addEventListener("click", function () {
    //     // Removed
    // });
    /*=====================*/
    // Calendar Init
    /*=====================*/
    calendar.render();
    var myModal = new bootstrap.Modal(document.getElementById("eventModal"));
    // var modalToggle = document.querySelector(".fc-addEventButton-button "); // Removed
    document
        .getElementById("eventModal")
        .addEventListener("hidden.bs.modal", function (event) {
            // Reset modal to original state (show input fields, hide display fields)
            document.getElementById("event-title-group").style.display = "block";
            document.getElementById("event-title-display-group").style.display = "none";
            document.getElementById("event-color-group").style.display = "block";
            document.getElementById("event-start-date-group").style.display = "block";
            document.getElementById("event-start-date-display-group").style.display = "none";
            document.getElementById("event-end-date-group").style.display = "block";

            getModalTitleEl.value = "";
            getModalStartDateEl.value = "";
            getModalEndDateEl.value = "";
            var getModalIfCheckedRadioBtnEl = document.querySelector(
                'input[name="event-level"]:checked'
            );
            if (getModalIfCheckedRadioBtnEl !== null) {
                getModalIfCheckedRadioBtnEl.checked = false;
            }
        });
});
