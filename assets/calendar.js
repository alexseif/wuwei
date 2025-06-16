import {
    Calendar
} from 'fullcalendar'
import interactionPlugin from '@fullcalendar/interaction';
import dayGridPlugin from '@fullcalendar/daygrid';
import timeGridPlugin from '@fullcalendar/timegrid';
import listPlugin from '@fullcalendar/list';

document.addEventListener('DOMContentLoaded', function () {
    const eventsData = document.getElementById('events-data').textContent;
    const events = JSON.parse(eventsData); // Retrieve events from the script tag
    const today = new Date();
    const year = today.getFullYear();
    const month = String(today.getMonth() + 1).padStart(2, '0'); // Months are zero-based
    const day = String(today.getDate()).padStart(2, '0');

    const formattedDate = `${year}-${month}-${day}`;

    var calendarEl = document.getElementById('calendar');
    var calendar = new Calendar(calendarEl, {
        plugins: [interactionPlugin, dayGridPlugin, timeGridPlugin, listPlugin],
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
        },
        initialDate: formattedDate,
        initialView: 'timeGridWeek',
        events: events,
        navLinks: true,
        editable: true,
        selectable: true,
        droppable: true,
        dayMaxEvents: true,
        businessHours: {
            // days of week. an array of zero-based day of week integers (0=Sunday)
            daysOfWeek: [0, 1, 2, 3, 4], // Monday - Thursday

            startTime: '09:00', // a start time (10am in this example)
            endTime: '18:00', // an end time (6pm in this example)
        },


        // Handle double-click (open task URL directly)
        eventClick: function (info) {
            let eventUrl = info.event.extendedProps.url;
            if (eventUrl) {
                window.open(eventUrl, "_blank");
            }
        }
    });

    calendar.render();

});