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
        navLinks: true, // can click day/week names to navigate views
        editable: true,
        selectable: true,
        droppable: true,
        dayMaxEvents: true, // allow "more" link when too many events
        navLinkDayClick: function (date, jsEvent) {
            this.changeView('timeGridDay', date);
        },
    });

    calendar.render();
});