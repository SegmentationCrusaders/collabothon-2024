<template>
    <div>
        <FullCalendar :events="events" @event-click="handleEventClick" :options="calendarOptions" />

        
    </div>
</template>

<script>
import FullCalendar from "@fullcalendar/vue3";
import dayGridPlugin from "@fullcalendar/daygrid";
import timeGridPlugin from "@fullcalendar/timegrid";
import interactionPlugin from "@fullcalendar/interaction";

export default {
    components: {
        FullCalendar,
    },
    props: {
        events: {
            type: Array,
            required: true,
        },
    },
    data() {
        return {
            selectedEvent: null,

            calendarOptions: {
                plugins: [dayGridPlugin, timeGridPlugin, interactionPlugin],
                initialView: "dayGridMonth",
                headerToolbar: {
                    left: "prev,next today",
                    center: "title",
                    right: "dayGridMonth,timeGridWeek,timeGridDay",
                },
                editable: true,
                selectable: true,
                themeSystem: "litera",
                initialEvents: this.events,
                select: this.handleDateSelect,
                eventClick: this.handleEventClick,
                eventsSet: this.handleEvents,
            },
        };
    },

    methods: {
        handleWeekendsToggle() {
            this.calendarOptions.weekends = !this.calendarOptions.weekends;
        },
        handleDateSelect(selectInfo) {
            let title = prompt("Please enter a new title for your event");
            let calendarApi = selectInfo.view.calendar;

            calendarApi.unselect(); // clear date selection

            if (title) {
                calendarApi.addEvent({
                    id: createEventId(),
                    title,
                    start: selectInfo.startStr,
                    end: selectInfo.endStr,
                    allDay: selectInfo.allDay,
                });
            }
        },

        handleEventClick(info) {
            const clickedEvent = info.event;

            console.log("CalendarComponent - clickedEvent", clickedEvent.title);

            // Find the corresponding original event using the event ID
            this.selectedEvent = this.findOriginalEvent(clickedEvent.id);
        },
        findOriginalEvent(eventId) {
            // Find the original event based on the ID in todoEvents and proposedEvents
            const allEvents = [...this.$props.events]; // Use the events prop passed from the parent

            return allEvents.find((event) => event.id === eventId) || null; // Return the event or null
        },
        handleEvents(events) {
            this.currentEvents = events;
        },
    },
};
</script>

<style scoped></style>
